<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Personnel;
use App\Entity\Ateliers;

class AteliersControllerTest extends WebTestCase
{
    public function testIndexAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/ateliers');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testAddToCartRedirectsIfNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('POST', '/ateliers/add/1');
        $this->assertResponseRedirects('/login');
    }

    public function testAddToCartAsLoggedUser()
    {
        $client = static::createClient();

        $user = new Personnel();
        $user->setEmail('test@example.com');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);

        $atelier = new Ateliers();
        $atelier->setNom('Atelier Test');
        $atelier->setDure('01:00:00');

        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->persist($atelier);
        $em->flush();

        $client->loginUser($user);

        $client->request('POST', '/ateliers/add/' . $atelier->getId(), [
            'heure' => '10:00'
        ]);
        $this->assertResponseRedirects('/parrainage/panier');
    }
}