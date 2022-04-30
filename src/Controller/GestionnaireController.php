<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Image;
use App\Entity\Burger;
use App\Entity\Complement;
use App\Form\BurgerFormType;
use App\Repository\BurgerRepository;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GestionnaireController extends AbstractController
{
    #[Route('/listCommande', name: 'listCommande')]
    public function ListCommande(): Response
    {
        return $this->render('gestionnaire/listCommande.html.twig', [
            'controller_name' => 'GestionnaireController',
        ]);
    }


    #[Route('/addMenu', name: 'addMenu')]
    public function addMenu(Request $request , EntityManagerInterface $entityManager, SluggerInterface $slugger , BurgerRepository $burgerRepo , ComplementRepository $complementRepo): Response
    {
        
        $method = $request->getMethod();
        $datas =  $request->request->all();
        extract($datas);
        
        $allBurger = $burgerRepo->findAll();    
       

        $allComplement = $complementRepo->findAll();
        /* $oneComplement = $burgerRepo->find($burgerName);
        $prixBurgerInMenu = $oneBurger->getPrix(); */
        

        $burger = new Burger();
        $menu = new Menu();
        $complement = new Complement();
        $image = new Image();


        $form = $this->createForm(BurgerFormType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($checked == "menu"){
                $oneBurger = $burgerRepo->find($burgerName);
                $prixBurgerInMenu = $oneBurger->getPrix();
                
                if (!isset($complementName)) {
                    $errorComplement = 'Veuillez ajouter au moins un complément!';
                    return $this->render('gestionnaire/formMenu.html.twig', [
                        'controller_name' => 'GestionnaireController',
                        'form' => $form->createView(),
                        'allBurger' => $allBurger,
                        'allComplement' => $allComplement,
                        'errorComplement'=>$errorComplement
                    ]);
                }else{

                    foreach ($complementName as $value) {
                        $som = 0;
                        $manyComplement = $complementRepo->find($value);
                        $sumPrixComplement[] = $manyComplement->getPrix();                
                    }
                    $prixComplementInMenu = array_sum($sumPrixComplement);
                    $prixMenu = $prixBurgerInMenu + $prixComplementInMenu;
                }
            }


            


                  /** @var UploadedFile $brochureFile */
                  $brochureFile = $form->get('image')->getData();
                  $brochureFile = $brochureFile[0];
                  
                  // this condition is needed because the 'brochure' field is not required
                  // so the PDF file must be processed only when a file is uploaded
                    if ($brochureFile) {
                        $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
      
                            // Move the file to the directory where brochures are stored
                        try {
                          $brochureFile->move(
                              $this->getParameter('brochures_directory'),
                              $newFilename
                          );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
                        $image->setNom($newFilename);
                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents

                        }
            if ($checked == "burger") {
                $burger->setNom($nom)
                       ->setPrix($prix)
                       ->setImage($image);
                $entityManager->persist($burger);

            }elseif($checked == "complement"){
                $complement->setNom($nom)
                           ->setPrix($prix)
                           ->setImage($image);
                $entityManager->persist($complement);

            }else{
                $menu->setNom($nom)
                     ->setBurger($oneBurger)
                     ->addComplement($manyComplement)
                     ->setPrix($prixMenu)
                     ->setImage($image);
                $entityManager->persist($menu);
            }
             
            $entityManager->persist($image);
            $entityManager->flush();
            
            // do anything else you need here, like send an email

                return $this->redirectToRoute('catalogue');
        }
        return $this->render('gestionnaire/formMenu.html.twig', [
            'controller_name' => 'GestionnaireController',
            'form' => $form->createView(),
            'allBurger' => $allBurger,
            'allComplement' => $allComplement,
            
            
        ]);
    }
}
