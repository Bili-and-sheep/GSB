<?php
// src/Form/SelectFicheType.php

namespace App\Form;

use App\Entity\FicheFrais;
use App\Repository\FicheFraisRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SelectFicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('fiche', EntityType::class, [
                'class' => FicheFrais::class,
                'choice_label' => function (FicheFrais $ficheFrais) {
                    return $ficheFrais->getMois()->format('Y-m'); // Adjust the format as needed
                },
                'label' => 'Fiche du : ',
                'placeholder' => 'Choisir une fiche',
                'required' => true,
                'query_builder' => function (FicheFraisRepository $repo) use ($user) {
                    return $repo->createQueryBuilder('f')
                        ->where('f.User = :user')
                        ->setParameter('user', $user);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
        ]);
    }
}