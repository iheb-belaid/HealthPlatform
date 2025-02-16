<?php

namespace App\Controller;

use App\Entity\SuiviMedical;
use App\Form\SuiviMedicalType;
use App\Repository\SuiviMedicalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/suivi/medical')]
final class SuiviMedicalController extends AbstractController
{
    #[Route(name: 'app_suivi_medical_index', methods: ['GET'])]
    public function index(SuiviMedicalRepository $suiviMedicalRepository, Request $request): Response
    {
        $search = $request->query->get('search', '');
        $suiviMedicals = $suiviMedicalRepository->findByPatientName($search);

        return $this->render('suivi_medical/index.html.twig', [
            'suivi_medicals' => $suiviMedicals,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'app_suivi_medical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviMedical = new SuiviMedical();
        $form = $this->createForm(SuiviMedicalType::class, $suiviMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviMedical);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_medical/new.html.twig', [
            'suivi_medical' => $suiviMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_medical_show', methods: ['GET'])]
    public function show(SuiviMedical $suiviMedical): Response
    {
        return $this->render('suivi_medical/show.html.twig', [
            'suivi_medical' => $suiviMedical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_medical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviMedical $suiviMedical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviMedicalType::class, $suiviMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_medical/edit.html.twig', [
            'suivi_medical' => $suiviMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_medical_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviMedical $suiviMedical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviMedical->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($suiviMedical);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_medical_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
