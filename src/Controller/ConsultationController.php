<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\Patient;
use App\Entity\Docteur;
use App\Form\ConsultationType;
use App\Form\RechercheConsultationType;
use App\Repository\ConsultationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{
    #[Route('/show', name: 'app_showconsultation')]
    public function showConsultation(ConsultationRepository $repoConsultation, Request $req): Response
    {
        $consultations = $repoConsultation->findAll(); // Récupérer toutes les consultations
        $form = $this->createForm(RechercheConsultationType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('search')->getData();
            $consultations = $repoConsultation->rechercheParCritere($data); // Filtrer les consultations si nécessaire
        }

        return $this->render('consultation/show.html.twig', [
            'tabconsultations' => $consultations, // Passer la liste des consultations au template
            'recherche' => $form->createView(),
        ]);
    }

    #[Route('/add', name: 'app_addconsultation')]
    public function addConsultation(ManagerRegistry $m): Response
    {
        $em = $m->getManager();
        $consultation = new Consultation();

        // Définir les attributs de l'entité Consultation
        $consultation->setDate(new \DateTime('2023-12-31'));
        $consultation->setHeure(new \DateTime('14:00')); // Assurez-vous que l'heure est au bon format
        $consultation->setMotif('Consultation de routine');
        $consultation->setDiagnostic(null); // ou une valeur par défaut
        $consultation->setTraitement(null); // ou une valeur par défaut
        $consultation->setPrix(100); // Exemple de prix (entier)

        // Si vous avez des objets Patient et Docteur à associer
        $patient = $em->getRepository(Patient::class)->find(1); // Remplacez par l'ID du patient
        $docteur = $em->getRepository(Docteur::class)->find(1); // Remplacez par l'ID du docteur

        if ($patient) {
            $consultation->setPatient($patient);
        } else {
            $this->addFlash('error', 'Patient non trouvé.');
            return $this->redirectToRoute('app_showconsultation');
        }

        if ($docteur) {
            $consultation->setDocteur($docteur);
        } else {
            $this->addFlash('error', 'Docteur non trouvé.');
            return $this->redirectToRoute('app_showconsultation');
        }

        $em->persist($consultation);
        $em->flush();

        $this->addFlash('success', 'La consultation a été ajoutée avec succès.');
        return $this->redirectToRoute('app_showconsultation');
    }

    #[Route('/addform', name: 'app_addformconsultation')]
    public function addFormConsultation(ManagerRegistry $m, Request $req): Response
    {
        $em = $m->getManager();
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($consultation);
            $em->flush();

            $this->addFlash('success', 'La consultation a été ajoutée avec succès.');
            return $this->redirectToRoute('app_showconsultation');
        }

        return $this->render('consultation/addform.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'app_updateconsultation')]
    public function updateConsultation(ManagerRegistry $m, Request $req, int $id, ConsultationRepository $repo): Response
    {
        $em = $m->getManager();
        $consultation = $repo->find($id);

        if (!$consultation) {
            throw $this->createNotFoundException('Consultation non trouvée.');
        }

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La consultation a été mise à jour avec succès.');
            return $this->redirectToRoute('app_showconsultation');
        }

        return $this->render('consultation/updateform.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_deleteconsultation', methods: ['POST'])]
    public function deleteConsultation(ManagerRegistry $m, Request $req, int $id, ConsultationRepository $repo): Response
    {
        $em = $m->getManager();
        $consultation = $repo->find($id);

        if (!$consultation) {
            throw $this->createNotFoundException('Consultation non trouvée.');
        }

        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $id, $req->request->get('_token'))) {
            $em->remove($consultation);
            $em->flush();

            $this->addFlash('success', 'La consultation a été supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_showconsultation');
    }
}