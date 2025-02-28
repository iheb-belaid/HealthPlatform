<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\ReclamationAdminType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ReclamationController extends AbstractController
{
    #[Route('/reclamation/new', name: 'patient_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $patient = $security->getUser();
        if (!$patient) {
            $this->addFlash('error', 'Vous devez être connecté pour soumettre une réclamation.');
            return $this->redirectToRoute('app_login');
        }

        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setPatient($patient);
            $reclamation->setDateCreation(new \DateTime());

            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réclamation a été soumise avec succès.');
            return $this->redirectToRoute('patient_reclamations_list'); // Redirection corrigée
        }

        return $this->render('reclamation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamation', name: 'patient_reclamations_list', methods: ['GET'])]
    public function list(ReclamationRepository $reclamationRepository, Security $security): Response
    {
        $patient = $security->getUser();
        if (!$patient) {
            return $this->redirectToRoute('app_login');
        }

        $reclamations = $reclamationRepository->findBy(['patient' => $patient], ['dateCreation' => 'DESC']);

        return $this->render('reclamation/list.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/reclamation/{id}', name: 'patient_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation, Security $security): Response
    {
        $patient = $security->getUser();
        if (!$patient || $reclamation->getPatient() !== $patient) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette réclamation.');
        }

        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/reclamation/edit/{id}', name: 'patient_reclamation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, Security $security): Response
{
    $patient = $security->getUser();
    if (!$patient || $reclamation->getPatient() !== $patient) {
        throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette réclamation.');
    }

    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'La réclamation a été modifiée avec succès.');
        return $this->redirectToRoute('patient_reclamations_list');
    }

    return $this->render('reclamation/edit.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $reclamation,
    ]);
}

    #[Route('/reclamation/delete/{id}', name: 'patient_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, Security $security): Response
    {
        $patient = $security->getUser();
        if (!$patient || $reclamation->getPatient() !== $patient) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette réclamation.');
        }

        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'La réclamation a été supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée.');
        }

        return $this->redirectToRoute('patient_reclamations_list');
    }

    #[Route('/admin/reclamations', name: 'admin_reclamations_list', methods: ['GET'])]
    public function adminList(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findBy([], ['dateCreation' => 'DESC']);

        return $this->render('reclamation/list.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }



    #[Route('/admin/reclamation/treat/{id}', name: 'admin_reclamation_treat', methods: ['GET', 'POST'])]
public function treat(
    Request $request, 
    Reclamation $reclamation, 
    EntityManagerInterface $entityManager,
    MailerInterface $mailer
): Response {
    $form = $this->createForm(ReclamationAdminType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Mise à jour en base de données
        $entityManager->flush();

        // Récupérer l'email de l'utilisateur via la relation entre réclamation et rendez-vous
        // Exemple : $reclamation->getRendezVous() renvoie l'objet RendezVous,
        // $reclamation->getRendezVous()->getUser() renvoie l'objet User,
        // $reclamation->getRendezVous()->getUser()->getEmail() renvoie l'email.
        $userEmail = $reclamation->getPatient()->getEmail();
        $status = $reclamation->getStatus(); // Statut défini par l'admin via le formulaire

        // Préparer le sujet et le contenu de l'email en fonction du statut
        if ($status === 'Confirmée') {
            $subject = 'Votre réclamation a été confirmée';
            $body = "<p>Bonjour,</p>
                     <p>Nous vous informons que votre réclamation a été confirmée et sera traitée dans les meilleurs délais.</p>
                     <p>Merci de nous avoir contactés.</p>";
        } elseif ($status === 'Refusée') {
            $subject = 'Votre réclamation a été refusée';
            $body = "<p>Bonjour,</p>
                     <p>Nous vous informons que votre réclamation a été refusée.</p>
                     <p>N'hésitez pas à nous contacter pour plus d'informations.</p>";
        } else { // Pour le statut "Non traité" ou tout autre statut
            $subject = 'Mise à jour sur votre réclamation';
            $body = "<p>Bonjour,</p>
                     <p>Votre réclamation est actuellement en cours de traitement. Nous vous tiendrons informé dès qu'elle sera traitée.</p>";
        }

        // Création et envoi de l'email
        $email = (new Email())
            ->from('admin@votre-site.com') // Assurez-vous d'utiliser un expéditeur validé
            ->to($userEmail)
            ->subject($subject)
            ->html($body);

        try {
            $mailer->send($email);
            $this->addFlash('success', 'Le statut de la réclamation a été mis à jour et un email a été envoyé à l\'utilisateur.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Le statut a été mis à jour, mais l\'envoi de l\'email a échoué : ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_reclamations_list');
    }

    return $this->render('reclamation/admin_treat.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form->createView(),
    ]);
}
}