<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{

    #[Route('/posts', name: 'post_index')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $posts = $entityManager->getRepository(Post::class)->findAll();
        
        return $this->render('post/listpost.html.twig', [
            'posts' => $posts,
        ]);
    }


    #[Route('/post/{id}', name: 'post_show')]
    public function show(Post $post): Response
    {
        // Utilisez la variable $post pour accéder aux propriétés de votre entité Post
        $title = $post->getTitle();
        $content = $post->getContent();

        // Vous pouvez renvoyer le contenu sous forme de réponse HTTP
        return $this->render('post/showpost.html.twig', [
            'title' => $title,
            'content' => $content,
        ]);
    }
}
