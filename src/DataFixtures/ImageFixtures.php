<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\FileUploader;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
  public const IMAGE_REFERENCE = 'image-ref';

  private $fileUploader;

  public function __construct(FileUploader $fileUploader)
  {
    $this->fileUploader = $fileUploader;
  }

  public function load(ObjectManager $manager): void
  {
    $tricksName = [
      'Mute',
      'Sad',
      'Indy',
      'Stalfish',
      'Tail grab',
      'Nose grab',
      'Japan',
      'Slide',
      'Ollie',
      'Melon',
      'Nollie',
    ];

    $trickImages = [
      0 => 'img-snowboard-1-628f7805e40fe.jpg',
      1 => 'img-snowboard-7-6261096003824.jpeg',
      2 => 'img-snowboard-1-628f7805e40fe.jpg',
      3 => 'img-snowboard-2-628f5ef387cf6.jpg',
      4 => 'snowtricks-photo-2-girl-628f55a2028cf.jpg',
      5 => 'img-snowboard-1-628f7805e40fe.jpg',
      6 => 'img-snowboard-7-6261096003824.jpeg',
      7 => 'img-snowboard-2-628f5ef387cf6.jpg',
      8 => 'img-snowboard-1-628f7805e40fe.jpg',
      9 => 'snowtricks-photo-2-girl-628f55a2028cf.jpg',
      10 => 'img-snowboard-2-628f5ef387cf6.jpg',
    ];

    for ($i = 0; $i < 10; $i++) {
      $image = new Image();
      $image->setTrick($this->getReference(TrickFixtures::TRICK_REFERENCE.'-'.$tricksName [$i]));
      $image->setName($trickImages [$i]);
      $manager->persist($image);
      $manager->flush();
      $this->addReference(self::IMAGE_REFERENCE.'-'.$i, $image);
    }
  }

  public function getDependencies()
  {
    return [
      TrickFixtures::class,
    ];
  }
}
