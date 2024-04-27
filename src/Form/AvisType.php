<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', null, [
                'constraints' => [

                    new Assert\Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Le commentaire doit contenir au moins 10 caractères.',
                        'maxMessage' => 'Le commentaire ne peut pas dépasser 255 caractères.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'avis doit être une chaîne sans chiffres ou caractères spéciaux.'
                    ])
                ]
            ])
            ->add('note', null, [
                'constraints' => [
                    new Assert\Range([
                        'min' => 0,
                        'max' => 5,
                        'minMessage' => 'La note doit être au moins 0.',
                        'maxMessage' => 'La note ne peut pas dépasser 5.',
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