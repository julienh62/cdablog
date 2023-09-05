<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





class PostController extends AbstractController
{
        #[IsGranted("ROLE_USER")]
        #[Route('/createpost', name: 'createpost')]
        public function create( Request $request, EntityManagerInterface $em): Response
        {
           $post = new Post();
   
               // Assurez-vous que l'utilisateur connecté est associé au post
          $post->setUser($this->getUser());
         //dd($post);

         // Utilisez le nom de l'utilisateur comme auteur du post (vous devez définir cette méthode dans votre entité Post)
          // Utilisez le nom de l'utilisateur comme auteur du post
           // $post->setAuthor($user->getName());
          

         // Générez automatiquement la date de création
         


         $form = $this->createForm(PostType::class, $post);


   
         $form->handleRequest($request);
    
              if ($form->isSubmitted() && $form->isValid()) { 
        
         
                $post->setCreatedAt(new \DateTime());
                $post->setUpdateAt(new \DateTime());
                

             // Enregistrez les modifications dans la base de données
                $em->persist($post);
                $em->flush();

                 // Redirigez vers la page de détails ou une autre page
              return $this->redirectToRoute('app_home');
              }

    
       return $this->render('post/createpost.html.twig', [
        'form' => $form
       ]);

     }

     #[Route('/posts', name: 'post_index')]
     public function index(EntityManagerInterface $entityManager): Response
     {
      //  $created_at = $post->getCreatedAt();
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
        $created_at = $post->getCreatedAt();

        // Vous pouvez renvoyer le contenu sous forme de réponse HTTP
        return $this->render('post/showpost.html.twig', [
            'title' => $title,
            'content' => $content,
            'created_at' => $created_at
        ]);
    }



}
