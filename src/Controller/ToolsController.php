<?php

namespace App\Controller;

use App\Entity\Tool;
use App\Form\ToolType;
use App\Repository\ToolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ToolsController extends AbstractController
{
    #[Route('/tools', name: 'app_tools')]
    public function index(ToolRepository $toolRep): Response
    {
        $tools = $toolRep->findAll();

        return $this->render('tools/index.html.twig', [
            'tools' => $tools
        ]);
    }

    #[Route('/tools/add', name: 'app_tools_add')]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $uploadsDirectory
    ): Response
    {   
        $user = $this->getUser();
        
        $newTool = new Tool;
        $form = $this->createForm(ToolType::class, $newTool);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
            $mainPic = $form->get('mainPic')->getData();

            if($mainPic){
                $originalFileName = pathinfo($mainPic->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $mainPic->guessExtension();

                try {
                    $mainPic->move($uploadsDirectory, $newFileName);
                } catch (FileException $e){
                    //Gérer les exceptions...
                }
                $newTool->setMainPic($newFileName);
            }
            
            //$newTool->setOwner();
            $newTool = $form->getData();
            $newTool->setOwner($user);
            $entityManager->persist($newTool);
            $entityManager->flush();
        }
        return $this->render('tools/add.html.twig', [
            'formulaire' => $form
        ]);
    }
}
