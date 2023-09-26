<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/employe', name: 'new_employe')]
    #[Route('/employe/{id}/edit', name: 'edit_employe')]
    public function new_edit(Employe $employe = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if (!$employe) {
            $employe = new Employe();
        }
        // just set up a fresh $task object (remove the example data)
        $employe = new Employe();
        // on creer le formulaire a partir de employeType
        $form = $this->createForm(EmployeType::class, $employe);
        // on prend en charge la requete demandé
        $form->handleRequest($request);
        // si le formulaire est rempli et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on recupere les données du formaulaire 
            $employe = $form->getData();
            // equivalence du prepare en PDO, on prepare l'object qui va etre en base de données
            $entityManager->persist($employe);
            // equivalence du execute en PDO
            $entityManager->flush();
            // on fait une redirection vers notre liste employe 
            return $this->redirectToRoute('app_employe');
        }
        return $this->render('employe/new.html.twig', [
            'formAddEmploye' => $form,
        ]);
    }

    #[Route('/employe/new', name: 'add_employe')]

    public function add(Request $request): Response
    {
        $employe = new Employe();

        $form = $this->createForm(EmployeType::class, $employe);

        return $this->render('employe/new.html.twig', [
            'formAddEmploye' => $form,
        ]);
    }

    #[Route('/entreprise/{id}/delete', name: 'delete_employe')]
    public function delete(Employe $employe, EntityManagerInterface $entityManager)

    {
        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
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
