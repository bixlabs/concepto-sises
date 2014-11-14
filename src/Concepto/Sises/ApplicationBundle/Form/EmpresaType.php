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


use Concepto\Sises\ApplicationBundle\Form\Archivos\ArchivoEmpresaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit')
            ->add('nombre')
            ->add('direccion')
            ->add('telefono')
            ->add('direccion')
            ->add('email')
            ->add('logo')
            ->add('privada')
            ->add('encargado', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\PersonaCargo'
            ))
            ->add('director',  'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Personal\Director'
            ))
            ->add('archivos', 'collection', array(
                'type' => new ArchivoEmpresaType(),
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
           'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Empresa'
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