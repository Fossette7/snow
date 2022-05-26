<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Video;
//use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\VideoType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;


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

            //->add('author', EntityType::class,[
               // 'label'=>'Auteur',
               // 'class'=>User::class,
               // ])

            ->add('image', FileType::class,[
              'label' => 'Photo',
              'mapped' => false,
              'multiple' => true,
              'required' => false,
              'constraints' => [
                new All([
                  new File([
                    'maxSize'=> '1024k',
                    'mimeTypes' => [
                      'image/jpeg',
                      'image/jpg',
                      'image/png'
                  ],
                  'mimeTypesMessage' => 'Télécharger une image avec une extension valide (jpeg,jpg,png)',
                  ])
                ])
              ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
          "allow_extra_fields" => true
        ]);
    }
}
