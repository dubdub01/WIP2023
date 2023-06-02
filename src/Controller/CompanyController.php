<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CompanyController extends AbstractController
{

    /**
     * Permet d'afficher une Company
     */
    #[Route('/companies/{Slug}', name: 'companies_show')]
    public function show(Company $company): Response
    {
        return $this->render('company/companyPartials.html.twig', [
            'company' => $company
        ]);
    }

    /**
     * Permet d'afficher toutes les companies
     *
     * @param CompanyRepository $repo
     * @return Response
     */
    #[Route("/companies", name: 'companies_index')]
    public function index(CompanyRepository $repo): Response
    {
        $companies = $repo->findAll();

        return $this->render('company/index.html.twig',[
            'companies' => $companies
        ]);
    }

    /**
     * Permet de modifier une Company
     */
    #[Route("/companies/{Slug}/edit", name:'company_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Company $company):Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($company);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre Company à bien été modifié {$company->getName()}"
            );
            return $this->redirectToRoute('companies_show', ['Slug'=>$company->getSlug()]);      
        }

        return $this->render("company/edit.html.twig",[
            "company" => $company,
            "myform" => $form->createView()
        ]);
    }

    /**
     * Permet d'ajouter une Company
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/company/new", name:"company_create")]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // gestion de mon image
            $file = $form['cover']->getData();
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
                $company->setCover($newFilename);
            }
            $user = $this->getUser();
            $company->setUser($user);
            $manager->persist($company);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre company a bien été créée"
            );

            return $this->redirectToRoute('app_home');

        }

        return $this->render("company/new.html.twig",[
            'myform' => $form->createView()
        ]);

    }

    /**
     * Permet de supprimer une Company
     */
    #[Route("/companies/{Slug}/delete", name:"company_delete")]
    public function delete(Company $company, EntityManagerInterface $manager): Response
    {
        $this->addFlash(
            "success", 
            "Voter Company {$company->getName()} à bien été supprimé"
        );

        $manager->remove($company);
        $manager->flush();
    
        return $this->redirectToRoute('companies_index');
    }
}
