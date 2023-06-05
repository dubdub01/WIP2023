<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_account_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin_account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
        ]);
    }
    
    #[Route('/admin/logout', name: 'admin_account_logout')]
    public function logout(): void
    {
        //
    }

}
