<?php

namespace App\Controller;

use App\Entity\Worker;
use App\Form\WorkerType;
use App\Repository\WorkerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkerController extends AbstractController
{


    #[Route("/workers/{Slug}", name: 'workers_show')]
    public function show(Worker $worker): Response
    {

        return $this->render('worker/workerPartials.html.twig', [
            "worker" => $worker
        ]);
    }

    #[Route("/workers", name: 'workers_index')]
    public function index(WorkerRepository $repo): Response
    {
        $workers = $repo->findAll();

        return $this->render('worker/index.html.twig', [
            'workers' => $workers
        ]);
    }

    #[Route("/worker/new", name:"worker_create")]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $worker = new Worker();

        $form = $this->createForm(WorkerType::class, $worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $manager->persist($worker);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre worker à bien été créé {$worker->getFirsname()}"
            );
            return $this->redirectToRoute(('app_home'));
        }

        return $this->render("worker/new.html.twig",[
            'myform' => $form->createView()
        ]);

    }
}
