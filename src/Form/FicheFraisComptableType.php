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

        /** @var FicheFrais|null $ficheFrais */
        $ficheFrais = $options['data'];

        $etat = $ficheFrais?->getEtat();
        $etatId = $etat?->getId();

        $builder

            ->add('nbJustificatifs', null, [
                'label' => 'Nombre de justificatifs : ',
                'attr' => [
                    'placeholder' => 'Nombre de justificatifs'
                ]
            ])

            ->add('montantValid', null, [
                'label' => 'Montant validé : ',
                'attr' => [
                    'placeholder' => 'Montant validé en euros'
                ]
            ])
            ->add('dateModif', null, [
                    'widget' => 'single_text',
                    'label' => 'Date de modification : ',
                    'data' => new \DateTime('now', new \DateTimeZone('Europe/Paris')),
                ])

            ->add('Etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
                'label' => 'État : ',
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
