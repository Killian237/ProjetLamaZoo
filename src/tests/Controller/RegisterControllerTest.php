<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterPageAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="registration_form"]');
    }

    public function testRegisterNewUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'nouveau@example.com',
            'registration_form[plainPassword][first]' => 'Motdepasse123!',
            'registration_form[plainPassword][second]' => 'Motdepasse123!',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects();

        $client->followRedirect();
        $this->assertSelectorExists('body');
    }
}