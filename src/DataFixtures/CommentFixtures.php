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
      //
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
        $comment1 = new Comment();
        $comment1->setContent('Je suis un commentaire d\'un passionné de snowboard' . $i . 'On peut rêver de réussir ce trick');
        $comment1->setCreatedAt(new \datetime('now'));
        $comment1->setIsEnabled('true');
        $comment1->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$idTrickAsso[$i]));
        $comment1->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$userNameAsso[$i]));

        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setContent('Je suis un commentaire d\'un passionné de snowboard'. $i .'On peut rêver de réussir ce trick');
        $comment2->setCreatedAt(new \datetime('now'));
        $comment2->setIsEnabled('true');
        $comment2->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$idTrickAsso[$i]));
        $comment2->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$userNameAsso[$i]));

        $manager->persist($comment2);

        $comment3 = new Comment();
        $comment3->setContent('Je suis un commentaire d\'un passionné de snowboard'. $i .'On peut rêver de réussir ce trick');
        $comment3->setCreatedAt(new \datetime('now'));
        $comment3->setIsEnabled('true');
        $comment3->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$idTrickAsso[$i]));
        $comment3->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$userNameAsso[$i]));

        $manager->persist($comment3);

        $comment4 = new Comment();
        $comment4->setContent('Je suis un commentaire d\'un passionné de snowboard'. $i .'On peut rêver de réussir ce trick');
        $comment4->setCreatedAt(new \datetime('now'));
        $comment4->setIsEnabled('true');
        $comment4->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$idTrickAsso[$i]));
        $comment4->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$userNameAsso[$i]));

        $manager->persist($comment4);

        $this->addReference(self::COMMENT_REFERENCE.'-'.$i, $comment1, $comment2, $comment3, $comment4);

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
