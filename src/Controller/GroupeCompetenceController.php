<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GroupeCompetenceController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/grpecompetences",
     *     methods={"GET"},
     *     name="getGroupeCompetences"
     * )
     */
    public function getGroupeCompetences(GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        if(!($this->isGranted("ROLE_ADMIN")))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $groupeCompetences = $groupeCompetenceRepository->findBy([
            "isDeleted" => false
        ]);
        return $this->json($groupeCompetences,Response::HTTP_OK);
    }
    /**
     * @Route(
     *     path="/api/admin/grpecompetences/{id<\d+>}",
     *     methods={"GET"},
     *     name="getGroupeCompetence"
     * )
     */
    public function getGroupeCompetence($id,GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        $groupeCompetence = new GroupeCompetence();
        if(!($this->isGranted("VIEW",$groupeCompetence)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $groupeCompetence = $groupeCompetenceRepository->findOneBy([
            "id" => $id
        ]);
        if($groupeCompetence){
            if (!$groupeCompetence->getIsDeleted())
                return $this->json($groupeCompetence,Response::HTTP_OK);
        }
        return $this->json(["message" => "Ressource inexistante"],Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route(
     *     path="/api/admin/grpecompetences/{id<\d+>}/competences",
     *     methods={"GET"},
     *     name="getCompetencesInGroupeCompetence"
     * )
     */
    public function getCompetencesInGroupeCompetence($id,GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        $groupeCompetence = new GroupeCompetence();
        if(!($this->isGranted("VIEW",$groupeCompetence)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $groupeCompetence = $groupeCompetenceRepository->findOneBy([
            "id" => $id
        ]);
        if($groupeCompetence){
            if (!$groupeCompetence->getIsDeleted()){
                $competences = $groupeCompetence->getCompetences();
                return $this->json($competences,Response::HTTP_OK);
            }
        }
        return $this->json(["message" => "Ressource inexistante"],Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route(
     *     path="/api/admin/grpecompetences/competences",
     *     methods={"GET"},
     *     name="getCompetences"
     * )
     */
    public function getCompetences(GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        $groupeCompetence = new GroupeCompetence();
        if(!($this->isGranted("VIEW",$groupeCompetence)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $groupeCompetence = $groupeCompetenceRepository->findBy([
            "isDeleted" => false
        ]);
        $competences = [];
        $size = count($groupeCompetence);
        for ($i = 0;$i < $size; $i++){
//            if(!$groupeCompetence[$i]->getIsDeleted()){
                $competence = $groupeCompetence[$i]->getCompetences();
                $length = count($competence);
                for ($j = 0; $j < $length; $j++){
                    $skill = $competence[$j];
                    if(!$skill->getIsDeleted()){
                        $competences[] = $skill;
                    }
                }
//            }
        }
        return $this->json($competences,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/admin/grpecompetences",
     *     methods={"POST"},
     *     name="addGroupeCompetence"
     * )
     */
    public function addGroupeCompetence(CompetenceRepository $competenceRepository,TokenStorageInterface $tokenStorage,Request $request,EntityManagerInterface $manager,SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $groupeCompetence = new GroupeCompetence();
        if(!$this->isGranted("EDIT",$groupeCompetence))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $groupeCompetenceJson = $request->getContent();
        $administrateur = $tokenStorage->getToken()->getUser();
        $groupeCompetenceTab = $serializer->decode($groupeCompetenceJson,"json");
        $competences = $groupeCompetenceTab["competences"];
        
        $groupeCompetenceTab["competences"] = [];
        $groupeCompetenceObj = $serializer->denormalize($groupeCompetenceTab,"App\Entity\GroupeCompetence");
        $groupeCompetenceObj->setIsDeleted(false)
            ->setAdministrateur($administrateur);
        $groupeCompetenceObj = $this->addComptenceToGroupe($competences,$serializer,$groupeCompetenceObj,$manager,$competenceRepository);
        // dd($groupeCompetenceObj);
        $errors = (array)$validator->validate($groupeCompetenceObj);
        // dd($competences);
        // if(count($errors))
        //     return $this->json($errors,Response::HTTP_BAD_REQUEST);
        if (!count($competences))
            return $this->json(["message" => "Ajoutez au moins une competence à cet groupe de competence."],Response::HTTP_BAD_REQUEST);
        // dd($groupeCompetenceObj);
        $manager->persist($groupeCompetenceObj);
        $manager->flush();
        return $this->json($groupeCompetenceObj,Response::HTTP_CREATED);
    }

     private function addCompetence(GroupeCompetence $groupeCompetence,$competences)
    {
        // dd($groupeCompetence);
        foreach ($competences as $competence){
            $groupeCompetence->addCompetence($competence);
        }
        // dd($groupeCompetence);
        return $groupeCompetence;
    }


    private function addComptenceToGroupe($competences,$serializer,$groupeCompetenceObj,$manager,$competenceRepository)
    {
        foreach ($competences as $comptence){
            $skill = $serializer->denormalize($comptence,"App\Entity\Competence");
            $id = isset($comptence["id"]) ? (int)$comptence["id"] : null;
            if($id)
            {
                $skill = $competenceRepository->findOneBy([
                    "id" => $id
                ]);
                
                if(!$skill)
                    return $this->json(["message" => "La competence avec l'id : $id, n'existe pas."],Response::HTTP_NOT_FOUND);
                  
                $groupeCompetenceObj->addCompetence($skill);
                // dd($skill);  
            }else{
                $skill->setId($id);
                $skill->setIsDeleted(false);
                // $error = (array) $validator->validate($skill);
                // // dd($error);
                // if (count($error))
                //     return $this->json($error,Response::HTTP_BAD_REQUEST);
                $manager->persist($skill);
                $groupeCompetenceObj->addCompetence($skill);
            }
        }
        // dd($groupeCompetenceObj);
        return $groupeCompetenceObj;
    }
}
