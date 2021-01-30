<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AvisType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


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
     * @Security("is_granted('ROLE_USER') && user.isVerified()== true")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
    {

        $avis = new Avis;

        $form = $this->createForm(AvisType::class,$avis);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $avis->setUser($this->getUser());
            $em->persist($avis);
            $em->flush();

            $this->addFlash('success',"L'avis a été ajouté !");

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
     * @Security("is_granted('AVIS_MANAGE', avis)")
     */
    public function edit(Request $request, Avis $avis, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(AvisType::class,$avis, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success',"L'avis a été modifié !");

            return $this->redirectToRoute('app_home');
        }

        return $this->render('avis/edit.html.twig', [
            'avis' => $avis,
            'monForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/avis/{id<[0-9]+>}", name="app_avis_delete" , methods={"DELETE"})
     * @Security("is_granted('AVIS_MANAGE', avis)")
     */
    public function delete(Request $request,Avis $avis, EntityManagerInterface $em): Response
    {

        if ($this->isCsrfTokenValid('avis_deletion_' . $avis->getId(), $request->request->get('csrf_token'))){
            $em->remove($avis);
            $em->flush();

            $this->addFlash('info',"L'avis a été supprimé !");
        }
        return $this->redirectToRoute('app_home');
    }
}
