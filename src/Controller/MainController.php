<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}