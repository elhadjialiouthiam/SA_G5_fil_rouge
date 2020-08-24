<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Promos;
use App\Entity\Apprenant;
use App\Repository\GroupeRepository;
use App\Repository\ProfilRepository;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use App\Controller\ResetPasswordController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PromosController extends AbstractController
{
    private $manager,
            $serializer,
            $validator,
            $promosRepository;
    public function __construct(ValidatorInterface $validator,EntityManagerInterface $manager,SerializerInterface $serializer,PromosRepository $promosRepository,GroupeRepository $groupeRepo)
    {
        $this->serializer = $serializer;
        $this->promosRepository = $promosRepository;
        $this->manager = $manager;
        $this->validator = $validator;
        $this->groupeRepo = $groupeRepo;
    }

    /**
     * @Route(
     *     name="show_promo_id_groupes_id_apprenants",
     *     path="/api/admin/promos/{id_promo}/groupes/{id_groupe}/apprenants",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="get_promo_id_groupes_id_apprenants"
     *     }
     * )
     */
    public function getpromoIdGroupeIdApprenant(int $id_promo, int $id_groupe)
    {
        $promo = $this->promosRepository->find($id_promo);
        $groupe = $this->groupeRepo->find($id_groupe);

        foreach ($promo->getGroupes() as  $value) {
            if ($value != $groupe) {
                $promo->removeGroupe($value);
            }
        }

        $promoJson = $this->serializer->serialize($promo, 'json',["groups"=>["promo_groupe_apprenants:read"]]);
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route(
     *     name="show_promo_id_ref_formateurs_apprenants",
     *     path="/api/admin/promo/{id}/principal",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="get__promo_id_ref_formateurs_apprenants"
     *     }
     * )
     */
    public function getPromoIdRefFormateursApprenants(int $id)
    {
        $promo = $this->promosRepository->find($id);

        $promoJson = $this->serializer->serialize($promo, 'json',["groups"=>["promo_ref_formateurs_apprenants:read"]]);
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }
    /**
     * @Route(
     *     name="show_promo_id_ref_GroupeCompet_Competences",
     *     path="/api/admin/promo/{id}/referentiels",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="get_promo_id_ref_GroupeCompet_Competences"
     *     }
     * )
     */
    public function getPromoIdRefGroupCompetCompetences(int $id)
    {
        $promo = $this->promosRepository->find($id);

        $promoJson = $this->serializer->serialize($promo, 'json',["groups"=>["promo_ref_GrpeCompet_Competences:read"]]);
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }
        /**
     * @Route(
     *     path="/api/admin/promos/{id}/principal",
     *     methods={"GET"},
     *     name="getPrincipal"
     * )
     */
    public function getPrincipal($id,PromosRepository $promosRepository)
    {
        
        $this->autorisation();
        $promoTab = [];
        $promo = $promosRepository->findOneBy(["id" => $id]);
        if ($promosRepository && !$promo->getIsDeleted())
        {
            $groupes = $promo->getGroupes();
            foreach ($groupes as $groupe )
            {
                if($groupe->getType() == "principal")
                {
                    $promoTab["apprenants"] = $groupe->getApprenant();
                    break;
                }
            }
            $promoTab["referentiel"] = $promo->getReferentiel();
            $promoTab["formateur"] = $promo->getFormateur();
            if(!isset($promoTab["apprenants"]))
            {
                return $this->json(["message" => "Pas de groupe princpal pour cette promo."]);
            }
            return $this->json($promoTab,Response::HTTP_OK);
        }
        return $this->json(["message" => "Ressource inexistante."],Response::HTTP_NOT_FOUND);
    }

    /**
    * @Route(
    *     path="/api/admin/promos/{id<\d+>}/referentiel",
    *     methods={"GET"},
    *     name="getPrincipalInPromozd"
    * )
    */
    
    public function getRefPromoGrpeVompetence($id,PromosRepository $promosRepository)
    {
        $promo = new Promos();
        if(!($this->isGranted("VIEW",$promo)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $promo = $promosRepository->findOneBy([
            "id" => $id
        ]);
        $data = [];
        if ($promo) {
            if (!$promo->getIsDeleted()) {
                // $data[] = $promo;
                $data[] = $promo->getReferentiel();
                $data[] = $promo->getReferentiel()->getGroupeComptence();
                // dd($promo->getReferentiel()->getGroupeComptence()->getCompetences());
                return $this->json($data,Response::HTTP_OK);
            }
        }
        return $this->json(["message" => "Ressource inexistante"],Response::HTTP_NOT_FOUND);
    }
    

    /**
     * @Route(
     *     path="/api/admin/promos/{id<\d+>}",
     *     methods={"GET"},
     *     name="getPromo"
     * )
     */
    public function getPromo($id,PromosRepository $promosRepository)
    {
        $promo = new Promos();
        if(!($this->isGranted("VIEW",$promo)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $promo = $promosRepository->findOneBy([
            "id" => $id
        ]);
        if($promo){
            if (!$promo->getIsDeleted())
                return $this->json($promo,Response::HTTP_OK);
        }
        return $this->json(["message" => "Ressource inexistante"],Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route(
     *     path="/api/admin/promos",
     *     methods={"GET"},
     *     name="getPromos"
     * )
    */
    public function getPromos()
    {
        $this->autorisation();
        $promos = $this->promosRepository->findBy([
            "isDeleted" => false
        ]);
        return $this->json($promos,Response::HTTP_OK);
    }

     /**
     * @Route(
     *     path="/api/admin/promos/principal",
     *     methods={"GET"},
     *     name="getGrpPrincipaux"
     * )
     */
    public function getGrpPrincipaux()
    {
        $this->autorisation();
        $promos = $this->promosRepository->findBy([
            "isDeleted" => false
        ]);
        $promoTab = [];
        $promoTab["referentiels"] = [];
        $promoTab["apprenants"] = [];
        $promoTab["formateurs"] = [];
        foreach ($promos as $promo)
        {
            $groupes = $promo->getGroupes();
            $promoTab["referentiels"][] = $promo->getReferentiel();
            $promoTab["formateurs"][] = $promo->getReferentiel();
            foreach ($groupes as $groupe)
            {
                if (!$groupe->getIsDeleted() && $groupe->getType() == "principal")
                {
                    $promoTab["apprenants"][] = $groupe->getApprenant();
                }
            }
        }
        return $this->json($promoTab,Response::HTTP_OK);
    }


    /**
     * @Route(
     *     path="/api/admin/promo/{id<\d+>}/apprenants/attente",
     *     methods={"GET"},
     *     name="getRefWaitingStudents"
     * )
     */
    public function getRefStudentsWaiting($id, PromosRepository $promosRepository)
    {
        $promo = new Promos();
        if(!($this->isGranted("VIEW",$promo)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $promo = $promosRepository->findOneBy([
            "id" => $id,
            "isDeleted" => false
        ]);
        $refStudentWaiting = [];
        if ($promo) {
            $referentiel = $promo->getReferentiel();
            $refStudentWaiting[]=$referentiel;
            $students = $promo->getGroupes();
            // dd($students);
            // dd($students);
            // $students = $groupes->getApprenant();
            
            foreach ($students as $student => $value)
                
                // $promoTab = $this->serializer->deserialize($value, Promos::class,"json");
                // dd($promoTab);
                {
                    $refStudentWaiting[] = $value;
                }
            
            return $this->json($refStudentWaiting,Response::HTTP_OK);
        }
        
        return $this->json(["message" => "Ressource inexistante"],Response::HTTP_NOT_FOUND);
    }

        /**
     * @Route(
     *     path="/api/admin/promos/apprenants/attente",
     *     methods={"GET"},
     *     name="getWaitingStudents"
     * )
     */
    public function getWaitingStudents()
    {
        $this->autorisation();
        $promos = $this->promosRepository->findBy([
            "isDeleted" => false
        ]);
        $waiting = [];
        foreach ($promos as $promo)
        {
            $referentiel = $promo->getReferentiel();
            $groupes = $promo->getGroupes();
            foreach ($groupes as $groupe)
            {
                $students = $groupe->getApprenant();
                foreach ($students as $student)
                {
                    if (!$student->getIsConnected())
                    {
                        $waiting[] = $promo;
                    }
                }
            }
        }
        return $this->json($waiting,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/admin/promo/{id}",
     *     methods={"PUT"},
     *     name="setPromo"
     * )
     */

    public function setPromo($id,Request $request,TokenStorageInterface $tokenStorage,ReferentielRepository $referentielRepository,ApprenantRepository $apprenantRepository,FormateurRepository $formateurRepository)
    {
        $promos = new Promos();
        $promos = $this->promosRepository->findBy([
            "id" => $id
        ]);
        $promoJson = $request->getContent();
        $promoTab = $this->serializer->decode($promoJson,"json");
        $referentiel = $promoTab['referentiel'];
        $promoTab['referentiel'] = [];
        $referentielId = $promoTab['referentiel'][0]["id"];
        $promoObj = $this->serializer->denormalize($promoTab,"App\Entity\Promos");
        $referentiel = $referentielRepository->findOneBy(["id" => $referentielId]);
        $promoObj->SetIsDeleted(false)
                ->setReferentiel($referentiel);
        $promos->setLangue($promoObj->getLangue())
                ->setTitre($promoObj->getTitre())
                ->setDescription($promoObj->getDescription())
                ->setDateDebut($promoObj->getDateDebut())
                ->setDateFinProvisoire($promoObj->getDateFinProvisoire())
                ->setDateFinReelle($promoObj->getDateFinReelle());
        $manager->flush();
        return $this->json($promos,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/admin/promos",
     *     methods={"POST"},
     *     name="addPromo"
     * )
     */
    public function addPromo(Request $request,TokenStorageInterface $tokenStorage,ReferentielRepository $referentielRepository,ApprenantRepository $apprenantRepository,FormateurRepository $formateurRepository, ProfilRepository $profilRepository, ResetPasswordController $reset,MailerInterface $mailer)
    {
        $promo = new Promos();
        if (!$this->isGranted("EDIT",$promo))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $promoJson = $request->getContent();
        // dd($promoJson);
        // $sender = 'abdoudiallo405@gmail.com';
        $promoTab = $this->serializer->decode($promoJson,"json");
        $referentielId = $promoTab['referentiel'][0]["id"];

        $referentielId = isset($promoTab["referentiel"]["id"]) ? (int)$promoTab["referentiel"]["id"] : null;
        // dd($referentielId);
        $referentielId = $promoTab['referentiel'][0]["id"];
        $groupes = isset($promoTab['groupes']) ? $promoTab['groupes'] : [];
        $formateurs = isset($promoTab["formateur"]) ? $promoTab["formateur"] : [];
        $students = isset($promoTab['apprenants']) ? $promoTab['apprenants'] : [];
        // dd($formateurs);
        $promoTab['formateur'] = [];
        $promoTab["referentiel"] = null;
        $promoTab['groupes'] = [];
        $promoTab['apprenants'] = [];
        
        $promoObj = $this->serializer->denormalize($promoTab,"App\Entity\Promos");
        $referentiel = $referentielRepository->findOneBy(["id" => $referentielId]);
        // dd($referentiel);
        // dd($promoObj);
        $creator = $tokenStorage->getToken()->getUser();
        $promoObj->setAdmin($creator)
                 ->setEtat(true)
                 ->setIsDeleted(false)
                 ->setReferentiel($referentiel);
        $errors = $this->validator->validate($promoObj);
        if(count($errors))
        {
            return $this->json($errors,Response::HTTP_BAD_REQUEST);
        }
        if (!count($groupes))
        {
            return $this->json(["message" => "Vous devez obligatoirement ajouter un groupe principal"],Response::HTTP_BAD_REQUEST);
        }
        
        // Gestion des groupes
        foreach ($groupes as $groupe)
        {
            $emails = isset($groupe["apprenant"]) ? $groupe["apprenant"] : [];
            $groupe["apprenant"] = [];
            // dd($emails);
            if(!count($emails))
            {
                return $this->json(["message" => "Ajouter des apprenants (leur email) dans le groupe."],Response::HTTP_BAD_REQUEST);
            }
            $unit = $this->serializer->denormalize($groupe,"App\Entity\Groupe");
            $dateCreation = new \DateTime();
            $unit->setDateCreation($dateCreation);
                //  ->setIsDeleted(false);
            foreach ($emails as $email)
            {
                $mailTo = $email["email"];
                // dd($mailTo);
                $student = $apprenantRepository->findOneBy(["email" => $email["email"]]);
                if($student == null)
                {
                    $Newapprenant = new Apprenant;
                    $Newapprenant->setEMail($mailTo)
                                ->setPassword("password")
                                ->setPrenom("firstname")
                                ->setNom("lastname");
                    $profil = $profilRepository->findOneBy([
                        "libelle" => "apprenant"
                    ]);
                    $Newapprenant->setProfil($profil)
                                ->setRoles($Newapprenant->getRoles());
                    $this->manager->persist($Newapprenant);
                    $unit->addApprenant($Newapprenant);
                    $promoObj->addApprenant($Newapprenant);
                    $this->manager->flush();
                }
                else{
                $unit->addApprenant($student);
                }
                $this->manager->flush();
                $reset->processSendingPasswordResetEmail(
                    $mailTo,
                    $mailer
                );
                
                
            }
            $unitErrors = $this->validator->validate($unit);
            if(count($unitErrors))
            {
                return $this->json($unitErrors,Response::HTTP_BAD_REQUEST);
            }
            $this->manager->persist($unit);
            $promoObj->addGroupe($unit);
        }

        // gestion formateur
        if(count($formateurs))
        {
            foreach ($formateurs as $formateur)
            {
                $teacherId = isset($formateur["id"]) ? $formateur["id"] : null;
                $teacher = $formateurRepository->findOneBy(["id" => $teacherId]);
                // dd($teacher);
                if(!$teacher)
                {
                    return $this->json(["message" => "Cette formateur n'existe pas."],Response::HTTP_NOT_FOUND);
                }
                $promoObj->addFormateur($teacher);
            }
        }
        // gestion des apprenants
        if(count($students)){
            // dd($students);
            foreach ($students as $apprenant) {
                $email = $apprenant["email"];
                // dd($email);
                
                $std = $apprenantRepository->findOneBy([
                    "email" => $apprenant["email"]
                ]);
               
              
                if ($std == null) {
                    $Newapprenant = new Apprenant;
                    $Newapprenant->setEMail($apprenant["email"])
                                ->setPassword("password")
                                ->setPrenom("firstname")
                                ->setNom("lastname");   
                    $profil = $profilRepository->findOneBy([
                        "libelle" => "apprenant"
                    ]);
                    $Newapprenant->setProfil($profil)
                                ->setRoles($Newapprenant->getRoles());
                    $this->manager->persist($Newapprenant);
                    $promoObj->addApprenant($Newapprenant);
                }
                else{
                $promoObj->addApprenant($std);
                }
                $this->manager->flush();
                $reset->processSendingPasswordResetEmail(
                    $email,
                    $mailer
                );
            }
        }
        // gestion avatar

        
        $this->manager->persist($promoObj);
        $this->manager->flush();
        return $this->json($promoObj,Response::HTTP_CREATED);
    }



    
    /**
     * @Route(
     *     name="add_Formateurs_in_promo",
     *     path="/api/admin/promo/{id}/formateurs",
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="add_Formateurs_in_promos"
     *     }
     * )
     */
    
    public function AddFormateurInPromo($id,Request $request, FormateurRepository $formateurRepo)
    {
        // dd("ghs");
        $promo = new Promos();
        $promo = $this->promosRepository->findOneBy([
            "id"=>$id
        ]);
        // dd($promo);
        if(!$promo)
            return $this->json(["message" => "Cette promotion n'existe pas."],Response::HTTP_NOT_FOUND);
        $formateurJson = $request->getContent();
        $formateurTab = $this->serializer->decode($formateurJson,"json");
        $formateurs = isset($formateurTab["id"]) ? $formateurTab["id"] : [];
        $formateurObj = $this->serializer->denormalize($formateurTab,"App\Entity\Promos");
        $formateur = $formateurRepo->findOneBy([
            "id"=> $formateurs
        ]);
        $IsIn = false;
        if ($formateur) {
            $promo->addFormateur($formateur);
            $this->manager->flush();
            return $this->json("ajouter");
        }
        return $this->json(["message" => "Ce formateur n'existe pas."],Response::HTTP_NOT_FOUND);
        
    }

    /**
     * @Route(
     *     name="delete_Formateurs_in_promo",
     *     path="/api/admin/promo/{id}/formateurs/{id_formateur}",
     *     methods={"DELETE"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="delete_Formateurs_in_promos"
     *     }
     * )
     */
    public function DeleteFormateurInPromo($id,$id_formateur,Request $request, FormateurRepository $formateurRepo){
        $promo = $this->promosRepository->findOneBy([
            "id"=>$id
        ]);
        if(!$promo)
            return $this->json(["message" => "Cette promotion n'existe pas."],Response::HTTP_NOT_FOUND);
        $formateur = $formateurRepo->findOneBy([
            "id"=> $id_formateur
        ]);
        if(!$formateur)
            return $this->json(["message" => "Cette promotion n'existe pas."],Response::HTTP_NOT_FOUND);

        $data = $promo->removeFormateur($formateur);
        $this->manager->flush();
        return $this->json(["message" => "Suppression avec succes."],Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="add_Apprenant_in_promo",
     *     path="/api/admin/promo/{id}/apprenants",
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="add_Apprenant_in_promos"
     *     }
     * )
     */
    public function AddApprenantInPromo($id,Request $request, ApprenantRepository $ApprenantRepo)
    {
        // dd("ghs");
        $promo = new Promos();
        $promo = $this->promosRepository->findOneBy([
            "id"=>$id
        ]);
        // dd($promo);
        if(!$promo)
            return $this->json(["message" => "Cette promotion n'existe pas."],Response::HTTP_NOT_FOUND);
        $apprenantJson = $request->getContent();
        $apprenantTab = $this->serializer->decode($apprenantJson,"json");
        $apprenants = isset($apprenantTab["id"]) ? $apprenantTab["id"] : [];
        // dd($apprenants);
        $apprenantObj = $this->serializer->denormalize($apprenantTab,"App\Entity\Promos");
        $apprenant = $ApprenantRepo->findOneBy([
            "id"=> $apprenants
        ]);
        // dd($apprenant);
        $IsIn = false;
        if ($apprenant) {
            $promo->addApprenant($apprenant);
            $this->manager->flush();
            return $this->json("ajouter");
        }
        return $this->json(["message" => "Ce apprenant n'existe pas."],Response::HTTP_NOT_FOUND);
        
    }
    /**
     * @Route(
     *     name="delete_apprenants_in_promo",
     *     path="/api/admin/promo/{id}/apprenants/{id_apprenant}",
     *     methods={"DELETE"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="delete_apprenants_in_promos"
     *     }
     * )
     */
    public function DeleteApprenantInPromo($id,$id_apprenant,Request $request, ApprenantRepository $apprenantRepo){
        $promo = $this->promosRepository->findOneBy([
            "id"=>$id
        ]);
        if(!$promo)
            return $this->json(["message" => "Cette promotion n'existe pas."],Response::HTTP_NOT_FOUND);
        $apprenant = $apprenantRepo->findOneBy([
            "id"=> $id_apprenant
        ]);
        if(!$apprenant)
            return $this->json(["message" => "Cet Apprenant n'existe pas."],Response::HTTP_NOT_FOUND);

        $data = $promo->removeApprenant($apprenant);
        $this->manager->flush();
        return $this->json(["message" => "Suppression avec succes."],Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="Modifier_statut_groupe",
     *     path="/api/admin/promo/{id}/groupes/{id_groupe}",
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=Promos::class,
     *         "_api_collection_operation_name"="Modifier_statut_groupe"
     *     }
     * )
     */

     public function editState($id,$id_groupe, GroupeRepository $groupeRepo)
     {
        $promo = $this->promosRepository->find($id);
        $groupe = $groupeRepo->findOneBy([
            "id"=> $id_groupe
        ]);

        foreach ($promo->getGroupes() as  $value) {
            if ($value == $groupe) {
                if ($groupe->getStatut() == "actif") {
                    $groupe->setStatut() == "archive";
                    $this->manager->flush();
                    return $this->json(["message" => "Modif avec succes."],Response::HTTP_OK);
                }
            }

        }
    }

    
    private function autorisation()
{
    $promo = new Promos();
    if (!$this->isGranted("VIEW",$promo))
        return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
}
 



    //import de fichier excel

    /**
   * @Route(
   * name="xlsx",
   * path="api/admin/promos/upload-excel", 
     *  methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\PromosController::uploadExcel",
     *  "_api_collection_operation_name"="xlsx"
     *     }
   * )
    */
    public function uploadExcel(Request $request, ApprenantRepository $apprenantRepository, ProfilRepository $profilRepository, PromosRepository $promoRepo, MailerInterface $mailer, ResetPasswordController $reset){
    //on recupere le fichier uploadé
   $file = $request->files->get('file'); 
   //l'emplacement ou on va enregistrer les fichier
   $fileFolder = __DIR__ . '/../../public/uploads/';  
  
   //  md5 function pour generer un unique id pour le fichier  and et le concatener avec le nom original  
   $filePathName = md5(uniqid()) . $file->getClientOriginalName();
      
            try {
                $file->move($fileFolder, $filePathName);
            } catch (FileException $e) {
                dd($e);
            }
    //on lit le contenu du fichier excel
    $spreadsheet = IOFactory::load($fileFolder . $filePathName); 
    //on recupere le contenu du fichier sous forme de tableau
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); 
    // dd($sheetData);
   //on recupere la derniere promo
   $lastPromo = $promoRepo->findOneBy([], ['id' => 'desc']);
    foreach ($sheetData as $Row) 
        { 
            $first_name = $Row['A']; 
            $last_name = $Row['B']; 
            $email= $Row['C'];  

            $user_existant = $apprenantRepository->findOneBy([
                "email" => $email
            ]); 
                // on verifie si l'utlisateur n'existe pas deja dans la BD
            if (!$user_existant) 
             {   
                $student = new Apprenant(); 
                $student->setPrenom($first_name);           
                $student->setNom($last_name);
                $student->setEmail($email);
                $student->setPassword("password");
                $profil = $profilRepository->findOneBy([
                    "libelle" => "apprenant"
                ]);
                $student->setProfil($profil)
                            ->setRoles($student->getRoles());
                $this->manager->persist($student); 
                $lastPromo->addApprenant($student);
                $this->manager->flush(); 
                //envoi des mails
                $reset->processSendingPasswordResetEmail(
                    $email,
                    $mailer
                );
             } 
        } 
    return $this->json("Succes", 200); 
}



}
