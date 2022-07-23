<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public const CATEGORY_REFERENCE = 'category-ref';

    public function load(ObjectManager $manager): void
    {

        $catName = ['Grab','Flip','Rotation','Slide'];
        for ($i = 0; $i <= 3; $i++) {
            $category = New Category();
            $category->setName($catName[$i]);

            // this reference returns the Trick object created in TrickFixtures
            $manager->persist($category);
            $this->addReference(self::CATEGORY_REFERENCE.'-'.$catName[$i], $category);
        }
        $manager->flush();
    }
}
