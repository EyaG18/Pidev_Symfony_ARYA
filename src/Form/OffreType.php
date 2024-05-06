<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'label' => 'Debut Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'data' => new \DateTime(), 
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Fin Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'data' => new \DateTime(), 
            ])
            ->add('reduction', TextType::class, [
                'label' => 'Reduction',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Reduction'
                ]
            ])
            ->add('titreOffre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre Offre'
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
            'data_class' => Offre::class,
        ]);
    }
}
