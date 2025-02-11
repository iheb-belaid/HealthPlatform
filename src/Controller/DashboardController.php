<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    // Tableau de bord pour l'admin
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('dashboard/admin.html.twig');
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
