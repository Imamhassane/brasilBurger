<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Burger;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Entity\Complement;
use App\Service\PdfService;
use App\Form\BurgerFormType;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use App\Repository\BurgerRepository;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Controller\CatalogueController;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

  /**
   * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT') or is_granted('ROLE_USER')")
   */
class GestionnaireController extends AbstractController
{

    #[Route('/traitementCommande', name: 'traitement')]
    public function traitement(EntityManagerInterface $entityManager , Request $request, CommandeRepository $commandeRepo): Response
    {
            
                $session    = $request->getSession();
                $data = $request->request->all();
                extract($data);

                if (count($commandeATraiter) != 0) {
                    foreach ($commandeATraiter as  $value) {
                        $commandes[] = $commandeRepo->find($value);
                    }
                }
                $session->set("commandeATraiter" , $commandeATraiter);

                
                return $this->render('gestionnaire/traitement.html.twig', [
                    'commandes' => $commandes,
                ]);
    } 
    #[Route('/commandeATraiter', name: 'commandeATraiter')]
    public function commandeATraiter(EntityManagerInterface $entityManager , Request $request ,  CommandeRepository $commandeRepo): Response
    {
            $session    = $request->getSession();
            $data = $request->request->all();
            extract($data);

         
            $commandeATraiter = $session->get("commandeATraiter");
            foreach ($commandeATraiter as $value2) {
                $commandes [] = $commandeRepo->find($value2);
                
                /*  */
            }
            foreach ($commandes as $value) {
                if ($choix=="valider") {
                    $commandesTraiter [] = $value->setEtat('valider');
                    
                    // message de succés
                    $commandeValiderByGest = "Commande(s) validée(s) avec succés!" ;
                    $session->set('commandeValiderByGest',$commandeValiderByGest);      
               
                }elseif ($choix=="annuler") {
                    $commandesTraiter [] =  $value->setEtat('annuler');
                    
                    // message de succés
                    $commandeAnnulerByGest = "Commande(s) annulée(s) avec succés!" ;
                    $session->set('commandeAnnulerByGest',$commandeAnnulerByGest);
                }
                $entityManager->persist($value);
                $entityManager->flush();
              
            }

            $session->remove("commandeATraiter");
                
            return $this->redirectToRoute('listCommande');       
               
    } 

    #[Route('/listCommande/{page?1}/{nbre?3}', name: 'listCommande')]
    #[Route('/commande{etat}/{page?1}/{nbre?3}', name: 'commandeFilter')]
    public function ListCommande( Request $request,CommandeRepository $commandeRepo , $page , $nbre, ClientRepository $clientRepo): Response
    {
        $uri = $request->getRequestUri();

        $datas = $request->request->all();
        extract($datas);
        $session    = $request->getSession();

        $clients = $clientRepo->findAll();

        $commandes = $commandeRepo->findBy(["etat" => "en cours"] , ["date" => "DESC"], $nbre , ($page - 1) * $nbre );
        
        $commandesEncours = $commandeRepo->findBy(["etat" => "en cours"]);
        
        $commandeValiderByGest = $session->get('commandeValiderByGest');
        $commandeAnnulerByGest = $session->get('commandeAnnulerByGest');
        
        
        if (array_values(explode ("/", $request->getrequestUri()))[1] != "listCommande") {
      
            if( $uri == "/commandeannuler"){
                $commandes = $commandeRepo->findBy(["etat" => "annuler"],["date" => "DESC"] );
            }
            
            
            
            
            
            
            
            elseif( $uri == "/commandevalider"){
                $commandes = $commandeRepo->findBy(["etat" => "valider"],["date" => "DESC"] );
            }elseif($uri == "/commandeencours"){
                $commandes = $commandeRepo->findBy(["etat" => "en cours"], ["date" => "DESC"] , $nbre , ($page - 1) * $nbre );
            }elseif($uri == "/commandeBurger"){
                $commandes = $commandeRepo->findBurgersAndCommande();
            }elseif($uri == "/commandeMenu"){
                $commandes = $commandes = $commandeRepo->findMenusAndCommande();
            }elseif(preg_match('~[0-9]+~', $uri)){
                $id = (int) filter_var($uri, FILTER_SANITIZE_NUMBER_INT);
                $commandes = $commandeRepo->findBy(["client" => $id]);
            }if(isset(explode('+' , $uri)[1])){
                $date = explode('+' , $uri)[1];
                $commandes = $commandeRepo->findBy(["date" => $date]);
            }
        }
            
        $nbCommandes = count($commandesEncours);
        $nbPage = ceil($nbCommandes / $nbre) ;

        return $this->render('gestionnaire/listCommande.html.twig', [
            'commandes' => $commandes,
            'isVide'    => count($commandes),
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre,
            'clients'       => $clients,
            'commandeValiderByGest'=>$commandeValiderByGest,
            'removecommandeValiderByGest'   => $session->remove('commandeValiderByGest'),
            'commandeAnnulerByGest'=>$commandeAnnulerByGest,
            'removecommandeAnnulerByGest'   => $session->remove('commandeAnnulerByGest'),
        ]);
    }
    
