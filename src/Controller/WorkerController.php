<?php

namespace App\Controller;

use App\Entity\Worker;
use App\Repository\WorkerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerController extends AbstractController
{


    #[Route("/workers/{Slug}", name:'workers_show')]
    public function show(Worker $worker):Response
    {
       
        return $this->render('worker/show.html.twig',[
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

   

}



