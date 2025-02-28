<?php

namespace App\Controller;

use App\Repository\DocteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/doctors/pending', name: 'admin_doctors_pending')]
    public function pendingDoctors(DocteurRepository $docteurRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $pendingDocteurs = $docteurRepository->findBy(['isApproved' => false]);

        return $this->render('admin/pending_doctors.html.twig', [
            'docteurs' => $pendingDocteurs,
        ]);
    }

    #[Route('/approve-doctor/{id}', name: 'admin_approve_doctor', methods: ['POST'])]
    public function approveDoctor(
        int $id, 
        DocteurRepository $docteurRepository, 
        Request $request, 
        EntityManagerInterface $entityManager, 
        MailerInterface $mailer
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $docteur = $docteurRepository->find($id);
        if (!$docteur) {
            throw $this->createNotFoundException('Docteur non trouvé.');
        }

        if ($this->isCsrfTokenValid('approve'.$docteur->getId(), $request->request->get('_token'))) {
            $docteur->setIsApproved(true);
            $entityManager->flush();  // Utilisation correcte de l'EntityManager

            // 📩 Envoi de l'e-mail de confirmation
            $emailMessage = (new Email())
                ->from('rawenebouafif2@gmail.com') // Remplace par ton e-mail réel
                ->to($docteur->getEmail()) // Assure-toi que l'entité Docteur a un champ "email"
                ->subject('Votre compte a été approuvé !')
                ->html("
                    <p>Bonjour <strong>{$docteur->getFirstName()}</strong>,</p>
                    <p>Votre compte a été approuvé par l'administrateur.</p>
                    <p>Vous pouvez maintenant vous connecter à votre compte.</p>
                    <p><a href='http://127.0.0.1:8000/login'>Se connecter</a></p>
                    <p>Merci de faire partie de notre communauté !</p>
                ");

            try {
                $mailer->send($emailMessage);
                $this->addFlash('success', 'Docteur approuvé avec succès. Un e-mail de confirmation a été envoyé.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Docteur approuvé, mais erreur lors de l\'envoi de l\'e-mail.');
            }
        } else {
            $this->addFlash('error', 'Échec de l\'approbation du docteur.');
        }

        return $this->redirectToRoute('admin_doctors_pending');
    }
}