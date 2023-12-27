<?php

namespace App\Form\Order;

use App\Entity\Payment;
use App\Entity\PaymentMethod;
use App\Entity\PaymentStatus;
use App\Repository\PaymentMethodRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class PaymentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $custom = $options['custom'];
        $builder
            ->add('paid_amount', null, [
                'label' => 'Kwota',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Kwota nie może być pusta.'
                    ]),
                    new Positive([
                        'message' => 'Kwota musi być dodatnia.'
                    ]),
                    new Regex([
                        'pattern' => '/^\d+(\.\d{1,2})?$/',
                        'message' => 'Kwota musi być do dwóch miejsc po przecinku.',
                    ]),
                ],
            ])
            ->add('method', EntityType::class, [
                'class' => PaymentMethod::class,
                'label' => 'Metoda Płatności',
                'choice_label' => 'name',
                'query_builder' => function (PaymentMethodRepository $repository) use ($custom) {
                    return $repository->createQueryBuilder('p')
                        ->where('p.id > :id')
                        ->setParameter('id', $custom);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            'custom' => null
        ]);
    }
}
