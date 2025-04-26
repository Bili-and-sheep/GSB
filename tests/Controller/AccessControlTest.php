<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessControlTest extends WebTestCase
{
    public function testRedirectToLoginIfNotAuthenticated()
    {
        $client = static::createClient();

        // On tente d'accéder à une page sécurisée sans être connecté
        $client->request('GET', '/comptable/manegeFF'); // Remplace si besoin par une vraie route protégée

        // Symfony doit renvoyer une redirection (302) vers /login
        $this->assertResponseRedirects('/login', 302);
    }
}