<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class acceuilController extends AbstractController {

    #[Route('/', name: 'app_acceuil')]
    public function acceuil(ArticleRepository $articleRepository) {
        $prenom = '';
        if ($this->getUser()) {
            $prenom = $this->getUser()->getPrenom();
        }

        $articles = $articleRepository->findAll();

        return $this->render('acceuil.html.twig', [
            'prenom' => $prenom,
            'articles' => $articles,
        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil() {
        $user = $this->getUser();

        return $this->render('profil.html.twig', [
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/legal/cgu', name: 'app_cgu')]
    public function cgu() {
        return $this->render('legal/cgu.html.twig');
    }

    #[Route('/legal/cgv', name: 'app_cgv')]
    public function cgv() {
        return $this->render('legal/cgv.html.twig');
    }

    #[Route('/legal/privacy', name: 'app_privacy')]
    public function privacy() {
        return $this->render('legal/privacy.html.twig');
    }

    #[Route('/legal/about', name: 'app_about')]
    public function about() {
        return $this->render('legal/about.html.twig');
    }
}
