<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(EmployeRepository $employeRepository): Response
    {
        // $employes = $employRepository->findAll();
        // SELECT * FROM empoye ORDER BY nom ASC;
        $employes = $employeRepository->findBy([], ['nom' => 'ASC']);
        // on les envoient grace a la methode render a la view index.html.twig
        return $this->render('employe/index.html.twig', [
            // on fais passer la variable entreprise a laquelle on lui donne la valeur entreprise
            'employes' => $employes
        ]);
    }

    #[Route('/employe/{id}', name: 'afficherDetail_employe')]
    public function afficherDetail(Employe $employe): Response
    {
        return $this->render('employe/afficherDetail.html.twig', [
            // on fais passer la variable employe a laquelle on lui donne la valeur employe
            'employe' => $employe
        ]);
    }
}
