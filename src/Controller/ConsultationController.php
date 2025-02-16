<?php 

namespace App\Controller;

use App\Entity\Consultation;
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
    #[Route('/showconsultation', name: 'app_showconsultation')]
    public function showConsultation(ConsultationRepository $repoConsultation, Request $req): Response
    {
        $consultations = $repoConsultation->findAll();
        $form = $this->createForm(RechercheConsultationType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('search')->getData();
            $consultations = $repoConsultation->rechercheParCritere($data);
        }

        return $this->render('consultation/show.html.twig', [
            'tabconsultations' => $consultations,
            'recherche' => $form->createView(),
        ]);
    }

    #[Route('/add', name: 'app_addconsultation')]
    public function addConsultation(ManagerRegistry $m): Response
    {
        $em = $m->getManager();
        $consultation = new Consultation();
        $consultation->setDate(new \DateTime());
        $em->persist($consultation);
        $em->flush();

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
            return $this->redirectToRoute('app_showconsultation');
        }

        return $this->render('consultation/addform.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/updateform/{id}', name: 'app_updateformconsultation')]
    public function updateFormConsultation(ManagerRegistry $m, Request $req, int $id, ConsultationRepository $rep): Response
    {
        $em = $m->getManager();
        $consultation = $rep->find($id);

        if (!$consultation) {
            throw $this->createNotFoundException('Consultation non trouvée.');
        }

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_showconsultation');
        }

        return $this->render('consultation/updateform.html.twig', [ // Correction du template
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_deleteconsultation', methods: ['POST'])]
    public function deleteConsultation(ManagerRegistry $m, Request $req, int $id, ConsultationRepository $rep): Response
    {
        $em = $m->getManager();
        $consultation = $rep->find($id);

        if (!$consultation) {
            throw $this->createNotFoundException('Consultation non trouvée.');
        }

        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $id, $req->request->get('_token'))) {
            $em->remove($consultation);
            $em->flush();
        }

        return $this->redirectToRoute('app_showconsultation');
    }
}
