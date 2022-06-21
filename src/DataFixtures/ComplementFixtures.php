<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Complement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ComplementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10 ; $i++) { 
            $image = new Image();
            $image->setNom("coca-62aa85f77005b.png");
            $complemnt = new Complement();
            $complemnt->setNom("Coca")
                      ->setPrix(800)
                      ->setImage($image);
            $manager->persist($complemnt);
        }
        for ($i=0; $i < 8 ; $i++) { 
            $image = new Image();
            $image->setNom("3x-62aa862770ed6.png");
            $complemnt = new Complement();
            $complemnt->setNom("Coca")
                      ->setPrix(900)
                      ->setImage($image);
            $manager->persist($complemnt);
        }
        $manager->flush();

    }
}
