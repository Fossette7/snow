<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom de figure'])

            ->add ('description', TextareaType::class)

            ->add('category', EntityType::class,[
                'label'=>'Categorie',
                'class' => Category::class,
                ])

            ->add('author', EntityType::class,[
                'label'=>'Auteur',
                'class'=> User::class
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'trick_item',
        ]);
    }
}
