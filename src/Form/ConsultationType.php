<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Docteur;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateTimeType::class, [
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'La date est obligatoire.']),
                new Assert\Type(['type' => '\DateTimeInterface', 'message' => 'La date doit être valide.']),
                new Assert\GreaterThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date de consultation ne peut pas être dans le passé.',
                ]),
            ],
        ])
            ->add('motif', ChoiceType::class, [
                'choices' => [
                    'Présentiel' => 'Présentiel',
                    'En ligne' => 'En ligne',
                ],
                'placeholder' => 'Sélectionnez un motif',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le motif est obligatoire.']),
                ],
            ])
            ->add('diagnostic', TextType::class, [
                'attr' => ['class' => 'form-control', 'maxlength' => 255],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le diagnostic est obligatoire.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le diagnostic ne peut pas dépasser 255 caractères.',
                    ]),
                ],
            ])
            ->add('traitement', TextType::class, [
                'attr' => ['class' => 'form-control', 'maxlength' => 255],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le traitement est obligatoire.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le traitement ne peut pas dépasser 255 caractères.',
                    ]),
                ],
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prix est obligatoire.']),
                    new Assert\PositiveOrZero(['message' => 'Le prix doit être un nombre positif ou zéro.']),
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function (Patient $patient) {
                    return $patient->getPrename();
                },
                'placeholder' => 'Sélectionnez un patient',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le patient est obligatoire.']),
                ],
            ])
            ->add('docteur', EntityType::class, [
                'class' => Docteur::class,
                'choice_label' => function (Docteur $docteur) {
                    return $docteur->getFirstname();
                },
                'placeholder' => 'Sélectionnez un docteur',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le docteur est obligatoire.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
