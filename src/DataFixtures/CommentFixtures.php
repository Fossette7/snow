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
        3 => 'Stalfish'
      ];

      $userNameAsso = [
        0 => 'matthias',
        1 => 'elodie',
        2 => 'admin',
        3 => 'joshua'
      ];


      for($i=0; $i<=3; $i++){
        $comment = new Comment();
        $comment->setContent('Je suis un commentaire '.$i);
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
