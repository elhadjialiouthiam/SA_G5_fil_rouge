<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Service\ArchiveService;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $archiveService;
    public function __construct(ArchiveService $archiveService){
        $this->archiveService = $archiveService;
    }
    /**
* @Route(
* name="archive_profil",
* path="api/profils/{id<[0-9]+>}",
* methods={"DELETE"},
* defaults={
* "_controller"="\app\ControllerApprenantController::archive",
* "_api_resource_class"=Profil::class,
* "_api_collection_operation_name"="archive_profil"
* }
* )
*/
public function archive(Profil $profil){
    return $this->archiveService->archive($profil);
    }

    /**
* @Route(
* name="show_profil",
* path="api/profils",
* methods={"GET"},
* defaults={
* "_controller"="\app\ControllerApprenantController::showProfils",
* "_api_resource_class"=Profil::class,
* "_api_collection_operation_name"="show_profils"
* }
* )
*/
public function showProfils(ProfilRepository $repository){
    return $this->archiveService->show($repository);
}

}
