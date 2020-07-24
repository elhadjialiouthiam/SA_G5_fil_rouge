<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
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
            $profil = new Profil();
        for ($p=0; $p < 3; $p++) { 
            $users = new User();
         
            // profiles
            $profil->setLibelle($theProfil);
            
            // users
            $users->setPrenom($faker->firstname);
            $users->setNom($faker->lastname);
            $users->setPassword($this->encoder->encodePassword($users, 'password'));
            $users->setEmail($faker->email);
            $users->setProfil($profil);
            $users->setRoles($users->getRoles());

            // persist
            $manager->persist($profil);
            $manager->persist($users);
        }
        $manager->flush();
    }
}
}
