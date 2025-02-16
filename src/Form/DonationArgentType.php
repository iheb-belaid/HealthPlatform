<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\DonationArgent;

class DonationArgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => true,
            'attr' => ['placeholder' => 'exemple@email.com']
        ])
        ->add('montant', NumberType::class, [
            'label' => 'Montant (€)',
            'required' => true,
            'attr' => ['min' => 5, 'step' => 5]
        ])
        ->add('methodePaiment', ChoiceType::class, [
            'label' => 'Méthode de paiement',
            'choices' => [
                'Carte bancaire' => 'carte',
                'PayPal' => 'paypal'
            ],
            'expanded' => true, // Pour afficher en boutons radio
            'multiple' => false
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Faire un don',
            'attr' => ['class' => 'btn btn-primary w-100']
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonationArgent::class,
        ]);
    }
}
