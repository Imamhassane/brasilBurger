<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientFormType;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CatalogueController extends AbstractController
{
    #[Route('/', name: 'catalogue')]
    public function catalogue(BurgerRepository $burgerRepo , MenuRepository $menuRepo): Response
    {
        $session = new Session();
        $role = $session->get('name');



        return $this->render('catalogue/catalogue.html.twig', [
            'controller_name' => 'CatalogueController',
          
        ]);
    }
    
}
