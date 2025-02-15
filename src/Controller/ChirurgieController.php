<?php

namespace App\Controller;

use App\Entity\Chirurgie;
use App\Form\Chirurgie1Type;
use App\Repository\ChirurgieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chirurgie')]
final class ChirurgieController extends AbstractController
{
    #[Route(name: 'app_chirurgie_index', methods: ['GET'])]
    public function index(ChirurgieRepository $chirurgieRepository): Response
    {
        return $this->render('chirurgie/index.html.twig', [
            'chirurgies' => $chirurgieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chirurgie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chirurgie = new Chirurgie();
        $form = $this->createForm(Chirurgie1Type::class, $chirurgie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chirurgie);
            $entityManager->flush();

            return $this->redirectToRoute('app_chirurgie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chirurgie/new.html.twig', [
            'chirurgie' => $chirurgie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chirurgie_show', methods: ['GET'])]
    public function show(Chirurgie $chirurgie): Response
    {
        return $this->render('chirurgie/show.html.twig', [
            'chirurgie' => $chirurgie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chirurgie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chirurgie $chirurgie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Chirurgie1Type::class, $chirurgie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chirurgie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chirurgie/edit.html.twig', [
            'chirurgie' => $chirurgie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chirurgie_delete', methods: ['POST'])]
    public function delete(Request $request, Chirurgie $chirurgie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chirurgie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chirurgie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chirurgie_index', [], Response::HTTP_SEE_OTHER);
    }
}
