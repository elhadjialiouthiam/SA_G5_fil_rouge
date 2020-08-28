<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Repository\PromosRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReferentielController extends AbstractController
{


    private $manager,
            $serializer,
            $validator,
            $ReferentielRepository;
    public function __construct(ValidatorInterface $validator,EntityManagerInterface $manager,SerializerInterface $serializer,ReferentielRepository $ReferentielRepository)
    {
        $this->serializer = $serializer;
        $this->ReferentielRepository = $ReferentielRepository;
        $this->manager = $manager;
        $this->validator = $validator;
    }
    // /**
    //  * @Route(
    //  *     path="/api/admin/referentiels",
    //  *     methods={"GET"},
    //  *     name="getReferentiel"
    //  * )
    // */
    // public function getReferentiels()
    // {
    //     // $this->autorisation();
    //     $referentiel = new Referentiel();
    //     $referentiel = $this->ReferentielRepository->findBy([
    //         "isDeleted" => false
    //     ]);
    //     return $this->json($promos,Response::HTTP_OK);
    // }

    /**
     * @Route(
     *     path="/api/admin/referentiels/grpecompetences",
     *     methods={"GET"},
     *     name="getGrpes"
     * )
    */
    public function getGrpeCompetence(ReferentielRepository $refRepository)
    {
        $referentiel = new Referentiel();
        $referentiel = $this->ReferentielRepository->findBy([
            "isDeleted" => false
        ]);
        
        // $grpCompetence = $referentiel->getGroupeComptence();
        $size = count($referentiel);
        for ($i=0; $i < $size; $i++) { 
            $grpCompetence[] = $referentiel[$i]->getGroupeComptence();
        }
        return $this->json($grpCompetence,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/admin/referentiels/{id}",
     *     methods={"GET"},
     *     name="getReferentiel"
     * )
    */
    public function getReferentiel($id, ReferentielRepository $refRepository)
    {
        // dd("nada");
        $referentiel = new Referentiel();
        $referentiel = $refRepository->findOneBy([
            "id" => $id
        ]);
        $ref = [];
        if($referentiel){
            if (!$referentiel->getIsDeleted()) {
                $ref[] = $referentiel->getGroupeComptence();
                return $this->json($ref,Response::HTTP_OK);
            }
        }
        return $this->json(["message" => "Ressource inexistante"],Response::HTTP_NOT_FOUND);
    }


    /**
    * @Route(
    *     path="api/admin/referentiels/{id}/grpecompetences/{idgrp}",
    *     methods={"GET"},
    *     name="getCompetence"
    * )
    */
    public function getCompetence($id,$idgrp, ReferentielRepository $refRepository){
        // dd("nada");
        $referentiel = new Referentiel();
        $referentiel = $refRepository->findOneBy([
            "id" => $id
        ]);
        if($referentiel){
            if (!$referentiel->getIsDeleted()) {
                $grpCompetence = $referentiel->getGroupeComptence();
                $size = count($grpCompetence);
                for ($i=0; $i < $size; $i++) { 
                    $competence[] = $grpCompetence[$i]->getCompetences();
                }
                return $this->json($competence,Response::HTTP_OK);
            }
        }
    }

    /**
     * @Route(
     *     path="/api/admin/referentiels",
     *     methods={"POST"},
     *     name="addReferentiel"
     * )
     */
    public function addReferentiel(Request $request,TokenStorageInterface $tokenStorage,ReferentielRepository $referentielRepository,GroupeCompetenceRepository $groupeCompetenceRepository,PromosRepository $promoRepository)
    {
        
        $referentiel = new Referentiel();
        $referentielJson = $request->getContent();
        $referentielTab =$this->serializer->decode($referentielJson,"json");
        $groupeCompetence = isset($referentielTab['groupeCompetence']) ? $referentielTab['groupeCompetence'] : [];
        $promos = isset($referentielTab["promos"]) ? $referentielTab["promos"] : [];
        $referentielTab['groupeCompetence'] = [];
        $referentielTab['promos']= [];
        $referntielObj = $this->serializer->denormalize($referentielTab,"App\Entity\Referentiel");
        $referntielObj->setIsDeleted(false);

        if ($groupeCompetence) {
            foreach ($groupeCompetence as $grpeCompet) {
                $grpCompetId = isset($grpeCompet["id"]) ? $grpeCompet["id"] : null;
                $grpCompetence = $groupeCompetenceRepository->findOneBy(["id" => $grpCompetId]);
                if (!$grpCompetence) {
                    return $this->json(["message" => "Ce groupe de competence n'existe pas."],Response::HTTP_NOT_FOUND);
                }
                $referntielObj->addGroupeComptence($grpCompetence);
            }
        }
        if ($promos) {
            foreach ($promos as $promotion) {
                $promoId = isset($promotion["id"]) ? $promotion["id"] : null;
                $promo = $promoRepository->findOneBy(["id" => $promoId]);
                if(!$promo){
                    return $this->json(["message" => "Cette promotion n'existe pas."],Response::HTTP_NOT_FOUND);
                }
                $referntielObj->addPromo($promo);
            }
        }
        // dd($referntielObj);
        $this->manager->persist($referntielObj);
        $this->manager->flush();
        return $this->json($referntielObj,Response::HTTP_CREATED);
    }


    /**
    * @Route(
    *     path="/api/admin/referentiels/{id<\d+>}",
    *     methods={"PUT"},
    *     name="setReferentiel"
    * )
    */
    public function setReferentiel($id,Request $request,EntityManagerInterface $manager,TokenStorageInterface $tokenStorage,ReferentielRepository $referentielRepository,GroupeCompetenceRepository $groupeCompetenceRepository,PromosRepository $promoRepository)
    {
        $referentiel = new Referentiel();
        $referentiel = $referentielRepository->findOneBy([
            "id"=>$id
        ]);
        if(!$referentiel || $referentiel->getIsDeleted())
            return  $this->json(["message" => "Ressource inexistante."],Response::HTTP_NOT_FOUND);
        $referentielJson = $request->getContent();
        $referentielTab = $this->serializer->decode($referentielJson, "json");
        $groupeCompetence = isset($referentielTab['groupeCompetence']) ? $referentielTab['groupeCompetence'] : [];
        $promos = isset($referentielTab["promos"]) ? $referentielTab["promos"] : [];
        $referentielTab['groupeCompetence'] = [];
        $referentielTab['promos']= [];
        $referntielObj = $this->serializer->denormalize($referentielTab,"App\Entity\Referentiel");
        $referntielObj->setIsDeleted(false);
        if(!count($groupeCompetence) || !isset($groupeCompetence[0]["id"]))
            return $this->json(["message" => "Le groupe de competence est obligatoire."],Response::HTTP_BAD_REQUEST);
        if(!count($promos) || !isset($promos[0]["id"]))
            return $this->json(["message" => "Le groupe de competence est obligatoire."],Response::HTTP_BAD_REQUEST);
        $IDgroupeCompetence = (int)($groupeCompetence[0]["id"]);
        $IDpromos = (int)($promos[0]["id"]);
        $newIDgroupeCompetence = $groupeCompetenceRepository->findOneBy([
            "id"=> $IDgroupeCompetence
        ]);
        $newIDpromos = $promoRepository->findOneBy([
            "id"=>$IDpromos
        ]);
        if (!$newIDgroupeCompetence || $newIDgroupeCompetence->getIsDeleted())
            return $this->json(["message" => "groupe de competence inexistant."],Response::HTTP_NOT_FOUND);
        $referntielObj->addGroupeComptence($newIDgroupeCompetence);

        if (!$newIDpromos || $newIDpromos->getIsDeleted())
            return $this->json(["message" => "Promo inexistant."],Response::HTTP_NOT_FOUND);
        
        $referntielObj->addPromo($newIDpromos);
        $referentiel->setLibelle($referntielObj->getLibelle())
                    ->setPresentation($referntielObj->getPresentation())
                    ->setProgramme($referntielObj->getProgramme())
                    ->setCritereAdmission($referntielObj->getCritereAdmission())
                    ->setCritereEvaluation($referntielObj->getCritereEvaluation());
        $manager->flush();
        return $this->json($referentiel,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="show_groupe_referentiel_id",
     *     path="/api/admin/referentiels/{id_referentiel}/groupe_competences/{id_groupe}",
     *     methods={"GET"}
     * )
     */
    public function showGroupe(int $id_referentiel, int $id_groupe, ReferentielRepository $referentielRepository,GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        $referentiel = $referentielRepository->find($id_referentiel);
        if(!$referentiel)
            return  $this->json(["message" => "Referentiel inexistant."],Response::HTTP_NOT_FOUND);
        $groupeCompetence = $groupeCompetenceRepository->find($id_groupe);
        if(!$groupeCompetence)
            return  $this->json(["message" => "Groupe de competence inexistant."],Response::HTTP_NOT_FOUND);


        foreach ($referentiel->getGroupeComptence() as  $value) {
            if ($value != $groupeCompetence) {
                $referentiel->removeGroupeCompetence($value);
            }
        }

        $referentielJson = $this->serializer->serialize($referentiel, 'json',["groups"=>["referentiel:read_all"]]);
        return new JsonResponse($referentielJson, Response::HTTP_OK, [], true);
    }
    
}
