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
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])
            ->add('password', null, [
        'label' => 'Mot de passe : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])
            ->add('nom', null, [
        'label' => 'Nom : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])
            ->add('prenom', null, [
        'label' => 'Prénom : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])
            ->add('adresse', null, [
        'label' => 'Adresse : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])
            ->add('cp', null, [
        'label' => 'Code postal : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])
            ->add('ville', null, [
        'label' => 'Ville : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
    ])

            ->add('dateEmbauche', null, [
                'label' => 'Date d\'embauche : ',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
            ])
            ->add('oldId', null, [
                'label' => 'Ancien identifiant : ',
                'attr' => [
                    'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                    'placeholder' => 'Entrez votre nom d\'utilisateur'
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Comptable' => 'ROLE_COMPTABLE',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'flex flex-col'
                ],
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
