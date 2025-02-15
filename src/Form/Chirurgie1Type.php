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
            ->add('nom_operation')
            ->add('date_chirurgie', null, [
                'widget' => 'single_text',
            ])
            ->add('nom_etablissement')
            ->add('notes')
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prename', 
                'label' => 'Patient',
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chirurgie::class,
        ]);
    }
}
