<?php



namespace App\Controller;

use App\Entity\Docteur;
use App\Entity\Patient;
use App\Entity\Admin;
use App\Form\DocteurRegistrationFormType;
use App\Form\PatientRegistrationFormType;
use App\Form\AdminRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class RegistrationController extends AbstractController
{

    #[Route('/register/docteur', name: 'register_docteur')]
    public function registerDocteur(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Créer une nouvelle instance de Docteur
        $user = new Docteur();
    
        // Créer le formulaire associé à l'entité Docteur
        $form = $this->createForm(DocteurRegistrationFormType::class, $user);
    
        // Traiter la requête du formulaire
        $form->handleRequest($request);
    
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe et l'assigner à l'utilisateur
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
    
            // Persister l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
    
        // Rendu du formulaire dans la vue
        return $this->render('registration/docteur_register.html.twig', [
            'form' => $form->createView(), // Passer le formulaire à la vue
        ]);
    }
    
    

    #[Route('/register/patient', name: 'register_patient')]
    public function registerPatient(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Patient();
        $form = $this->createForm(PatientRegistrationFormType::class, $user);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('registration/patient_register.html.twig', [
            'form' => $form->createView(), // Ici on passe bien la variable "form"
        ]);
    }
    

    #[Route('/register/admin', name: 'register_admin')]
    public function registerAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Admin();
        $form = $this->createForm(AdminRegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/admin_register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
