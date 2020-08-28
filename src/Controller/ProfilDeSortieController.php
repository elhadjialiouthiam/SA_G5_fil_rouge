<<<<<<< HEAD
<?php

namespace App\Controller;

use App\Entity\ProfilDeSortie;
use App\Service\ArchiveService;
use App\Repository\ProfilDeSortieRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
*/
class ProfilDeSortieController extends AbstractController
{

    private $archiveService;
    public function __construct(ArchiveService $archiveService){
        $this->archiveService = $archiveService;
    }
    /**
     * @Route(
     * path="api/admin/profil_de_sorties/{id<[0-9]+>}", 
     * name="archive_profilSortie",
     * methods={"DELETE"},
     * defaults = {
     * "_controller"="\app\Controller\ProfilDeSortieController::archiveProfilSortie",
     * "_api_resource_class"=ProfilDeSortie::class,
     * "_api_collection_operation_name"="archive_profil_sortie"
     * }
     * )
     */
    public function archiveProfilSortie(ProfilDeSortie $profilSortie)
    {
        return $this->archiveService->archive($profilSortie);
    }

        /**
     * @Route(
     * path="api/admin/profil_de_sorties", 
     * name="show_profil_sortie",
     * methods={"GET"},
     * defaults = {
     * "_controller"="\app\Controller\ProfilDeSortieController::showProfilsSortie",
     * "_api_resource_class"=ProfilDeSortie::class,
     * "_api_collection_operation_name"="show_profil_sortie"
     * }
     * )
     */
    public function showProfilsSortie(ProfilDeSortieRepository $repository){
        return $this->archiveService->show($repository);
    }
}
=======
<?php

namespace App\Controller;

use App\Entity\ProfilDeSortie;
use App\Service\ArchiveService;
use App\Repository\PromosRepository;
use App\Repository\ProfilDeSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProfilDeSortieController extends AbstractController
{

    private $serializer,$psRepo, $promoRepo;

    public function __construct(SerializerInterface  $serializer, ProfilDeSortieRepository $psRepo, PromosRepository $promoRepo){
        $this->serializer = $serializer;
        $this->psRepo = $psRepo;
        $this->promoRepo = $promoRepo;
    }

        /**
         * @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')", message="Acces non autorisé")
     * @Route(path="api/admin/profilsorties", name="show_profil_sortie", methods={"GET"})
     */
    public function showProfilsSortie(){
        // dd("ps");
        $profilsorties = $this->psRepo->findAll();
        $profilsorties = $this->serializer->serialize($profilsorties, "json", ["groups"=>["ps:read"]]);
        return new JsonResponse($profilsorties, Response::HTTP_OK, [], true);
        // dd($profilsorties);

    }

    //afficher les apprenants d'une promo par profil de sortie
     /**
      * @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')", message="Acces non autorisé")
     * @Route(path="api/admin/promo/{idPromo}/profilsorties", name="getStudentsByPs", methods={"GET"})
     */

     public function getStudentsByPs($idPromo){
        $tab=[];
        $promo = $this->promoRepo->findOneBy(["id" => $idPromo]);
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        //on recupere l'ensemble des profils de sortie
        $profilsorties = $this->psRepo->findAll();
        //pour chaque profil de sortie on recupere ses apprenants
        foreach ($profilsorties as $profilsortie) {
            // $tab = $this->getByPs($profilsortie, $promo);
            $apprenants = $profilsortie->getApprenants();
            foreach($apprenants as $apprenant){
                if($apprenant->getPromos() != $promo){
                    $profilsortie->removeApprenant($apprenant);
                }
            }
            if(count($profilsortie->getApprenants())>0){
                if(!in_array($profilsortie, $tab, true)){
                    $tab[] = $profilsortie;
                }
            }
            
        }
        $tabJson = $this->serializer->serialize($tab, "json", ["groups"=>["ps:read"]]);
        return new JsonResponse($tabJson, Response::HTTP_OK, [], true); 
    }

    //afficher les apprenants d'un profil de sortie d'une promo

    /**
     * @Security("is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')", message="Acces non autorisé")
     * @Route(path="api/admin/promo/{idPromo}/profilsorties/{idPs}", name="getStudentsOfAPs", methods={"GET"})
     */
    public function getStudentsOfAPs($idPromo, $idPs){
        $tab=[];
        //on recupere la promo
        $promo = $this->promoRepo->findOneBy(["id" => $idPromo]);
        if(!$promo){
            return $this->json(["message" => "Promo inexistante"]);
        }
        //on recupere le profil de sortie
        $profilsortie = $this->psRepo->findOneBy(["id" => $idPs]);
        if(!$profilsortie){
            return $this->json(["message" => "Ce profil de sortie n'existe pas"]);
        }
        //on recupere les apprenants du ps
            $apprenants = $profilsortie->getApprenants();
            foreach($apprenants as $apprenant){
                if($apprenant->getPromos() != $promo){
                    $profilsortie->removeApprenant($apprenant);
                }
            }
            if(count($profilsortie->getApprenants())>0){
                if(!in_array($profilsortie, $tab, true)){
                    $tab[] = $profilsortie;
                }
            }
        $tabJson = $this->serializer->serialize($tab, "json", ["groups"=>["ps:read"]]);
        return new JsonResponse($tabJson, Response::HTTP_OK, [], true); 
    }


}
>>>>>>> 05c02e8f4000920d6fa02d5b7a7e346f9896a230
