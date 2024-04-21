<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomuser', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Nom',
            ])
            ->add('prenomuser', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Prenom',
            ])
            ->add('adruser', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Address',
            ])
            ->add('numtel', IntegerType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Numero Telephone',
            ])
            ->add('role', ChoiceType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'choices' => [
                    'Client' => 'client',
                    'Livreur' => 'livreur',
                    'Employee' => 'employee',
                    'Fournisseur' => 'fournisseur',
                    'Administrateur' => 'administrateur',
                ]
            ])
            ->add('image',FileType ::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'required' => false,
                'data_class' => null,
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ]);
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
