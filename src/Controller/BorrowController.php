<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\Tool;
use App\Form\BorrowingType;
use App\Repository\ToolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function PHPSTORM_META\map;

class BorrowController extends AbstractController
{
    // #[Route('/borrow', name: 'app_borrow')]
    // public function index(): Response
    // {
    //     return $this->render('borrow/index.html.twig', [
    //         'controller_name' => 'BorrowController',
    //     ]);
    // }

    #[Route('/borrow/{id}', name: 'app_borrow')]
    public function details(
        ToolRepository $toolRep,
        Tool $wich,
        $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ): Response
    {
        $selectedTool = $toolRep->find($id);

        $user = $this->getUser();
        
        $newBorrow = new Borrow;
        $form = $this->createForm(BorrowingType::class, $newBorrow);
        $form->handleRequest($request);
        
        dump($selectedTool->getBorrows());

        if($form->isSubmitted() && $form->isValid()){

            

            $newBorrow = $form->getData();
            $newBorrow->setBorrower($user);
            $newBorrow->setToolBorrowed($wich);
            $entityManager->persist($newBorrow);
            $entityManager->flush();
        }

        return $this->render('borrow/index.html.twig', [
            'selectedTool' => $selectedTool,
            'formulaire' => $form,
        ]);
    }
}
