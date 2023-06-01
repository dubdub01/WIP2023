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

    /**
     * Permet d'afficher la page d'un worker
     */
    #[Route("/workers/{Slug}", name: 'workers_show')]
    public function show(Worker $worker): Response
    {

        return $this->render('worker/workerPartials.html.twig', [
            "worker" => $worker
        ]);
    }

    /**
     * Permet d'afficher la liste des Workers
     *
     * @param WorkerRepository $repo
     * @return Response
     */
    #[Route("/workers", name: 'workers_index')]
    public function index(WorkerRepository $repo): Response
    {
        $workers = $repo->findAll();

        return $this->render('worker/index.html.twig', [
            'workers' => $workers
        ]);
    }

    /**
     * Permet de créer un Worker
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/worker/new", name:"worker_create")]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $worker = new Worker();

        $form = $this->createForm(WorkerType::class, $worker);
        $form->handleRequest($request);

        /**
         * Permet de vérifier si le User à déjà un Worker
         */
        if ($this->getUser()->getWorker()){
            $this->addFlash("danger", "Malheureusement vous ne pouvez avoir qu'un worker");
        
            return $this->redirectToRoute('workers_index');
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user = $this->getUser();
            $worker->setUser($user);
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

    #[Route("/workers/{Slug}/edit", name:'worker_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Worker $worker):Response
    {
        $form = $this->createForm(WorkerType::class, $worker);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();
            $worker->setUser($user);
            $manager->persist($worker);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre worker à bien été modifié {$worker->getFirsname()}"
            );
            return $this->redirectToRoute('workers_show', ['Slug'=>$worker->getSlug()]);      
        }

        return $this->render("worker/edit.html.twig",[
            "worker" => $worker,
            "myform" => $form->createView()
        ]);    }

    /**
     * Permet de supprimer un Worker
     */
    #[Route("/workers/{Slug}/delete", name:"worker_delete")]
    public function delete(Worker $worker, EntityManagerInterface $manager): Response
    {
        $this->addFlash(
            "success", 
            "Voter Worker {$worker->getFirsname()} - {$worker->getLastname()} à bien été supprimé"
        );

        $manager->remove($worker);
        $manager->flush();
    
        return $this->redirectToRoute('workers_index');
    }
}
