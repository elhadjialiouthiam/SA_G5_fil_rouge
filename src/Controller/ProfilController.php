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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProfilController extends AbstractController
{
    private $archiveService;
    public function __construct(ArchiveService $archiveService){
        $this->archiveService = $archiveService;
    }
    /**
* @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé")
* @Route(
* name="archive_profil",
* path="api/admin/profils/{id<[0-9]+>}",
* methods={"DELETE"},
* defaults={
* "_controller"="\app\Controller\ProfilController::archive",
* "_api_resource_class"=Profil::class,
* "_api_collection_operation_name"="archive_profil"
* }
* )
*/
public function archive(Profil $profil){
    return $this->archiveService->archive($profil);
    }

    /**
* @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé")
* @Route(
* name="show_profil",
* path="api/admin/profils",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\ProfilController::showProfils",
* "_api_resource_class"=Profil::class,
* "_api_collection_operation_name"="show_profils"
* }
* )
*/
public function showProfils(ProfilRepository $repository){
    return $this->archiveService->show($repository);
}

}
