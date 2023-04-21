<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route("/user/new", name:"user_create")]
    public function create(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        
       $form = $this->createForm(UserType::class, $user);
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid())
       {
        $hash = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hash);

        $manager->persist($user);
        $manager->flush();
       
            $this->addFlash(
                'success', "votre inscription est réussite bienvenue {$user->getUsername()}" 
            );

            return $this->redirectToRoute(('app_home'));
        }



        return $this->render("user/new.html.twig",[
            'myform' => $form->createView()
        ]);
    }
}
