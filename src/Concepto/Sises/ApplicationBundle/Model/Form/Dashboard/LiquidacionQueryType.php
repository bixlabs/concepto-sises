<?php
/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */

namespace Concepto\Sises\ApplicationBundle\Model\Form\Dashboard;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LiquidacionQueryType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Model\Dashboard\LiquidacionQuery'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa', 'text', array('required' => false,))
            ->add('lugar', 'text', array('required' => false,))
            ->add('recurso', 'text', array('required' => false,))
            ->add('start', 'datetime', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                'required' => false,
            ))
            ->add('end', 'datetime', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                'required' => false,
            ))
        ;
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'liquidacion_query';
    }
}