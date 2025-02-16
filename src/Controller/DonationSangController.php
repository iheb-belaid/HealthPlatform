<?php

namespace App\Controller;

use App\Entity\DonationSang;
use App\Form\DonationSangType;
use App\Repository\DonationSangRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/donation/sang')]
final class DonationSangController extends AbstractController
{
    #[Route(name: 'app_donation_sang_index', methods: ['GET'])]
    public function index(DonationSangRepository $donationSangRepository): Response
    {
        return $this->render('donation_sang/index.html.twig', [
            'donation_sangs' => $donationSangRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_donation_sang_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donationSang = new DonationSang();
        $form = $this->createForm(DonationSangType::class, $donationSang);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donationSang);
            $entityManager->flush();
    
            // ✅ Ajouter un message flash avec un ID pour le formulaire
            $this->addFlash('success', '
                <strong>Votre donation a été enregistrée avec succès !</strong> <br>
                <a href="' . $this->generateUrl('app_donation_sang_edit', ['id' => $donationSang->getId()]) . '" class="btn btn-warning btn-sm">Modifier</a>
                <a href="' . $this->generateUrl('app_donation_sang_delete', ['id' => $donationSang->getId()]) . '" class="btn btn-danger btn-sm">Supprimer</a>
                <br><br>
                <button class="btn btn-primary shadow-lg mt-3" onclick="window.location.href=\'' . $this->generateUrl('app_homepage') . '\'">
                    <i class="fas fa-arrow-right"></i> Continuer
                </button>
            ');
    
            // ❌ Ne pas rediriger vers app_homepage immédiatement
            return $this->redirectToRoute('app_donation_sang_new');
        } else {
            // ❌ Ajouter une notification d'erreur
            $this->addFlash('error', 'Erreur lors de l\'enregistrement. Veuillez vérifier les champs.');
        }
    
        return $this->render('donation_sang/new.html.twig', [
            'donation_sang' => $donationSang,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_donation_sang_show', methods: ['GET'])]
    public function show(DonationSang $donationSang): Response
    {
        return $this->render('donation_sang/show.html.twig', [
            'donation_sang' => $donationSang,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donation_sang_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationSang $donationSang, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationSangType::class, $donationSang);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        } else {
            // Vérifie si des erreurs existent
            dump($form->getErrors(true, false));
        }

        return $this->render('donation_sang/edit.html.twig', [
            'donation_sang' => $donationSang,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_sang_delete', methods: ['POST'])]
    public function delete(Request $request, DonationSang $donationSang, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donationSang->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donationSang);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
    }
}
