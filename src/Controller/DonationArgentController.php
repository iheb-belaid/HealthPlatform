<?php

namespace App\Controller;

use App\Entity\DonationArgent;
use App\Form\DonationArgentType;
use App\Repository\DonationArgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/donation/argent')]
final class DonationArgentController extends AbstractController
{
    #[Route(name: 'app_donation_argent_index', methods: ['GET'])]
    public function index(DonationArgentRepository $donationArgentRepository): Response
    {
        return $this->render('donation_argent/index.html.twig', [
            'donation_argents' => $donationArgentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_donation_argent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donationArgent = new DonationArgent();
        $form = $this->createForm(DonationArgentType::class, $donationArgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donationArgent);
            $entityManager->flush();
             // Ajouter une notification pour la confirmation
             $this->addFlash('success', '
             <strong>Votre donation a été enregistrée avec succès !</strong> <br>
             <a href="' . $this->generateUrl('app_donation_argent_edit', ['id' => $donationArgent->getId()]) . '" class="btn btn-warning btn-sm">Modifier</a>
             <a href="' . $this->generateUrl('app_donation_argent_delete', ['id' => $donationArgent->getId()]) . '" class="btn btn-danger btn-sm">Supprimer</a>
         ');

            return $this->redirectToRoute('app_donation_argent_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation_argent/new.html.twig', [
            'donation_argent' => $donationArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_argent_show', methods: ['GET'])]
    public function show(DonationArgent $donationArgent): Response
    {
        return $this->render('donation_argent/show.html.twig', [
            'donation_argent' => $donationArgent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donation_argent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationArgent $donationArgent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationArgentType::class, $donationArgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donation_argent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation_argent/edit.html.twig', [
            'donation_argent' => $donationArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_argent_delete', methods: ['POST'])]
    public function delete(Request $request, DonationArgent $donationArgent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donationArgent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donationArgent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donation_argent_index', [], Response::HTTP_SEE_OTHER);
    }
}
