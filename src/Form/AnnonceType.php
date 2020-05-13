<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('title', TextType::class, $this->getConfiguration("titre", "taper un titre")
            )

            ->add(
                'slug',
                 TextType::class,
                  $this->getConfiguration("adresse web", "taper une adresse web",[
                'required' => false
            ])
            )

            ->add('introduction', TextType::class, $this->getConfiguration("introduction", "écrivé une intro")
            )
            
            ->add('content', TextareaType::class, $this->getConfiguration("description", "donne le plus de detail")
            )

            ->add('coverImage', UrlType::class, $this->getConfiguration("URL DE LIMAGE", "image principal")
            )

            ->add('rooms', IntegerType::class, $this->getConfiguration("nombre de chambre", "chambre dispo")
            )

            ->add('price', MoneyType::class, $this->getConfiguration("prix par nuit", "prix note ici")
            )
            
            ->add(
                'images', CollectionType::class,
                [
                        'entry_type' => ImageType::class,
                        'allow_add' => true,
                        'allow_delete' => true


                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
