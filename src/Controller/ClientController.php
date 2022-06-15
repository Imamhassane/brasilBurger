<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\PaiementRepository;
use App\Repository\ComplementRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
   * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT') or is_granted('ROLE_USER')")
*/

class ClientController extends AbstractController
{
    
    #[Route('/mescommandes/{page?1}/{nbre?3}', name: 'mescommandes')]
    #[Route('/mescommandeEtat{etat}/{page?1}/{nbre?3}', name: 'mescommandeEtat')]
    public function mesCommande( $page , $nbre , Request $request , CommandeRepository $commandeRepo, ClientRepository $clientRepo, BurgerRepository $burgerRepo , MenuRepository $menuRepo, EntityManagerInterface $entityManager):Response{
        
        $datas = $request->request->all();
        $uri =$request->getRequestUri();

        extract($datas);
        $session    = $request->getSession();
        $userConnect = $clientRepo->findOneBy(['user' => $session->get('idUser')]);
                
        $errorNumber = $session->get('errorNumber');
        $errorMontant = $session->get('errorMontant');
        $success = $session->get('success');
        $successCommande = $session->get('successCommande');
        $commandeValider = $session->get('commandeValider');
        $errorValidation = $session->get('errorValidation');
        $alreadyPayed = $session->get('alreadyPayed');

       
      
        $UserCommande = $commandeRepo->findBy(['client' => $userConnect, 'etat' => "valider"]);
        $myCommandes = $commandeRepo->findBy(['client' => $userConnect, 'etat' => "valider"] , [], $nbre , ($page - 1) * $nbre );
        
        if( $uri == "/mescommandeEtatannuler"){
            $myCommandes = $commandeRepo->findBy(['client' => $userConnect, "etat" => "annuler"] );
        }elseif( $uri == "/mescommandeEtatvalider"){
            $myCommandes = $commandeRepo->findBy(['client' => $userConnect, "etat" => "valider"] , [], $nbre , ($page - 1) * $nbre );
        }elseif( $uri == "/mescommandeEtaten%20cours"){
            $myCommandes = $commandeRepo->findBy(['client' => $userConnect, "etat" => "en cours"]);
        }

        $nbCommandes = count($UserCommande);
        $nbPage = ceil($nbCommandes / $nbre)  ;

        return $this->render('client/mescommandes.html.twig', [
            'myCommandes' => $myCommandes,
            'isVide'    => count($myCommandes),
            'errorNumber'=> $errorNumber,
            'errorMontant'=>$errorMontant,
            'success'=>$success,
            'commandeValider'=>$commandeValider,
            'successCommande'=>$successCommande,
            'errorValidation'=>$errorValidation,
            'alreadyPayed'=>$alreadyPayed,
            'removeAlreadyPayed'   => $session->remove('alreadyPayed'),
            'removeErrorValidation'   => $session->remove('errorValidation'),
            'removeSessionNumber'   => $session->remove('errorNumber'),
            'removecommandeValider'   => $session->remove('commandeValider'),
            'removeSessionMontant'   => $session->remove('errorMontant'),
            'removeSessionSuccess'   => $session->remove('success'),
            'removeSessionSuccessCommande'   => $session->remove('successCommande'),
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre,
        ]);
       

    }
    
    #[Route('/paiement', name: 'paiement')]
    public function paiement(Request $request ,CommandeRepository $commandeRepo, ClientRepository $clientRepo, BurgerRepository $burgerRepo , MenuRepository $menuRepo, EntityManagerInterface $entityManager):Response{
        $datas =  $request->request->all();
        $session    = $request->getSession();   
        $method  = $request->getMethod();
        extract($datas);

        foreach ($commandeAPayer as $value) {
            $commandes [] = $commandeRepo->find($value);
        }
        $session->set("commandesTovalidate" , $commandes);
        $total = 0;
        foreach($commandes as $value) {
            $total += $value->getMontant();
        }
        $session->set("prixTotalCommande" , $total);
    
        return $this->render('client/paiement.html.twig', [
            'commandes' =>$commandes,
            'total'=>$total
        ]);
    }


