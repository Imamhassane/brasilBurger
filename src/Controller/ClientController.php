<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ClientController extends AbstractController
{
    #[Route('/newClient', name: 'newClient')]
    public function formClient(Request $request , EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $method = $request->getMethod();
        $datas =  $request->request->all();
        extract($datas);

        $newClient = new Client();
        $user = new User();
        if ($method == "POST") {
            
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
