<?php 
// src/Form/ArticleSelectionType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleSelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $articles = $options['articles'];

        foreach ($articles as $article) {
            $builder->add('articles_' . $article->getId(), CheckboxType::class, [
                'label' => $article->getLibelle(),
                'required' => false,
                'data' => false, // Cela définit l'état par défaut de la case à cocher
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Faire une Dette',
            'attr' => ['class' => 'bg-burgundy text-white px-4 py-2 rounded hover:bg-red-700'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
        $resolver->setRequired(['articles']); // S'assurer que l'option 'articles' est fournie
    }
}
