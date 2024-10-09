<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NavbarController extends AbstractController
{
    #[Route('/navbar', name: 'app_navbar')]
    public function index(CategoryRepository $catRep): Response
    {
        $categories = $catRep->findAll();
        
        return $this->render('navbar/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
