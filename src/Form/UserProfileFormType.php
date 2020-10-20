<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('code_postal', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'options' => ['attr' => ['class' => 'form-control']],
                'mapped'            => false,
                'required'          => false,
                'type'              => PasswordType::class,
                'invalid_message'   => 'Les mots de passe ne correspondent pas.',
                'constraints'       => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le mot de passe doit contenir au moins 5 caractères',
                        'max' => 4096
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
