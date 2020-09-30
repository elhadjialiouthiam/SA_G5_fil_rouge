<?php
namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PromosControllerTest extends WebTestCase
{
    public function testAjoutPromo()
    {
        $n = 5;
        $client = static::createClient();
        // dd($client);
        // $container = self::$container;
        // $userRepository = static::$container->get(UserRepository::class);
        // $user = $userRepository->findOneByEmail('dady@gmail.com');
        // $resultat = $promoCtl->addPromo();
        // $client->loginUser($user);

        // $crawler = $client->request('GET', '/post/hello-world');
        $client->request('GET', '/admin/users/apprenants');

        $this->asserEquals(404, $client->getResponse()->getStatusCode());



        // $this->assertEquals(10, $b);
    }
}