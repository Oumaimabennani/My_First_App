<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/author')]
class CrudAuthorController extends AbstractController
{
    #[Route('/list', name: 'app_crud_author')]
    public function list(AuthorRepository $repository): Response
    {
        $list = $repository->findAll();
        return $this->render('crud_author/list.html.twig', [
            'list' => $list,
        ]);
    }

    // Method to search an author by name
    #[Route('/search/{name}', name: 'app_crud_search')]
    public function searchByName(AuthorRepository $repository, string $name): Response
    {
        $authors = $repository->findByName($name);

        return $this->render('crud_author/list.html.twig', [
            'list' => $authors,
        ]);
    }

    // Method to insert a new author 
    #[Route('/new', name: 'app_crud_new_author')]
    public function newAuthor(ManagerRegistry $doctrine): Response
    {
        $author = new Author();
        $author->setName('Ahmed');
        $author->setEmail('ahmed@gmail.com');
        $author->setNbrBooks(4);

        $em = $doctrine->getManager();
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('app_crud_author'); // Redirect to the list page after adding
    }

    // Method to delete an author by id
    #[Route('/delete/{id}', name: 'app_delete_author')]
    public function deleteAuthor(Author $author, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('app_crud_author'); // Redirect to the list page after deletion
    }

    // Method to update an author by id
    #[Route('/update/{id}', name: 'app_update_author')]
    public function updateAuthor(ManagerRegistry $doctrine, AuthorRepository $rep, Request $request): Response
    {
        // Retrieve the author by id
        $id = $request->get('id');
        $author = $rep->find($id);

        if (!$author) {
            throw $this->createNotFoundException('No author found for id ' . $id);
        }

        // Update the object with new data
        $author->setEmail('flenlfleni@gmail.com');

        // Persist the updated data
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('app_crud_author'); // Redirect to the list page after update
    }
}
