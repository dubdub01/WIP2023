<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user', name: 'admin_user_index')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(UserRepository $repo): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'users' => $repo->findAll(),

        ]);
    }


    #[Route('/admin/{Slug}/delete', name: 'admin_user_delete')]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteUser(User $user, UserRepository $repo, EntityManagerInterface $manager): Response
    {
        $this->addFlash(
            "success",
            "Vous avez bien supprimé le user {$user->getUsername()}"
        );

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_user_index', [
            'users' => $repo->findAll(),

        ]);
    }

    #[Route('/admin/{Slug}/roles', name: 'admin_user_role')]
    #[IsGranted("ROLE_ADMIN")]
    public function modifRole(Request $request, EntityManagerInterface $manager, User $user, UserRepository $repo): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "vous avez bien modifié {$user->getUsername()}"
            );
            return $this->redirectToRoute('admin_user_index', [
                'users' => $repo->findAll()
            ]);
        }

        return $this->render("admin_user/edit.html.twig", [
            "myform" => $form->createView()
        ]);
    }
}
