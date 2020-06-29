<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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