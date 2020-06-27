<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Change to radio button for deliveryTime
        $builder
            ->add('deliveryDate')
            ->add('deliveryTime', ChoiceType::class, [
                'choices' => [
                    'Morning' => 'morning',
                    'Afternoon' => 'afternoon',
                ],
            ])
            ->add('paymentType', ChoiceType::class, [
                'choices' => [
                    'Cash' => Orders::PAYMENT_CASH,
                    'Direct Credit' => Orders::PAYMENT_DIRECT_CREDIT,
                ],
            ])
            ->add('comment', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}