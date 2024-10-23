<?php

namespace App\Form\Type;

use App\Entity\Currency;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class ExchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sourceCurrency', EntityType::class, [
                'class' => Currency::class,
                'choice_label' => 'code',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isActive = :active')
                        ->setParameter('active', true);
                },
            ])
            ->add('amount', NumberType::class)
            ->add('targetCurrency', EntityType::class, [
                'class' => Currency::class,
                'choice_label' => 'code',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isActive = :active')
                        ->setParameter('active', true);
                },
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
        ;
    }
}