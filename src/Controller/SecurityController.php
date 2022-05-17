<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(Request $request , AuthenticationUtils $authenticationUtils): Response
    {
        
        $session    = $request->getSession();
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $InscriptSuccess = $session->get('InscriptSuccess');

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'InscriptSuccess' => $InscriptSuccess, 'remove' =>$session->remove('InscriptSuccess') ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(Request $request): void
    {
       
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/redirection', name: 'redirection')]
    public function redirection(Request $request ): Response
    {

        if ($this->getUser()) {
            $role = $this->getUser()->getRoles();
            $idUser = array_values((array)$this->getUser())[0];
            $session    = $request->getSession();

            $session->set('idUser', $idUser);
            $session->set('name', $role[0]);

            $targetPath = $session->get('targetPath');


            if($role[0] == "ROLE_ADMIN" && $targetPath == null){
                return $this->redirectToRoute('listCommande');
            }elseif($role[0] == "ROLE_CLIENT" && $targetPath == null){
                return $this->redirectToRoute('catalogue');
            }else{
                return new RedirectResponse($targetPath);
            }
        }   
    }
}
