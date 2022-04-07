<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
              'label'=>'Pseudo'
            ])

            ->add('email',EmailType::class,[
              'label'=>'Votre email',
            ])

            ->add('avatar',FileType::class,[
              'label'=>'Votre photo',
              'mapped'=>false,
              'required'=>false,
              'constraints'=> [
                new File([
                  'maxSize' => '1024k',
                  'mimeType' => [
                    'application/jpeg',
                    'application/png',
                    'application/jpg'
                  ],
                  'mimeTypesMessage' => 'Ajouter une image valide',
                ])
              ]
            ])

            ->add('password', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'user_item',
          ]);
    }
}
