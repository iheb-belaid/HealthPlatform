<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/patient')]
class PatientController extends AbstractController
{
    #[Route('/dashboard', name: 'patient_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/patient.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }
}
