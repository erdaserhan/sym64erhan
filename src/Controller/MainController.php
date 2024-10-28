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
        $articleBanner = $em->getRepository(Article::class)->findBy(['published' => true], ['articleDatePosted' => 'DESC'], 3);
        $articles = $em->getRepository(Article::class)->findBy(['published' => true], ['articleDatePosted' => 'DESC'], 10);
        return $this->render('main/index.html.twig', [
            'sections' => $sections,
            'articleBanner' => $articleBanner,
            'articles' => $articles,
        ]);
    }

    #[Route('/section/{slug}', name: 'section')]
    public function section($slug, EntityManagerInterface $em): Response
    {
        $sections = $em->getRepository(Section::class)->findAll();
        $section = $em->getRepository(Section::class)->findOneBy(['sectionSlug' => $slug]);
        return $this->render('main/section.html.twig', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }

}
