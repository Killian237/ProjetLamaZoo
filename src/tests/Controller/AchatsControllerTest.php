<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Personnel;

class AchatsControllerTest extends WebTestCase
{
    public function testRedirectIfNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/mes-achats');
        $this->assertResponseRedirects('/login');
    }

    public function testIndexAsLoggedUser()
    {
        $client = static::createClient();

        $user = new Personnel();
        $user->setEmail('test@example.com');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/mes-achats');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
        $this->assertSelectorExists('.participations');
        $this->assertSelectorExists('.animaux-parraines');
    }
}