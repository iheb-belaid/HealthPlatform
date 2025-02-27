<?php 


// src/Form/SuiviMedicalType.php
namespace App\Form;

use App\Entity\Docteur;
use App\Entity\Patient;
use App\Entity\SuiviMedical;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'placeholder' => 'Choisissez un type de suivi',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
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
                'placeholder' => 'Choisissez une fréquence',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prename',
                'label' => 'Patient',
            ])
            ->add('docteur', EntityType::class, [
                'class' => Docteur::class,
                'choice_label' => 'firstName',
                'label' => 'Docteur',
            ])
            // Champ Rapport médical (PDF)
            ->add('rapportMedicalFile', VichFileType::class, [
                'label' => 'Rapport médical (PDF)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
            // Bouton Enregistrer (déplacé après le champ rapportMedicalFile)
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
            'data_class' => SuiviMedical::class,
        ]);
    }
}