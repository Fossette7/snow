<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{
  public const USER_REFERENCE = 'user-ref';

  public function load(ObjectManager $manager): void
  {

    $userList = [
      [
        'username' => 'admin',
        'mail' => 'pomme@pommemail.com',
        'role' => ['ROLE_ADMIN'],
        'password' => '123456',
      ],
      [
        'username' => 'matthias',
        'mail' => 'matthias@pommemail.com',
        'role' => ['ROLE_USER'],
        'password' => '123456',
      ],
      [
        'username' => 'elodie',
        'mail' => 'elodie@pommemail.com',
        'role' => ['ROLE_USER'],
        'password' => '123456',
      ],
      [
        'username' => 'joshua',
        'mail' => 'joshua@pommemail.com',
        'role' => ['ROLE_USER'],
        'password' => '123456',
      ],
      [
        'username' => 'anna',
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
      $user->setavatar($faker->imageUrl());
      $user->setPassword($currentUser['password']);

      $manager->persist($user);
      $manager->flush();

      $this->addReference(self::USER_REFERENCE.'-'.$currentUser['username'], $user);
    }
  }
}
