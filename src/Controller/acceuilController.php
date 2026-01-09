<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class acceuilController extends AbstractController {

    #[Route('/', name: 'app_acceuil')]
    public function acceuil(ArticleRepository $articleRepository) {
        if ($this->getUser() != null) {
            $user = $this->getUser();

            $prenom = $user->getPrenom();
        } else {
                $prenom = '';
        }

        $articles = $articleRepository->findAll();

        return $this->render('acceuil.html.twig', [
            'prenom' => $prenom, 
            'articles' => $articles,
        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil() {
        if ($this->getUser() != null) {
            $user = $this->getUser();

            $email = $user->getEmail();
            $prenom = $user->getPrenom();
            $nom = $user->getNom();
        } else {
                $prenom = '';
        }

        return $this->render('profil.html.twig', [
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
        ]);
    }

    #[Route('/legal/cgu', name: 'app_cgu')]
    public function cgu(): Response {
        return $this->render('legal/cgu.html.twig');
    }

    #[Route('/legal/cgv', name: 'app_cgv')]
    public function cgv(): Response {
        return $this->render('legal/cgv.html.twig');
    }

    #[Route('/legal/privacy', name: 'app_privacy')]
    public function privacy(): Response {
        return $this->render('legal/privacy.html.twig');
    }

    #[Route('/legal/about', name: 'app_about')]
    public function about(): Response {
        return $this->render('legal/about.html.twig');
    }
}
