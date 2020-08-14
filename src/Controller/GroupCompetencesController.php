<?php

namespace App\Controller;

use App\Service\ArchiveService;
use App\Entity\GroupCompetences;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupCompetencesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupCompetencesController extends AbstractController
{
    private $archiveService;
    public function __construct(ArchiveService $archiveService){
        $this->archiveService = $archiveService;
    }
/** 
* @Route(
* name="show_gcs",
* path="api/admin/grpecompetences",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\GroupCompetencesController::showGroupCompetences",
* "_api_resource_class"=GroupCompetences::class,
* "_api_collection_operation_name"="show_gcs"
* }
* )
*/
    public function showGroupCompetences(GroupCompetencesRepository $repository)
        {
            return $this->archiveService->show($repository);
        }
    
  //archivage d'une competence

      /**
     * @Route(
     * name="archive_gc",
     * path="api/admin/grpecompetences/{id}",
     * methods={"DELETE"},
     * defaults={
     * "_controller"="app\Controller\GroupCompetencesController::archiveGroupCompetences",
     * "_api_resource_class"=GroupCompetences::class,
     * "api_collection_operation_name"="archive_gc"
     * }
     * )
     */
  public function archiveGroupCompetences(GroupCompetences $competence){
        return $this->archiveService->archive($competence);
  }


  //ajout d'un nouveau competences

        /**
     * @Route(
     * name="ajout_gc_competence",
     * path="api/admin/grpecompetences/{id}/competences",
     * methods={"PUT"},
     * defaults={
     * "_controller"="app\Controller\GroupCompetencesController::addNewCompetence",
     * "_api_resource_class"=GroupCompetences::class,
     * "api_collection_operation_name"="ajout_gc_competence"
     * }
     * )
     */
  public function addNewCompetence($id, Request $request, GroupCompetencesRepository $gcRepository, SerializerInterface $serializer, CompetenceRepository $comRepo, EntityManagerInterface $manager){
    $groupComp = $gcRepository->findOneBy([
        "id"=>$id
    ]);
    $requete = $request->getContent();
    $competenceArray = $serializer->decode($requete, "json");
  
    $theNewCompetence = $comRepo->findOneBy([
        "id" => $competenceArray["id"],
    ]);
    $groupComp->addCompetence($theNewCompetence);
    $manager->flush();
    return new Response("Compétence ajoutée avec succès"); 
  }

  //supprimer une competence d'un groupe de competences
          /**
     * @Route(
     * name="remove_gc_competence",
     * path="api/admin/grpecompetences/{id}/competences/{iden}",
     * methods={"DELETE"},
     * defaults={
     * "_controller"="app\Controller\GroupCompetencesController::removeCompetenceFromGc",
     * "_api_resource_class"=GroupCompetences::class,
     * "api_collection_operation_name"="remove_gc_competence"
     * }
     * )
     */

     public function removeCompetenceFromGc($id, $iden, Request $request, GroupCompetencesRepository $gcRepository, SerializerInterface $serializer, CompetenceRepository $comRepo, EntityManagerInterface $manager){
        $groupComp = $gcRepository->findOneBy([
            "id"=>$id
        ]);
        // $requete = $request->getContent();
        // $competenceArray = $serializer->decode($requete, "json");
      
        $theNewCompetence = $comRepo->findOneBy([
            "id" => $iden,
        ]);
        $groupComp->removeCompetence($theNewCompetence);
        $manager->flush();
        return new Response("Compétence retirée du groupe de competences"); 
     }

}
