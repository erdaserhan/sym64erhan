<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $articles = $em->getRepository(Article::class)->findAll();
        $sections = $em->getRepository(Section::class)->findAll();
        $section = $em->getRepository(Section::class)->findOneBy(['sectionSlug' => $slug]);
        return $this->render('main/section.html.twig', [
            'sections' => $sections,
            'section' => $section,
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{slug}', name: 'article', methods: ['GET', 'POST'])]
    public function article($slug, EntityManagerInterface $em, Request $request): Response
    {

        $sections = $em->getRepository(Section::class)->findAll();
        $articles = $em->getRepository(Article::class)->findAll();
        $article = $em->getRepository(Article::class)->findOneBy(['titleSlug' => $slug]);


        // Partie commentaires
        // création du commentaire "vierge"
        $comment = new Comments();

        // génère le formulaire
        $commentForm = $this->createForm(CommentsType::class, $comment);

        $commentForm->handleRequest($request);

        //Traitement du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setArticles($article);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('article', ['slug' => $article->getTitleSlug()]);
        }




        return $this->render('main/article.html.twig', [
            'sections' => $sections,
            'article' => $article,
            'articles' => $articles,
            'commentForm' => $commentForm->createView(),
        ]);
    }

}
