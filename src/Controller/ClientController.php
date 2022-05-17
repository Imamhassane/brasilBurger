<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Paiement;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
   * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT') or is_granted('ROLE_USER')")
   */

class ClientController extends AbstractController
{
    

    #[Route('/mescommandes/{page?1}/{nbre?3}', name: 'mescommandes')]
    public function mesCommande($page , $nbre , Request $request , CommandeRepository $commandeRepo, ClientRepository $clientRepo, BurgerRepository $burgerRepo , MenuRepository $menuRepo, EntityManagerInterface $entityManager):Response{
        
        $dateTime = new DateTime();

        $session    = $request->getSession();
        $userConnect = $clientRepo->findOneBy(['user' => $session->get('idUser')]);
                
        $errorNumber = $session->get('errorNumber');
        $errorMontant = $session->get('errorMontant');
        $success = $session->get('success');
        $successCommande = $session->get('successCommande');
        $commandeValider = $session->get('commandeValider');
        $UserCommande = $commandeRepo->findBy(['client' => $userConnect, 'etat' => "en cours", 'date' => $dateTime ]);

        $myCommandes = $commandeRepo->findBy(['client' => $userConnect, 'etat' => "en cours" , 'date' => $dateTime] , [], $nbre , ($page - 1) * $nbre );

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
            'removeSessionNumber'   => $session->remove('errorNumber'),
            'removeSessionMontant'   => $session->remove('errorMontant'),
            'removeSessionSuccess'   => $session->remove('success'),
            'removeSessionSuccessCommande'   => $session->remove('successCommande'),
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre
        ]);
    }
    
    #[Route('/paiement', name: 'paiement')]
    public function paiement(Request $request , CommandeRepository $commandeRepo, ClientRepository $clientRepo, BurgerRepository $burgerRepo , MenuRepository $menuRepo, EntityManagerInterface $entityManager):Response{
        $datas =  $request->request->all();
        $session    = $request->getSession();              

        extract($datas);
        $commande  = $commandeRepo->findOneBy(['numero'=>$numero]);
        $errorNumber = "";
        $errorMontant = "";
        $montant = (int)$montant;
        
        if($commande == null){
            $errorNumber = "Ce numéro de commande n'existe pas!";
            $session->set('errorNumber',$errorNumber);
        }else{
            if($commande->getMontant() != $montant ){
                $errorMontant = 'Le montant saisi ne correspond pas!';
                $session->set('errorMontant',$errorMontant);
            }else{
        
                $paiement  = $commande->getPaiement();
                $paiement->setMontant($montant);
        
                $entityManager->persist($paiement);
                $entityManager->flush();
        
                $success = "La numéro de commnade ".$numero." à été avec succés!" ;
                $session->set('success',$success);
            }
        }

        return $this->redirectToRoute('mescommandes');
    }

    #[Route('/validation', name: 'validation')]
    public function validateCommande(EntityManagerInterface $entityManager , ClientRepository $clientRepo  , Request $request , SessionInterface $session , BurgerRepository $burgerRepo , MenuRepository $menuRepo):Response{
       
        $commande = new Commande();
        $paiement = new Paiement();
        $numero = substr(str_shuffle(str_repeat($x='023456789ABCDEFGHKMNOPQRSTUVWXYZ', ceil(4/strlen($x)) )),1,3);
        $userConnect = $clientRepo->findOneBy(['user' => $session->get('idUser')]);
        $method         = $request->getMethod();
        $session    = $request->getSession();
        $role = $session->get('name');

        $panier =   $session->get("panier",[]);
        $data = []; 

        foreach ($panier as $id => $quantite) {
            $idChecked = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $idBurgers = [];
            $idMenus = [];
            if(str_contains($id, 'Burger')){
                $idBurgers [] =  (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            }
            if(str_contains($id, 'Menu')){
                $idMenus [] =  (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT) ;            }
            
            $data [] = [
                'produit' => str_contains($id, 'Burger') ? $burgerRepo->find($idChecked) : $menuRepo->find($idChecked),
                'quantite'=> $quantite
            ];
   
        }
        $total = 0;
        foreach ($data as $item) {
            $totalItems =   $item['produit']->getPrix() *  $item['quantite'];
            $total += $totalItems;
        }
        if($method == "POST"){
            $commande->setDate(new \DateTime())
                    ->setMontant($total)
                    ->setNumero('BrasiL'.$numero)
                    ->setClient($userConnect);
                    
            if (count($idMenus) > 0) {
                foreach ($idMenus as $value) {
                    $commande->addMenu($menuRepo->find($value));
                }
            }
            if(count($idBurgers) > 0){
                foreach ($idBurgers as $value) {
                    $commande->addBurger($burgerRepo->find($value));
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

        return $this->render('client/validateCommande.html.twig', [
           'items'            => $data  ,
            'total'     => $total,
            'role'      => $role
         
        ]);
    }


}
