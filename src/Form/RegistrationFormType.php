<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
              'label'=>'Pseudo'
            ])
            ->add('avatar', FileType::class,[
              'label'=>'Votre avatar',
              'mapped'=> false,
              'required'=> false,
              'constraints' => [
                new File([
                  'maxSize'=> '1024k',
                  'mimeTypes' => [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif'
                ],
                    'mimeTypesMessage' => 'Télécharger une image avec une extension valide (jpeg,jpg,png,gif)',
                ])
              ]
            ])
            ->add('email', EmailType::class,[
              'label'=>'E-mail'
            ])
            ->add('RGPD', CheckboxType::class, [
                'label'=> 'J\'accepte que mes données soient conservées dans notre base de données.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un Mot de passe, ce champs ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 15,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
