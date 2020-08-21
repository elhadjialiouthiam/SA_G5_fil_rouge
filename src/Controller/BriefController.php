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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BriefController extends AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /** 
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
                // return new Response("trouve");
                $briefGroupe=$groupe->getBriefGroupe();
                $briefs = $briefGroupe->getBriefs();
                foreach ($briefs as $value) {
                    $valueJson = $this->serializer->serialize($value, 'json',["groups"=>["briefOfGroup:read"]]);
                    return new JsonResponse($valueJson,Response::HTTP_OK,[],true);
                }
            }
        }

    }

    //les briefs d'un promo

        /** 
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
        $briefJson = $this->serializer->serialize($brief, 'json',["groups"=>["briefOfPromo:read", "briefOfGroup:read"]]);
        return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
        // dd($briefPromo->getPromo());
        // dd($briefPromo);

    }

    //affichage des briefs brouillons

            /** 
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
        // dd($briefs);
        foreach ($briefs as $brief ) {
            if($brief->getEtats() == "brouillon"){
                $briefTab[] = $brief;
              
            }
        }
        $briefJson = $this->serializer->serialize($briefTab, 'json',["groups"=>["briefOfGroup:read"]]);
        return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
    }

                /** 
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
    // dd($briefs);
    $briefTab = [];
    foreach ($briefs as $brief ) {
        if($brief->getEtats() == "valide" || $brief->getEtats() == "non assigne"){
            $briefTab[] = $brief;
          
        }
    }
    if(empty($briefTab)){
       return new Response("Pas de brief validé ou non assigné"); 
    }
    $briefJson = $this->serializer->serialize($briefTab, 'json',["groups"=>["briefOfGroup:read"]]);
    return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
}

/**
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
                        // return $this->json($brief,Response::HTTP_OK);
                        $briefJson = $this->serializer->serialize($brief, 'json',["groups"=>["briefOfGroup:read"]]);
                        return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
                    }
                }
            }
        }
        return $this->json(["message" => "ressource inexistante."],Response::HTTP_NOT_FOUND);
    }

    /**
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
        // dd($briefPromos);
        foreach ($briefPromos as $briefPromo) {
            $briefApprenant = $briefPromo->getBriefAprennant();
            // dd($briefApprenant);
            if($briefApprenant->getStatut() == "assigne"){
                $brief = $briefPromo->getBriefs();
                $briefJson = $this->serializer->serialize($brief, 'json',["groups"=>["briefOfPromo:read"]]);
                return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
            }
            return new Response("Pas de brief assigné");
        }

    }

    

}
