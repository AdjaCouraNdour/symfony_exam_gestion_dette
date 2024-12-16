<?php

namespace App\Form;

use App\Dto\ClientDto;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfonycasts\DynamicForms\DependentField;

use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('surname', TextType::class, [
                'label' => 'Surname',
                'required' => false, 
                'attr' => [
                    'id' => 'surname',
                    'class' => 'text-sm p-2 border rounded w-full',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire.',
                    ]),
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Telephone',
                'required' => false, 
                'attr' => [
                    'id' => 'telephone',
                    'class' => 'text-sm p-2 border rounded w-full',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un numéro de téléphone valide.',
                    ]),
                    new NotNull([
                        'message' => 'Le téléphone ne peut pas être vide',
                    ]),
                    new Regex(
                        '/^(77|78|76)([0-9]{7})$/',
                        'Le numéro de téléphone doit être au format 77XXXXXX ou 78XXXXXX ou 76XXXXXX'
                    )
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'required' => false,
                'label' => 'Adresse',
                'attr' => [
                    'id' => 'adresse',
                    'class' => 'text-sm p-2 border rounded w-full',
                ],
            ])
            ->add('addUser', CheckboxType::class, [
                'label' => 'Ajouter un compte ?',
                'required' => false,
                'data' => false,
                'mapped' => false,

                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])

            ->addDependent('user', 'addUser', function (DependentField $field, ?string $choice) {
                if (empty($choice)) {
                    return;
                }
                // if ($choice == "1") {
              
                $field
                    ->add(UserType::class, [
                        'label' => false,
                        'attr' => [],
                        'default' => null,
                    ]);
                // }
            })

            // ->add('save', SubmitType::class, [
            //     'attr'=>[
            //         'class' => 'btn btn-primary my-2 my-sm-0'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            // APPLICATION CONDITIONNELLE DU GROUPE
            'validation_groups' => function (FormInterface $form) {
                if ($form->has("addUser") && $form->get("addUser")->getData()) {
                    return ['Default', "WITH_USER"];
                }
                return ['Default'];
            }

        ]);
    }
}


