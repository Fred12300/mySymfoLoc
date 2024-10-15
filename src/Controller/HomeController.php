<?php

namespace App\Controller;

use App\Repository\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ToolRepository $tools): Response
    {
        $lastEntries = $tools->findLastEntries();
        
        return $this->render('home/index.html.twig', [
            'lastTools' => $lastEntries
        ]);
    }
}
