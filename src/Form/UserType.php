<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
        'label' => 'Email : ',
    ])
            ->add('password', null, [
        'label' => 'Mot de passe : ',
    ])
            ->add('nom', null, [
        'label' => 'Nom : ',
    ])
            ->add('prenom', null, [
        'label' => 'Prénom : ',
    ])
            ->add('adresse', null, [
        'label' => 'Adresse : ',
    ])
            ->add('cp', null, [
        'label' => 'Code postal : ',
    ])
            ->add('ville', null, [
        'label' => 'Ville : ',
    ])

            ->add('dateEmbauche', null, [
                'label' => 'Date d\'embauche : ',
                'widget' => 'single_text',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Comptable' => 'ROLE_COMPTABLE',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('oldId', null, [
                'label' => 'Ancien identifiant : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
