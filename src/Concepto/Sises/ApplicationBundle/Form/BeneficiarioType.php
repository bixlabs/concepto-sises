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


use Concepto\Sises\ApplicationBundle\Form\Archivos\ArchivoBeneficiarioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BeneficiarioType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //TODO: posible necesidad de optimizar larga coleccion
        $builder
            ->add('persona', null, array(
                'property' => 'id'
            ))
            ->add('beneficios', 'collection', array(
                'type' => new BeneficioType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('archivos', 'collection', array(
                'type' => new ArchivoBeneficiarioType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('contrato', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Contrato'
            ))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Beneficiario',
            'cascade_validation' => true
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