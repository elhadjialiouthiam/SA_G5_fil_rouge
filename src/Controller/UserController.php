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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;
    private $archiveService;
    public function __construct(UserPasswordEncoderInterface $encoder, ArchiveService $archiveService){
        $this->encoder = $encoder;
        $this->archiveService = $archiveService;
    }
    /**
     * @Route(
     * name="user",
     * path="api/users",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::add",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="add_user"
     * }
     * )
     */
    public function add(Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, SerializerInterface $serialier)
    {
        $user = $serialier->deserialize($request->getContent(),User::class,'json');
        $errors = $validator->validate($user);
        if(count($errors) > 0){
            $errors = $serialier->serialize($errors,'json');
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $user->setRoles($user->getRoles());
        $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));
        $manager->persist($user);
        $manager->flush();
        return new JsonResponse("Créé avec success",Response::HTTP_CREATED,[],true);
    }

    //archivage d'un utilisateur
     /**
     * @Route(
     * name="archive_user",
     * path="api/users/{id}",
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

    //afficher l'ensemble des users non archivés
     /**
     * @Route(
     * name="show_user",
     * path="api/users",
     * methods={"GET"},
     * defaults={
     * "_controller"="app\Controller\UserController:showUsers",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="show_user"
     * }
     * )
     */
    public function showUsers(UserRepository $repository){
        return $this->archiveService->show($repository);
    }
}
