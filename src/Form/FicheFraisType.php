<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheFraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mois', null, [
                'widget' => 'single_text',
                'label' => 'Date : ',
            ])
            ->add('nbJustificatifs', null, [
                'label' => 'Nombre de justificatifs : ',
            ])
            ->add('montantValid', null, [
                'label' => 'Montant validé : ',
            ])

            ->add('dateModif', null, [
                'widget' => 'single_text',
                'label' => 'Date de modification : ',
            ])
            ->add('ToBeValided', null, [
                'label' => 'To Be Validated : ',
            ])
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'label' => 'Utilisateur : ',
            ])
            ->add('Etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
                'label' => 'État : ',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheFrais::class,
        ]);
    }
}
