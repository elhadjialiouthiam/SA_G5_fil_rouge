<?php

namespace App\Controller;

use DateTime;
use App\Repository\UserRepository;
use App\Entity\CommentairesGenerale;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChatGeneralRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentairesGeneraleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 *@Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')", message="Acces non autorisé")
*/
class CommentairesGeneraleController extends AbstractController
{
    private $serializer,
            $commentRepo,
            $promoRepo,
            $userRepo,
            $apprenantRepo,
            $chatRepo,
            $manager;
    public function __construct(SerializerInterface $serializer, CommentairesGeneraleRepository $commentRepo, PromosRepository $promoRepo, UserRepository $userRepo, ApprenantRepository $apprenantRepo, ChatGeneralRepository $chatRepo, EntityManagerInterface $manager){
        $this->serializer = $serializer;
        $this->commentRepo = $commentRepo;
        $this->promoRepo = $promoRepo;
        $this->userRepo = $userRepo;
        $this->apprenantRepo = $apprenantRepo;
        $this->chatRepo = $chatRepo;
        $this->manager = $manager;
    }

    /**
     * @Route("api/users/promo/{idPromo}/apprenant/{idAp}/chats", name="getCommentsOfAStudent", methods={"GET"})
     */
    public function getCommentsOfAStudent($idPromo, $idAp)
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
                return $this->commentDuJour($days, $apprenant, $chatGeneral);
            }
        }
    }

    //les commentaires par jour d'un user

    /**
     * 
     * @Route("api/users/promo/{idPromo}/user/chats", name="getCommentaires", methods={"GET"})
     */
    public function getCommentaires($idPromo)
    {
        $promo = $this->promoRepo->findOneBy([
            "id" => $idPromo
        ]);
        //on verifie si la promo existe
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        //on cherche le chat general correspondant à ce promo
        $chatGeneral = $promo->getChatgeneral();
        $days = $this->commentRepo->getDayOfComments($chatGeneral);
        return $this->commentDuJour($days, null, $chatGeneral);
    }

    //fonction permettant d'envoyer un commentaire sur le chat general

     /**
     * @Route("api/users/promo/{idPromo}/apprenant/{idAp}/chats", name="studentSendComment", methods={"POST"})
     */
    public function studentSendComment($idPromo, $idAp, Request $request){
     //on teste si la promo existe
     $promo = $this->promoRepo->findOneBy(["id" => $idPromo]);
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        $apprenant = $this->apprenantRepo->findOneBy(["id" => "$idAp"]);
        if(!$apprenant){
            return $this->json(["message" => "Apprenant inexistant"]);
        }
        //on verifie si l'apprenant est dans la promo
        $students = $promo->getApprenants();
        foreach($students as $student){
            if($apprenant == $student){
                $comment = $request->request->all();
                $chat = $promo->getChatgeneral();
                //on verifie si y'a un chat general sur ce promo
                if(!$chat){
                    return $this->json(["message"=>"Aucun chat concernant ce promo"]);
                }
                return $this->toComment($request, $apprenant, $chat);
            }
        }
    }

    //envoyer un commentaire en tant qu'utilisateur

     /**
     * @Route("api/users/promo/{idPromo}/user/{idUser}/chats", name="userSendComment", methods={"POST"})
     */

     public function userSendComment($idPromo, $idUser, Request $request){
         //on teste si la promo existe
        $promo = $this->promoRepo->findOneBy(["id" => $idPromo]);
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        $comment = $request->request->all();
        //on recupere le chat general correspondant a ce promo
        $chat = $promo->getChatgeneral();
        if(!$chat){
            return $this->json(["message"=>"Aucun chat concernant ce promo"]);
        }
        //on teste si l'utilisateur existe dans notre BD
        $user = $this->userRepo->findOneBy(["id" => $idUser]);
        if(!$user){
            return $this->json(["message" => "Utilisateur inexistant"]);
        }
        return $this->toComment($request, $user, $chat);
     }

     //function permettant a un user d'emettre un commentaire
     private function toComment($request, $user, $chat){
         $comment = $request->request->all();
         $date = new DateTime;
         $libelle = $comment["libelle"];
         $commentJson = $this->serializer->encode($comment, "json");
         $commentObj = $this->serializer->deserialize($commentJson, CommentairesGenerale::class, "json");
         $commentObj->setLibelle($libelle);
         if($request->files->get("pj")){
            $pj = $request->files->get("pj");
            $pj= fopen($pj->getRealPath(), 'rb');
            $commentObj->setPj($pj);
        }
         $commentObj->setUser($user);
         $commentObj->setchatgeneral($chat);
         $commentObj->setDate($date);
         $this->manager->persist($commentObj);
         $this->manager->flush();
         return $this->json(["message" => "Commentaire envoyé"]);
     }

     //fonction permettant de lister les commentaires pour chaque jour sur un chat
     private function commentDuJour($days, $apprenant=null, $chatGeneral){
        $tab=[];
        foreach($days as $day){
            if($apprenant){
                $comments = $this->commentRepo->getCommentsOfADay($day["day"], $apprenant, $chatGeneral);
            }
            $comments = $this->commentRepo->getCommentsOfADayAllUsers($day["day"], $chatGeneral);
            foreach($comments as $comment){
                if($comment["msg"]->getPj()){
                    $pj = base64_encode(stream_get_contents($comment["msg"]->getPj()));
                    $comment["msg"]->setPj($pj);
                }
                $day["msg"][]=$comment;                     
            }
            $tab[]=$day;
        }
        $tabJson = $this->serializer->serialize($tab, "json");
        return new Response($tabJson, Response::HTTP_OK,[],true);
     }
}