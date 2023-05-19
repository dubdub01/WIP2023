<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends AbstractController
{
    #[Route('/companies/{Slug}', name: 'companies_show')]
    public function show(Company $company): Response
    {
        return $this->render('company/companyPartials.html.twig', [
            'company' => $company
        ]);
    }

    #[Route("/companies", name: 'companies_index')]
    public function index(CompanyRepository $repo): Response
    {
        $companies = $repo->findAll();

        return $this->render('company/index.html.twig',[
            'companies' => $companies
        ]);
    }
}
