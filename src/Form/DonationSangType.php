<?php

namespace App\Form;

use App\Entity\DonationSang;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Hospital;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;

class DonationSangType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('TypeSang', ChoiceType::class, [
            'choices' => [
                'A+' => 'A+',
                'A-' => 'A-',
                'B+' => 'B+',
                'B-' => 'B-',
                'AB+' => 'AB+',
                'AB-' => 'AB-',
                'O+' => 'O+',
                'O-' => 'O-',
            ],
            'placeholder' => 'Sélectionner un groupe sanguin',
            'required' => true,
        ])
        //->add('Date_Donation', null, [
            //'widget' => 'single_text',
          //  'data' => new \DateTime(), // Définit la date actuelle comme valeur par défaut
           // 'attr' => ['class' => 'form-control', 'readonly' => true] // Empêche la modification
      //  ])
        
           // ->add('EmailUser')
            ->add('EmailUser', EmailType::class, [
                'label' => 'Votre e-mail',
                'required' => true,
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez entrer une adresse e-mail valide.',
                    ]),
                ],
            ])
            ->add('Cin')
            ->add('hospital', EntityType::class, [ 
                'label' => 'Hôpital',
                'class' => Hospital::class,         // L'entité associée
                'choice_label' => 'name',           // Afficher le nom de l’hôpital
               'placeholder' => 'Sélectionner un hôpital', // Option vide par défaut
                'required' => true,
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonationSang::class,
        ]);
    }
}
