<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SelectFicheControllerTest extends WebTestCase
{
    public function testSelectFicheAccessAsUser()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        DatabaseTestCase::createDatabaseSchema($entityManager);

        $user = new User();
        $user-> setNom('UserNom')
            ->setPrenom('UserPrenom')
            ->setEmail('user@example.com')
            ->setAdresse('1 rue test')
            ->setCp('75000')
            ->setVille('Paris')
            ->setDateEmbauche(new \DateTime('2020-01-01'))
            ->setRoles(['ROLE_USER'])
            ->setPassword(password_hash('password', PASSWORD_DEFAULT));
        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);
        $client->request('GET', '/selectfiche');

        $this->assertResponseIsSuccessful();
    }
}