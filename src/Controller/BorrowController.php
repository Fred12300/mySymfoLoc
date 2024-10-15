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
use Symfony\Component\Validator\Constraints\Date;

class BorrowController extends AbstractController
{
    // #[Route('/borrow', name: 'app_borrow')]
    // public function index(): Response
    // {
    //     return $this->render('borrow/index.html.twig', [
    //         'controller_name' => 'BorrowController',
    //     ]);
    // }

    #[Route('/myLoans', name: 'app_myLoans')]
    public function loans(
        ): Response
    {
        $user = $this->getUser();
        $tools = $user->getTools();
        $today = new \DateTime();

        return $this->render('/profile/borrow/myLoans.html.twig', [
            'user' => $user,
            'tools' => $tools,
            'today' => $today,
        ]);
    }

    #[Route('/myBorrows', name: 'app_myBorrows')]
    public function borrows(
        ): Response
    {
        $user = $this->getUser();
        $today = new \DateTime();

        return $this->render('/profile/borrow/myBorrows.html.twig', [
            'user' => $user,
            'today' => $today,
        ]);
    }

    #[Route('/profile/borrow/{id}', name: 'app_borrow')]
    public function details(
        ToolRepository $toolRep,
        Tool $selectedTool,
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

        $a = $selectedTool->getBorrows();
        
        function isDateOk($inputStart, $inputEnd, $a)
        {
            $out = true;
                foreach ($a as $val) {
                    $startBorrow = $val->getStartDate();
                    $endBorrow = $val->getEndDate();
                    if(($inputStart >= $startBorrow && $inputStart <= $endBorrow)||($inputEnd >= $startBorrow && $inputEnd <= $endBorrow))
                    {
                        $out = false;
                        break;
                    }
                }
            return $out;

        }

        if($form->isSubmitted() && $form->isValid()){
            $newBorrow = $form->getData();
            $inputStart = $newBorrow->getStartDate();
            $inputEnd = $newBorrow->getEndDate();

            if (!isDateOk($inputStart, $inputEnd, $a)) {
                $this->addFlash(
                    'notice',
                    'Outil déjà prêté à ces dates...'
                );
                return $this->redirectToRoute('app_borrow', ['id' => $id]);
            } else {
                $newBorrow->setBorrower($user);
                $newBorrow->setToolBorrowed($selectedTool);
                $entityManager->persist($newBorrow);
                $entityManager->flush();
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('app_borrow', ['id' => $id]);
            }

        }

        return $this->render('/profile/borrow/index.html.twig', [
            'selectedTool' => $selectedTool,
            'formulaire' => $form,
        ]);
    }



}
