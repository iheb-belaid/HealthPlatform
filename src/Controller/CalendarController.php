<?php

namespace App\Controller;

use App\Repository\ChirurgieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig');
    }

    #[Route('/calendar/events', name: 'app_calendar_events')]
    public function getEvents(ChirurgieRepository $chirurgieRepository): JsonResponse
    {
        $chirurgies = $chirurgieRepository->findAll();
        $events = [];

        foreach ($chirurgies as $chirurgie) {
            $events[] = [
                'title' => $chirurgie->getNomOperation(),
                'start' => $chirurgie->getDateChirurgie()->format('Y-m-d'),
            ];
        }

        return new JsonResponse($events);
    }
}