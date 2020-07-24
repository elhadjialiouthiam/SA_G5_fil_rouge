<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
{
    /**
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
        $apprenants = $repository->findByProfil('apprenant');
        return $this->json($apprenants,Response::HTTP_OK,);
    }

       /**
* @Route(
* name="apprenant_delete",
* path="api/apprenants/{id<[0-9]+>}",
* methods={"DELETE"},
* defaults={
* "_controller"="\app\ControllerApprenantController::delete",
* "_api_resource_class"=User::class,
* "_api_collection_operation_name"="delete_apprenant"
* }
* )
*/

    public function deleteApprenant(User $apprenant, EntityManagerInterface $manager){
        if($apprenant->getProfil()=="apprenant"){
            $manager->remove($apprenant);
            $manager->flush();
            return new JsonResponse("success",Response::HTTP_OK,[],true);
        }
        else{
            return new JsonResponse("La ressource que vous tentez de supprimer n'est pas un apprenant",Response::HTTP_OK,[],true);
        }
    }


}
