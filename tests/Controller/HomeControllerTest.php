<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful(); // Vérifie code 200
        $this->assertSelectorExists('h1');   // Vérifie qu'il y a un h1
    }

    public function testIndexPageContainsWelcomeText()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Index'); // Remplace "Bienvenue" par ce qui est vraiment affiché
    }
}