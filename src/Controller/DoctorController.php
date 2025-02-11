<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/doctor')]
class DoctorController extends AbstractController
{
    #[Route('/dashboard', name: 'doctor_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/doctor.html.twig', [
            'controller_name' => 'DoctorController',
        ]);
    }
}
