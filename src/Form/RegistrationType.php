<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use App\Form\RegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this-> getConfiguration("prenom", "votre prenom"))
            ->add('lastName', TextType::class, $this-> getConfiguration("nom", "votre nom de famille"))
            ->add('email', EmailType::class, $this-> getConfiguration("email", "votre email"))
            ->add('picture', UrlType::class, $this-> getConfiguration("photo de profil", "url photo de profil"))
            ->add('hash', PasswordType::class, $this-> getConfiguration("mot de passe ", "choissisé un mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this-> getConfiguration("confirmation de mdp ", "réecrivez votre mdp"))
            ->add('introduction', TextType::class, $this-> getConfiguration("printroductione", "présenté vous on quelque mot"))
            ->add('description', TextareaType::class, $this-> getConfiguration("description detaillé", "approfondir votre description"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
