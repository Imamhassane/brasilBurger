<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\ClientRepository;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CatalogueController extends AbstractController
{

    public static function getAllFoods(Request $request , BurgerRepository $burgerRepo , MenuRepository $menuRepo, ComplementRepository $complementRepo):array{
        $session    = $request->getSession();
        $role = $session->get('name');
        
        $burgers = $burgerRepo->findBy(["etat" => "non-archive"]);
        $menus = $menuRepo->findBy(["etat" => "non-archive"]);
        if($role == "ROLE_ADMIN"){
            $complement = $complementRepo->findBy(["etat" => "non-archive"]);
            return array_merge($burgers, $menus, $complement) ;
        }else{
            return array_merge($burgers, $menus) ;

        }
    }

    #[Route('/', name: 'catalogue')]
    public function catalogue(Request $request , BurgerRepository $burgerRepo , MenuRepository $menuRepo , ComplementRepository $complementRepo): Response
    {
        $session    = $request->getSession();
        $role = $session->get('name');
       
        $catalogue = $this->getAllFoods($request , $burgerRepo, $menuRepo, $complementRepo);
        
        return $this->render('catalogue/catalogue.html.twig', [
            'controller_name' => 'CatalogueController',
            'role'            => $role,
            'catalogue'       => $catalogue

        ]);
    }

    #[Route('/showDetails/{id}', name: 'showDetails')]
    public function showDetails(Request $request , BurgerRepository $burgerRepo , MenuRepository $menuRepo , ComplementRepository $complementRepo): Response
    {
        $session    = $request->getSession();
        $role = $session->get('name');
        
        $id  = array_values(explode ("/", $request->getrequestUri()))[2];
        $catalogue = $this->getAllFoods($request , $burgerRepo, $menuRepo,$complementRepo);
        foreach ($catalogue as $value) {
            if($value->getId() == $id){

                if ($value->getType() == "Menu") {
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

    #[Route('/newClient', name: 'newClient')]
    public function formClient(Request $request , EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher , ClientRepository $clientRepo): Response
    {

        $method = $request->getMethod();
        $datas =  $request->request->all();
        $allClients = $clientRepo->findAll();
        extract($datas);

        $newClient = new Client();
        $user = new User();
        $session    = $request->getSession();
        
        
        if ($method == "POST") {
            $session->set("prenom" , $prenom);
            $session->set("nom" , $nom);
            $session->set("email" , $email);
            $session->set("password" , $password);
            $session->set("telephone" , $telephone);

            foreach ($allClients as $value) {
                if ($value->getEmail() == $email) {
                    return $this->render('client/formClient.html.twig', [
                        'restorPrenom' => $session->get('prenom'),
                        'restorNom' => $session->get('nom'),
                        // 'restorEmail' => $session->get('email'),
                        'restorPassword' => $session->get('password'),
                        'restorTelephone' => $session->get('telephone'),
                        'emailExist'    => 'L\'email exite déjà!'
                    ]);
                }
            }
            if ($prenom == "" || $nom == "" || $password == "" || $telephone == "") {
                return $this->render('client/formClient.html.twig', [
                    'restorPrenom' => $session->get('prenom'),
                    'restorNom' => $session->get('nom'),
                    'restorEmail' => $session->get('email'),
                    'restorPassword' => $session->get('password'),
                    'restorTelephone' => $session->get('telephone'),
                    'error'    => 'Merci de renseigner tous les champs!'
                ]);
            }
            
            $user->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setPassword(
                $userPasswordHasher->hashPassword(
                    $newClient,
                    $password
                    )
                )
                ->setRoles(["ROLE_CLIENT"]);
            $newClient->setTelephone($telephone)
                      ->setPrenom($prenom)
                      ->setNom($nom)
                      ->setEmail($email)
                      ->setRoles(["ROLE_CLIENT"])
                      ->setPassword(
                        $userPasswordHasher->hashPassword(
                            $newClient,
                            $password
                            )
                        );
            $newClient->setUser($user);

            $entityManager->persist($user);
            $entityManager->persist($newClient);
            $entityManager->flush();
            $InscriptSuccess = 'Votre inscription a reussi, vous pouvez vous connecter!';
            $session->set('InscriptSuccess',$InscriptSuccess);

            return $this->redirectToRoute('login');
        }

        return $this->render('client/formClient.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
    
    #[Route('/panier/add/{id}', name: 'add')]
    public function add($id , SessionInterface $session){
        $panier = $session->get("panier", []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] =  1;
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute('catalogue');

    }

    #[Route('/panier/moins/{id}', name: 'moins')]
    public function moins($id , SessionInterface $session){
        $panier = $session->get("panier", []);
        if(!empty($panier[$id])){
            $panier[$id]--;
            if ($panier[$id]==0) {
               return $this->remove($id, $session);
            }
        }else{
            $panier[$id] =  0;
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute('panier');
    }

    #[Route('/panier/addition/{id}', name: 'addition')]
    public function addition($id , SessionInterface $session){
        $panier = $session->get("panier", []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] =  1;
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute('panier');
    }

    #[Route('/panier/remove/{id}', name: 'remove')]
    public function remove($id , SessionInterface $session){
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("panier");
    }

    #[Route('/panier', name: 'panier')]
    public function panier(Request $request , SessionInterface $session , BurgerRepository $burgerRepo , MenuRepository $menuRepo):Response{
       
        $session    = $request->getSession();
        $role = $session->get('name');

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
        foreach ($data as $item) {
            $totalItems =   $item['produit']->getPrix() *  $item['quantite'];
            $total += $totalItems;
        }

        return $this->render('client/validateCommande.html.twig', [
           'items'            => $data  ,
            'total'     => $total,
            'role'      => $role
         
        ]);
    }

}
