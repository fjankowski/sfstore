<?php

namespace App\Form;

use App\Entity\Shipping;
use App\Entity\ShippingAddress;
use App\Entity\ShippingMethod;
use App\Entity\ShippingStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shipped_date')
            ->add('tracking')
            ->add('method', EntityType::class, [
                'class' => ShippingMethod::class,
'choice_label' => 'name',
            ])
            ->add('status', EntityType::class, [
                'class' => ShippingStatus::class,
'choice_label' => 'name',
            ])
            ->add('address', EntityType::class, [
                'class' => ShippingAddress::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shipping::class,
        ]);
    }
}
