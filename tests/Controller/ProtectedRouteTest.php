<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Zenstruck\Foundry\Test\Factories;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\DatabaseTestCase;

class ProtectedRouteTest extends WebTestCase
{
//    use Factories;
    public function testAccessFicheFraisAsUser()
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

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
        $user->setRoles(['ROLE_USER']);

//        $user = UserFactory::createOne();


        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/selectfiche');

        $this->assertResponseIsSuccessful();
    }
}