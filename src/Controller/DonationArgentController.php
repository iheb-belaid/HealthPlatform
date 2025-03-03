<?php

namespace App\Controller;

use App\Entity\DonationArgent;
use App\Form\DonationArgentType;
use App\Repository\DonationArgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/donation/argent')]
final class DonationArgentController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route(name: 'app_donation_argent_index', methods: ['GET'])]
    public function index(DonationArgentRepository $donationArgentRepository, Request $request): Response
    {
        $donationQuery = $donationArgentRepository->findAllQuery();
        $pagination = $this->paginator->paginate($donationQuery, $request->query->getInt('page', 1), 10);

        return $this->render('donation_argent/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_donation_argent_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $donationArgent = new DonationArgent();
        $form = $this->createForm(DonationArgentType::class, $donationArgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($donationArgent);
            $this->entityManager->flush();

            $this->addFlash('success', 'Veuillez confirmer votre paiement.');

            return $this->redirectToRoute('donation_confirm', [
                'donationId' => $donationArgent->getId()
            ]);
        }

        return $this->render('donation_argent/new.html.twig', [
            'form' => $form->createView(),
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY'] ?? getenv('STRIPE_PUBLIC_KEY')
        ]);
    }

    #[Route('/confirm/{donationId}', name: 'donation_confirm', methods: ['GET'])]
    public function confirm(int $donationId): Response
    {
        $donation = $this->entityManager->getRepository(DonationArgent::class)->find($donationId);

        if (!$donation) {
            throw $this->createNotFoundException('Donation introuvable.');
        }

        return $this->render('donation_argent/confirm.html.twig', [
            'donation' => $donation,
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
        ]);
    }

    #[Route('/stripe/payment/{donationId}', name: 'stripe_payment', methods: ['GET'])]
    public function stripePayment(int $donationId): JsonResponse
    {
        $donation = $this->entityManager->getRepository(DonationArgent::class)->find($donationId);

        if (!$donation) {
            return new JsonResponse(['error' => 'Donation not found'], Response::HTTP_NOT_FOUND);
        }

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => 'Donation'],
                        'unit_amount' => $donation->getMontant() * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('app_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('donation_confirm', ['donationId' => $donationId], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return new JsonResponse(['id' => $session->id]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
 
    
    #[Route('/{id}', name: 'app_donation_sang_show', methods: ['GET'])]
    public function show(DonationArgent $donationArgent): Response
    {
        return $this->render('donation_argent/show.html.twig', [
            'donation_argent' => $donationArgent,
        ]);
    } 

    #[Route('/{id}/edit', name: 'app_donation_argent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationArgent $donationArgent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationArgentType::class, $donationArgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donation_argent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation_argent/edit.html.twig', [
            'donation_argent' => $donationArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_argent_delete', methods: ['POST'])]
    public function delete(Request $request, DonationArgent $donationArgent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donationArgent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donationArgent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donation_argent_index', [], Response::HTTP_SEE_OTHER);
    }
}
