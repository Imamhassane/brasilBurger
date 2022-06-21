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
        for ($i=0; $i < 5; $i++){ 
            $burger = new Burger();
            $image = new Image();
            $image->setNom("product-3-62748c146b0db.png");
            $burger->setNom("Cheese")
                    ->setPrix(2000)
                    ->setImage($image);
                    
            $manager->persist($burger);
            $manager->persist($image);
            $manager->flush();
        }
        for ($i=0; $i < 7; $i++){ 
            $burger = new Burger();
            $image = new Image();
            $image->setNom("product-6.png");
            $burger->setNom("Mini")
                    ->setPrix(1000)
                    ->setImage($image);
                    
            $manager->persist($burger);
            $manager->persist($image);
            $manager->flush();
        }
    }
}
