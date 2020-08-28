<?php

namespace App\DataFixtures;

use App\Entity\CM;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // configurer la langue
        $tab = ['admin','formateur','CM', "apprenant"];
        // $tab = implode(",", $tab);
        $faker = Factory::create('fr_FR');
            
            foreach($tab as $theProfil){
                $profil = new Profil;
                $profil->setLibelle($theProfil);
                $manager->persist($profil);
            for($p=0;$p<3;$p++){
              if($theProfil=="admin"){
                    $admin = new Admin;
                $admin->setEmail($faker->email);
                $admin->setPrenom($faker->firstname);
                $admin->setNom($faker->lastname);
                $admin->setPassword($this->encoder->encodePassword($admin, 'password'));
                $admin->setProfil($profil);
                $admin->setRoles($admin->getRoles());
                $manager->persist($admin);
              }
              else if($theProfil=="formateur"){
                  $formateur = new Formateur;
                $formateur->setEmail($faker->email);
                $formateur->setPrenom($faker->firstname);
                $formateur->setNom($faker->lastname);
                $formateur->setPassword($this->encoder->encodePassword($formateur, 'password'));
                $formateur->setProfil($profil);
                $formateur->setRoles($formateur->getRoles());
                $manager->persist($formateur);
              }
              else if($theProfil=="CM"){
                     $cm = new CM;
            $cm->setEmail($faker->email);
            $cm->setPrenom($faker->firstname);
            $cm->setNom($faker->lastname);
            $cm->setPassword($this->encoder->encodePassword($cm, 'password'));
            $cm->setProfil($profil);
            $cm->setRoles($cm->getRoles()); 
            $manager->persist($cm);

              }
              else{
                 
            $apprenant = new Apprenant;
            $apprenant->setEmail($faker->email);
            $apprenant->setPrenom($faker->firstname);
            $apprenant->setNom($faker->lastname);
            $apprenant->setPassword($this->encoder->encodePassword($apprenant, 'password'));
            $apprenant->setProfil($profil);
            $apprenant->setRoles($apprenant->getRoles()); 
            $manager->persist($apprenant);
              }
            }


             
                // $user = new strtolower


            
        // // for ($p=0; $p < 2; $p++) {
        //     $formateur = new Formateur;
        //     $apprenant = new Apprenant;
        //     $admin = new Admin;
        //     $cm = new CM;
        //     // $users = new User();

        //      // profiles
            

            
        //     $formateur->setEmail($faker->email);
        //     $formateur->setPrenom($faker->firstname);
        //     $formateur->setNom($faker->lastname);
        //     $formateur->setPassword($this->encoder->encodePassword($formateur, 'password'));
        //     $formateur->setProfil($profil);
        //     $formateur->setRoles($formateur->getRoles());

        //     // $apprenant = new Apprenant;
        //     $apprenant->setEmail($faker->email);
        //     $apprenant->setPrenom($faker->firstname);
        //     $apprenant->setNom($faker->lastname);
        //     $apprenant->setPassword($this->encoder->encodePassword($apprenant, 'password'));
        //     $apprenant->setProfil($profil);
        //     $apprenant->setRoles($apprenant->getRoles());

        //     // $admin = new Admin;
        //     $admin->setEmail($faker->email);
        //     $admin->setPrenom($faker->firstname);
        //     $admin->setNom($faker->lastname);
        //     $admin->setPassword($this->encoder->encodePassword($admin, 'password'));
        //     $admin->setProfil($profil);
        //     $admin->setRoles($admin->getRoles());

        //     // $cm = new CM;
        //     $cm->setEmail($faker->email);
        //     $cm->setPrenom($faker->firstname);
        //     $cm->setNom($faker->lastname);
        //     $cm->setPassword($this->encoder->encodePassword($cm, 'password'));
        //     $cm->setProfil($profil);
        //     $cm->setRoles($cm->getRoles());
         
           
            
        //     // users
        //     // $users->setPrenom($faker->firstname);
        //     // $users->setNom($faker->lastname);
        //     // $users->setPassword($this->encoder->encodePassword($users, 'password'));
        //     // $users->setEmail($faker->email);
        //     // $users->setProfil($profil);
        //     // $users->setRoles($users->getRoles());

            // persist
            // $manager->persist($profil);
            // // $manager->persist($apprenant);
            // // $manager->persist($cm);
            // $manager->persist($admin);
            // $manager->persist($formateur);
        }
        $manager->flush();
    }
}
// }

