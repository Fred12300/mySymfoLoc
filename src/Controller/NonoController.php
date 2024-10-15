<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NonoController extends AbstractController
{
    #[Route('/nono', name: 'app_nono')]
    public function index(): Response
    {
        return $this->render('nono/index.html.twig', [
            'controller_name' => 'NonoController',
        ]);
    }
}
