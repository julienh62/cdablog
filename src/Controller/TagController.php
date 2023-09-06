<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'tag')]
    public function index(TagRepository $tagRepo): Response
    {
        $tags = $tagRepo->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
          
        ]);
    }



    #[Route('/tag/{name}', name: 'all_tag_show')]
    public function showTag(Tag $tag): Response
    {
        // Récupérez tous les posts associés à ce tag
    $posts = $tag->getPosts();

    return $this->render('tag/showposttag.html.twig', [
        'tag' => $tag,
        'posts' => $posts,
    ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/createtag', name: 'create_tag')]
    public function createTag(Request $request, EntityManagerInterface $em , SluggerInterface $slugger): Response
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
