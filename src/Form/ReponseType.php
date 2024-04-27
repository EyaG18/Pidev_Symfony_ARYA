<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une réponse.'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'La réponse doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La réponse ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La reponse doit être une chaîne sans chiffres ou caractères spéciaux.'
                    ])
                ]
            ])
            //->add('dateReponse')
            ->remove('idReclamation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}