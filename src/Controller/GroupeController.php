<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Service\ArchiveService;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    private $archiveService;
    private $repository;
    private $grpeRepo;
    private $apprenantRepo;
    private $manager;
    private $serializer;
    public function __construct(ArchiveService $archiveService, GroupeRepository $grpeRepo, ApprenantRepository $apprenantRepo, EntityManagerInterface $manager, SerializerInterface $serializer){
        $this->archiveService = $archiveService;
        $this->grpeRepo = $grpeRepo;
        $this->apprenantRepo = $apprenantRepo;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }
    //supprimer un apprenanat du groupe
    /**
     * @Route(
     * path="api/admin/groupes/{id}/apprenants/{iden}",
     * name="remove_apprenant",
     * requirements={"id":"\d+"},
     * requirements={"iden":"\d+"},
     * methods={"DELETE"},
     * defaults={
     * "_controller"="app\Controller\GroupeController::removeApprenantFromGroup",
     * "_api_resource_class"=Groupe::class,
     * "api_item_operation_name"="remove_apprenant"
     * }
     * )
     */
    public function removeApprenantFromGroup($id,$iden){
        $groupe = $this->grpeRepo->findOneBy([
            "id"=>$id
        ]);
        $etudiantWithId = $this->apprenantRepo->findOneBy([
            "id"=>$iden
        ]);
        $apprenants = $groupe->getApprenants();
        if($apprenants->contains($etudiantWithId)){
            $groupe->removeApprenant($etudiantWithId);
            $this->manager->flush();
            return new Response("L'apprenant a été supprimé du groupe");
        }
        return new Response("L'apprenant que vous tentez du supprimé est introuvable dans ce groupe");
    }

    //archiver un groupe
    /** 
    * @Route(
        * path="api/admin/groupes/{id}",
        * name="archive_groupe",
        * methods={"DELETE"},
        * defaults={
        * "_controller"="app\Controller\GroupeController::archiveGroupe",
        * "_api_resource_class"=Groupe::class,
        * "api_item_operation_name"="archive_groupe"
        * }
        * )
        */
    public function archiveGroupe(Groupe $group){
        return $this->archiveService->archive($group);
    }

    //groupe non archives

      /** 
    * @Route(
        * path="api/admin/groupes",
        * name="show_groupes",
        * methods={"GET"},
        * defaults={
        * "_controller"="app\Controller\GroupeController::showGroupsNonArchives",
        * "_api_resource_class"=Groupe::class,
        * "api_item_operation_name"="show_groupes"
        * }
        * )
        */
        public function showGroupsNonArchives(){
            return $this->archiveService->show($this->grpeRepo);
        }


        
       /** 
    * @Route(
        * path="api/admin/groupes/apprenants",
        * name="show_groups_apprenants",
        * methods={"GET"},
        * defaults={
        * "_controller"="app\Controller\GroupeController::showGroupsNonArchives",
        * "_api_resource_class"=Groupe::class,
        * "api_item_operation_name"="show_groups_apprenants",
        *"normalization_context"={"groups"="groupe_apprenants:read"}
        * }
        * )
        */

        public function showApprenantsOfGroups(){
            return $this->archiveService->show($this->grpeRepo);
        }

        //ajouter un apprenant d'un groupe

          /**
     * @Route(
     * path="api/admin/groupes/{id}/apprenants",
     * name="add_apprenant_group",
     * requirements={"id":"\d+"},
     * requirements={"iden":"\d+"},
     * methods={"PUT"},
     * defaults={
     * "_controller"="app\Controller\GroupeController::addApprenantToGroup",
     * "_api_resource_class"=Groupe::class,
     * "api_item_operation_name"="add_apprenant_group"
     * }
     * )
     */
    public function addApprenantToGroup($id,Request $request){
        $groupe = $this->grpeRepo->findOneBy([
            "id"=>$id
        ]);
        $request = $request->getContent();
        //on traduit la requette sous forme de tableau
        $requestArray = $this->serializer->decode($request, "json");
        $apprenants = $groupe->getApprenants();
        $etudiantWithId = $this->apprenantRepo->findOneBy([
            "id"=>$requestArray["id"]
        ]);
        if($apprenants->contains($etudiantWithId)){
            return new Response("Cet apprenant existe déjà dans le groupe");
        }
        $groupe->addApprenant($etudiantWithId);
        $this->manager->flush();
        return new Response("L'apprenant a été ajouté avec success");
    }

   
}
