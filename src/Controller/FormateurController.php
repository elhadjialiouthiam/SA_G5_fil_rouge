<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
       /**
* @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CM')", message="Acces non autorisé") 
* @Route(
* name="formateur_liste",
* path="api/formateurs",
* methods={"GET"},
* defaults={
* "_controller"="\app\ControllerApprenantController::showFormateurs",
* "_api_resource_class"=User::class,
* "_api_collection_operation_name"="get_formateurs"
* }
* )
*/
public function showFormateurs(UserRepository $repository)
{
    $apprenants = $repository->findByProfil('formateur');
    return $this->json($apprenants,Response::HTTP_OK,);
}

}
