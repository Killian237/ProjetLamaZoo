<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Personnel;
use App\Entity\Animaux;

class ParrainageControllerTest extends WebTestCase
{
    public function testIndexAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/parrainage');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testAddToCartRedirectsIfNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/parrainage/add/1');
        $this->assertResponseRedirects('/login');
    }

    public function testAddAndRemoveAnimalAsLoggedUser()
    {
        $client = static::createClient();

        $user = new Personnel();
        $user->setEmail('test@example.com');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);

        $animal = new Animaux();
        $animal->setNom('Test');
        $animal->setParrainage(0);

        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->persist($animal);
        $em->flush();

        $client->loginUser($user);

        $client->request('GET', '/parrainage/add/' . $animal->getId());
        $this->assertResponseRedirects('/parrainage');

        $crawler = $client->request('GET', '/parrainage/panier');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');

        $client->request('GET', '/parrainage/panier/retirer/' . $animal->getId());
        $this->assertResponseRedirects('/parrainage/panier');
    }
}