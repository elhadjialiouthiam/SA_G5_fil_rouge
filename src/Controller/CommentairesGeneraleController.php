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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentairesGeneraleController extends AbstractController
{
    private $serializer,
            $commentRepo,
            $promoRepo,
            $userRepo,
            $apprenantRepo,
            $chatRepo,
            $manager,
            $tokenStorage;
    public function __construct(SerializerInterface $serializer, CommentairesGeneraleRepository $commentRepo, PromosRepository $promoRepo, UserRepository $userRepo, ApprenantRepository $apprenantRepo, ChatGeneralRepository $chatRepo, EntityManagerInterface $manager, TokenStorageInterface $tokenStorage){
        $this->serializer = $serializer;
        $this->commentRepo = $commentRepo;
        $this->promoRepo = $promoRepo;
        $this->userRepo = $userRepo;
        $this->apprenantRepo = $apprenantRepo;
        $this->chatRepo = $chatRepo;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
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

    //les commentaires par jour d'un user

    /**
     * @Route("api/users/promo/{idPromo}/user/chats", name="getCommentaires", methods={"GET"})
     */
    public function getCommentaires($idPromo)
    {
        // dd("hey");
        // dd("hey");
        $promo = $this->promoRepo->findOneBy([
            "id" => $idPromo
        ]);
        //on verifie si la promo existe
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        //on verifie si l'apprenant existe et est de ce promo
        // $user = $this->userRepo->findOneBy(["id" => $idAp]);
        // if(!$user){
        //     return $this->json(["message" => "Utilisateur inexistant"]);  
        // }
        
        //on cherche le chat general correspondant à ce promo
        $chatGeneral = $promo->getChatgeneral();
        $days = $this->commentRepo->getDayOfComments($chatGeneral);
        // dd($days);
        $tab=[];
        foreach($days as $day){
            $month = $this->commentRepo->getCommentsOfADayAllUsers($day["day"], $chatGeneral);
            $day["msg"]=$month;
            $tab[]=$day;
        }
        $tabJson = $this->serializer->serialize($tab, "json");
        return new Response($tabJson, Response::HTTP_OK,[],true);
    }

    //fonction permettant d'envoyer un commentaire sur le chat general

     /**
     * @Route("api/users/promo/{idPromo}/apprenant/{idAp}/chats", name="sendComment", methods={"POST"})
     */
    public function sendComment($idPromo, $idAp, Request $request){
     //on teste si la promo existe
     $promo = $this->promoRepo->findOneBy(["id" => $idPromo]);


        $date = new DateTime;
    
        // dd($request->getContent());
        // $requete =$request->getContent();
        // $comment = $this->serializer->decode($requete, "json");
        // dd($comment);
        $comment = $request->request->all();
    //    dd($comment);
        $pj = $request->files->get("pj");
        $pj= fopen($pj->getRealPath(), 'rb');

        $libelle = $comment["libelle"];
        // $comment["pj"] = $pj;
        // $comment["date"] = $date;
        //on recupere le chat general correspondant a l'id donné
        $chatId = $comment["chatgeneral"];
        $chat = $this->chatRepo->findOneBy(["id"=>$chatId]);
        $userId = $comment["user"];
        $user = $this->userRepo->findOneBy(["id"=>$userId]);
        // $comment["chatgeneral"] = $chat;
       
        // dd($user);
        // $user = $this->tokenStorage->getToken()->getUser();
        // dd($user);
        // $comment["user"]= $user;
        // dd($chat);
        // dd($comment);
        // dd($comment);
        $commentJson = $this->serializer->encode($comment, "json");
        // dd($commentJson);
        // dd($commentJson["libelle"]);
        // $commentObj = $this->serializer->denormalize($comment, "App/Entity/CommentairesGeneral", true);
        $commentObj = $this->serializer->deserialize($commentJson, CommentairesGenerale::class, "json");
        // dd($commentObj);
        $commentObj->setLibelle($libelle);
        $commentObj->setPj($pj);
        $commentObj->setUser($user);
        $commentObj->setchatgeneral($chat);
        $commentObj->setDate($date);
        // dd($commentObj);
        $this->manager->persist($commentObj);
        $this->manager->flush();
        // return $this->json($commentObj);
        // dd($pj)
        //idAp => id de l'apprenant qui envoie le commentaire
        // $date = new DateTime;
        // $requestTab = $this->serializer->decode($request->getContent(), "json");
        // dd($requestTab);
        // dd($requestTab["libelle"]);
        // $comment = new CommentairesGenerale;

        // dd($date);

        //afaire 
        //utiliser apprenantRepo, faire les tests necessaires
        //l'apprenant c le mm apprenant dont on a l'id sur url
    }
}
