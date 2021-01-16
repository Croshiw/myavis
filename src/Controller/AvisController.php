<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AvisType;

class AvisController extends AbstractController
{
    /**
     * @Route("/", name="app_home" , methods="GET")
     */
    public function index(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findBy([],['createdAt' => 'DESC']);
        
        return $this->render('avis/index.html.twig', compact('avis'));
    }

    /**
     * @Route("/avis/create", name="app_avis_create" , methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $avis = new Avis;

        $form = $this->createForm(AvisType::class,$avis);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($avis);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('avis/create.html.twig', [
            'monForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/avis/{id<[0-9]+>}", name="app_avis_show" , methods="GET")
     */
    public function show(Avis $avis): Response
    {
        return $this->render('avis/show.html.twig', compact('avis'));
    }

    /**
     * @Route("/avis/{id<[0-9]+>}/edit", name="app_avis_edit" , methods="GET|PUT")
     */
    public function edit(Request $request, Avis $avis, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AvisType::class,$avis, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('avis/edit.html.twig', [
            'avis' => $avis,
            'monForm' => $form->createView()
            ]);
    }
}
