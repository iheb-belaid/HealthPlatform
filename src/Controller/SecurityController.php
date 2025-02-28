<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
    }

    // 🔹 Demande de réinitialisation du mot de passe
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            // Vérifier si l'utilisateur existe
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet e-mail.');
                return $this->redirectToRoute('app_forgot_password');
            }

            // Générer un code aléatoire
            $resetCode = random_int(100000, 999999);
            $session->set('reset_code', $resetCode);
            $session->set('reset_user_id', $user->getId());

            // Envoyer l'e-mail avec le code
            $emailMessage = (new Email())
                ->from('rawenebouafif2@gmail.com')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->html("
                    <p>Bonjour,</p>
                    <p>Votre code de réinitialisation de mot de passe est : <strong>{$resetCode}</strong></p>
                    <p>Veuillez entrer ce code pour choisir un nouveau mot de passe.</p>
                ");

            try {
                $mailer->send($emailMessage);
                $this->addFlash('success', 'Un code de réinitialisation a été envoyé à votre adresse e-mail.');
                return $this->redirectToRoute('app_reset_password');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'e-mail.');
            }
        }

        return $this->render('security/forgot_password.html.twig');
    }

    // 🔹 Réinitialisation du mot de passe avec le code
    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $enteredCode = $request->request->get('reset_code');
            $newPassword = $request->request->get('new_password');

            // Vérifier si le code est correct
            $savedCode = $session->get('reset_code');
            $userId = $session->get('reset_user_id');

            if (!$savedCode || !$userId || $enteredCode != $savedCode) {
                $this->addFlash('error', 'Code incorrect ou expiré.');
                return $this->redirectToRoute('app_reset_password');
            }

            // Récupérer l'utilisateur
            $user = $entityManager->getRepository(User::class)->find($userId);
            if (!$user) {
                $this->addFlash('error', 'Utilisateur introuvable.');
                return $this->redirectToRoute('app_forgot_password');
            }

            // Mettre à jour le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
            $entityManager->flush();

            // Supprimer les données de session
            $session->remove('reset_code');
            $session->remove('reset_user_id');

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig');
    }
}