<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('livrable', ChoiceType::class, [
            'choices' => [
                'Retrait en Magasin ' => false,
                'Livraison à Domicile' => true,
            ],
            'expanded' => false,
            'multiple' => false,
            'label' => 'Mode de livraison',
            'attr' => ['class' => 'form-control']
            
        ])
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Traitée' => 'Traitée',
                'Annulée' => 'Annulée',
                'en attente' => 'en attente',
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
