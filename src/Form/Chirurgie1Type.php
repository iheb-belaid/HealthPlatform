<?php
// src/Form/Chirurgie1Type.php
namespace App\Form;

use App\Entity\Patient;
use App\Entity\Chirurgie;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Chirurgie1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_operation', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('date_chirurgie', null, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('nom_docteur', null, [
                'label' => 'Nom du Docteur',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('nom_etablissement', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('notes', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prename',
                'label' => 'Patient',
                'attr' => [
                    'class' => 'form-control mb-1',
                ],
            ])
            // Champ Rapport de chirurgie (PDF)
            ->add('rapportChirurgieFile', VichFileType::class, [
                'label' => 'Rapport de chirurgie (PDF)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
            // Bouton Enregistrer (déplacé après le champ rapportChirurgieFile)
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg w-100 mb-3',
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