<?php

namespace App\Form;

use App\Entity\FicheFrais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaisiFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('km', IntegerType::class, [
                    'label' => 'Kilomètres',
                    'attr' => [
                        'placeholder' => 'Kilomètres'
                    ]
            ])
            ->add('nuites', IntegerType::class, [
                    'label' => 'Nuitées',
                    'attr' => [
                        'placeholder' => 'Nuitées'
                    ]
            ])
            ->add('repas', IntegerType::class, [
                    'label' => 'Repas',
                    'attr' => [
                        'placeholder' => 'Repas'
                    ]
            ])
            ->add('etp', IntegerType::class, [
                    'label' => 'Etape',
                    'attr' => [
                        'placeholder' => 'Etape'
                    ]
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
