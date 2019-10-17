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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Operator;
use Doctrine\ORM\EntityRepository;

class RefillType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {                
        $builder
            ->add('operator_id', EntityType::class, [
                'class' => Operator::class,
                'choice_value' => 'id',
                'data' => $options['data']['operator'] ?? null,
            ])
            ->add('balance', NumberType::class, [
                'data' => $options['data']['balance'] ?? 0,
                'disabled' => true
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
