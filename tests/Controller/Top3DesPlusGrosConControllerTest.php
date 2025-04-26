<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Top3DesPlusGrosConControllerTest extends WebTestCase
{
    public function testTop3RequiresLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/lepodium');

        $this->assertResponseRedirects('/login');
    }

    public function testTop3AccessAsUser()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        DatabaseTestCase::createDatabaseSchema($entityManager);

        $user = new User();
        $user->setOldId(1);
        $user->setNom('Buffet');
        $user->setPrenom('Ethienne');
        $user->setEmail('buffer.ethiene@gsb.fr');
        $user->setAdresse('123 rue de Test');
        $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
        $user->setCp('75000');
        $user->setVille('Paris');
        $user->setDateEmbauche(new \DateTime('2022-01-01'));
        $user->setRoles(['ROLE_COMPTABLE']);

        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);
        $client->request('GET', '/lepodium');

        $this->assertResponseIsSuccessful();
    }
}