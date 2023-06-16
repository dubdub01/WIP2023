<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Worker;
use Symfony\Component\Mime\Email;
use App\Repository\WorkerRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    #[Route('/email/{Slug}', name: 'email')]
    public function sendEmail(MailerInterface $mailer, Worker $worker, WorkerRepository $workerRepository): Response
    {

        $creatorEmail = $worker->getUser()->getEmail();
        $user = $this->getUser();
        $email = (new Email())
            ->from($user->getEmail())
            ->to($creatorEmail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        $this->addFlash(
            'success',
            'Mail Envoyé'
        );



        // ...
        return $this->render('/mailer/index.html.twig');
    }
    
}

