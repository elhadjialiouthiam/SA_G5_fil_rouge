<?php

namespace App\Controller;

use App\Entity\CM;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Service\ArchiveService;
use App\Repository\UserRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;
    private $archiveService;
    private $serializer;
    private $repository;
    private $validator;
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder, ArchiveService $archiveService, SerializerInterface $serializer, UserRepository $repository, EntityManagerInterface $manager, ValidatorInterface $validator){
        $this->encoder = $encoder;
        $this->archiveService = $archiveService;
        $this->serializer = $serializer;
        $this->repository = $repository;
        $this->validator = $validator;
        $this->manager = $manager;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé") 
     * @Route(
     * name="add_apprenant",
     * path="api/admin/users/apprenants",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addApprenant",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="add_apprenant"
     * }
     * )
     */
    public function addApprenant(Request $request)
    {
        return $this->add("App\Entity\Apprenant", $request);
    }

       /**
     * @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé") 
     * @Route(
     * name="add_formateur",
     * path="api/admin/users/formateurs",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addFormateur",
     * "_api_resource_class"=Formateur::class,
     * "api_collection_operation_name"="add_formateur"
     * }
     * )
     */
    public function addFormateur(Request $request)
    {
        return $this->add("App\Entity\Formateur", $request);
    }

        /**
     * @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé") 
     * @Route(
     * name="add_admin",
     * path="api/admin/users/admins",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addAdmin",
     * "_api_resource_class"=Admin::class,
     * "api_collection_operation_name"="add_admin"
     * }
     * )
     */
    public function addAdmin(Request $request){
        return $this->add("App\Entity\Admin", $request);
    }

       /**
     * @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé")  
     * @Route(
     * name="add_cm",
     * path="api/admin/users/c_ms",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addCM",
     * "_api_resource_class"=CM::class,
     * "api_collection_operation_name"="add_cm"
     * }
     * )
     */
    public function addCM(Request $request){
        return $this->add("App\Entity\CM", $request);
    }

    /**
     * 
    */
    public function add($entite, $request)
    {
        // dd($request);
        $user = $request->request->all();
        $avatar = $request->files->get("avatar");

        
        //on ouvre le fichier et on le lit en format binaire
        $avatar = fopen($avatar->getRealPath(), 'rb');
        $user["avatar"]=$avatar;
        $userWithSameEmail = $this->repository->findBy([
            "email" => $user["email"],
            "etat" => null
        ]);

        if(count($userWithSameEmail)){
            throw $this->createNotFoundException("Un utilisateur avec cet email existe déjà");
        }
        $user = $this->serializer->denormalize($user, $entite, true);
        $errors = $this->validator->validate($user);
        if(count($errors) > 0){
            $errors = $this->serializer->serialize($errors,'json');
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $user->setRoles($user->getRoles());
        $user->setPassword($this->encoder->encodePassword($user,"password"));
        $this->manager->persist($user);
        $this->manager->flush();
        fclose($avatar);
        return new JsonResponse("Créé avec success",Response::HTTP_CREATED,[],true);
    }



    //affichage de la liste des professeurs

        /**
* @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CM')", message="Acces non autorisé") 
* @Route(
* name="show_formateurs",
* path="api/admin/users/formateurs",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\UserController::showFormateurs",
* "_api_resource_class"=User::class,
* "_api_collection_operation_name"="show_formateurs"
* }
* )
*/
public function showFormateurs(FormateurRepository $repository)
{
    return $this->archiveService->show($repository);
}


    /**
* @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')", message="Acces non autorisé") 
* @Route(
* name="apprenant_liste",
* path="api/admin/users/apprenants",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\UserController::showApprenants",
* "_api_resource_class"=Apprenant::class,
* "_api_collection_operation_name"="apprenant_liste"
* }
* )
*/
public function showApprenants(ApprenantRepository $repository)
{
    return $this->archiveService->show($repository);
}


    
}
