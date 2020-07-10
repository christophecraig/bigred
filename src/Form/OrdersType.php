<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //creation
        if (null === $builder->getData()->getId()) {
            $builder
                ->add('deliveryDate', DateType::class, [
                    'widget' => 'single_text',
                    'attr' => ['style' => 'visibility: hidden'],
                ])
                ->add('deliveryTime', HiddenType::class)
                ->add('paymentType', ChoiceType::class, [
                    'choices' => [
                        'Cash' => Orders::PAYMENT_CASH,
                        'Direct Credit' => Orders::PAYMENT_DIRECT_CREDIT,
                    ],
                ])
                ->add('comment', TextareaType::class, [
                    'required' => false,
                    'attr' => ['class' => 'textarea'],
                ]);
        } else {
            $builder
                ->add('deliveryDate', DateType::class, [
                    'widget' => 'single_text',
                ])
                ->add('deliveryTime', ChoiceType::class, [
                    'choices' => [
                        'Morning' => 'morning',
                        'Afternoon' => 'afternoon',
                    ],
                ])
                ->add('deliveryRealTime', TimeType::class, [
                    'hours' => [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                    'minutes' => [0, 15, 30, 45],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}