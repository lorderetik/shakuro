<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class RefillType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator_id', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Your operator ID may not be blank.'
                    ])
                ]
            ])
            ->add('phone', TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Phone may not be blank.'
                    ])
                ]
            ])
            ->add('amount', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Amount may not be blank.'
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Send"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