    #[Route('/addMenu', name: 'addMenu')]
    #[Route('/edit/{id}', name: 'edit')]
    public function addMenu(Burger $burger = null , Menu $menu = null , Complement $complement =null,  Request $request , EntityManagerInterface $entityManager, SluggerInterface $slugger , BurgerRepository $burgerRepo , MenuRepository $menuRepo , ComplementRepository $complementRepo): Response
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
        if (!$complement) {
            $complement = new Complement();
        }
        $image      = new Image();
        $session    = $request->getSession();
        
        $url= array_values(explode ("/", $request->getrequestUri()));
        if($method == "GET" && $url[1] == "edit"){
            $id  = $url[2];
            $Allburgers = $burgerRepo->findAll();
            $Allmenus = $menuRepo->findAll();
            $Allcomplement = $complementRepo->findAll();
            $newId =  (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $element = [];
            $catalogue = array_merge( $Allburgers, $Allmenus,$Allcomplement);
            foreach ($catalogue as $value) {
                if($value->getId() == $newId){
                    if (str_contains($id , "Menu")) {
                        $element = $menuRepo->find($newId);
                        $complement =  $element->getComplements()->toArray();
                    }elseif(str_contains($id , "Burger")){
                        $element = $burgerRepo->find($newId);
                    }elseif(str_contains($id , "Complement")){
                        $element = $complementRepo  ->find($newId);
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
            // $session->set("image" ,  $form->get('image')->getData());
            if($checked == "Menu"){
                $oneBurger = $burgerRepo->find($burgerName);
                $prixBurgerInMenu = $oneBurger->getPrix();
                foreach($complementName as $value) {
                    $manyComplement = $complementRepo->find($value);
                    $sumPrixComplement[] = $manyComplement->getPrix();                
                }
                $prixComplementInMenu = array_sum($sumPrixComplement);
                $prixMenu = $prixBurgerInMenu + $prixComplementInMenu;
                
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
            
            if ($checked == "Burger") {
                $burger->setNom($nom)
                       ->setPrix($prix)
                       ->setImage($image)
                       ->setEtat("non-archive");
                $entityManager->persist($burger);
            }elseif($checked == "Complement"){
                $complement->setNom($nom)
                           ->setPrix($prix)
                           ->setImage($image)
                           ->setEtat("non-archive");
                $entityManager->persist($complement);
            }elseif($checked == "Menu"){
                $menu->setNom($nom)
                     ->setBurger($oneBurger)
                     ->setPrix($prixMenu)
                     ->setImage($image)
                     ->setEtat("non-archive");
                     foreach($complementName as $value) {
                        $menu->addComplement($complementRepo->find($value));
                    }
                $entityManager->persist($menu);
            }

            $entityManager->persist($image);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // added success
            $session->set("addFoodSuccess", "Produit ajouter avec succés");

            return $this->redirectToRoute('listMenu');
        }
            return $this->render('gestionnaire/formMenu.html.twig', [
                'controller_name' => 'GestionnaireController',
                'form'          => $form->createView(),
                'allBurger'     => $allBurger,
                'allComplement' => $allComplement,            
            ]);
    }

    #[Route('/desarchiver/{id}', name: 'desarchiver')]
    #[Route('/archiver/{id}', name: 'archiver')]
    #[Route('/delete/{id}', name: 'delete')]
    public function archiveDelete(Request $request , EntityManagerInterface $entityManager, BurgerRepository $burgerRepo , MenuRepository $menuRepo, ComplementRepository $complementRepo  ): Response
    {
        
        $method         = $request->getMethod();
        $url  = array_values(explode ("/", $request->getrequestUri()));

        if($method=="GET" && $url[1] != "addMenu"){
            $id  = $url[2];
            $action  = $url[1];
            $burgers = $burgerRepo->findAll();
            $menus = $menuRepo->findAll();
            $complement = $complementRepo->findAll();
            $catalogue = array_merge($burgers , $menus , $complement);
            // $catalogue = CatalogueController::getAllFoods($burgerRepo, $menuRepo,$complementRepo);
            foreach ($catalogue as $value) {
                if($value->getId() == $id){
                    if ($value->getType() == "Menu") {
                        $element = $menuRepo->find($id);
                    }elseif ($value->getType() == "Burger"){
                        $element = $burgerRepo->find($id);
                    }else{
                        $element = $complementRepo->find($id);
                    }
                    if ($action == "archiver") {
                        $element->setEtat("archive");
                        $entityManager->persist($element);
                    }elseif ($action == "delete") {
                        $entityManager->remove($element);
                    }elseif($action == "desarchiver") {
                        $element->setEtat("non-archive");
                        $entityManager->persist($element);
                        $entityManager->flush();
                        return $this->redirectToRoute('archiveList');
                    }
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('listMenu');
        }
       
    }

    #[Route('/archiveList', name: 'archiveList')]
    public function archiveList(Request $request,BurgerRepository $burgerRepo , MenuRepository $menuRepo, ComplementRepository $complementRepo): Response
    {
        $session    = $request->getSession();
        $role = $session->get('name');

        $burgers = $burgerRepo->findBy(["etat" => "archive"]);
        $menus = $menuRepo->findBy(["etat" => "archive"]);
        $complement = $complementRepo->findBy(["etat" => "archive"]);
        
        $catalogue = array_merge($burgers , $menus , $complement);
        $vide = count($catalogue)." élément archivé(s)";
        if (count($catalogue)==0) {
            $vide = "Aucun élément n'a été archivé!";
        }
        return $this->render('gestionnaire/archives.html.twig', [
            'catalogue' => $catalogue,
            'isVide'      => $vide,
            'role'      => $role,
            'isPaginated'   => true
        ]);
    }

    #[Route('/listMenu/{page?1}/{nbre?1}', name: 'listMenu')]
    public function listMenu(Request $request , $page , $nbre , BurgerRepository $burgerRepo , MenuRepository $menuRepo, ComplementRepository $complementRepo): Response
    {
        $burgers = $burgerRepo->findBy(['etat' => "non-archive"] , [], $nbre , ($page - 1) * $nbre );
        $menus = $menuRepo->findBy(['etat' => "non-archive"] , [], $nbre , ($page - 1) * $nbre );
        $complements = $complementRepo->findBy(['etat' => "non-archive"] , [], $nbre , ($page - 1) * $nbre );
             
        $count = max( count($burgerRepo->findBy(['etat' => "non-archive"])) , count($menuRepo->findBy(['etat' => "non-archive"])) , count($complementRepo->findBy(['etat' => "non-archive"])) );
        
        $catalogue = array_merge( $burgers, $menus, $complements);
        $session    = $request->getSession();
    
        $addFoodSuccess = $session->get('addFoodSuccess');
               
        $nbPage = $count;
        
        return $this->render('gestionnaire/listMenu.html.twig', [
            'catalogue'       => $catalogue,
            'isVide'    => count($catalogue),
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre,
            'addFoodSuccess'=>$addFoodSuccess,
            'removeaddFoodSuccess'   => $session->remove('addFoodSuccess'),

        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(Request $request,BurgerRepository $burgerRepo , MenuRepository $menuRepo, ComplementRepository $complementRepo , CommandeRepository $commandes): Response
    {
       
        $date = new DateTime("now", new DateTimeZone('Africa/Dakar') );
        $cureentDate = $date->format('Y-m-d');
        
        $commandeJournee = $commandes->findBy(["date" => $cureentDate, "etat" => "valider"]);
        $recettes = 0 ;
        foreach ($commandeJournee as $value) {
            $recettes += $value->getMontant();
        }
        return $this->render('gestionnaire/dashboard.html.twig', [
            'burgers'           => count($burgerRepo->findAll()),
            'menus'             => count($menuRepo->findAll()),
            'complements'       => count($complementRepo->findAll()),
            'commandeEncours'   => $commandes->findBy(["etat"=>"en cours" , "date" => $cureentDate]),
            'commandeValider'   => $commandes->findBy(["etat"=>"valider" , "date" => $cureentDate]),
            'commandeAnnuler'   => $commandes->findBy(["etat"=>"annuler" , "date" => $cureentDate]),
            'date'              => $date->format('d-m-Y'),
            'recettes'          => $recettes,
        ]);
    }

    #[Route('/pdf', name: 'pdf')]
    public function generatePdf(CommandeRepository $commandes , PdfService $pdf)
    {
        // generate pdf 

        $date = new DateTime("now", new DateTimeZone('Africa/Dakar') );
        $cureentDate = $date->format('Y-m-d');

        $commandeJournee = $commandes->findBy(["date" => $cureentDate, "etat" => "valider"]);
        $recettes = 0 ;

        foreach ($commandeJournee as $value) {
            $recettes += $value->getMontant();
        }

        $html =  $this->render('gestionnaire/pdf.html', [
            'items'            => $commandeJournee  ,
            'date'      => $date->format('d-m-Y'),
            'recettes'  => $recettes,

        ])->getContent();

        $pdf->showPdfFile($html);
        

    }
}
