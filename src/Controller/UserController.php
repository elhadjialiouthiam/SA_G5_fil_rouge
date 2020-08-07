<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ArchiveService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;
    private $archiveService;
    private $serializer;
    private $repository;
    public function __construct(UserPasswordEncoderInterface $encoder, ArchiveService $archiveService, SerializerInterface $serializer, UserRepository $repository){
        $this->encoder = $encoder;
        $this->archiveService = $archiveService;
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @Route(
     * name="add_user",
     * path="api/admin/users",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::add",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="add_user"
     * }
     * )
     */
    public function add(Request $request, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
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
            throw $this->createNotFoundException("L'email existe déjà");
        }

        $user = $this->serializer->denormalize($user, 'App\Entity\User', true);
        $errors = $validator->validate($user);
        if(count($errors) > 0){
            $errors = $serializer->serialize($errors,'json');
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        // fclose($avatar);
        $user->setRoles($user->getRoles());
        $user->setPassword($this->encoder->encodePassword($user,"password"));
        $manager->persist($user);
        $manager->flush();
        return new JsonResponse("Créé avec success",Response::HTTP_CREATED,[],true);
    }

    //afficher l'ensemble des users non archivés
     /**
     * @Route(
     * name="show_user",
     * path="api/admin/users",
     * methods={"GET"},
     * defaults={
     * "_controller"="app\Controller\UserController:showUsers",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="show_user"
     * }
     * )
     */
    public function showUsers(SerializerInterface $serializer){
        $users=$this->repository->findBy([
            "etat" => null
        ]);
        // dd($users);
        // $user = new User();
        // $users = $this->repository->findAll();

        // $users=$this->serializer->encode();
        // $tab=[];
        $jsonEncoder = new JsonEncoder();
        // $norme = $this->serializer->normalize($users);
        //     $norme = $jsonEncoder->encode($users, JsonEncoder::FORMAT);
        // dd($norme);
           
        foreach($users as $user){
            // $userEncode = $jsonEncoder->encode($user);
            // $serializer=new serializer([],$userEncode);
            // if($user->getAvatar()){
            // $tab=$serializer->$user->getAvatar();
            // }
            $norme = $jsonEncoder->encode($users, JsonEncoder::FORMAT);
                $tab[]=$norme;
            // $norme = $this->serializer->normalize($user);
        
        }
        dd($tab);
    

        // dd($tab[0]);
        // return $this->archiveService->show($this->repository);
    }

    //archiver un user
         /**
     * @Route(
     * name="archive_user",
     * path="api/admin/users/{id}",
     * methods={"DELETE"},
     * defaults={
     * "_controller"="app\Controller\UserController:archiveUser",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="archive_user"
     * }
     * )
     */
    public function archiveUser(User $user){
        return $this->archiveService->archive($user);
    }

    
}
