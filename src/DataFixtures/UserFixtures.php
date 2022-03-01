<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++){
            $user= New User();
            $user->setUsername($faker->name);
            $user->setEmail($faker->email);
            $user->setActive(true);
            $user->setCreatedAt(new \DateTime());
            $user->setRole('user');
            $user->setComment('comment');
            $user->setavatar('avatar');
            $user->setPassword($faker->password());


        }

        $manager->flush();
    }
}
