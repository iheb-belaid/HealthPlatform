<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Docteur; // Assurez-vous d'importer l'entité Docteur
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                    return $patient->getPrename(); // Choisissez la méthode pour afficher le nom
                },
                'placeholder' => 'Sélectionnez un patient',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('docteur', EntityType::class, [
                'class' => Docteur::class,
                'choice_label' => function(Docteur $docteur) {
                    return $docteur->getfirstname(); // Choisissez la méthode pour afficher le nom
                },
                'placeholder' => 'Sélectionnez un docteur',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',  // Utilisation d'un champ de texte unique
                'html5' => false,           // Désactivation du rendu HTML5 pour utiliser Flatpickr
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'consultation_date', // ID pour l'initialisation de Flatpickr
                    'placeholder' => 'Sélectionnez une date',
                ],
            ])
            ->add('motif', TextType::class)
            ->add('diagnostic', TextType::class, ['required' => false])
            ->add('traitement', TextType::class, ['required' => false])
            ->add('prix', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}