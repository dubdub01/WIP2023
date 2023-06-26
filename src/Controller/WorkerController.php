<?php

namespace App\Controller;

use App\Entity\Worker;
use App\Form\WorkerType;
use App\Repository\SkillsRepository;
use App\Repository\UserRepository;
use App\Repository\WorkerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function index(WorkerRepository $repo, SkillsRepository $skillsrepo, Request $request, PaginatorInterface $paginator): Response
    {
        $selectedSkillsId = $request->query->get('skills');

    $skills = $skillsrepo->findAll();
    $workers = $repo->findBySkills($selectedSkillsId);

    // Paginer les résultats
    $pagination = $paginator->paginate(
        $workers,
        $request->query->getInt('page', 1), // Numéro de la page en cours, par défaut 1
        2 // Nombre d'éléments par page
    );

    return $this->render('worker/index.html.twig', [
        'workers' => $workers,
        'skills' => $skills,
        'selectedSkillsId' => $selectedSkillsId,
        'pagination' => $pagination,
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
    public function create(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
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
            $cv = $form->get('cv')->getData();
            if ($cv) {
                $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger -> slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cv->guessExtension();

                try{
                    $cv->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'error',
                        "something went wrong"
                    );
                }

                $worker->setCv($newFilename);

            }


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

    /**
     * Permet de modifier un Worker 
     */
    #[Route("/workers/{Slug}/edit", name:'worker_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Worker $worker, SluggerInterface $slugger):Response
    {
        $form = $this->createForm(WorkerType::class, $worker);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $cv = $form->get('cv')->getData(); // Récupère le nouveau fichier CV
            if ($cv) {
                $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $cv->guessExtension();
    
                try {
                    $cv->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'error',
                        "Une erreur s'est produite lors de la mise à jour du CV."
                    );
                }
    
                $worker->setCv($newFilename);
            }


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
    public function delete(Worker $worker, EntityManagerInterface $manager, UserRepository $user): Response
    {

        $worker->getUser()->setWorker(null);
        
        $this->addFlash(
            "success", 
            "Voter Worker {$worker->getFirsname()} - {$worker->getLastname()} à bien été supprimé"
        );

        $manager->remove($worker);
        $manager->flush();
    
        return $this->redirectToRoute('workers_index');
    }
}
