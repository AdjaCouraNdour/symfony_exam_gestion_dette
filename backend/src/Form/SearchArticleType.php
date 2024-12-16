<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle',TextType::class, [
            'label' => 'libelle', 
            'required' => false, 
            'constraints' => [
                new NotNull([ 
                    'message'=>'le libelle ne peut pas etre vide .',
                ]),
                new NotBlank([
                    'message'=>'entrez un article valide.',
                ]),
            ]])
        ->add('rechercher', SubmitType::class, [
            'label' => 'Search',
            'attr' => ['class' => 'bg-burgundy text-white text-sm px-3 rounded-r-md h-10 hover:bg-red-700']
        ])       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
