<?php

namespace App\Form;

use App\Entity\User;
use App\Enums\UserRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'data' => false,
                'constraints' => [
                ]
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'data' => false,
            ])
            ->add('login', TextType::class, [
                'required' => false,
                'data' => false,
            ])
            ->add('password', TextType::class, [
                'required' => false,
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Boutiquier' => UserRole::roleBoutiquier,
                    'Client' => UserRole::roleClient,
                ],
                'placeholder' => 'Choisissez un rôle',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le rôle est obligatoire.',
                    ])
                ],
                'choice_label' => function($choice, $key, $value) {
                    return $key;
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
