<?php

namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFournisseur', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fournisseur Nom'
                ]
            ])
            ->add('numFournisseur', TextType::class, [
                'label' => 'Numero',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fournisseur Numero'
                ]
            ])
            ->add('adresseFournisseur', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fournisseur Adresse'
                ]
            ])
            ->add('idProduit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'nomP',
                'label' => 'Produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Select Produit'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
