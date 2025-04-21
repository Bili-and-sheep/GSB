<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheFraisComptableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nbJustificatifs', null, [
                'label' => 'Nombre de justificatifs : ',
                'attr' => [
                    'placeholder' => 'Nombre de justificatifs'
                ]
            ])

            ->add('ToBeValided', null, [
                'label' => 'Valider : ',
                'attr' => [
                    'placeholder' => 'A valider'
                ]
            ])
            ->add('montantValid', null, [
                'label' => 'Montant validé : ',
                'attr' => [
                    'placeholder' => 'Montant validé'
                ]
            ])
            ->add('dateModif', null, [
                'widget' => 'single_text',
            ])




            ->add('Etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionner un état :',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer les modifications'

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheFrais::class,
        ]);
    }
}
