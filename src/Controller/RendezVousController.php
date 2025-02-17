<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RendezVousRepository;
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Form\RechercheStatutType;
use Doctrine\Persistence\ManagerRegistry;

final class RendezVousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendez_vous')]
    public function index(): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'controller_name' => 'RendezVousController',
        ]);
    }



        #[Route('/showrendezvous', name: 'app_showrendezvous')]
    public function showrendezvous(RendezVousRepository $repoRendezVous, Request $req): Response
    {
        $rendezvous = $repoRendezVous->findAll();
        $f = $this->createForm(RechercheStatutType::class);
        $f->handleRequest($req);

        if ($f->isSubmitted()) {
            $data = $f->get('search')->getData();
            $rendezvous = $repoRendezVous->rechercheParStatut($data);
            return $this->render('rendez_vous/showrendezvous.html.twig', [
                'tabrendezvous' => $rendezvous,
                'recherche' => $f->createView()
            ]);
        }

        return $this->render('rendez_vous/showrendezvous.html.twig', [
            'tabrendezvous' => $rendezvous,
            'recherche' => $f->createView()
        ]);
    }

    #[Route('/addrendezvous', name: 'app_addrendezvous')]
    public function addrendezvous(ManagerRegistry $m): Response
    {
        $em = $m->getManager();
        $rendezvous = new RendezVous();
        $rendezvous->setDate(new \DateTime('2023-12-31'));
        $rendezvous->setHeure(new \DateTime('14:00'));
        $rendezvous->setStatut('En attente');
        $em->persist($rendezvous);
        $em->flush();

        return $this->redirectToRoute('app_showrendezvous');
    }

    #[Route('/addformrendezvous', name: 'app_addformrendezvous')]
    public function addformrendezvous(ManagerRegistry $m, Request $req): Response
    {
        $em = $m->getManager();
        $rendezvous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezvous);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rendezvous);
            $em->flush();
            return $this->redirectToRoute('app_showrendezvous');
        }

        return $this->render('rendez_vous/addformrendezvous.html.twig', [
            'addform' => $form->createView(),
        ]);
    }

    #[Route('/updateformrendezvous/{id}', name: 'app_updateformrendezvous')]
    public function updateformrendezvous(ManagerRegistry $m, Request $req, $id, RendezVousRepository $rep): Response
    {
        $em = $m->getManager();
        $rendezvous = $rep->find($id);
        $form = $this->createForm(RendezVousType::class, $rendezvous);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rendezvous);
            $em->flush();
            return $this->redirectToRoute('app_showrendezvous');
        }

        return $this->render('rendez_vous/addformrendezvous.html.twig', [
            'addform' => $form->createView(),
        ]);
    }

    #[Route('/deleterendezvous/{id}', name: 'app_deleterendezvous')]
    public function deleterendezvous(ManagerRegistry $m, $id, RendezVousRepository $rep): Response
    {
        $em = $m->getManager();
        $rendezvous = $rep->find($id);

        if (!$rendezvous) {
            throw $this->createNotFoundException('Rendez-vous non trouvé.');
        }

        $em->remove($rendezvous);
        $em->flush();

        return $this->redirectToRoute('app_showrendezvous');
    }




}
