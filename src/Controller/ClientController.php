<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ClientController extends AbstractController
{
    #[Route('/newClient', name: 'newClient')]
    public function formClient(Request $request , EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher , ClientRepository $clientRepo): Response
    {
        $method = $request->getMethod();
        $datas =  $request->request->all();
        $allClients = $clientRepo->findAll();
        extract($datas);

        $newClient = new Client();
        $user = new User();
        $session = new Session();
        
        
        if ($method == "POST") {
            $session->set("prenom" , $prenom);
            $session->set("nom" , $nom);
            // $session->set("email" , $email);
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

            return $this->redirectToRoute('catalogue');
        }

        return $this->render('client/formClient.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    
}
