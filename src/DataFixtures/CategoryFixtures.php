<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{

    public const CATEGORY_REFERENCE = 'category-ref';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        $categories = [];
        $catName = ['Grab','Flip','Rotation','Slide'];

        for ($i = 0; $i < 3; $i++) {
            $categories = New Category();
            $categories->setName(array_rand($catName, 1));
            $categories->addTricks($categories[$faker->numberBetween(0,9)]);
            // this reference returns the Trick object created in TrickFixtures
            $categories->addTricks($this->getReference(TrickFixtures::TRICK_REFERENCE));


            $manager->persist($categories);
        }

        $this->addReference(self::CATEGORY_REFERENCE, $categories);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
        ];
    }
}
