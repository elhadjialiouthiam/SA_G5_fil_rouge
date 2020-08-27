<?php

namespace App\Controller;

use App\Entity\ProfilDeSortie;
use App\Service\ArchiveService;
use App\Repository\ProfilDeSortieRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
*/
class ProfilDeSortieController extends AbstractController
{

    private $archiveService;
    public function __construct(ArchiveService $archiveService){
        $this->archiveService = $archiveService;
    }
    /**
     * @Route(
     * path="api/admin/profil_de_sorties/{id<[0-9]+>}", 
     * name="archive_profilSortie",
     * methods={"DELETE"},
     * defaults = {
     * "_controller"="\app\Controller\ProfilDeSortieController::archiveProfilSortie",
     * "_api_resource_class"=ProfilDeSortie::class,
     * "_api_collection_operation_name"="archive_profil_sortie"
     * }
     * )
     */
    public function archiveProfilSortie(ProfilDeSortie $profilSortie)
    {
        return $this->archiveService->archive($profilSortie);
    }

        /**
     * @Route(
     * path="api/admin/profil_de_sorties", 
     * name="show_profil_sortie",
     * methods={"GET"},
     * defaults = {
     * "_controller"="\app\Controller\ProfilDeSortieController::showProfilsSortie",
     * "_api_resource_class"=ProfilDeSortie::class,
     * "_api_collection_operation_name"="show_profil_sortie"
     * }
     * )
     */
    public function showProfilsSortie(ProfilDeSortieRepository $repository){
        return $this->archiveService->show($repository);
    }
}
