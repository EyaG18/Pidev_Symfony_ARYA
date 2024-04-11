<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Id_Catégorie', EntityType::class, [
            'class' => 'App\Entity\Catégorie', // Nom de la classe Catégorie
            'choice_label' => 'NomCatégorie', // Attribut à afficher dans la liste déroulante
            'placeholder' => 'Sélectionnez une catégorie',]) // Texte par défaut dans la liste déroulante
            ->add('NomP')
            ->add('PrixP')
            ->add('QteP')
            ->add('QteSeuilP')
            ->add('ImageP',FileType ::class,[
                'required'=>false,
                 'mapped'=>false,
            ])
            ->add('Ajouter', SubmitType::class);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
