<?php

namespace App\Controller;

use App\Entity\Briefs;
use App\Entity\BriefPromo;
use App\Entity\LivrableAttendu;
use App\Repository\TagRepository;
use App\Entity\LivrablesAprennant;
use App\Repository\BriefsRepository;
use App\Repository\GroupeRepository;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\RessourcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BriefGroupeRepository;
use App\Repository\ReferentielRepository;
use App\Repository\LivrableAttenduRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivrablesAprennantRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BriefController extends AbstractController
{

    private $serializer,
            $manager;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $this->serializer = $serializer;
        $this->manager = $manager;
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

    /**
     * @Route("/api/formateurs/{id}/promos/{idPromo}/briefs/{idBrief}",name="getBriefFormateurInPromo",methods={"GET"})
    */
    public function getBriefsFormateursInPromo($id,$idPromo,$idBrief,PromosRepository $promosRepository,BriefsRepository $briefRepository, FormateurRepository $formateurRepo)
    {
        
        
        $formateur = $formateurRepo->findOneBy(["id" => $id]);
        if($formateur)
        {
            $brief = $briefRepository->findOneBy(["id" =>$idBrief]);
            if($brief)
            {
                if ($formateur==$brief->getFormateur()) {
                    $promo = $promosRepository->findOneBy(["id" => $idPromo]);
                    if ($promo) {
                        $promoBriefs = $promo->getBriefPromos();
                        foreach ($promoBriefs as $promoBrief)
                        {
                            if($promoBrief->getBriefs() == $brief)
                            {
                                return $this->json($brief,Response::HTTP_OK);
                            }
                        }
                    }
                    return $this->json(["message" => "Promo inexistante."],Response::HTTP_NOT_FOUND);
                    
                }
                return $this->json(["message" => "Ce formateur n'a pas créé ce brief."],Response::HTTP_NOT_FOUND);
            }
            return $this->json(["message" => "Ce brief n'existe pas."],Response::HTTP_NOT_FOUND);
        }
        return $this->json(["message" => "Formateur inexistant."],Response::HTTP_NOT_FOUND);
    }

     /**
     * @Route("/api/apprenants/{id}/promos/{idPromo}/briefs/{idBrief}",name="getApprenantNews",methods={"GET"})
    */
    public function getApprenantNews($id,$idPromo,$idBrief,PromosRepository $promosRepository,BriefsRepository $briefRepository, ApprenantRepository $apprenantRepo)
    {
        $apprenant = $apprenantRepo->findOneBy(["id"=>$id]);
        if ($apprenant) {
            $brief = $briefRepository->findOneBy(["id"=>$idBrief]);
            if ($brief) {
                foreach ($brief->getBriefPromos() as $value) {
                    if ($value->getBriefAprennant() == $apprenant->getBriefAprennant()) {
                        $promo = $promosRepository->findOneBy(["id"=>$idPromo]);
                        $promoBriefs = $promo->getBriefPromos();
                        foreach ($promoBriefs as $promoBrief)
                        {
                            if($promoBrief->getBriefs() == $brief)
                            {
                                // return $this->json($apprenant->getApprenantLivrablepratielles());
                                
                                // dd($apprenant->getApprenantLivrablepratielles());
                                foreach ($apprenant->getApprenantLivrablepratielles() as $app) {
                                    $briefJson = $this->serializer->serialize($app, 'json',["groups"=>["apprenantlivable:read"]]);
                                return new JsonResponse($briefJson,Response::HTTP_OK,[],true);
                                }
                                
                            }
                        }
                    }
                }
                
            }
        }
    }
    /**
     * @Route("/api/formateurs/briefs/{id}",name="dupliquer",methods={"POST"})
    */
    public function dupliquer($id,TokenStorageInterface $tokenStorage, BriefsRepository $briefRepository, FormateurRepository $formateurRepo, ReferentielRepository $refRepo, NiveauRepository $niveauRpo, LivrableAttenduRepository $lvrAttenduRep,TagRepository $tagRepo, RessourcesRepository $ressourceRepo)
    {
        $briefs = $briefRepository->findOneBy([
            "id"=>$id
        ]);
        if ($briefs) {
            $newBrief = new Briefs();
            $newBrief->setTitre($briefs->getTitre())
                  ->setEnonce($briefs->getEnonce())
                  ->setContext($briefs->getContext())
                  ->setCreatedAt($briefs->getCreatedAt())
                  ->setDateEcheance($briefs->getDateEcheance())
                  ->setEtats($briefs->getEtats())
                  ->setBriefGroupe($briefs->getBriefGroupe());
            $formateur = $tokenStorage->getToken()->getUser();
                  // on l'affecte le formateur actuellement connecté
            $newBrief->setFormateur($formateur);
                  // on lui desafete le briefpromo de l'ancien
            foreach ($newBrief->getBriefPromos() as $briefPromo) {
                $newBrief->removeBriefPromo($briefPromo);
            }
            $this->manager->persist($newBrief);
            $this->manager->flush();
            return new JsonResponse("Brief dupliqué avec succes",Response::HTTP_OK,[],true);
        }
        return $this->json(["message" => "Impossible de dupliquer un brief qui n'existe pas."],Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/formateurs/briefs",name="addBrief",methods={"POST"})
    */
    public function addBrief(Request $request,\Swift_Mailer $mailer, GroupeRepository $grpeRepo, BriefGroupeRepository $briefGroupeRepo ,BriefsRepository $briefRepository, FormateurRepository $formateurRepo, ReferentielRepository $refRepo, NiveauRepository $niveauRpo, LivrableAttenduRepository $lvrAttenduRep,TagRepository $tagRepo, RessourcesRepository $ressourceRepo)
    {
        $brief = new Briefs();
        $briefjson = $request->getContent();
        $briefTab = $this->serializer->decode($briefjson, "json");
        
        $referentielId = $briefTab['referentiel']['id'];
        $formateurId = $briefTab['formateur']['id'];
        $briefsGroupeId = $briefTab['briefGroupe']["id"];
        $groupes = $briefTab['briefGroupe']["groupe"];
        $niveaux = isset($briefTab['niveaux']) ? $briefTab['niveaux'] : [];
        $livrableAttendus = isset($briefTab['livrableAttendus']) ? $briefTab['livrableAttendus'] : [];
        $tags = isset($briefTab['tags']) ? $briefTab['tags'] : [];
        $ressources = isset($briefTab['ressources']) ? $briefTab['ressources'] : [];
        // dd($groupes);

        $briefTab["formateur"]=null;
        $briefTab["referentiel"]=null;
        $briefTab["niveaux"]=[];
        $briefTab["livrableAttendus"]=[];
        $briefTab["tags"]=[];
        $briefTab["ressources"]=[];
        $briefTab["briefGroupe"]= null;


        $briefObj = $this->serializer->denormalize($briefTab, "App\Entity\Briefs");

        $referentiel = $refRepo->findOneBy(["id"=>$referentielId]);
        if (!$referentiel) {
            return $this->json(["message" => "Ce referentiel n'existe pas"]);
        }
        $formateur = $formateurRepo->findOneBy(["id"=>$formateurId]);
            if (!$formateur) {
                return $this->json(["message" => "Ce formateur n'existe pas"]);
            }
        
        

        $briefObj->setFormateur($formateur)
                ->setReferentiel($referentiel);

        // Gestion Niveau
        if (!$niveaux) {
            return $this->json(["message" => "vous devez mettre au moins un niveau"]);
        }
        foreach ($niveaux as $niveau) {
            $niveauId = isset($niveau['id']) ? $niveau["id"]: null;
            $niv = $niveauRpo->findOneBy(["id"=>$niveauId]);
            if (!$niv) {
                return $this->json(["message" => "Ce niveau n'existe pas"]);
            }
            $briefObj->addNiveau($niv);
        }

        // gestion livrableAttendus
        if (!$livrableAttendus) {
            return $this->json(["message" => "vous devez mettre au moins un Livrablle Attendu"]);
        }
        foreach ($livrableAttendus as $livrableAttendu) {
            $livrableAttendu_Id = isset($livrableAttendu["id"])? $livrableAttendu["id"] : null;
            $livrblAttendu = $lvrAttenduRep->findOneBy(["id"=>$livrableAttendu_Id]);
            if (!$livrblAttendu) {
                return $this->json(["message" => "Ce livrable attendu n'existe pas"]);
            }
            $briefObj->addLivrableAttendu($livrblAttendu);
        }

        // Gestion Tags
        if (!$tags) {
            return $this->json(["message" => "vous devez mettre au moins un tag pour ce brief"]);
        }
        foreach ($tags as $tag) {
            $tag_id = isset($tag["id"])? $tag["id"] : null;
            $tagToStore = $tagRepo->findOneBy(["id"=>$tag_id]);
            if (!$tagToStore) {
                return $this->json(["message" => "Ce tag n'existe pas"]);
            }
            $briefObj->addTag($tagToStore);
        }

        // Gestion ressources
        if (!$ressources) {
            return $this->json(["message" => "vous devez mettre au moins un tag pour ce brief"]);
        }
        foreach ($ressources as $ressource) {
            $ressource_id = isset($ressource["id"])? $ressource["id"] : null;
            $ressourceToStore = $ressourceRepo->findOneBy(["id"=>$ressource_id]);
            if (!$ressourceToStore) {
                return $this->json(["message" => "Cette ressource n'existe pas"]);
            }
            $briefObj->addRessource($ressourceToStore);
        }


        $brifGroupe = $briefGroupeRepo->findOneBy(["id"=>$briefsGroupeId]);
        $briefObj->setBriefGroupe($brifGroupe);
        $this->manager->persist($briefObj);
        if ($groupes) {
            
            foreach ($groupes as $groupe) {
                //dd($groupe);
                if ($grpeRepo->findOneBy(["id"=>$groupe['id']])) {
                    $brifGroupe->addGroupe($grpeRepo->findOneBy(["id"=>$groupe['id']]));
                    $promo = $grpeRepo->findOneBy(["id"=>$groupe['id']])->getPromos();
                    $briefPromo = new BriefPromo();
                    $briefPromo->setPromo($promo);
                    $briefPromo->setBriefs($briefObj);
                    $this->manager->persist($briefPromo);
                    
                    foreach ($grpeRepo->findOneBy(["id"=>$groupe['id']])->getApprenants() as $value) {
                        //dd($value);
                        $this->sendEmail($mailer, $value, $briefObj);
                    }
                }
            }
            
            
        }
        $this->manager->flush();
        return new JsonResponse("Brief ajouté avec succés",Response::HTTP_OK,[],true);
    }
    /**
     * @Route("/api/formateurs/promo/{id_promo}/brief/{id_brief}/assignation",name="assignation",methods={"PUT"})
    */
    public function assignation($id_promo,$id_brief, GroupeRepository $grouperepo,BriefsRepository $briefRepository, PromosRepository $promoRepo,Request $request, ApprenantRepository $ApprenantRepo)
    {
        $promo = $promoRepo->findOneBy(["id"=>$id_promo]);
        if ($promo) {
            $brief = $briefRepository->findOneBy(["id"=>$id_brief]);
            if ($brief) {
                $promoBriefs = $promo->getBriefPromos();
                foreach ($promoBriefs as $promoBrief)
                {
                    if($promoBrief->getBriefs() == $brief)
                    {
                        $contentJson = $request->getContent();
                        $contentTab = $this->serializer->decode($contentJson, "json");
                        $apprenants =  isset($contentTab['apprenant']['id']) ? $contentTab['apprenant']['id'] : null;
                        $OneGroupes = isset($contentTab['groupe']['id']) ? $contentTab['groupe']['id'] : null;
                        $groupes = isset($contentTab['groupes']) ? $contentTab['groupes'] : [];

                        $contentTab["apprenant"] = null;
                        $contentTab["groupe"] = null;
                        $contentTab["groupes"] = [];
                        $contentObj = $this->serializer->denormalize($contentTab, "App\Entity\Briefs");
                        $apprenant = $ApprenantRepo->findOneBy(["id"=>$apprenants]);
                        $OneGroupe = $grouperepo->findOneBy(["id"=>$OneGroupes]);
                        if ($apprenant) {
                            $briefPromo = $brief->getBriefPromos();
                            foreach ($briefPromo as $value) {
                                if ($value->getBriefAprennant() == $apprenant->getBriefAprennant()) {
                                    $value->getBriefAprennant()->removeApprenant($apprenant);
                                    $this->manager->flush();
                                    return $this->json("Cet Apprenant n'est plus concerné par ce brief");
                                }
                                else {
                                    $value->getBriefAprennant()->addApprenant($apprenant);
                                    $this->manager->flush();
                                    return $this->json("Brief affecte à cet apprenant");
                                }
                            }
                        }
                        elseif ($OneGroupe) {
                            $briefGrp = $brief->getBriefGroupe();
                            if ($briefGrp == $OneGroupe->getBriefGroupe()) {
                                $briefGrp->removeGroupe($OneGroupe);
                                $this->manager->flush();
                                return $this->json("Brief desafecté au groupe");
                            }
                            else {
                                if ($OneGroupe->getType() == "secondaire") {
                                    $promo = $OneGroupe->getPromos();
                                    $briefPromo = new BriefPromo();
                                    $briefPromo->setPromo($promo);
                                    $briefGrp->addGroupe($OneGroupe);
                                    $this->manager->persist($briefPromo);
                                    $this->manager->flush();
                                    return $this->json("Brief affecté au groupe");
                                }
                                $briefGrp->addGroupe($OneGroupe);
                                $this->manager->flush();
                                return $this->json("Brief affecté au groupe");
                            }
                        }
                        elseif ($groupes) {
                            $briefGrp = $brief->getBriefGroupe();
                            foreach ($groupes as $groupe) {
                                $groupe_id = isset($groupe["id"])? $groupe["id"] : null;
                                $grp = $grouperepo->findOneBy(["id"=>$groupe_id]);
                                if ($briefGrp == $grp->getBriefGroupe()) {
                                    $briefGrp->removeGroupe($grp);
                                    $this->manager->flush();
                                    return $this->json("Brief desafecté au groupe");
                                }
                                else {
                                    if ($grp->getType() == "secondaire") {
                                        $promo = $grp->getPromos();
                                        $briefPromo = new BriefPromo();
                                        $briefPromo->setPromo($promo);
                                        $briefGrp->addGroupe($grp);
                                        $this->manager->persist($briefPromo);
                                        $this->manager->flush();
                                        return $this->json("Brief affecté au groupe");
                                    }
                                    $briefGrp->addGroupe($grp);
                                    $this->manager->flush();
                                    return $this->json("Brief affecté au groupe");
                                }

                            }
                        }
                        
                    }
                }
            }
            
        }
    }

    /**
     * @Route("/api/formateurs/promo/{id_promo}/brief/{id_brief}",name="setBriefs",methods={"PUT"})
    */
    public function setBriefs($id_promo, $id_brief,PromosRepository $promoRepo,Request $request,GroupeRepository $grpeRepo, BriefGroupeRepository $briefGroupeRepo ,BriefsRepository $briefRepository, FormateurRepository $formateurRepo, ReferentielRepository $refRepo, NiveauRepository $niveauRpo, LivrableAttenduRepository $lvrAttenduRep,TagRepository $tagRepo, RessourcesRepository $ressourceRepo)
    {
        $promo = $promoRepo->findOneBy(["id" => $id_promo]);
        if($promo)
        {
            $brief = $briefRepository->findOneBy(["id" =>$id_brief]);
            if($brief)
            {
                $promoBriefs = $promo->getBriefPromos();
                foreach ($promoBriefs as $promoBrief)
                {
                    if($promoBrief->getBriefs() == $brief)
                    {
                        $contentJson = $request->getContent();
                        $briefTab = $this->serializer->decode($contentJson, "json");

                        $etat = isset($briefTab["etat"]) ? $briefTab["etat"] : null;
                        $niveaux = isset($briefTab['niveaux']) ? $briefTab['niveaux'] : [];
                        $livrableAttendus = isset($briefTab['livrableAttendus']) ? $briefTab['livrableAttendus'] : [];
                        $tags = isset($briefTab['tags']) ? $briefTab['tags'] : [];
                        $ressources = isset($briefTab['ressources']) ? $briefTab['ressources'] : [];

                        $briefTab["niveaux"]=[];
                        $briefTab["livrableAttendus"]=[];
                        $briefTab["tags"]=[];
                        $briefTab["ressources"]=[];

                        $briefObj = $this->serializer->denormalize($briefTab, "App\Entity\Briefs");
                        if ($etat) {
                            
                            $brief->setEtats($etat);
                            $this->manager->flush();
                            return $this->json(["message" => "statut changé avec succés"]);
                        }
                        
                        if ($niveaux) {
                            foreach ($niveaux as $niveau) {
                                $niveauId = isset($niveau['id']) ? $niveau["id"]: null;
                                $niv = $niveauRpo->findOneBy(["id"=>$niveauId]);
                                if ($niv) {
                                    if (count($brief->getNiveaux()) == 0) {
                                            $brief->addNiveau($niv);
                                            $this->manager->flush();
                                            return $this->json(["message" => "Niveau ajouté au brief"]);
                                    }
                                    else {
                                        foreach ($brief->getNiveaux() as $value) {
                                            // dd($niv != $value);
                                            if ($niv != $value) {
                                                $brief->addNiveau($niv);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Niveau ajouté au brief"]);
                                            }
                                            else {
                                                $brief->removeNiveau($niv);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Niveau Supprimé du brief"]);
                                            }
                                        }
                                    }
                                    
                                }
                                
                            }
                        }

                        elseif ($livrableAttendus) {
                            foreach ($livrableAttendus as $livrableAttendu) {
                                $livrableAttendu_Id = isset($livrableAttendu["id"])? $livrableAttendu["id"] : null;
                                $livrblAttendu = $lvrAttenduRep->findOneBy(["id"=>$livrableAttendu_Id]);
                                if ($livrblAttendu) {
                                    if (count($brief->getLivrableAttendus()) == 0) {
                                            $brief->addLivrableAttendu($livrblAttendu);
                                            $this->manager->flush();
                                            return $this->json(["message" => "Livrable attendu ajoute au brief"]);
                                    }
                                    else {
                                        foreach ($brief->getLivrableAttendus() as $value) {
                                            if ($livrableAttendu != $value) {
                                                $brief->addLivrableAttendu($livrblAttendu);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Livrable attendu ajoute au brief"]);
                                            }
                                            else {
                                                $brief->removeLivrableAttendu($livrblAttendu);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Livrable supprimé du brief"]);
                                            }
                                        }
                                    }
                                    

                                }
                                else {
                                    return $this->json(["message" => "Ce livrable attendu n'existe pas"]);  
                                }
                                
                            }
                        }
                        elseif ($tags) {
                            
                            foreach ($tags as $tag) {
                                
                                $tag_id = isset($tag["id"])? $tag["id"] : null;
                                $tagToStore = $tagRepo->findOneBy(["id"=>$tag_id]);
                                
                                if ($tagToStore) {
                                    if (count($brief->getTags()) == 0) {
                                            $brief->addTag($tagToStore);
                                            $this->manager->flush();
                                            return $this->json(["message" => "Tag ajouté au brief"]);
                                    }
                                    else {
                                        foreach ($brief->getTags() as $value) {
                                        
                                            if ($tagToStore != $value) {
                                                $brief->addTag($tagToStore);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Tag ajouté au brief"]);
                                            }
                                            else {
                                                $brief->removeTag($tagToStore);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Tag supprimé au brief"]);
                                            }
                                        }
                                    }
                                    
                                    
                                }
                                else {
                                    return $this->json(["message" => "Ce tag n'existe pas"]);
                                }
                                
                               
                            }
                        }
                        elseif ($ressources) {
                            foreach ($ressources as $ressource) {
                                $ressource_id = isset($ressource["id"])? $ressource["id"] : null;
                                $ressourceToStore = $ressourceRepo->findOneBy(["id"=>$ressource_id]);
                                if ($ressourceToStore) {
                                    if (count($brief->getRessources()) == 0) {
                                        $brief->addRessource($ressourceToStore);
                                        $this->manager->flush();
                                        return $this->json(["message" => "Ressources ajouté au brief"]);
                                    }
                                    else {
                                        foreach ($brief->getRessources() as $value) {
                                            if ($ressourceToStore != $value) {
                                                $brief->addRessource($ressourceToStore);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Ressources ajouté au brief"]);
                                            }
                                            else {
                                                $brief->removeRessource($ressourceToStore);
                                                $this->manager->flush();
                                                return $this->json(["message" => "Ressource supprime au brief"]);
                                            }
                                        }
                                    }
                                    
                                   
                                }
                                else {
                                    return $this->json(["message" => "Cette ressource n'existe pas"]);
                                }
                                
                                
                            }
                        }
                        
                    }
                }
            }
        }
    }

    /**
     * @Route("/api/apprenant/{id}/groupe/{id_groupe}/livrables",name="addUrl",methods={"POST"})
    */
    public function addUrl($id, $id_groupe, LivrableAttenduRepository $livrableAttneduRepo ,LivrablesAprennantRepository $lAprRepo, Request $request,ApprenantRepository $apprenantRepo, GroupeRepository $groupeRepo)
    {
        $apprenant = $apprenantRepo->findOneBy(["id"=>$id]);
        
        if ($apprenant) {
            $groupe = $groupeRepo->findOneBy(["id"=>$id_groupe]);
            if ($groupe) {
                // return $this->json($groupe->getApprenants());
                foreach ($groupe->getApprenants() as $apprenant_in_this_groupe) {
                    if ($apprenant_in_this_groupe == $apprenant) {
                        $livrableApprenant = new LivrablesAprennant();
                        $contentJson = $request->getContent();
                        $contentTab = $this->serializer->decode($contentJson, "json");
                        $livrableAttendu = $livrableAttneduRepo->findOneBy(["id"=>$contentTab["livrableAttendu"]["id"]]);
                        if ($livrableAttendu) {
                            $livrableApprenant->setLivrableAttendu($livrableAttendu);
                        }
                        if ($contentTab["url"]) {
                            $livrableApprenant->setLien($contentTab["url"]);
                            $sonGroupe = $apprenant->getGroupes();
                            foreach ($sonGroupe as $grupes) {
                                $apprnant = $grupes->getApprenants();
                                foreach ($apprnant as $aprnt) {
                                    foreach ($aprnt->getLivrablesAprennants() as $apprenantLiv) {
                                        $apprenantLiv->setLien($contentTab["url"]);
                                    }
                                    
                                }
                            }
                        }
                        
                        $livrableApprenant->setApprenant($apprenant);
                        $this->manager->persist($livrableApprenant);
                        $this->manager->flush();
                        
                        return $this->json("Good",Response::HTTP_CREATED);
                        
                    }
                    return $this->json("not in",Response::HTTP_NOT_FOUND);
                }
            }
            else {
                return $this->json(["message" => "Groupe inexistant"],Response::HTTP_NOT_FOUND);
            }
        }else {
            return $this->json(["message" => "Apprenant inexistant"],Response::HTTP_NOT_FOUND);
        }
        
    }

    
    
    public function sendEmail(\Swift_Mailer $mailer, $user, $brief)
    {
        $msg = (new \Swift_Message('Sonatel Academy'))
            ->setFrom('abdoudiallo405@gmail.com')
            ->setTo($user->getEmail())
            ->setBody("Vous avez été assigné au brief " . strtoupper($brief->getTitre()) . ".Veuillez vous connecter sur la plateforme pour voir les détails.");
        $mailer->send($msg);
    }
}
