<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label_attr' => ['class' => 'hidden'],
                'attr' => [
                    'class' => 'w-full',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('first_name', TextType::class, [
                'label_attr' => ['class' => 'hidden'],
                'attr' => [
                    'class' => 'w-full',
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('last_name', TextType::class, [
                'label_attr' => ['class' => 'hidden'],
                'attr' => [
                    'class' => 'w-full',
                    'placeholder' => 'Nom',
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
