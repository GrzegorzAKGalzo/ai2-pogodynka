<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => [
                    'placeholder' => 'Enter City Name',
                ],
            ])
            ->add('longitude')
            ->add('latitude')
            ->add('code', ChoiceType::class, [
                'choices' =>[
                    'Poland' => 'PL',
                    'England' => 'EN',
                    'Germany' => 'DE',
                    'USA' => 'US',
                    'Italy' => 'IT',
                ]
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
