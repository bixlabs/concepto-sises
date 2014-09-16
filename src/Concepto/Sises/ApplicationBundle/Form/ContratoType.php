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

namespace Concepto\Sises\ApplicationBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('nombre')
            ->add('descripcion')
            ->add('resolucion')
            ->add('valor')
            ->add('fechaInicio', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('fechaCierre', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('empresa', null, array(
                'property' => 'id'
            ))
            ->add('contratante', null, array(
                'property' => 'id'
            ))
            ->add('servicios', 'collection', array(
                'type' => new ServicioContratadoType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
        ;
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Contrato'
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return '';
    }
}