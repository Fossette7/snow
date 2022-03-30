<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public const TRICK_REFERENCE = 'trick-ref';

    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {

        $catTrickAsso = [
            0 =>'Grab',
            1 =>'Grab',
            2 =>'Grab',
            3 => 'Flip',
            4 =>'Grab',
            5 => 'Rotation',
            6 =>'Slide',
            7 => 'Rotation',
            8 => 'Flip',
            9 => 'Rotation',
            10 =>'Slide'
        ];

        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        $tricksName = ['Mute','Sad','Indy','Stalfish','Tail grab','Nose grab','Japan','Slide', 'pomme', 'poire', 'choux'];
        for ($i = 0; $i < 10; $i++){
            $trick = new Trick();
            $trick->setName($tricksName[$i]);
            $trick->setCreatedAt(new \DateTime());
            $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE.'-'.$catTrickAsso[$i]));
            $trick->setAuthor($this->getReference('USER_REFERENCE'));
            $trick->setDescription('lorem ipsum in vocate');
            $trick->setSlug(strtolower($this->slugger->slug($trick->getName())));
            $manager->persist($trick);
            $manager->flush();

        }
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }


}
