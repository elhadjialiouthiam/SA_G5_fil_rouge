<?php

namespace App\Controller;

use App\Entity\Promos;
use App\Entity\Commentaire;
use App\Entity\LivrableAttendu;
use App\Entity\LivrablesPartiels;
use App\Repository\BriefsRepository;
use App\Repository\GroupeRepository;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use JMS\Serializer\SerializerInterface;
use App\Repository\BriefPromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use App\Entity\ApprenantLivrablepratielle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CompetencesValideRepository;
use App\Repository\LivrablesPartielsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use ContainerBh74dR1\getApprenantRepositoryService;
use App\Repository\ApprenantLivrablepratielleRepository;
use App\Repository\BriefAprennantRepository;
use ContainerKSzw4ay\getLivrablesPartielsRepositoryService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/api")
 */

class LivrablesPartielsController extends AbstractController
{

    /**
     * @Route(
     * path="/formateurs/promo/{id_promo}/referentiel/{id_ref}/competences", 
     * name="show_competences_by_apprenant",
     * methods="GET"
     * )
     */
    public function showCompetenceByApprenant(SerializerInterface $serializer, int $id_promo, int $id_ref, ReferentielRepository $repoRefrerentiel, PromosRepository $repoPromo)
    {
        $referentiel = $repoRefrerentiel->find($id_ref);
        if (empty($referentiel)) {
            return new JsonResponse("Ce referentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promo = $repoPromo->find($id_promo);
        if (empty($promo) || !$referentiel->getPromos()->contains($promo)) {
            return new JsonResponse("Cette promotion n'existe pas dans ce referentiel", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenants = $promo->getApprenants();
        $apprenantsJson = $serializer->serialize($apprenants, 'json', ["groups" =>["apprenant_competence:read"] ]);
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route(
     * path="/apprenant/{id_apprenant}/promo/{id_promo}/referentiel/{id_ref}/competences", 
     * name="show_competences_by_apprenant_id",
     * methods="GET"
     * )
     */
    public function showCompetenceByApprenantId(SerializerInterface $serializer, int $id_apprenant, int $id_promo, int $id_ref, ApprenantRepository $repoApprenant, ReferentielRepository $repoRefrerentiel, PromosRepository $repoPromo)
    {
        $referentiel = $repoRefrerentiel->find($id_ref);
        if (empty($referentiel)) {
            return new JsonResponse("Ce referentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promo = $repoPromo->find($id_promo);
        if (empty($promo) || !$referentiel->getPromos()->contains($promo)) {
            return new JsonResponse("Cette promotion n'existe pas dans ce referentiel..", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenant = $repoApprenant->find($id_apprenant);
        if (empty($apprenant) || !$promo->getApprenants()->contains($apprenant)) {
            return new JsonResponse("Cet apprenant n'existe pas dans cette promotion..", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenantJson = $serializer->serialize($apprenant, 'json', ["groups" => ["apprenant_competence:read"]]);
        return new JsonResponse($apprenantJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route(
     * path="/apprenants/{id_apprenant}/promo/{id_promo}/referentiel/{id_ref}/statistiques/briefs", 
     * name="show_statistiques_by_apprenant_id",
     * methods="GET"
     * )
     */
    public function showStatistiquesByApprenantId(int $id_apprenant, int $id_promo, int $id_ref, ApprenantRepository $repoApprenant, BriefAprennantRepository $repoBriefApprenant, ReferentielRepository $repoReferentiel)
    {
        $apprenant = $repoApprenant->findOneBySomeField($id_apprenant, $id_promo);
        if (!$apprenant) {
            return new JsonResponse("Cet apprenant n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        $referentiel = $repoReferentiel->find($id_ref);
        $promo = $apprenant->getPromotion();
        if (!$promo->getReferentiels()->contains($referentiel)) {
            return new JsonResponse("Ce referentiel n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

    }

    /**
     * @Route(
     * path="/formateurs/promo/{id_promo}/referentiel/{id_ref}/statistiques/competences", 
     * name="show_statistiques_by_competences",
     * methods="GET"
     * )
     */
    public function showStatistiquesByCompetences()
    {
        
    }

    /**
     * @Route(
     * path="/formateurs/livrablepartiels/{id_livrable}/commentaires", 
     * name="get_commentaires_by_livrablePartiel", 
     * methods="GET"
     * )
     */
    public function getCommentairesByLivrablePartiel(SerializerInterface $serializer, int $id_livrable, LivrablesPartielsRepository $repoLivrablePartiels)
    {
        $livrablePartiel = $repoLivrablePartiels->find($id_livrable);
        if (empty($livrablePartiel)) {
            return new JsonResponse("Ce livrablespartiels n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $commentaires = $livrablePartiel->getApprenantLivrablepratielles();
        $commentairesJson = $serializer->serialize($commentaires, 'json',["groups" => ["commentaire:read"]]);
        return new JsonResponse($commentairesJson, Response::HTTP_OK, [], true);
    }
    /**
     * @Route(
     * path="/formateurs/livrablespartiels/{id_livrable}/commentaires", 
     * name="post_commentaire_by_formateur", 
     * methods="POST"
     * )
     */
    public function postCommentaireByFormateur(FormateurRepository $repoFormateur, Request $request, EntityManagerInterface $em, int $id_livrable, LivrablesPartielsRepository $repoLivrablePartiels,TokenStorageInterface $tokenStorage)
    {
        $data = $request->request->all();
        if (!isset($data['contenu']) || empty($data['contenu'])) {
            return new JsonResponse("Veuillez remplir le contenu du commentaire.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $commentaire = new Commentaire();
        $commentaire->setContenu($data['contenu']);
        $commentaire->setDate(new \DateTime());
        $file = $request->files;
        if ($file->get('PieceJointe') !== null) {
            $commentaire->setPieceJointe($this->uploadFile($file->get('PieceJointe'), "PieceJointe"));
        }
        $formateur = $tokenStorage->getToken()->getUser();
        $commentaire->setFormateur($formateur);



        $livrablePartiel = $repoLivrablePartiels->find($id_livrable);
        foreach ($livrablePartiel->getApprenantLivrablepratielles() as $value) {
            $value->addCommentaire($commentaire);
        }
        $em->persist($commentaire);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    //Fonction traitement image
    public function uploadFile($file, $name)
    {
        $fileType = explode("/", $file->getMimeType())[1];
        $filePath = $file->getRealPath();
        return file_get_contents($filePath, $name . '.' . $fileType);
    }

    /**
     * @Route(
     * path="/apprenant/livrablespartiels/id/commentaires", 
     * name="post_commentaire_by_apprenant",
     * methods="POST"
     * )
     */
    
    public function  postCommentaireByApprenant(ApprenantRepository $repoApprenant, Request $request, EntityManagerInterface $em, int $id, LivrablesPartielsRepository $repoLivrablePartiels,TokenStorageInterface $tokenStorage)
    {

        $data = $request->request->all();

        if (!isset($data['contenu']) || empty($data['contenu'])) {
            return new JsonResponse("Veuillez remplir le contenu du commentaire.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $commentaire = new Commentaire();
        $commentaire->setContenu($data['contenu']);
        $commentaire->setDate(new \DateTime());
        $file = $request->files;
        if ($file->get('PieceJointe') !== null) {
            $commentaire->setPieceJointe($this->uploadFile($file->get('PieceJointe'), "PieceJointe"));
        }

        $apprenant = $tokenStorage->getToken()->getUser();
        $apprenant = $repoApprenant->findBy($apprenant);

        $livrablePartiel = $repoLivrablePartiels->find($id);
        foreach ($livrablePartiel->getApprenantLivrablepratielles() as $value) {
            if ($value->getApprenant() == $apprenant) {
                $value->addCommentaire($commentaire);
            }
        }
        $em->persist($commentaire);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    
    /**
     * @Route(
     * path="/formateurs/promo/{id_promo}/brief/{id_brief}/livrablespartiels", 
     * name="put_livrable_partiel_by_formateur",
     * methods="PUT"
     * )
     */
    public function putLivrablePartielByFormateur( Request $request, $id_promo, $id_brief,EntityManagerInterface $manager, PromosRepository $promoRepository, BriefPromoRepository $briefPromoRepository, LivrablesPartielsRepository $livrablePartielRepository)
    {
        //Archivage livrablespartiels
        $promo = new Promos();
        $data = json_decode($request->getContent(), true);
        $promo = $promoRepository->findOneBy(["id" => $id_promo]);
        $briefPromo = $briefPromoRepository->findBy(['promo'=> $id_promo, 'briefs'=> $id_brief]);
        $livrablePartiel = $livrablePartielRepository->findOneBy(['id'=> $data['id']]);
        if ($livrablePartiel && !$livrablePartiel->getDeleted()) {
            $livrablePartiel->setDeleted(true);
            $briefPromo[0]->removeLivrablePartiel($livrablePartiel);
            $manager->flush();
            return $this->json($briefPromo,Response::HTTP_OK);
        }
        return $this->json(["livrables partieles archivé avec succes"],Response::HTTP_NOT_FOUND);



    }
    /**
     * @Route(
     * path="/apprenants/{id_apprenant}/livrablepartiels/{id_livrable}", 
     * name="put_statut_by_apprenant",
     * methods="PUT"
     * )
     */
    public function updateLivrablePartiel(Request $request,  $id_apprenant, $id_livrable, EntityManagerInterface $em, LivrablesPartielsRepository $livrablePartielRepository, ApprenantLivrablepratielleRepository $apprenantLivrablePartielRepository, ApprenantRepository $apprenantRepository)
    {
        $apprenant = $apprenantRepository->findOneBy(['id'=> $id_apprenant]);
        $data = json_decode($request->getContent(), true);
        $ApprenantlivrablePartiel = $apprenantLivrablePartielRepository->findBy(['apprenant'=>$id_apprenant, 'livrablesPartiels'=>$id_livrable]);
        $livrablePartiel = $apprenantLivrablePartielRepository->find($id_livrable);
        $livrablePartiel->setStatut($data['statut']);
        $em->flush();
        
        return $this->json("Statut du livrable partiel modifier avec suces",Response::HTTP_NOT_FOUND);
    }
}