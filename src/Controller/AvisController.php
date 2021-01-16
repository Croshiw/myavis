<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use App\Entity\Avis;

class AvisController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findBy([],['createdAt' => 'DESC']);
        
        return $this->render('avis/index.html.twig', compact('avis'));
    }

    /**
     * @Route("/avis/{id<[0-9]+>}", name="app_avis_show")
     */
    public function show(Avis $avis): Response
    {
        return $this->render('avis/show.html.twig', compact('avis'));
    }
}
