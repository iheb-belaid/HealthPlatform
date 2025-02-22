<?php 
// src/Controller/StatistiquesController.php
namespace App\Controller;

use App\Service\StatistiquesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    #[Route('/statistiques', name: 'app_statistiques')]
    public function index(StatistiquesService $statistiquesService): Response
    {
        // Récupérer les statistiques
        $statistiques = $statistiquesService->getStatistiquesParPatient();

        // Debug : Afficher les données dans la console Symfony
        dump($statistiques);

        return $this->render('statistiques/index.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }
}