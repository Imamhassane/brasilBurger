<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\Image;
use App\Entity\Burger;
use App\Entity\Complement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        /*  */
        $burger = new Burger();
        $imageBurger = new Image();
        $imageBurger->setNom("about10.png");
        $burger->setNom("Herbi")
                ->setPrix(2500)
                ->setImage($imageBurger);
        /*  */
        $imageComplement = new Image();
        $imageComplement->setNom("boisson5.png");
        $complement = new Complement();
        $complement->setNom("Coca")
                  ->setPrix(1200)
                  ->setImage($imageComplement);
        for ($i = 1; $i < 5 ; $i++){ 
            /*  */
            $menu = new Menu();
            $image = new Image();
            $image->setNom("menu$i.png");
            $menu->setNom("yakhanaal".$i)
                    ->setPrix($burger->getPrix() + $complement->getPrix())
                    ->setImage($image)
                    ->setBurger($burger)
                    ->addComplement($complement);
                    
            $manager->persist($burger);
            $manager->persist($complement);
            $manager->persist($menu);
            $manager->persist($image);
            $manager->flush();
        }
        
        $manager->flush();
    }
}
