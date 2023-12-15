<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\PaymentMethod;
use App\Entity\PaymentStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paid_amount')
            ->add('method', EntityType::class, [
                'class' => PaymentMethod::class,
                'choice_label' => 'name',
            ])
            ->add('status', EntityType::class, [
                'class' => PaymentStatus::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
