<?php


namespace App\Controller;

use App\Entity\DonationSang;
use App\Form\DonationSangType;
use App\Repository\DonationSangRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Knp\Component\Pager\PaginatorInterface; // Ajouter l'importation du PaginatorInterface

#[Route('/donation/sang')]
final class DonationSangController extends AbstractController
{
    private $paginator;

    // Injection du service PaginatorInterface
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route(name: 'app_donation_sang_index', methods: ['GET'])]
    public function index(DonationSangRepository $donationSangRepository, Request $request): Response
    {
        // Récupérer toutes les donations avec la requête (ou une requête personnalisée)
        $donationSangsQuery = $donationSangRepository->createQueryBuilder('d')->getQuery();

        // Appliquer la pagination
        $donationSangs = $this->paginator->paginate(
            $donationSangsQuery, // La requête qui récupère les résultats
            $request->query->getInt('page', 1), // Récupérer la page depuis la requête, défaut 1
            10 // Nombre de résultats par page
        );

        return $this->render('donation_sang/index.html.twig', [
            'donation_sangs' => $donationSangs,
        ]);
    }
    #[Route('/new', name: 'app_donation_sang_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, HttpClientInterface $client): Response
{
    $donationSang = new DonationSang();
    $form = $this->createForm(DonationSangType::class, $donationSang);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($donationSang);
        $entityManager->flush();

        // Récupérer l'email de l'utilisateur
        $EmailUser = $donationSang->getEmailUser();

        // Utilisation de l'API Mailboxlayer pour vérifier l'email
        $apiKey = '32fcbc80c33643c8012e22a535a1851a'; // Remplace par ta clé API Mailboxlayer
        $url = "http://apilayer.net/api/check?access_key=" . $apiKey . "&email=" . urlencode($EmailUser);

        // Effectuer la requête HTTP pour vérifier l'email
        $response = $client->request('GET', $url);
        $data = $response->toArray(); // Obtenir la réponse JSON sous forme de tableau

        // Vérifier si l'email est valide
        if (!$data['smtp_check'] && $data['free'] !== true) {
            // Si l'email n'a pas pu être vérifié via SMTP et n'est pas un fournisseur gratuit, afficher un message d'erreur
            $this->addFlash('error', 'L\'adresse email fournie est invalide ou ne peut être vérifiée. Veuillez vérifier et réessayer.');
            return $this->redirectToRoute('app_donation_sang_new');
        }

      // Envoyer un e-mail de confirmation à l'utilisateur
$emailMessage = (new Email())
->from('rawenebouafif2@gmail.com')
->to($EmailUser)
->subject('📢 Confirmation de votre don de sang')
->html("
    <h2 style='color: #d32f2f;'>Merci pour votre engagement ! ❤️</h2>
    <p>Bonjour,</p>
    <p>Nous avons bien enregistré votre demande de don de sang.</p>
    <p>Nous vous contacterons prochainement pour vous confirmer la date et l'heure de votre rendez-vous.</p>
    <p>Si vous avez des questions, n'hésitez pas à nous contacter à <a href='mailto:contact@votre-site.com'>contact@votre-site.com</a>.</p>
    <br>
   
");


        // Gérer les erreurs d'envoi d'e-mail
        try {
            $mailer->send($emailMessage);
            $this->addFlash('success', 'Votre don a été enregistré. Un e-mail de confirmation vous a été envoyé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'e-mail. Veuillez réessayer. Détail : ' . $e->getMessage());
            error_log('Erreur SendGrid : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_donation_sang_new');
    }

    return $this->render('donation_sang/new.html.twig', [
        'donation_sang' => $donationSang,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_donation_sang_show', methods: ['GET'])]
    public function show(DonationSang $donationSang): Response
    {
        return $this->render('donation_sang/show.html.twig', [
            'donation_sang' => $donationSang,
        ]);
    } 

    #[Route('/{id}/edit', name: 'app_donation_sang_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationSang $donationSang, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationSangType::class, $donationSang);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        } else {
            // Vérifie si des erreurs existent
            dump($form->getErrors(true, false));
        }

        return $this->render('donation_sang/edit.html.twig', [
            'donation_sang' => $donationSang,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_sang_delete', methods: ['POST'])]
    public function delete(Request $request, DonationSang $donationSang, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donationSang->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($donationSang);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donation_sang_index', [], Response::HTTP_SEE_OTHER);
    }
}



















   /*  #[Route('/new', name: 'app_donation_sang_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $donationSang = new DonationSang();
        $form = $this->createForm(DonationSangType::class, $donationSang);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donationSang);
            $entityManager->flush(); */
    
           /*  // Envoi de l’email de confirmation via SendGrid
            $email = (new Email())
                ->from('helloworld@gmail.com') // Remplace par ton adresse d’expéditeur validée dans SendGrid
                ->to($donationSang->getEmailUser()) // Utilise getEmailUser() pour l’email du donneur
                ->subject('Confirmation de votre don de sang')
                ->text('Merci pour votre don de sang ! Votre contribution a été enregistrée avec succès.')
                ->html('
                    <h1>Merci pour votre don !</h1>
                    <p>Votre don de sang a été enregistré avec succès le ' . date('d/m/Y') . '.</p>
                    <p>Pour toute question, contactez-nous à cette adresse.</p>
                ');
    
            try {
                $mailer->send($email);
                $this->addFlash('success', '
                    <strong>Votre donation a été enregistrée avec succès !</strong> <br>
                    Un email de confirmation vous a été envoyé. <br>
                    <a href="' . $this->generateUrl('app_donation_sang_edit', ['id' => $donationSang->getId()]) . '" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="' . $this->generateUrl('app_donation_sang_delete', ['id' => $donationSang->getId()]) . '" class="btn btn-danger btn-sm">Supprimer</a>
                    <br><br>
                    <button class="btn btn-primary shadow-lg mt-3" onclick="window.location.href=\'' . $this->generateUrl('app_homepage') . '\'">
                        <i class="fas fa-arrow-right"></i> Continuer
                    </button>
                ');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l’envoi de l’email : ' . $e->getMessage());
            }
    
            return $this->redirectToRoute('app_donation_sang_new');
        } else {
            $this->addFlash('error', 'Erreur lors de l\'enregistrement. Veuillez vérifier les champs.');
        }
    
        return $this->render('donation_sang/new.html.twig', [
            'donation_sang' => $donationSang,
            'form' => $form,
        ]);
    } */

   /* #[Route('/new', name: 'app_donation_sang_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donationSang = new DonationSang();
        $form = $this->createForm(DonationSangType::class, $donationSang);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donationSang);
            $entityManager->flush();
    
            //  Ajouter un message flash avec un ID pour le formulaire
            $this->addFlash('success', '
                <strong>Votre donation a été enregistrée avec succès !</strong> <br>
                <a href="' . $this->generateUrl('app_donation_sang_edit', ['id' => $donationSang->getId()]) . '" class="btn btn-warning btn-sm">Modifier</a>
                <a href="' . $this->generateUrl('app_donation_sang_delete', ['id' => $donationSang->getId()]) . '" class="btn btn-danger btn-sm">Supprimer</a>
                <br><br>
                <button class="btn btn-primary shadow-lg mt-3" onclick="window.location.href=\'' . $this->generateUrl('app_homepage') . '\'">
                    <i class="fas fa-arrow-right"></i> Continuer
                </button>
            ');
    
            //  Ne pas rediriger vers app_homepage immédiatement
            return $this->redirectToRoute('app_donation_sang_new');
        } else {
            //  Ajouter une notification d'erreur
            $this->addFlash('error', 'Erreur lors de l\'enregistrement. Veuillez vérifier les champs.');
        }
    
        return $this->render('donation_sang/new.html.twig', [
            'donation_sang' => $donationSang,
            'form' => $form,
        ]);
    } */