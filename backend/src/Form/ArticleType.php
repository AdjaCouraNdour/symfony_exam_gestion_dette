<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleType extends AbstractType
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
                ]])
            ->add('qte_stock',TextType::class, [
                'label' => 'qteStock', 
                'required' => false, 
                'constraints' => [
                    new NotNull([ 
                        'message'=>'la quantite ne peut pas etre vide .',
                    ]),
                ]])
            ->add('prix',TextType::class, [
                'label' => 'prix', 
                'required' => false, 
                'constraints' => [
                    new NotNull([ 
                        'message'=>'le prix ne peut pas etre vide .',
                    ]),
                ]])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'bg-burgundy hover:bg-burgundy-dark text-white font-bold py-2 px-4 rounded']
            ])       
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
