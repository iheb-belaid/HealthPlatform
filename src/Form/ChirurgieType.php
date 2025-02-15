<?php

namespace App\Form;

use App\Entity\Chirurgie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Patient;

class ChirurgieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_operation', TextType::class, [
                'label' => 'Nom de l\'opération',
            ])
            ->add('date_chirurgie', DateTimeType::class, [
                'label' => 'Date de la chirurgie',
                'widget' => 'single_text', // Afficher en un seul champ
            ])
            ->add('nom_etablissement', TextType::class, [
                'label' => 'Nom de l\'établissement',
            ])
            
            ->add('notes', TextareaType::class, [
                'label' => 'Notes supplémentaires',
                'required' => false, // Champ facultatif
                'attr' => [
                    'rows' => 5, // Nombre de lignes visibles
                    'cols' => 50, // Largeur du champ
                    'style' => 'resize: none;', // Désactiver le redimensionnement
                    'class' => 'form-control', // Classe Bootstrap (optionnel)
                ],
            ])

            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prename', // Afficher le prénom du patient
                'label' => 'Patient',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chirurgie::class,
        ]);
    }
}