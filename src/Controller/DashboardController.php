<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    // Tableau de bord pour l'admin
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(ReclamationRepository $reclamationRepository): Response
    {
        
        // Récupérer le nombre total de réclamations
        $totalReclamationsCount = $reclamationRepository->count([]);

        // Récupérer le nombre de réclamations avec le statut "Ouvert"
        $pendingReclamationsCount = $reclamationRepository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.status = :status')
            ->setParameter('status', 'non traité')
            ->getQuery()
            ->getSingleScalarResult();

        // Récupérer les réclamations en attente (statut "Ouvert") pour la liste
        $pendingReclamations = $reclamationRepository->findBy(
            ['status' => 'non traité'],
            ['dateCreation' => 'DESC'],
            5 // Limite à 5 pour éviter une liste trop longue
        );

        return $this->render('dashboard/admin.html.twig', [
            'total_reclamations_count' => $totalReclamationsCount,
            'pending_reclamations_count' => $pendingReclamationsCount,
            'pending_reclamations' => $pendingReclamations,
        ]);
    }
    // Tableau de bord pour le docteur
    #[Route('/doctor/dashboard', name: 'doctor_dashboard')]
    public function doctorDashboard(): Response
    {
        return $this->render('dashboard/doctor.html.twig');
    }

    // Tableau de bord pour le patient
    #[Route('/patient/dashboard', name: 'patient_dashboard')]
    public function patientDashboard(): Response
    {
        return $this->render('dashboard/patient.html.twig');
    }
}
