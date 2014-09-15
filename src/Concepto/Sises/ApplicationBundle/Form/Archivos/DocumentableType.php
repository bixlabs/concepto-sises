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

namespace Concepto\Sises\ApplicationBundle\Form\Archivos;


use JMS\DiExtraBundle\Annotation\FormType;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DocumentableType
 * @package Concepto\Sises\ApplicationBundle\Form\Archivos
 * @Service()
 * @FormType(alias="documentable")
 */
class DocumentableType extends AbstractType
{
    const ARCHIVOS_CLASS = 'archivos_class';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('archivos', 'collection', array(
                'type' => 'archivo',
                'data_class' => $options[self::ARCHIVOS_CLASS],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
            self::ARCHIVOS_CLASS => null
        ));

        $resolver->setRequired(array(self::ARCHIVOS_CLASS));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'documentable';
    }
}