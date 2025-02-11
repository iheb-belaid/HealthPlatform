<?php

namespace App\Form;  // Assure-toi que le namespace est bien déclaré comme ceci


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
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\UserAuthenticator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register/docteur', name: 'register_docteur')]
    #[Route('/register/patient', name: 'register_patient')]
    #[Route('/register/admin', name: 'register_admin')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $type = explode('/', $request->getPathInfo())[2];
        $user = match ($type) {
            'docteur' => new Docteur(),
            'patient' => new Patient(),
            'admin' => new Admin(),
        };

        $form = $this->createForm(match ($type) {
            'docteur' => DocteurRegistrationFormType::class,
            'patient' => PatientRegistrationFormType::class,
            
        }, $user);
        
        // Gérer la soumission ici...

        return $this->render('registration/register.html.twig', ['form' => $form->createView()]);
    }
}
