<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users_index')]
    public function index(UserRepository $repo): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'users' => $repo->findAll(),
            
        ]);
    }
}
