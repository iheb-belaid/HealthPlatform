<?php

namespace App\Form;

use App\Entity\Docteur;
use App\Entity\Patient;
use App\Entity\SuiviMedical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SuiviMedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         ->add('type_suivi', ChoiceType::class, [
            'label' => 'Type de suivie',
            'choices' => [
                'Suivi général' => 'Suivi général',
                'Suivi nutritionnel' => 'Suivi nutritionnel',
                'Suivi psychologique' => 'Suivi psychologique',
                'Suivi chronique' => 'Suivi chronique',
                'Suivi postopératoire' => 'Suivi postopératoire',
                'Suivi préventif' => 'Suivi préventif',
                'Suivi de traitement' => 'Suivi de traitement',
                'Suivi de grossesse' => 'Suivi de grossesse',
             ],
                'placeholder' => 'Choisissez un type de suivi', // Option vide par défaut
                'empty_data' => '', // Force une valeur vide à l'initialisation
                'required' => false, // Permet de ne pas sélectionner immédiatement une valeur
                'attr' => [
                    'class' => 'form-control mb-3',
            ],
        ])
        
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text', // Afficher en un seul champ
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text', // Afficher en un seul champ
            ])
            ->add('frequence', ChoiceType::class, [
                'label' => 'Fréquence',
                'choices' => [
                    'Quotidienne : Une fois par jour.' => 'Quotidienne',
                    'Hebdomadaire : Une fois par semaine.' => 'Hebdomadaire',
                    'Mensuelle : Une fois par mois.' => 'Mensuelle',
                    'Trimestrielle : Une fois tous les trois mois.' => 'Trimestrielle',
                    'Semestrielle : Une fois tous les six mois.' => 'Semestrielle',
                    'Annuelle : Une fois par an.' => 'Annuelle',
                    'À la demande : Selon les besoins ou les symptômes.' => 'À la demande',
                    'Continu : En permanence, sans interruption.' => 'Continu',
                ],
                'placeholder' => 'Choisissez une fréquence', // Option vide par défaut
                'empty_data' => '', // Force une valeur vide à l'initialisation
                'required' => false, // Permet de ne pas sélectionner immédiatement une valeur
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false, // Champ facultatif
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prename', // Afficher le prénom du patient
                'label' => 'Patient',
            ])
            ->add('docteur', EntityType::class, [
                'class' => Docteur::class,
                'choice_label' => 'firstName', // Afficher le prénom du docteur
                'label' => 'Docteur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviMedical::class,
        ]);
    }
}