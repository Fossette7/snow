<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('TrickName',Trick::class,[
                'label'=>'Nom de figure'])

            ->add('createdAt',DateType::class,[
                'label'=>'écrit le'
            ])
            ->add('category', Category::class,[
                'label'=>'Category'])

            ->add('author', User::class,[
                'label'=>'Rider-Auteur'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
