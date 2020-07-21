<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        

        // configurer la langue
        $tab = ['administrateur','formateur','CM'];
        // $tab = implode(",", $tab);
        $faker = Factory::create('fr_FR');
        for ($p=0; $p < 3; $p++) { 
            $users = new User();
            $profil = new Profil();
            // profiles
            $profil->setLibelle($tab[$p]);
            
            // users
            $users->setPrenom($faker->firstname);
            $users->setNom($faker->lastname);
            $users->setPassword('password');
            $users->setEmail($faker->email);
            $users->setProfil($profil);

            // persist
            $manager->persist($profil);
            $manager->persist($users);
        }
        $manager->flush();
    }
}
