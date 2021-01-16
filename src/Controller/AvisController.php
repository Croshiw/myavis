<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;

class AvisController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AvisRepository $avisRepository)
    {
        $avis = $avisRepository->findAll();
        
        return $this->render('avis/index.html.twig', compact('avis'));
    }
}
