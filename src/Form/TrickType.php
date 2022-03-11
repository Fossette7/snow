<?php

namespace App\Form;

use App\Entity\Trick;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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

            ->add('createdAt',DateType::class,[
                'label'=>'écrit le'
            ])
            ->add('category', ChoiceType::class,[
                'label'=>'Catégories',
                'choices' => [
                    'Slide'=> 'choix1',
                    'Rotation'=> 'choix2',
                    'Flip'=>'choix3',
                    'Grab'=>'choix4'
                ],
                ])

            ->add('author', TextType::class,[
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
