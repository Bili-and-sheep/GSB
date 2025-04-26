<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Tests\DatabaseTestCase;

class RoleAccessTest extends WebTestCase
{
    public function testAccessFicheFraisAsUser()
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

        DatabaseTestCase::createDatabaseSchema($entityManager);

        $user = new User();
        $user->setNom('UserNom');
        $user->setPrenom('UserPrenom');
        $user->setEmail('user@example.com');
        $user->setAdresse('1 rue test');
        $user->setCp('75000');
        $user->setVille('Paris');
        $user->setDateEmbauche(new \DateTime('2020-01-01'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(password_hash('password', PASSWORD_DEFAULT));

        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/selectfiche'); // Remplacer si besoin

        $this->assertResponseIsSuccessful();
    }

    public function testAccessValiderFraisAsUserIsForbidden()
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

        DatabaseTestCase::createDatabaseSchema($entityManager);

        $user = new User();
        $user->setNom('User2Nom');
        $user->setPrenom('User2Prenom');
        $user->setEmail('user2@example.com');
        $user->setAdresse('2 rue test');
        $user->setCp('75001');
        $user->setVille('Paris');
        $user->setDateEmbauche(new \DateTime('2021-01-01'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(password_hash('password', PASSWORD_DEFAULT));

        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/comptable/manegeFF'); // Remplacer si besoin

        $this->assertResponseStatusCodeSame(403); // Forbidden
    }
}