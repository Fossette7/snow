<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();
        $users = [];

        for ($i = 0; $i < 10; $i++){
            $user= new User();
            $user->setUsername($faker->name);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password());
            $user->setComment($faker->text(100));
            $user->setCreatedAt(new \DateTime());

            $manager->persist($user);
            $users[] = $user;
        }

        $categories = [];
        $categoryName = [" Rotation","Slide","Grab","Flip"];
        for ($i = 0; $i < 5; $i++){
            $category = new Category();
            $category->getName(array_rand($categoryName, 1));

            $manager->persist($category);
            $categories[] = $category;

        }

        $tricks = [];
        $tricksName = ["Mute","Sad","Indy","Stalfish","Tail grab","Nose grab","Japan"];
        for ($i = 0; $i < 7; $i++){
            $trick= new Trick();
            $trick->setName(array_rand($tricksName, 1));
            $trick->setCreatedAt(new \DateTime());
            $trick->getImage($trick);
            $trick->setCategory($category);
            $trick->getUser($user);

            $manager->persist($trick);
            $tricks[] = $trick;
        }

        $images = [];
        for ($i = 0; $i < 7; $i++){
            $image = new Image();
            $image->setName($faker->imageUrl($width = 640, $height = 480));
            $image->getTrick($trick);
        }


        $manager->flush();
    }
}
