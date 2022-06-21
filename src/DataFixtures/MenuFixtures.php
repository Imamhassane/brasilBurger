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
        for ($i = 0; $i < 15 ; $i++){ 
            /*  */
            $burger = new Burger();
            $imageBurger = new Image();
            $imageBurger->setNom("product-2.png");
            $burger->setNom("Herbi")
                    ->setPrix(1500)
                    ->setImage($imageBurger);
            /*  */
            $imageComplement = new Image();
            $imageComplement->setNom("coca-62aa85f77005b.png");
            $complement = new Complement();
            $complement->setNom("Coca")
                      ->setPrix(800)
                      ->setImage($imageComplement);
            /*  */
            $menu = new Menu();
            $image = new Image();
            $image->setNom("burgerImg5-removebg-preview-628d5c0d521ea.png");
            $menu->setNom("yakhanaal")
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
