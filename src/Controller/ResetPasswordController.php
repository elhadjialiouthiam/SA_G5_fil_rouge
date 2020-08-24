<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Entity\ResetPasswordRequest;
use App\Form\ChangePasswordFormType;
use App\Repository\ApprenantRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ResetPasswordRequestRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;

class ResetPasswordController extends AbstractController
{

    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    // private $resetToken;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    /**
     * @IsGranted("IS_ANONYMOUS")
     * @Route(
     * path="/api/reset_password", 
     * name="api_reset_pwd",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\ControllerApprenantController::request",
    * "_api_resource_class"=User::class,
    * "_api_collection_operation_name"="api_reset_pwd"
    * }
     * )
     */
    public function request(Request $request, MailerInterface $mailer, SerializerInterface $serializer,UserRepository $repo)
    {
        $requete = $request->getContent();
        $user = $serializer->deserialize($requete,User::class,"json");
        $email = $user->getEmail();
        return $this->processSendingPasswordResetEmail(
            $email,
            $mailer
        );
    }


/**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reset/{token}", name="app_reset_password")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return new Response("error");
            // return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            //nom et prenom
            $prenom = $form->get("prenom")->getData();
            
            $nom = $form->get("nom")->getData();
            $avatar = $form->get("avatar")->getData();

            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);

            //nom et prenom
            $user->setPrenom($prenom);
            $user->setNom($nom);
            $user->setAvatar($avatar);

            $this->getDoctrine()->getManager()->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();
            // dd($prenom);
            return $this->redirectToRoute('api_platform');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    public function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Marks that you are allowed to see the app_check_email page.
        $this->setCanCheckEmailInSession();

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return new Response("Utilisateur inexistant");
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
        //     // If you want to tell the user why a reset email was not sent, uncomment
        //     // the lines below and change the redirect to 'app_forgot_password_request'.
        //     // Caution: This may reveal if a user is registered or not.
        //     //
        //     // $this->addFlash('reset_password_error', sprintf(
        //     //     'There was a problem handling your password reset request - %s',
        //     //     $e->getReason()
        //     // ));
            return new Response("Erreur survenue au cours de la reinitialisation du mot de passe");
        }

        $email = (new TemplatedEmail())
            ->from(new Address('ndeyesalydione@gmail.com', 'Ndeye Saly Dione'))
            ->to($emailFormData)
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
            ])
        ;
        $mailer->send($email);

        return new Response('Email de réinialisation de mot de passe envoyé');

        // return $this->redirectToRoute('app_reset_password');
    }


//relance d'invitations par email des apprenants qui n'ont pas encore rejoint le plateforme

//relancer ttes les invitaions

/**
 * @Route(path="api/reset/relance", name="relanceTout")
 */
public function relanceTout(ResetPasswordRequestRepository $resetRepo, MailerInterface $mailer){
    $resets = $resetRepo->findAll();
    foreach ($resets as $reset ) {
        $apprenant = $reset->getUser();
        $email = $apprenant->getEmail();
        $this->processSendingPasswordResetEmail(
            $email,
            $mailer
        );
    }
    return $this->json("Toutes les invitations relancées");
}

    //ralance d'une invitation particuliere

    /**
 * @Route(path="api/reset/relance/apprenant/{id}", name="relanceUneInvitation")
 */
    public function relanceUneInvitation(ResetPasswordRequestRepository $resetRepo, MailerInterface $mailer, ApprenantRepository $appRepo, $id){
        $resets = $resetRepo->findAll();
        $student = $appRepo->findOneBy(["id"=>$id]);
        if(!$student){
            return $this->json("Apprenant inexistant");
        }
        $email = $student->getEmail();
        foreach ($resets as $reset ) {
            $apprenant = $reset->getUser();
                if($student == $apprenant){
                    return $this->processSendingPasswordResetEmail(
                        $email,
                        $mailer
                    );
                }
                return $this->json(null);
        }

    }

}
