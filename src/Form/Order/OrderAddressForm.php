<?php

namespace App\Form;

use App\Entity\ShippingAddress;
use App\Entity\ShippingMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderAddressForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shipping', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'choices' => $options['data'],
                'choice_label' => 'name'
            ])
            ->add('name')
            ->add('lastname')
            ->add('street')
            ->add('building_nr')
            ->add('locale_nr')
            ->add('postcode')
            ->add('city')
            ->add('phone_nr')
            ->add('shipping_choice', HiddenType::class)
            ->add('remember', HiddenType::class)
            ->add('alreadyRemembered', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}