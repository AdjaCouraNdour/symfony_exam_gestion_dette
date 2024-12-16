<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\DTO\ClientSearchDto;

class ClientSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control']
            ])
            ->add('surname', TextType::class, [
                'required' => false,
                'label' => 'Nom de famille',
                'attr' => ['class' => 'form-control']
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'label' => 'adresse',
                'attr' => ['class' => 'form-control']
            ])
            ->add('compte', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'required' => false,
                'label' => 'Compte',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientSearchDto::class,
        ]);
    }
}