<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Personnel;
use App\Entity\Panier;
use App\Entity\Animaux;
use App\Entity\Mettre;

class ValidationControllerTest extends WebTestCase
{
    public function testRedirectIfNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('POST', '/parrainage/valider');
        $this->assertResponseRedirects('/login');
    }

    public function testValiderParrainageAsLoggedUser()
    {
        $client = static::createClient();

        $user = new Personnel();
        $user->setEmail('test@example.com');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);
        $user->setParrainage(0);

        $animal = new Animaux();
        $animal->setNom('Test');
        $animal->setParrainage(0);

        $panier = new Panier();
        $panier->setPersonnel($user);
        $panier->setDateCreation(new \DateTime());
        $panier->setRegler(false);

        $mettre = new Mettre();
        $mettre->setPanier($panier);
        $mettre->setAnimaux($animal);

        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->persist($animal);
        $em->persist($panier);
        $em->persist($mettre);
        $em->flush();

        $client->loginUser($user);

        $client->request('POST', '/parrainage/valider', [
            'montant' => [
                $animal->getId() => 20
            ]
        ]);

        $this->assertResponseRedirects('/parrainage');
        $client->followRedirect();
        $this->assertSelectorExists('.flash-success');
    }
}