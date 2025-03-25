<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SelectMonthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month', ChoiceType::class, [
                'choices' => [
                    'Janvier' => '2024-01-01',
                    'Février' => '2024-02-01',
                    'Mars' => '2024-03-01',
                    'Avril' => '2024-04-01',
                    'Mai' => '2024-05-01',
                    'Juin' => '2024-06-01',
                    'Juillet' => '2024-07-01',
                    'Août' => '2024-08-01',
                    'Septembre' => '2024-09-01',
                    'Octobre' => '2024-10-01',
                    'Novembre' => '2024-11-01',
                    'Décembre' => '2024-12-01',
                ],
                'label' => 'Mois'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ]);
    }
}
