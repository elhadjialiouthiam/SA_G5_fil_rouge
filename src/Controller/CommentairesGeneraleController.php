<?php

namespace App\Controller;

use DateTime;
use App\Entity\CommentairesGenerale;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
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
            $apprenantRepo;
    public function __construct(SerializerInterface $serializer, CommentairesGeneraleRepository $commentRepo, PromosRepository $promoRepo, ApprenantRepository $apprenantRepo){
        $this->serializer = $serializer;
        $this->commentRepo = $commentRepo;
        $this->promoRepo = $promoRepo;
        $this->apprenantRepo = $apprenantRepo;
    }
    /**
     * @Route("api/users/promo/{idPromo}/apprenant/{idAp}/chats", name="getCommentaires")
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
        $apprenant = $this->apprenantRepo->findOneBy(["id" => $idAp]);
        if(!$apprenant){
            return $this->json(["message" => "Apprenant inexistante"]);  
        }
        $apprenantsOfPromo = $promo->getApprenants();
        
        //on cherche le chat general correspondant à ce promo
        $chatGeneral = $promo->getChatgeneral();
        foreach ($apprenantsOfPromo as $apprenantOfPromo ) {
            if($apprenant == $apprenantOfPromo){
                $days = $this->commentRepo->getDay($apprenant, $chatGeneral);
                $tab=[];
                foreach($days as $day){
                    $month = $this->commentRepo->getCommentsOfADay($day["day"], $apprenant, $chatGeneral);
                    $day["msg"]=$month;
                    $tab[]=$day;
                }
                $tabJson = $this->serializer->serialize($tab, "json");
                return new Response($tabJson, Response::HTTP_OK,[],true);
            }
        }
    }

    //fonction permettant d'envoyer un commentaire sur le chat general

     /**
     * @Route("api/users/promo/{idPromo}/apprenant/{idAp}/chats", name="sendComment")
     */
    public function sendComment($idPromo){

    }
}
