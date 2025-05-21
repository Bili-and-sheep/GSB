<?php

namespace App\Form;


use App\Entity\FicheFrais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlafondLFHLmaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plafondLFHF', NumberType::class, [
                'label' => 'Nouveau Plafond LFHF : ',
                'required' => true,
                'scale' => 2, // Nombre de décimales autorisées
//                'attr' => [
//                    'placeholder' => $options['plafond_actuel'] ?? 'Valeur par défaut'
//                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheFrais::class,
        ]);
    }

}