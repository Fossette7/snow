<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i <= 9; $i++){
            $comment = new Comment();
            $comment->setAuthor($this->getReference('ADMIN_USER'));;
            $comment->setCreatedAt(new \DateTime());
            $comment->setTrick($this->getReference('TRICK'));
            $comment->setContent('Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.');
            $comment->setIsEnabled(1);

            $manager->persist($comment);
            $

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
            UserFixtures::class
        ];
    }
}
