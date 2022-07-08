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
      0 =>'Mute',
      1 =>'Sad',
      2 =>'Indy',
      3 =>'Stalfish',
      4 =>'Tail grab',
      5 =>'Nose grab',
      6 =>'Japan',
      7 =>'Slide',
      8 =>'Ollie',
      9 =>'Melon',
      10 => 'Nollie',
      11 => 'Nose Press',
      12 => '50-50',
      13 => 'Tail Press',
      14 => 'Nose Press',
      15 => 'Backside 180'
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
      11 => 'img-snowboard-7-6261096003824.jpeg',
      12 => 'img-snowboard-7-6261096003824.jpeg',
      13 => 'img-snowboard-1-628f7805e40fe.jpg',
      14 => 'img-snowboard-2-628f5ef387cf6.jpg',
      15 => 'snowtricks-photo-2-girl-628f55a2028cf.jpg',
    ];

    for ($i = 0; $i <= 15; $i++) {
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
