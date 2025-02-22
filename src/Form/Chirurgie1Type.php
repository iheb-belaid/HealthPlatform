<?php

namespace App\Form;

use App\Entity\Chirurgie;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Chirurgie1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_operation', null, [
                'required' => false, // Désactive la validation HTML5
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('date_chirurgie', null, [
                'widget' => 'single_text',
                'required' => false, // Désactive la validation HTML5
                'empty_data' => null, // Permet de retourner null si le champ est vide
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('nom_docteur', null, [
                'label' => 'Nom du Docteur',
                'required' => false, // Désactive la validation HTML5
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('nom_etablissement', null, [
                'required' => false, // Désactive la validation HTML5
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('notes', null, [
                'required' => false, // Désactive la validation HTML5
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prename', 
                'label' => 'Patient',
                // 'required' => false, // Désactive la validation HTML5
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chirurgie::class,
        ]);
    }
}