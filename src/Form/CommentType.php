<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class,[
              'label'=> false,
              'attr'=>[
                'placeholder'=>'Votre commentaire',
                'class'=> 'form-control'
              ]
            ])

           ->add('author', EntityType::class,[
                'label'=> false,
                'class'=> User::class,
                'choice_label' => 'Username',
              ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class'=> Comment::class,
            'csrf_protection' => true,
            'csrf_field_name' =>'_token',
            'csrf_token_id' => 'trick_item',
        ]);
    }
}
