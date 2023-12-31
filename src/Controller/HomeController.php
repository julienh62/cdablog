<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   #[Route('/', name: 'app_home')]
    public function index(): Response
    {
       $user = $this->getUser();
        return $this->render('home/index.html.twig', [
            'user' => $user,
           
        ]);
    }
   
/*
    #[Route('/', name: 'app_home')]
    public function index(User $user): Response
    {
       
        return $this->render('home/index.html.twig', [
            'user' => $user,
           
        ]);
    }*/
    
}