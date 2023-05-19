<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\ImgModifyType;
use App\Entity\UserImgModify;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account_profile')]
    public function account(): Response
    {
        return $this->render('account/profile.html.twig', [
        ]);
    }

    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/index.html.twig', [
            'hasError' => $error !== null,
            'username' => $username

        ]);
    }

    #[Route("/logout", name:"account_logout")]
    public function logout(): void
    {

    }

    /**
     * Modify user informations
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/account/mail", name:"account_mail")]
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les données ont été enregistrées'
            );
        }

        return $this->render("account/mail.html.twig",[
            'myform' =>$form->createView()
        ]);
    }

    #[Route("/account/password-update", name:'account_password')]
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getPassword()))
            {
                $form->get('oldPassword')->addError(New FormError("Le mot de passe que vous avez entré n'est pas votre mot de passe actuel"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié"
                );

                return $this->redirectToRoute('app_home');
            }

        }
        return $this->render("account/password.html.twig",[
            'myform' => $form->createView()
        ]);
        
        /**
         * Permet de modifier l'image de l'utilisateur 
         * 
         * @param Request $request
         * @param EntityManagerInterface $manager
         * @return Response
         */

    }

    #[Route("/account/imgmodify",name:"account_modifimg")]
    public function imgModify(Request $request, EntityManagerInterface $manager): Response
    {
        $imgModify = new UserImgModify();
        $user = $this->getUser();
        $form = $this->createForm(ImgModifyType::class, $imgModify);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //supprimer l'image dans le dossier
            if(!empty($user->getImage()))
            {
                unlink($this->getParameter('uploads_directory').'/'.$user->getImage());
            }

            $file = $form['newImage']->getData();
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin;Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().".".$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $user->setImage($newFilename);
            }
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
            'success',
            'Votre avatar a bien été modifié'
        );

        return $this->redirectToRoute('app_home');

        }

        return $this->render("account/imgModify.html.twig",[
            'myform' => $form->createView()
        ]);

    }

    #[Route("/account/delimg", name:'account_delimg')]
    public function removeImg(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if(!empty($user->getImage()))
        {
            unlink($this->getParameter('uploads_directory').'/'.$user->getImage());
            $user->setImage('');
            $manager->persist($user);
            $manager->flush();

            $this->addflash(
                'success',
                'Votre avatar a bien été supprimé'
            );
        }

        return $this->redirectToRoute('app_home');
    }



    }
