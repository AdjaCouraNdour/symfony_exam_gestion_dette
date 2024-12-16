<?php
namespace App\Form;

use App\Entity\Dette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'border border-gray-300 p-2 rounded-md',
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'border border-gray-300 p-2 rounded-md',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer Dette',
                'attr' => [
                    'class' => 'bg-burgundy text-white px-4 py-2 rounded hover:bg-red-700',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dette::class, // Assurez-vous que c'est votre entité
        ]);
    }
}
