<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une description.'
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[\w\d\s.,!?()]+$/',
                        'message' => 'La description ne doit contenir que des lettres, des chiffres, des espaces et les caractères suivants: .,!?()'
                    ])
                ]
            ])
            /* ->add('dateReclamation')*/
            /* ->add('statuReclamation')*/
            ->add('typeReclamation', ChoiceType::class, [
                'choices' => [
                    'probléme produit' => 'probléme produit',
                    'probléme livraison' => 'probléme livraison',
                    'service client insatisfaisant' => 'service client insatisfaisant',
                    'autres' => 'autres',
                ],
            ])
            ->remove('idClient');

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,

        ]);
    }
}