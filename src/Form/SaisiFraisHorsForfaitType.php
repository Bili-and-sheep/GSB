<?php

namespace App\Form;

use App\Entity\LigneFraisHorsForfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaisiFraisHorsForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = (int) date('Y');

        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé : ',
                'attr' => ['placeholder' => 'Libellé']
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de la facture : ',
                'html5' => true,
                'attr' => [
                    'min' => $currentYear . '-01-01',
                    'max' => $currentYear . '-12-31',
                ]
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant : ',
                'currency' => false,
                'attr' => [
                    'class' => 'money-input',
                    'placeholder' => '0.00'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneFraisHorsForfait::class,
        ]);
    }
}