<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ActualiteMedicaleController extends AbstractController
 {
    #[Route('/actualite-medicale/1', name: 'actualite_medicale_1')]
    public function show1(): Response
    {
        return $this->render('actualites_medicales/1.html.twig');
    }

    #[Route('/actualite-medicale/2', name: 'actualite_medicale_2')]
    public function show2(): Response
    {
        return $this->render('actualites_medicales/2.html.twig');
    }

    #[Route('/actualite-medicale/3', name: 'actualite_medicale_3')]
    public function show3(): Response
    {
        return $this->render('actualites_medicales/3.html.twig');
    }
   
 }

