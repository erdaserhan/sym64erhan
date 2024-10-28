<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Entity\Section;


class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $em): Response
    {
        $sections = $em->getRepository(Section::class)->findAll();
        $articles = $em->getRepository(Article::class)->findBy(['published'=>true], ['articleDatePosted' => 'DESC'], 10);
        return $this->render('main/index.html.twig', [
            // on envoie les catégories à la vue
            'sections' => $sections,
            // on envoie les articles à la vue
            'articles' => $articles,
        ]);
    }

}
