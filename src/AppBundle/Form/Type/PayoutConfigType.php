<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayoutConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('percentCw', PercentType::class, array(
                'required' => true,
            ))
            ->add('percentSh', PercentType::class, array(
                'required' => true,
            ))
            ->add('percentHqBonus', PercentType::class, array(
                'required' => true,
            ))
            ->add('ptsCommanderWin', IntegerType::class, array(
                'required' => true,
            ))
            ->add('ptsCommanderDraw', IntegerType::class, array(
                'required' => true,
            ))
            ->add('ptsCommanderLost', IntegerType::class, array(
                'required' => true,
            ))
            ->add('ptsPlayerWon', IntegerType::class, array(
                'required' => true,
            ))
            ->add('ptsPlayerDraw', IntegerType::class, array(
                'required' => true,
            ))
            ->add('ptsPlayerLost', IntegerType::class, array(
                'required' => true,
            ))
            ->add('recruitFactor', PercentType::class, array(
                'required' => true,
            ))
            ->add('reservistFactor', PercentType::class, array(
                'required' => true,
            ))
            ->add('minResourceToBePaid', IntegerType::class, array(
                'required' => true,
            ))
            ->add('minResourceToBeExtraPaid', IntegerType::class, array(
                'required' => true,
            ))
            ->add('percentExtraShare', PercentType::class, array(
                'required' => true,
            ))

            ->add('save', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PayoutConfig'
        ));
    }
}
