<?php

namespace App\Form;

use App\Entity\ShippingAddress;
use App\Entity\ShippingMethod;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class OrderAddressForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shipping', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'choices' => $options['data'],
                'choice_label' => 'name',
                'label' => "Metoda Dostawy"
            ])
            ->add('name', null, [
                'label' => "Imię*"
            ])
            ->add('lastname', null, [
                'label' => "Nazwisko*"
            ])
            ->add('street', null, [
                'label' => "Ulica*"
            ])
            ->add('building_nr', null, [
                'label' => "Numer Budynku*"
            ])
            ->add('locale_nr', null, [
                'label' => "Numer Mieszkania",
                'required' => false,
            ])
            ->add('postcode', null, [
                'label' => "Kod Pocztowy*",
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{2}-\d{3}$/',
                        'message' => 'Proszę podać liczbę w formacie 00-000.',
                    ]),
                ],
            ])
            ->add('city', null, [
                'label' => "Miasto*"
            ])
            ->add('phone_nr', null, [
                'label' => "Numer Telefonu*",
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{9}$/',
                        'message' => 'Proszę podać prawidłowy numer telefonu.',
                    ]),
                ],
            ])
            ->add('shipping_choice', HiddenType::class, [
                'empty_data' => 0
            ])
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