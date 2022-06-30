<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BurgerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i= 1 ; $i < 9 ; $i++){ 
            $burger = new Burger();
            $image = new Image();
            $image->setNom("about$i.png");
            $burger->setNom("Cheese".$i)
                    ->setPrix(2000)
                    ->setImage($image);
                    
            $manager->persist($burger);
            $manager->persist($image);
       
            $manager->flush();
        }
    }
}
