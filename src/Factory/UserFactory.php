<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'email' => self::faker()->unique()->safeEmail(),
            'adresse' => self::faker()->streetAddress(),
            'cp' => self::faker()->postcode(),
            'ville' => self::faker()->city(),
            'dateEmbauche' => self::faker()->dateTimeBetween('-10 years', 'now'),
            'roles' => ['ROLE_USER'],
            'password' => password_hash('password', PASSWORD_DEFAULT),
        ];
    }

    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
