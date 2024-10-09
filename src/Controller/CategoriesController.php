<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriesController extends AbstractController
{
    // #[Route('/categories', name: 'app_categories')]
    // public function index(): Response
    // {
        
    //     return $this->render('categories/index.html.twig', [
    //     ]);
    // }

    #[Route('/categories/{id}', name: 'app_categories_details')]
    public function details(
        CategoryRepository $catRep,
        $id,
        ): Response

    {
        $selectedCat = $catRep->find($id);
        
        return $this->render('categories/index.html.twig', [
            'selectedCategory' => $id,
            'catRep' => $catRep,
            'selectedCat' => $selectedCat,
        ]);
    }


}
