<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('trick')
            ->add('image',FileType::class, [
              'label' => 'Votre image',
              'mapped' => false,
              'required' => false,
              'constraints' => [
                new File([
                  'maxSize' => '1024k',
                  'mimeTypes' => [
                    'application/jpg'
                  ],
                  'mimeTypesMessage' => 'Please upload a valid document',
                ])
              ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
