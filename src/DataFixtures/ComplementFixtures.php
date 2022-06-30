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
        for ($i= 1 ; $i < 4 ; $i++) { 
            $image = new Image();
            $image->setNom("boisson$i.png");
            $complemnt = new Complement();
            $complemnt->setNom("complemnt".$i)
                      ->setPrix(800)
                      ->setImage($image);
            $manager->persist($complemnt);
        }
        $manager->flush();
    }
}
