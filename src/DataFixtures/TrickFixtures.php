<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TrickFixtures extends Fixture
{
    public const TRICK_REFERENCE = 'trick-ref';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        $tricks = [];
        $tricksName = ['Mute','Sad','Indy','Stalfish','Tail grab','Nose grab','Japan','Slide'];
        $category = ['Grab','Flip','Rotation','Slide'];
        for ($i = 0; $i < 10; $i++){
            $tricks = new Trick();
            $tricks->setName(array_rand($tricksName, 1));
            $tricks->setCreatedAt(new \DateTime());
            $tricks->setImage($faker->imageUrl());
            $tricks->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE));
            $tricks->setUser($faker->randomElement($array = ['Paul','Amanda','Joshua']));
            $tricks->setDescription($faker->text($maxNbChars = 200));

            $manager->persist($tricks);
        }
        $this->addReference(self::TRICK_REFERENCE, $tricks);

        $manager->flush();
    }

}
