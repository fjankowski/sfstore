<?php

namespace App\Form;

use App\Entity\ShippingAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('street')
            ->add('building_nr')
            ->add('locale_nr')
            ->add('postcode')
            ->add('city')
            ->add('phone_nr')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShippingAddress::class,
        ]);
    }
}