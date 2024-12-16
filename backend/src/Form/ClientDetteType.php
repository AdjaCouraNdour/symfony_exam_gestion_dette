<?php
// src/Form/ClientDebtType.php
namespace App\Form;

use App\Entity\Client; // Assurez-vous que vous avez une entité Client
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientDetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class, [
                'label' => 'surname',
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Vérifier Client',
                'attr' => ['class' => 'bg-burgundy text-white px-4 py-2 rounded hover:bg-red-700']
            ]);
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Client::class,
    //     ]);
    // }
}
