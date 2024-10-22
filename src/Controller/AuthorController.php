<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function showAuthor(): Response
    {
        $authorName = 'Victor Hugo';
        $authorEmail = 'victor.hugo@example.com';

        return $this->render('author/showAuthor.html.twig', [
            'authorName' => $authorName,
            'authorEmail' => $authorEmail,
        ]);
    }

    #[Route('/list', name: 'app_list_authors')]
    public function listAuthors(): Response
    {
        $authors = [
            ['name' => 'Victor Hugo', 'email' => 'victor.hugo@example.com', 'picture' => 'picture/im1.jpg'],
            ['name' => 'Émile Zola', 'email' => 'emile.zola@example.com', 'picture' => 'picture/im2.jpg'],
            ['name' => 'Gustave Flaubert', 'email' => 'gustave.flaubert@example.com', 'picture' => 'picture/im3.jpg'],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/searchauth', name: 'author_search')]
    public function searchByLibraryName(Request $request, AuthorRepository $authorRepository): Response
    {
        $libraryName = $request->query->get('library_name');
        $authors = $authorRepository->findByLibraryName($libraryName);

        return $this->render('author/search.html.twig', [
            'authors' => $authors,
        ]);
    }
}
