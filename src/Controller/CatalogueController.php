<?php

namespace App\Controller;


use App\Repository\BurgerRepository;
use App\Repository\ComplementRepository;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class CatalogueController extends AbstractController
{

    public static function getAllFoods(BurgerRepository $burgerRepo , MenuRepository $menuRepo, ComplementRepository $complementRepo = null):array{
        $session = new Session();
        $role = $session->get('name');
        
        $burgers = $burgerRepo->findBy(["etat" => "non-archive"]);
        $menus = $menuRepo->findBy(["etat" => "non-archive"]);
        if($role == "ROLE_ADMIN"){
            $complement = $complementRepo->findBy(["etat" => "non-archive"]);
            return array_merge($burgers, $menus,$complement) ;
        }else{
            return array_merge($burgers, $menus) ;

        }
    }

    #[Route('/', name: 'catalogue')]
    public function catalogue(BurgerRepository $burgerRepo , MenuRepository $menuRepo , ComplementRepository $complementRepo=null): Response
    {
        $session = new Session();
        $role = $session->get('name');

        if($role == "ROLE_ADMIN"){
            $catalogue = $this->getAllFoods($burgerRepo, $menuRepo, $complementRepo);
        }else{
            $catalogue = $this->getAllFoods($burgerRepo, $menuRepo);

        }
        return $this->render('catalogue/catalogue.html.twig', [
            'controller_name' => 'CatalogueController',
            'role'            => $role,
            'catalogue'       => $catalogue

        ]);
    }
    

    #[Route('/showDetails/{id}', name: 'showDetails')]
    public function showDetails(Request $request , BurgerRepository $burgerRepo , MenuRepository $menuRepo): Response
    {
        $session = new Session();
        $role = $session->get('name');
        
        $id  = array_values(explode ("/", $request->getrequestUri()))[2];
        $catalogue = $this->getAllFoods($burgerRepo, $menuRepo);
        foreach ($catalogue as $value) {
            if($value->getId() == $id){

                if ($value->getType() == "menu") {
                    $details = $menuRepo->find($id);
                }else{
                    $details = $burgerRepo->find($id);
                }

            }
        }
        // dd($details);
        return $this->render('catalogue/showDetails.html.twig', [
            'details'       => $details,
            'role'            => $role,

        ]);
    }
    
}
