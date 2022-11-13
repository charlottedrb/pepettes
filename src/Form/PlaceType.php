<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label_attr' => ['class' => 'hidden'],
                'attr' => [
                    'class' => 'w-full',
                    'placeholder' => 'Nom du lieu',
                ],
            ])
            ->add('price_range', RangeType::class, [
                'label' => 'Gamme de prix',
                'label_attr' => ['class' => 'block'],
                'attr' => [
                    'class' => 'w-full range-red',
                    'min' => 1,
                    'max' => 5,
                ],
            ])
            ->add('security_level', RangeType::class, [
                'label' => 'Niveau de tranquilité',
                'label_attr' => ['class' => 'block'],
                'attr' => [
                    'class' => 'w-full range-green',
                    'min' => 1,
                    'max' => 5,
                ],
            ])
            ->add('charo_rate', RangeType::class, [
                'label' => 'Taux de charo',
                'label_attr' => ['class' => 'block'],
                'attr' => [
                    'class' => 'w-full range-blue',
                    'min' => 1,
                    'max' => 5,
                ],
            ])
            ->add('has_cocktails', CheckboxType::class, [
                'label' => 'Cocktails',
                'required' => false,
            ])
            ->add('has_beers', CheckboxType::class, [
                'label' => 'Bières',
                'required' => false,
            ])
            ->add('has_wines', CheckboxType::class, [
                'label' => 'Vins',
                'required' => false,
            ])
            ->add('has_softs', CheckboxType::class, [
                'label' => 'Softs',
                'required' => false,
            ])
            ->add('tags', ChoiceType::class)
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
