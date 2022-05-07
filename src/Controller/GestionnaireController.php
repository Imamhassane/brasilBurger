<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Image;
use App\Entity\Burger;
use App\Entity\Complement;
use App\Form\BurgerFormType;
use App\Repository\BurgerRepository;
use App\Controller\CatalogueController;
use App\Repository\ComplementRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GestionnaireController extends AbstractController
{
    #[Route('/commande/{id}', name: 'commande')]
    public function commande(): Response
    {
        return $this->render('gestionnaire/listCommande.html.twig', [
            'controller_name' => 'GestionnaireController',
        ]);
    }

    #[Route('/listCommande', name: 'listCommande')]
    public function ListCommande(): Response
    {
        return $this->render('gestionnaire/listCommande.html.twig', [
            'controller_name' => 'GestionnaireController',
        ]);
    }


    
    #[Route('/addMenu', name: 'addMenu')]
    #[Route('/edit/{id}', name: 'edit')]
    public function addMenu(Burger $burger = null , Menu $menu = null , Request $request , EntityManagerInterface $entityManager, SluggerInterface $slugger , BurgerRepository $burgerRepo , MenuRepository $menuRepo , ComplementRepository $complementRepo): Response
    {
        
        $method         = $request->getMethod();
        $datas          = $request->request->all();
        $allBurger      = $burgerRepo->findAll();    
        $allComplement  = $complementRepo->findAll();
        $form = $this->createForm(BurgerFormType::class, $burger);
        $form->handleRequest($request);
        extract($datas);
             
        if(!$burger){
            $burger     = new Burger();
        }
        if (!$menu) {
            $menu       = new Menu();
        }
        $complement = new Complement();
        $image      = new Image();
        $session    = new Session();
        $url= array_values(explode ("/", $request->getrequestUri()));
        if($method == "GET" && $url[1] != "addMenu"){
            $id  = $url[2];
            $catalogue = CatalogueController::getAllFoods($burgerRepo, $menuRepo,$complementRepo);
            foreach ($catalogue as $value) {
                if($value->getId() == $id){
                    if ($value->getType() == "Menu") {
                        $element = $menuRepo->find($id);
                        $complement =  $element->getComplements()->toArray();
                    }elseif($value->getType() == "Burger"){
                        $element = $burgerRepo->find($id);
                    }else{
                        $element = $complementRepo  ->find($id);
                    }
                    
                }
            }
            return $this->render('gestionnaire/formMenu.html.twig', [
                'controller_name' => 'GestionnaireController',
                'form'          => $form->createView(),
                'allBurger'     => $allBurger,
                'allComplement' => $allComplement,
                'element'       => $element,
                'complement'    => $complement
            ]);
        }

        
       

 
        if ($form->isSubmitted() && $form->isValid()) {

            
            $session->set("checked" , $checked);
            $session->set("nom" , $nom);
            $session->set("prix" , $prix);
            $session->set("burgerName" , $burgerName);
            // $session->set("image" ,  $form->get('image')->getData());

            if($checked == "menu"){
                $oneBurger = $burgerRepo->find($burgerName);
                $prixBurgerInMenu = $oneBurger->getPrix();
                if (!isset($complementName)) {
                    return $this->render('gestionnaire/formMenu.html.twig', [
                        'form' => $form->createView(),
                        'allBurger' => $allBurger,
                        'allComplement' => $allComplement,
                        'restorChecked' => $session->get('checked'),
                        'restorNom' => $session->get('nom'),
                        'restorBurgerName' => $oneBurger,
                        // 'restorImage' => $session->get('image'),
                        'errorComplement'=> 'Veuillez ajouter au moins un complément!'
                    ]);
                }else{
                    foreach($complementName as $value) {
                        $manyComplement = $complementRepo->find($value);
                        $sumPrixComplement[] = $manyComplement->getPrix();                
                    }
                    $prixComplementInMenu = array_sum($sumPrixComplement);
                    $prixMenu = $prixBurgerInMenu + $prixComplementInMenu;
                }
            }


            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('image')->getData();
            if (empty($brochureFile)){
                return $this->render('gestionnaire/formMenu.html.twig', [
                    'form' => $form->createView(),
                    'allBurger' => $allBurger,
                    'allComplement' => $allComplement,
                    'restorChecked' => $session->get('checked'),
                    'restorNom' => $session->get('nom'),
                    'restorPrix' => $session->get('prix'),
                    'restorBurgerName' => $session->get('burgerName'),
                    'errorImage'=> 'Veuillez ajouter une image!'
                ]);
            }else{
                $brochureFile = $brochureFile[0];
            }
                  
                  // this condition is needed because the 'brochure' field is not required
                  // so the PDF file must be processed only when a file is uploaded
                    if ($brochureFile) {
                        $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                            // Move the file to the directory where brochures are stored
                        try{
                          $brochureFile->move(
                              $this->getParameter('brochures_directory'),
                              $newFilename
                          );
                        }catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
                        $image->setNom($newFilename);
                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents
                    }
            
            if ($checked == "burger") {
                $burger->setNom($nom)
                       ->setPrix($prix)
                       ->setImage($image)
                       ->setEtat("non-archive");
                $entityManager->persist($burger);
            }elseif($checked == "complement"){
                $complement->setNom($nom)
                           ->setPrix($prix)
                           ->setImage($image)
                           ->setEtat("non-archive");
                $entityManager->persist($complement);
            }else{
                $menu->setNom($nom)
                     ->setBurger($oneBurger)
                     ->setPrix($prixMenu)
                     ->setImage($image)
                     ->setEtat("non-archive");
                     foreach($complementName as $value) {
                        $manyComplement = $complementRepo->find($value);
                        $menu->addComplement($manyComplement);
                    }
                $entityManager->persist($menu);
            }
             
            $entityManager->persist($image);
            $entityManager->flush();
            // do anything else you need here, like send an email

                return $this->redirectToRoute('catalogue');
        }
        return $this->render('gestionnaire/formMenu.html.twig', [
            'controller_name' => 'GestionnaireController',
            'form'          => $form->createView(),
            'allBurger'     => $allBurger,
            'allComplement' => $allComplement,            
        ]);
    }



    /* #[Route('/archiver/{id}', name: 'archiver')]
    #[Route('/delete/{id}', name: 'delete')]
    public function archiveDelete(Request $request , EntityManagerInterface $entityManager, BurgerRepository $burgerRepo , MenuRepository $menuRepo  ): Response
    {
        
        $method         = $request->getMethod();
        $url  = array_values(explode ("/", $request->getrequestUri()));

        if($method=="GET" && $url[1] != "addMenu"){
            $id  = $url[2];
            $action  = $url[1];
            $catalogue = CatalogueController::getAllFoods($burgerRepo, $menuRepo);
            foreach ($catalogue as $value) {
                if($value->getId() == $id){
                    if ($value->getType() == "menu") {
                        $element = $menuRepo->find($id);
                    }else{
                        $element = $burgerRepo->find($id);
                    }

                    if ($action == "archiver") {
                        $element->setEtat("archive");
                        $entityManager->persist($element);
                    }elseif ($action == "delete") {
                        $entityManager->remove($element);
                    }
                }
            }
            // dd($details);
            $entityManager->flush();
            return $this->redirectToRoute('catalogue');
        }
       
    } */
}
