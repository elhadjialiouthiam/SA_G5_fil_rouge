<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Service\ArchiveService;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ApprenantController extends AbstractController
{
    private $encoder;
    private $archiveService;
    public function __construct(UserPasswordEncoderInterface $encoder, ArchiveService $archiveService){
        $this->encoder = $encoder;
        $this->archiveService = $archiveService;
    }    

    /**
* @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')", message="Acces non autorisé") 
* @Route(
* name="apprenant_liste",
* path="api/apprenants",
* methods={"GET"},
* defaults={
* "_controller"="\app\ControllerApprenantController::showApprenants",
* "_api_resource_class"=User::class,
* "_api_collection_operation_name"="get_apprenants"
* }
* )
*/
    public function showApprenants(UserRepository $repository)
    {
        $apprenants = $repository->findByProfil("apprenant");
        return $this->json($apprenants,Response::HTTP_OK,);
    }


/**
* @Route(
* name="apprenant_add",
* path="api/apprenants",
* methods={"POST"},
* defaults={
* "_controller"="\app\Controller\ApprenantController::add",
* "_api_resource_class"=User::class,
* "_api_collection_operation_name"="add_apprenant"
* }
* )
*/
public function add(EntityManagerInterface $manager, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserRepository $repository, ProfilRepository $repo){
    $sent = $request->getContent();
    $apprenant = $serializer->deserialize($sent,User::class,'json');
    $errors = $validator->validate($apprenant);
    if(count($errors) > 0){
        $errorString = $serializer->serialize($errors, "json");
        return new JsonResonse($errorString, Response::HTTP_BAD_REQUEST,[],true);
    }
    $profil = $repo->findOneBy([
        "libelle" => "apprenant"
    ]);
    $apprenant->setProfil($profil);
    $apprenant->setRoles($apprenant->getRoles());
    $apprenant->setPassword($this->encoder->encodePassword($apprenant,$apprenant->getPassword()));
    $manager->persist($apprenant);
    $manager->flush();
    return new JsonResponse("success",Response::HTTP_CREATED,[],true);
}


      /**
* @IsGranted("ROLE_ADMIN") 
* @Route(
* name="apprenant_delete",
* path="api/apprenants/{id<[0-9]+>}",
* methods={"DELETE"},
* defaults={
* "_controller"="\app\Controller\ApprenantController::delete",
* "_api_resource_class"=User::class,
* "_api_collection_operation_name"="delete_apprenant"
* }
* )
*/

public function delete(User $apprenant, EntityManagerInterface $manager){
    if($apprenant->getProfil()=="apprenant"){
        return $this->archiveService->archive($apprenant);
    }
    else{
        return $this->json("La ressource que vous tentez d'archiver n'est pas un apprenant",Response::HTTP_OK,);
    }
}









}
