<?php

namespace App\Controller;

use App\Form\TagType;
use App\Repository\TagRepository;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class TagController extends AbstractController
{
    #[Route('/tag', name: 'tag')]
    public function index(TagRepository $tagRepo): Response
    {
        $tags = $tagRepo->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
            'email' => 'hennebo@gmail.com'
        ]);
    }

    #[Route('/createtag', name: 'create_tag')]
    public function createTag(Request $request, EntityManagerInterface $em): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        //pour recuperer les infos du formulaire
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
                $em->persist($tag);
                $em->flush();
              
              return $this->redirectToRoute('app_home');

        }
        return $this->render('tag/createtag.html.twig', [
            'tagForm' => $form
        ]);
    }
}