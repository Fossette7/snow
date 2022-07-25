<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public const COMMENT_REFERENCE = 'comment-ref';

    public function load(ObjectManager $manager ): void
    {
        // $product = new Product();
        // $manager->persist($product);

      $idTrickAsso = [
        0 => 'Mute',
        1 => 'Sad',
        2 => 'Indy',
        3 => 'Stalfish',
        4 => 'Tail grab',
        5 => 'Nose grab',
        6 => 'Japan',
        7 => 'Slide',
        8 => 'Ollie',
        9 => 'Melon',
        10 => 'Nose Press',
        11 => '50-50',
        12 => 'Tail Press',
        13 => 'Nose Press',
        14 => 'Front side 180',
        15 => 'Front side 180'
      ];

      $userNameAsso = [
        0 => 'Matthias',
        1 => 'Elodie',
        2 => 'Admin',
        3 => 'Joshua',
        4 => 'Joshua',
        5 => 'Matthias',
        6 => 'Matthias',
        7 => 'Anna',
        8 => 'Anna',
        9 => 'Anna',
        10 => 'Joshua',
        11 => 'Joshua',
        12 => 'Joshua',
        13 => 'Elodie',
        14 => 'Elodie',
        15 => 'Anna'
      ];


      for($i=0; $i<=15; $i++){
        $comment = new Comment();
        $comment->setContent('Je suis un commentaire. 
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor 
        incididunt ut labore et dolore magna aliqua. '.$i);
        $comment->setCreatedAt(new \datetime('now'));
        $comment->setIsEnabled('true');
        $comment->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$idTrickAsso[$i]));
        $comment->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$userNameAsso[$i]));

        $manager->persist($comment);
        $manager->flush();

        $this->addReference(self::COMMENT_REFERENCE.'-'.$i, $comment);
      }
    }

  public function getDependencies()
  {
    return [
      TrickFixtures::class,
      UserFixtures::class
    ];
  }
}
