<?php

namespace App\Controller;

use App\Entity\Book; // Importation de l'entité Book
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry; // Utilisez Doctrine\Persistence pour Symfony 6.x et supérieur
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Import de Request pour gérer le formulaire
use Symfony\Component\HttpFoundation\Response; // Import de Response
use Symfony\Component\Routing\Annotation\Route; // Import des annotations de route

#[Route('/crud/book')]
class CrudBookController extends AbstractController
{
    #[Route('/new', name: 'app_new_book')]
    public function newBook(ManagerRegistry $doctrine, Request $request): Response // Ajout du paramètre Request
    {
        // 1. Créer une instance de l'entité Book
        $book = new Book();

        // 2. Créer le formulaire associé
        $form = $this->createForm(BookType::class, $book);

        // 3. Traiter la requête du formulaire
        $form->handleRequest($request);

        // 4. Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // 5. Sauvegarder les données dans la base de données
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            // Redirection après la sauvegarde
            return $this->redirectToRoute('app_book_list');
        }

        // 6. Afficher le formulaire à l'utilisateur
        return $this->render('crud_book/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/list', name: 'app_book_list')]
    public function listBook(BookRepository $repository): Response
    {
        // Récupérer tous les livres depuis la base de données
        $books = $repository->findAll();

        // Renvoyer la liste des livres à la vue
        return $this->render('crud_book/index.html.twig', ['books' => $books]);
    }
}
