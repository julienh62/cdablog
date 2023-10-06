<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;





class PostController extends AbstractController
{
        #[IsGranted("ROLE_USER")]
        #[Route('/createpost', name: 'createpost')]
        public function create( Request $request, EntityManagerInterface $em,  SluggerInterface $slugger): Response
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
                


                 /** @var UploadedFile $imageFile */
                //  $imageFile = $form->get('image')->getData();

                   // this condition is needed because the 'image' field is not required
                   // so the PDF file must be processed only when a file is uploaded
               /*    if ($imageFile) {
                      $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                  // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $post->setImage($newFilename);*/

               
          //  }

        

             // Enregistrez les modifications dans la base de données
                $em->persist($post);
                $em->flush();

                 // Redirigez vers la page de détails ou une autre page
              return $this->redirectToRoute('post_index');
              }

    
       return $this->render('post/createpost.html.twig', [
        'form' => $form
       ]);

     }
/*
     #[Route('/posts', name: 'post_index')]
     public function index(PostRepository $postRepo): Response
     {
         $posts = $postRepo->findAll();
       
 
         return $this->render('post/listpost.html.twig', [
             'posts' => $posts
         ]);
     }
     */
      
    #[Route('/posts', name: 'post_index')]
    public function firstFivePosts(PostRepository $postRepository)
    {
        // Récupérez les 5 premiers posts
        $posts = $postRepository->findBy([], [], 5);

        // Retournez les données au format JSON
        return $this->render('post/listpost.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/api/{offset}', name: 'apipost')]
    public function api(Request $request, PostRepository $postRepository, $offset=0)
    {
        // Récupérez les 5 premiers posts
        $posts = $postRepository->findBy([], [], 5, $offset);

        return $this->render('post/post-partial.html.twig', [
            'posts' => $posts
        ]);
    }



    #[Route('/post/{id}', name: 'post_show')]
    public function show(Post $post): Response
    {
       
        return $this->render('post/showpost.html.twig', [
           'post'=> $post
            
        ]);
    }

    //#[Security("is_granted('ROLE_USER') and post.getAuthor() == user")]
    #[IsGranted("post-edit", "post")] 
    #[Route('/edit/{id}', name: 'post-edit')]
    public function update(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Post $post): Response
    {
        // Check if the user has permission to edit this post
    
        // Create and handle the form with the existing post data
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the 'updatedAt' property
            $post->setUpdateAt(new \DateTime());

            /** @var UploadedFile $imageFile */
         //   $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the PDF file must be processed only when a file is uploaded
         /*   if ($imageFile) {
               $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
           // this is needed to safely include the file name as part of the URL
             $safeFilename = $slugger->slug($originalFilename);
             $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

         // Move the file to the directory where images are stored
         try {
             $imageFile->move(
                 $this->getParameter('images_directory'),
                 $newFilename
             );
         } catch (FileException $e) {
             // ... handle exception if something happens during file upload
         }

         // updates the 'imageFilename' property to store the PDF file name
         // instead of its contents
         $post->setImage($newFilename);
     }*/
    
            // Persist and flush the changes
            //$em->persist($post);
            $em->flush();
    
            return $this->redirectToRoute('post_index');
        }
    
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
      //  #[Security("is_granted('ROLE_USER') and post.getAuthor() == user")] 
     #[IsGranted("post-remove", "post")]     
     #[Route('/remove/{id}', name: 'post-remove')]
     public function delete( Post $post, Request $request, EntityManagerInterface $em): Response
     {
        //dd($post);
         $em->remove($post);
         $em->flush();
 
         return $this->redirectToRoute('post_index');


 }


}
