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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function(Patient $patient) {
                    return $patient->getPrename(); // Affiche le prénom du patient
                },
                'placeholder' => 'Sélectionnez un patient',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('docteur', EntityType::class, [
                'class' => Docteur::class,
                'choice_label' => function(Docteur $docteur) {
                    return $docteur->getFirstname(); // Affiche le prénom du docteur
                },
                'placeholder' => 'Sélectionnez un docteur',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',  
                'html5' => false,           
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'consultation_date', 
                    'placeholder' => 'Sélectionnez une date',
                ],
            ])
            ->add('heure', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez une heure',
                ],
            ])
            ->add('motif', ChoiceType::class, [
                'choices' => [
                    'Présentiel' => 'Présentiel',
                    'En ligne' => 'En ligne',
                ],
                'placeholder' => 'Sélectionnez un motif', // Placeholder pour le champ motif
                'attr' => ['class' => 'form-control'],
            ])
            ->add('diagnostic', TextType::class, ['required' => false])
            ->add('traitement', TextType::class, ['required' => false])
            ->add('prix', IntegerType::class, [ // Utilisation de IntegerType
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le prix'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}