<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager ): void
    {
        // $product = new Product();
        // $manager->persist($product);

      $idTrickAsso = [
        0 => 'Mute',
        1 => 'Sad',
        2 => 'Indy',
        3 => 'Stalfish'
      ];

      $userNameAsso = [
        0 => 'Matthias',
        1 => 'Elodie',
        2 => 'Anna',
        3 => 'Joshua'
      ];


      for($i=0; $i<=3; $i++){
        $comment = new Comment();
        $comment->setContent('Je suis un commentaire');
        $comment->setCreatedAt(new \datetime('now'));
        $comment->setIsEnabled('true');
        $comment->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$idTrickAsso[$i]));
        $comment->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$userNameAsso[$i]));

          $manager->flush();
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
