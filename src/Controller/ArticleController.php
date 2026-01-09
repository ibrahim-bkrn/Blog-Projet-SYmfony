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
    #[Route('/articles', name: 'app_article_index')]
    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/new', name: 'app_article_create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $article->setCreatedAt(new \DateTimeImmutable());

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_show')]
    public function show($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/edit', name: 'app_article_edit')]
    public function edit($id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $em)
    {
        $article = $articleRepository->find($id);
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/delete', name: 'app_article_delete')]
    public function delete($id, ArticleRepository $articleRepository, EntityManagerInterface $em)
    {
        $article = $articleRepository->find($id);
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_article_index');
    }
}