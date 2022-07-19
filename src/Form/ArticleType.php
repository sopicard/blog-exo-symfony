<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //génère l'input pour le title avec valeur par défaut.
            //Si je souhaite par ex un text area, il faudra que je le rajoute moi-même
            ->add('title')
            ->add('content')
            ->add('isPublished')
            ->add('author')

            //ajout d'un champ pour gérer le choix de la cat voulue pour l'article.
            // dont le type est Entity parce que ?
            ->add("category",EntityType::class, [
                //tableau d'arguments pour mettre en forme notre champ
                // avec affichage des titres de catégories à choisir
                "class" => Category::class,
                "choice_label" => "title",
                "placeholder"=>"Choisissez votre catégorie"
            ])
            // je peux en rajouter
            //ajout d'un champ image
            ->add("image", FileType::class, [
                "mapped" => false
            ])
            ->add("submit", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
