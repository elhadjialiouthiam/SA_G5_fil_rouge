<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
        /**
     * @Route(
     *     path="/api/admin/users",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::addUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="add_user"
     *     }
     * )
    */
    public function addUser(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,ProfilRepository $profil,EntityManagerInterface $manager)
    {
        $user = $request->request->all();
        $avatar = $request->files->get("avatar");
        $avatar = fopen($avatar->getRealPath(),"rb");
        $user["avatar"] = $avatar;
        $user = $serializer->denormalize($user,"App\Entity\User");
        $errors = $validator->validate($user);
        
        if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($user);
        $manager->flush();
        fclose($avatar);
        return $this->json($serializer->normalize($user),Response::HTTP_CREATED);
    }
    
    /**
     * @Route(
     *     path="/api/users/{id}/archivage",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::setUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="set_Users"
     *     }
     * )
     */
    public function archiver($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if ($user->getEtat() == "disponible") {
            $user->setEtat("archive");
            $entityManager->flush();
        }
        else{
            $user->setEtat("disponible");
            $entityManager->flush();
        }
        return $this->json($user,Response::HTTP_CREATED);
    }
    
    /**
    * @Route(
    *     path="/api/admin/users",
    *     methods={"GET"},
    *     
    * )
    */

    public function afficherUser(UserRepository $userRepository)
    {
        $users = $userRepository->findBy([
            "etat"=>"disponible"
        ]);
        return $this->json($users, Response::HTTP_OK);
    }
}
