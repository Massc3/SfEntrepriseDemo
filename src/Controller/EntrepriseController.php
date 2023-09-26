<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/entreprise', name: 'new_entreprise')]
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$entreprise) {
            $entreprise = new Entreprise();
        }
        // just set up a fresh $task object (remove the example data)
        // on creer le formulaire a partir de entrepriseType
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        // on prend en charge la requete demandé
        $form->handleRequest($request);

        // si le formulaire est rempli et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on recupere les données du formaulaire 
            $entreprise = $form->getData();
            // equivalence du prepare en PDO, on prepare l'object qui va etre en base de données
            $entityManager->persist($entreprise);
            // equivalence du execute en PDO
            $entityManager->flush();
            // on fait une redirection vers notre liste entreprise 
            return $this->redirectToRoute('app_entreprise');
        }
        return $this->render('entreprise/new.html.twig', [
            'formAddEntreprise' => $form,
            'edit' => $entreprise->getId()
        ]);
    }

    #[Route('/entreprise/new', name: 'add_entreprise')]
    public function add(Request $request): Response
    {
        $entreprise = new Entreprise();

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        return $this->render('entreprise/new.html.twig', [
            'formAddEntreprise' => $form,
        ]);
    }


    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')]

    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
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
