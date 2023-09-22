<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    // public function index(EntityManagerInterface $entityManager): Response
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        // Render : ce qui permet de faire le lien entre le controller et la view
        // on recuperer toutes les entreprise de la bases de données
        // $entreprises = $entrepriseRepository->findAll;
        // SELECT * FROM entreprise ORDER BY raisonSociale ASC;
        $entreprises = $entrepriseRepository->findBy([], ["raisonSociale" => "ASC"]);
        // on les envoient grace a la methode render a la view index.html.twig
        return $this->render('entreprise/index.html.twig', [
            // on fais passer la variable entreprise a laquelle on lui donne la valeur entreprise
            'entreprises' => $entreprises
        ]);
    }

    #[Route('/entreprise/{id}', name: 'afficherDetail_entreprise')]
    public function afficherDetail(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/afficherDetail.html.twig', [
            // on fais passer la variable entreprise a laquelle on lui donne la valeur entreprise
            'entreprise' => $entreprise
        ]);
    }
}
