<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label_attr' => ['class' => 'hidden'],
                'attr' => [
                    'class' => 'w-full',
                    'placeholder' => 'Nom de la ville',
                ],
            ])
            ->add('country', TextType::class, [
                'label_attr' => ['class' => 'hidden'],
                'attr' => [
                    'class' => 'w-full',
                    'placeholder' => 'Nom du pays',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
