<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Catégorie;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Id_Categorie', ChoiceType::class, [
                'choices' => $options['categories'],
                'choice_label' => function (Catégorie $catégorie) {
                    return $catégorie->getNomCategorie();
                },
                'placeholder' => 'Sélectionnez une catégorie',
                'attr' => ['class' => 'form-control']
            ])
            
        
            ->add('NomP', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le nom du produit doit être une chaîne sans chiffres ou caractères spéciaux.'
                    ])
                ],
               
            ])
            ->add('PrixP', NumberType::class, [
                'constraints' => [
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'Le prix doit être un nombre.'
                    ])
                ],
                // Autres options pour le champ PrixP
            ])
            ->add('QteP', IntegerType::class, [
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'La quantité en stock doit être un nombre entier.'
                    ])
                ],
                // Autres options pour le champ QteP
            ])
            ->add('QteSeuilP', IntegerType::class, [
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'La quantité seuil de stock doit être un nombre entier.'
                    ])
                ],
                // Autres options pour le champ QteSeuilP
            ])
            ->add('ImageP', FileType::class, [
                'required' => false,
                'mapped' => false,
              
            ])
            ->add('Ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'categories' => [],
        ]);
    }
}
