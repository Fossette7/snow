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
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

            $user= New User();
            $user->setUsername('admin');
            $user->setEmail('pomme@pommemail.com');
            $user->setActive(true);
            $user->setCreatedAt(new \DateTime());
            $user->setRole('admin');
            $user->setavatar($faker->imageUrl());
            $password = '123456';
            $user->setPassword($password);

            $manager->persist($user);

            $manager->flush();

            $this->addReference(self::USER_REFERENCE, $user);
    }
}
