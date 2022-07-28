<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;
use Faker;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
  public const TRICK_REFERENCE = 'trick-ref';

  protected $slugger;
  private $fileUploader;

  public function __construct(SluggerInterface $slugger, FileUploader $fileUploader)
  {
    $this->slugger = $slugger;
    $this->fileUploader = $fileUploader;
  }

  public function load(ObjectManager $manager): void
  {

    $catTrickAsso = [
      0 => 'Grab',
      1 => 'Grab',
      2 => 'Grab',
      3 => 'Flip',
      4 => 'Grab',
      5 => 'Rotation',
      6 => 'Slide',
      7 => 'Rotation',
      8 => 'Flip',
      9 => 'Rotation',
      10 => 'Slide',
      11 => 'Slide',
      12 => 'Rotation',
      13 => 'Flip',
      14 => 'Rotation',
      15 => 'Slide',
    ];

    $trickUser = [
      0 => 'Admin',
      1 => 'Admin',
      2 => 'Matthias',
      3 => 'Admin',
      4 => 'Matthias',
      5 => 'Elodie',
      6 => 'Anna',
      7 => 'Elodie',
      8 => 'Joshua',
      9 => 'Elodie',
      10 => 'Joshua',
      11 => 'Anna',
      12 => 'Elodie',
      13 => 'Joshua',
      14 => 'Elodie',
      15 => 'Joshua',
    ];

    $faker = Faker\Factory::create();

    $tricksName = [
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
      13 => 'Backside 180',
      14 => 'Front side 180',
      15 => 'Nollie'
    ];

    for ($i = 0; $i <= 15; $i++) {
      $trick = new Trick();
      $trick->setName($tricksName[$i]);
      $trick->setCreatedAt(new \DateTime());
      $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE.'-'.$catTrickAsso[$i]));
      $trick->setAuthor($this->getReference(UserFixtures::USER_REFERENCE.'-'.$trickUser[$i]));
      $trick->setDescription('Une description d\'une figure folle, ou le rider vole dans les airs. Lorem ipsum dolor sit amet');
      $trick->setSlug(strtolower($this->slugger->slug($trick->getName())));

      $manager->persist($trick);
      $manager->flush();

      $this->addReference(self::TRICK_REFERENCE.'-'.$tricksName[$i], $trick);
    }
  }

  public function getDependencies()
  {
    return [
      CategoryFixtures::class,
      UserFixtures::class,
    ];
  }


}
