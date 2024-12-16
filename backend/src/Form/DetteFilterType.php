<?php

namespace App\Form;

use App\Enums\TypeDette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('types', ChoiceType::class, [
                'choices' => [
                    'Tous' => TypeDette::all->value,
                    'Solde' => TypeDette::solde->value,
                    'nonSolde' => TypeDette::nonSolde->value,
                ],
                'label' => 'Statut',
                // 'expanded' => true,
                // 'multiple' => true,
                
                'required' => false,
            ])
            ->add('Search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success my-2 my-sm-0'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}