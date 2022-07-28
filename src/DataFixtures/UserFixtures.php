<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
  public const USER_REFERENCE = 'user-ref';

  private UserPasswordHasherInterface $hasher;

  public function __construct(UserPasswordHasherInterface $hasher)
  {
    $this->hasher = $hasher;
  }

  public function load(ObjectManager $manager): void
  {

    $userList = [
      [
        'username' => 'Admin',
        'mail' => 'pomme@pommemail.com',
        'role' => ['ROLE_ADMIN'],
        'password' => '123456',
      ],
      [
        'username' => 'Matthias',
        'mail' => 'matthias@pommemail.com',
        'role' => ['ROLE_USER'],
        'password' => '123456',
      ],
      [
        'username' => 'Elodie',
        'mail' => 'elodie@pommemail.com',
        'role' => ['ROLE_USER'],
        'password' => '123456',
      ],
      [
        'username' => 'Joshua',
        'mail' => 'joshua@pommemail.com',
        'role' => ['ROLE_USER'],
        'password' => '123456',
      ],
      [
        'username' => 'Anna',
        'mail' => 'fraise@pommemail.com',
        'role' => ['ROLE_ADMIN'],
        'password' => '123456',
      ],
    ];

    foreach ($userList as $currentUser) {
      $faker = Faker\Factory::create();

      $user = new User();
      $user->setUsername($currentUser['username']);
      $user->setEmail($currentUser['mail']);
      $user->setActive(true);
      $user->setCreatedAt(new \DateTime());
      $user->setRoles($currentUser['role']);
      //$user->setavatar($faker->imageUrl());
      $password = $this->hasher->hashPassword($user, $currentUser['password']);
      $user->setPassword($password);

      $manager->persist($user);
      $manager->flush();

      $this->addReference(self::USER_REFERENCE.'-'.$currentUser['username'], $user);
    }
  }
}
