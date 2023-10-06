<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SearchController extends AbstractController
{
    #[Route('/search/post', name: 'app_post_search')]
        public function search(Request $request, PostRepository $postRepo): Response
    {
        //$request->query->get("search");
          // dd($request->query->get("search"));
       //   $postRepo->findBy()

       $search = $request->query->get("search");
       $posts = $postRepo->search($search);

       return $this->render('search/index.html.twig', [
        'posts' => $posts
       ]);
   }

 }
        

