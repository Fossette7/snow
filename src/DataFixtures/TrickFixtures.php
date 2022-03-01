<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();


        $tricksName = ["Mute","Sad","Indy","Stalfish","Tail grab","Nose grab","Japan","Slide"];
        for ($i = 0; $i < 10; $i++){
            $trick= new Trick();
            $trick->setName(array_rand($tricksName, 1));
            $trick->setCreatedAt(new \DateTime());
            $trick->getImage('snowtricks_photo_2-girl.jpg');
            $trick->setUser($faker->randomElement($array = array ('Paul','Amanda','Joshua')));

            $manager->persist($trick);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
