<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('commentaire', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Le commentaire ne peut pas être vide.'
                ]),
                new Length([
                    'min' => 10,
                    'max' => 255,
                    'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le commentaire ne peut pas dépasser {{ limit }} caractères.'
                ]),
            ]
        ])
        ->add('note', null, [
            'constraints' => [
                new Range([
                    'min' => 0,
                    'max' => 5,
                    'minMessage' => 'La note doit être au moins {{ limit }}.',
                    'maxMessage' => 'La note ne peut pas dépasser {{ limit }}.',
                ]),
            ]
        ])
            ->remove('idClient')
            ->remove('idProduit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}