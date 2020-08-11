<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Repository\ProfilRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder,ProfilRepository $profilRepository) {
        $this->encoder = $encoder;
        $this->profilRepository = $profilRepository;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        
        
        $profils = $this->profilRepository->findAll();
        // configurer la langue
        // $tab = ['ADMIN','FORMATEUR','CM'];
        // $tab = implode(",", $tab);
        $faker = Factory::create('fr_FR');
        $users = null;
        for ($p=0; $p < 3; $p++) { 
            foreach ($profils as $profil){
                if ($profil->getLibelle() == "ADMIN") {
                    $users = new Admin();
                    $harsh = $this->encoder->encodePassword($users, 'password');
            
                    // users
                    $users->setPrenom($faker->firstname);
                    $users->setNom($faker->lastname);
                    $users->setPassword($harsh);
                    $users->setEmail($faker->email);
                    $users->setProfil($profil);
                    $users->setEtat("disponible");
        
                    // persist
                    $manager->persist($profil);
                    $manager->persist($users);
                }
                
                
               
            }
            
        }
        $manager->flush();
    }
}