    #[Route('/commandesTovalidate', name: 'commandesTovalidate')]
    public function commandesTovalidate( Request $request , PdfService $pdf ,PaiementRepository $paiementRepo ,CommandeRepository $commandeRepo, ClientRepository $clientRepo, BurgerRepository $burgerRepo , MenuRepository $menuRepo, EntityManagerInterface $entityManager):Response{
       

            $session    = $request->getSession();   
            $commandes = $session->get("commandesTovalidate");
            foreach ($commandes as $value) {
                $paiements[] =[
                    'paiement' =>$paiementRepo->find($value->getId()),
                    'montant'=>$value->getMontant()
                ] ;
            }
            foreach ($paiements as $value) {
                $value['paiement']->setMontant($value['montant']);
                $entityManager->persist($value['paiement']);
                $entityManager->flush();
            }   

            
            $success = "Commandes(s) payé(s) avec succés!" ;
            $session->set('success',$success);
            // $this->pdfClient($request , $pdf);
            return $this->redirectToRoute('mescommandes');
        
  
    }
    #[Route('/pdfClient' , name: 'pdfClient')]
    public function pdfClient(Request $request , PdfService $pdf ){
        $date = new DateTime("now", new DateTimeZone('Africa/Dakar'));
        $session    = $request->getSession();  

        $commandes = $session->get("commandesTovalidate");
        $html =  $this->render('client/pdf.html', [
            'items'            => $commandes  ,
            'date'      => $date->format('d-m-Y'),
            'recettes'  => $session->get("prixTotalCommande")

        ])->getContent();
        
        $pdf->showPdfFile($html);
        $commandes = $session->remove("commandesTovalidate");
        $session->remove("prixTotalCommande");
        
    }


    #[Route('/commandeValidate', name: 'commandeValidate')]
    public function commandeValidate(Request $request , ClientRepository $clientRepo, BurgerRepository $burgerRepo , MenuRepository $menuRepo, EntityManagerInterface $entityManager , ComplementRepository $complementRepo):Response{

       
        $commande = new Commande();
        $paiement = new Paiement();
        $session    = $request->getSession();
        $datas = $request->query->all();
        extract($datas);
        $numero = substr(str_shuffle(str_repeat($x='023456789ABCDEFGHKMNOPQRSTUVWXYZ', ceil(4/strlen($x)) )),1,3);
        $userConnect = $clientRepo->findOneBy(['user' => $session->get('idUser')]);

        $panier =   $session->get("panier",[]);
        $data = []; 
        foreach ($panier as $id => $quantite) {
            $idChecked = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $data [] = [
                'produit' => str_contains($id, 'Burger') ? $burgerRepo->find($idChecked) : $menuRepo->find($idChecked),
                'quantite'=> $quantite
            ];
        }
        
        $total = 0;
        $totalComplement = 0;
        foreach ($data as $item) {
            $totalItems =   $item['produit']->getPrix() *  $item['quantite'];
            $total += $totalItems;
        }
        
    
        if(count($complementadded) != 0){
            foreach ($complementadded as $value) {
                $explodeIdandQuantite [] = explode('-' , $value);
            }
            //
            foreach ($explodeIdandQuantite as $value) {
                $getKeyIdandQuantite [] = [
                    'id' => $value[0],
                    'quantite'=>$value[1]
                ];
            }
            //
            foreach ($getKeyIdandQuantite as  $value) {
                $complement = $complementRepo->find($value['id']);
                $totalPrixComplement = $complement->getPrix() * $value['quantite'];
                $total+=$totalPrixComplement;
            }
        
        }
        
        $total += $totalComplement;
       
        $date = new DateTime("now", new DateTimeZone('Africa/Dakar') );
        $cureentDate = $date->format('Y-m-d');

            $commande->setDate($cureentDate)
                    ->setMontant($total)
                    ->setNumero('BrasiL'.$numero)
                    ->setClient($userConnect);
                    
            foreach ($data as $value) {
                if(get_class($value['produit']) == "App\Entity\Menu"){
                        $commande->addMenu($value['produit']);
                }
                if(get_class($value['produit']) == "App\Entity\Burger"){
                    $commande->addBurger($value['produit']);               
                }
            }
            
            $paiement->setMontant(0)
                     ->setCommande($commande);
            
            $entityManager->persist($commande);
            $entityManager->persist($paiement);
            $entityManager->flush();
    
            $panier =   $session->remove("panier",[]);
    
            $commandeValider = "Votre commande a été enregistré avec succés!" ;
            $session->set('commandeValider',$commandeValider);
            
            return $this->redirectToRoute("mescommandes");
        
    
    }

   
}
