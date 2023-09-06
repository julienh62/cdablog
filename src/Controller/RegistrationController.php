<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

              /** @var UploadedFile $avatarFile */
              $avatarFile = $form->get('avatar')->getData();

              // this condition is needed because the 'avatar' field is not required
              // so the PDF file must be processed only when a file is uploaded
              if ($avatarFile) {
                 $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
             // this is needed to safely include the file name as part of the URL
               $safeFilename = $slugger->slug($originalFilename);
               $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

           // Move the file to the directory where avatars are stored
           try {
               $avatarFile->move(
                   $this->getParameter('avatars_directory'),
                   $newFilename
               );
           } catch (FileException $e) {
               // ... handle exception if something happens during file upload
           }

           // updates the 'avatarFilename' property to store the PDF file name
           // instead of its contents
           $user->setAvatar($newFilename);
       }



            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
