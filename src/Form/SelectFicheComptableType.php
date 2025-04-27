<?php
// src/Form/SelectFicheComptableType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectFicheComptableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mois', ChoiceType::class, [
                'label' => 'Mois',
                'choices' => $this->getMonthChoices(),
                'placeholder' => 'Sélectionner un mois',
                'required' => true,
            ])
            ->add('annee', ChoiceType::class, [
                'label' => 'Année',
                'choices' => $this->getYearChoices(),
                'placeholder' => 'Sélectionner une année',
                'required' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'label' => 'Utilisateur',
                'placeholder' => 'Choisir un utilisateur',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ]);
    }

    private function getYearChoices(): array
    {
        $currentYear = (int)date('Y');
        $years = [];
        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }

    private function getMonthChoices(): array
    {
        $months = [
            'Janvier' => 1,
            'Février' => 2,
            'Mars' => 3,
            'Avril' => 4,
            'Mai' => 5,
            'Juin' => 6,
            'Juillet' => 7,
            'Août' => 8,
            'Septembre' => 9,
            'Octobre' => 10,
            'Novembre' => 11,
            'Décembre' => 12,
        ];
        return $months;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}