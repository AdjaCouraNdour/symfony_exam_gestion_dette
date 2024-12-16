<?php

namespace App\Form;

use App\Dto\UserSearchDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class SearchUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('login', TextType::class, [
            'label' => 'Telephone', 
            'required' => false,
            'constraints'=>[
                
                new NotBlank([
                    'message'=>'renseigner un login valide.',
                ]),

            ]
        ]) 
        ->add('rechercher', SubmitType::class, [
            'label' => 'Search',
            'attr' => ['class' => 'bg-burgundy text-white text-sm px-3 rounded-r-md h-10 hover:bg-red-700']
        ])       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            $resolver->setDefaults([
                'data_class' => UserSearchDto::class,
             ])       
            ]);
    }
}
