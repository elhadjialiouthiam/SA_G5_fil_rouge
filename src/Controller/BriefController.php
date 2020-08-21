<?php

namespace App\Controller;

use App\Entity\Briefs;
use App\Repository\BriefsRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromosRepository;
use App\Repository\FormateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BriefController extends AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /** 
* @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')", message="Acces non autorisé")  
* @Route(
* name="getBriefOfGroup",
* path="api/formateurs/promo/{idPromo}/groupe/{idGroup}/briefs",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\BriefController::getBriefsOfGroup",
* "_api_resource_class"=Briefs::class,
* "_api_collection_operation_name"="getBriefOfGroup"
* }
* )
*/
    public function getBriefsOfGroup(PromosRepository $promoRepo, GroupeRepository $groupeRepo, $idPromo, $idGroup)
    {
        //recupere la promo
        $promo = $promoRepo->findOneBy([
            "id" => $idPromo
        ]);

        //groupe
        $groupe = $groupeRepo->findOneBy([
            "id" => $idGroup
        ]);

        if(!$promo){
            return new Response("Promo inexistante");
        }
        foreach ($promo->getGroupes() as $value) {
            if($groupe == $value){
                $briefGroupe=$groupe->getBriefGroupe();
                $briefs = $briefGroupe->getBriefs();
                foreach ($briefs as $value) {
                    $valueJson = $this->serializer->serialize($value, 'json',["groups"=>["brief:read", "briefOfGroup:read"]]);
                    return new JsonResponse($valueJson,Response::HTTP_OK,[],true);
                }
            }
        }

    }

    //les briefs d'un promo

        /** 
* @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')", message="Acces non autorisé")
* @Route(
* name="getBriefsOfAPromo",
* path="api/formateurs/promos/{idPromo}/briefs",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\BriefController::getBriefsOfAPromo",
* "_api_resource_class"=Briefs::class,
* "_api_collection_operation_name"="getBriefsOfAPromo"
* }
* )
*/
    public function getBriefsOfAPromo(PromosRepository $promoRepo,$idPromo){
        //recupere la promo
        $promo = $promoRepo->findOneBy([
            "id" => $idPromo
        ]);
        $briefPromos=$promo->getBriefPromos();
        foreach ($briefPromos as $briefPromo) {
            $brief[] = $briefPromo->getBriefs();
        }
        $briefJson = $this->serializer->serialize($brief, 'json',["groups"=>["brief:read","briefOfPromo:read", "briefOfGroup:read"]]);
        return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
    }

    //affichage des briefs brouillons

            /** 
* @Security("is_granted('ROLE_FORMATEUR')", message="Acces non autorisé")
* @Route(
* name="getBriefsBrouillon",
* path="api/formateurs/{id}/briefs/brouillons",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\BriefController::getBriefsBrouillon",
* "_api_resource_class"=Briefs::class,
* "_api_collection_operation_name"="getBriefsBrouillon"
* }
* )
*/
    public function getBriefsBrouillon($id, FormateurRepository $formateurRepo){
        $formateur = $formateurRepo->findOneBy([
            "id" => $id
        ]);

        if(!$formateur){
            return new Response("Formateur existant");
        }
        $briefs = $formateur->getBriefs();
        $briefTab=[];
        foreach ($briefs as $brief ) {
            if($brief->getEtats() == "brouillon"){
                $briefTab[] = $brief;
              
            }
        }
        if(empty($briefTab)){
            return $this->json(["message"=>"Aucun brief brouillons de ce formateur trouvé"]);
        }
        $briefJson = $this->serializer->serialize($briefTab, 'json',["groups"=>["brief:read"]]);
        return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
    }

                /** 
* @Security("is_granted('ROLE_FORMATEUR')", message="Acces non autorisé") 
* @Route(
* name="getBriefsValides",
* path="api/formateurs/{id}/briefs/valide",
* methods={"GET"},
* defaults={
* "_controller"="\app\Controller\BriefController::getBriefsValides",
* "_api_resource_class"=Briefs::class,
* "_api_collection_operation_name"="getBriefsValides"
* }
* )
*/
public function getBriefsValides($id, FormateurRepository $formateurRepo){
    $formateur = $formateurRepo->findOneBy([
        "id" => $id
    ]);

    if(!$formateur){
        return new Response("Formateur existant");
    }
    $briefs = $formateur->getBriefs();
    $briefTab = [];
    foreach ($briefs as $brief ) {
        if($brief->getEtats() == "valide" || $brief->getEtats() == "non assigne"){
            $briefTab[] = $brief;
          
        }
    }
    if(empty($briefTab)){
       return new Response("Aucun de brief validé ou non assigné de ce formateur"); 
    }
    $briefJson = $this->serializer->serialize($briefTab, 'json',["groups"=>["brief:read"]]);
    return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
}

/**
     * @Security("is_granted('ROLE_FORMATEUR')", message="Acces non autorisé")
     * @Route("/api/formateurs/promos/{idPromo}/briefs/{idBrief}",name="getBriefInPromo",methods={"GET"})
    */
    public function getBriefInPromo($idPromo,$idBrief,PromosRepository $promosRepository,BriefsRepository $briefRepository)
    {
        $brief = new Briefs();
        if ($this->isGranted("VIEW",$brief))
        {
            return $this->json(["message" => "Vous n'avez pas accés à cette ressource"],Response::HTTP_FORBIDDEN);
        }
        $promo = $promosRepository->findOneBy(["id" => $idPromo]);
        if($promo)
        {
            $brief = $briefRepository->findOneBy(["id" =>$idBrief]);
            if($brief)
            {
                $promoBriefs = $promo->getBriefPromos();
                foreach ($promoBriefs as $promoBrief)
                {
                    if($promoBrief->getBriefs() == $brief)
                    {
                        $briefJson = $this->serializer->serialize($brief, 'json',["groups"=>["brief:read","briefOfPromo:read", "briefOfGroup:read"]]);
                        return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
                    }
                }
            }
        }
        return $this->json(["message" => "ressource inexistante."],Response::HTTP_NOT_FOUND);
    }

    /**
     * @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')", message="Acces non autorisé")
     * @Route("/api/apprenants/promos/{id}/briefs",name="briefOfApprenantPromo",methods={"GET"})
    */
    public function briefOfApprenantPromo(PromosRepository $promoRepo, $id){
        $promo = $promoRepo->findOneBy([
            "id" => $id
        ]);

        if(!$promo){
            return new Response("Promo inexistante");
        }

        $briefPromos = $promo->getBriefPromos();
        foreach ($briefPromos as $briefPromo) {
            $briefApprenant = $briefPromo->getBriefAprennant();
            if($briefApprenant->getStatut() == "assigne"){
                $brief = $briefPromo->getBriefs();
                $briefJson = $this->serializer->serialize($brief, 'json',["groups"=>["brief:read","briefOfPromo:read", "briefOfGroup:read"]]);
                return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
            }
            return new Response("Pas de brief assigné");
        }

    }

    //afficher l'ensemble des briefs

    /**
     * @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')", message="Acces non autorisé")
     * @Route("/api/formateurs/briefs",name="getAllBriefs",methods={"GET"})
    */

    public function getAllBriefs(BriefsRepository $briefRepo){
        $briefs = $briefRepo->findAll();
        $briefsJson = $this->serializer->serialize($briefs, 'json',["groups"=>["brief:read"]]);
        return new JsonResponse($briefsJson, Response::HTTP_OK,[],true);
    }


    

}
