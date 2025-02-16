<?php

namespace App\Form;

use App\Entity\Docteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DocteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom', 'minlength' => 2, 'maxlength' => 50],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est obligatoire.']),
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'Le prénom doit contenir au moins 2 caractères.']),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom', 'minlength' => 2, 'maxlength' => 50],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'Le nom doit contenir au moins 2 caractères.']),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Entrez votre ville', 'maxlength' => 100],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La ville est obligatoire.']),
                    new Assert\Length(['max' => 100, 'maxMessage' => 'La ville ne peut pas dépasser 100 caractères.']),
                ],
            ])
            ->add('specialty', ChoiceType::class, [
                'label' => 'Spécialité',
                'choices' => [
                    'Endocrinologie' => 'Endocrinologie',
                    'Psychiatrie' => 'Psychiatrie',
                    'Gastro-entérologie' => 'Gastro-entérologie',
                    'Orthopédie' => 'Orthopédie',
                    'Gynécologie' => 'Gynécologie',
                    'Pédiatrie' => 'Pédiatrie',
                    'Ophtalmologie' => 'Ophtalmologie',
                    'Neurologie' => 'Neurologie',
                    'Dermatologie' => 'Dermatologie',
                    'Cardiologie' => 'Cardiologie',
                ],
                'placeholder' => 'Sélectionnez une spécialité',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La spécialité est obligatoire.']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['placeholder' => 'Entrez votre adresse email'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Assert\Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Choisissez un mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins 6 caractères.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire en tant que Docteur'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Docteur::class,
        ]);
    }
}