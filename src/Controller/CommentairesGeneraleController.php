<?php

namespace App\Controller;

use DateTime;
use App\Repository\UserRepository;
use App\Entity\CommentairesGenerale;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentairesGeneraleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentairesGeneraleController extends AbstractController
{
    private $serializer,
            $commentRepo,
            $promoRepo,
            $userRepo;
    public function __construct(SerializerInterface $serializer, CommentairesGeneraleRepository $commentRepo, PromosRepository $promoRepo, UserRepository $userRepo){
        $this->serializer = $serializer;
        $this->commentRepo = $commentRepo;
        $this->promoRepo = $promoRepo;
        $this->userRepo = $userRepo;
    }
    /**
     * @Route("api/users/promo/{idPromo}/user/{idAp}/chats", name="getCommentaires")
     */
    public function getCommentaires($idPromo, $idAp)
    {
        $promo = $this->promoRepo->findOneBy([
            "id" => $idPromo
        ]);
        //on verifie si la promo existe
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        //on verifie si l'apprenant existe et est de ce promo
        $user = $this->userRepo->findOneBy(["id" => $idAp]);
        if(!$user){
            return $this->json(["message" => "Utilisateur inexistant"]);  
        }
        
        //on cherche le chat general correspondant à ce promo
        $chatGeneral = $promo->getChatgeneral();
        $days = $this->commentRepo->getDay($user, $chatGeneral);
        $tab=[];
        foreach($days as $day){
            $month = $this->commentRepo->getCommentsOfADay($day["day"], $user, $chatGeneral);
            $day["msg"]=$month;
            $tab[]=$day;
        }
        $tabJson = $this->serializer->serialize($tab, "json");
        return new Response($tabJson, Response::HTTP_OK,[],true);
    }

    //fonction permettant d'envoyer un commentaire sur le chat general

}
