<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    // LISTER TOUS LES ARTICLES
    #[Route('/articles', name: 'app_article_index')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/new', name: 'app_article_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $article->setCreatedAt(new \DateTimeImmutable());
            
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // AFFICHER UN ARTICLE EN DÉTAIL
    #[Route('/articles/{id}', name: 'app_article_show')]
    public function show(string $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
        
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    // MODIFIER UN ARTICLE
    #[Route('/articles/{id}/edit', name: 'app_article_edit')]
    public function edit(string $id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = $articleRepository->find($id);
        
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    // SUPPRIMER UN ARTICLE
    #[Route('/articles/{id}/delete', name: 'app_article_delete', methods: ['GET', 'POST'])]
    public function delete(string $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $article = $articleRepository->find($id);
        
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_index');
    }
}