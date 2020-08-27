<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Service\ArchiveService;
use App\Repository\CompetenceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{

    private $archiveService;
    public function __construct(ArchiveService $archiveService){
        $this->archiveService = $archiveService;
    }
/** 
* @Route(
* name="show_competences",
* path="api/admin/competences",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\CompetenceController::showCompetences",
* "_api_resource_class"=Competence::class,
* "_api_collection_operation_name"="show_competences"
* }
* )
*/
    public function showCompetences(CompetenceRepository $repository)
        {
            return $this->archiveService->show($repository);
        }
    
  //archivage d'une competence

      /**
     * @Route(
     * name="archive_competence",
     * path="api/admin/competences/{id}",
     * methods={"DELETE"},
     * defaults={
     * "_controller"="app\Controller\Competence::archiveCompetence",
     * "_api_resource_class"=Competence::class,
     * "api_collection_operation_name"="archive_competence"
     * }
     * )
     */
  public function archiveCompetence(Competence $competence){
        return $this->archiveService->archive($competence);
  }
   
}
