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

namespace Concepto\Sises\ApplicationBundle\Model\Form\Seguridad;


use JMS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DirectorType
 * @package Concepto\Sises\ApplicationBundle\Model\Form\Seguridad
 * @FormType(alias="usuario_director")
 */
class DirectorType extends CoordinadorType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Model\Seguridad\Director'
        ));
    }

    public function getName()
    {
        return 'usuario_director';
    }

} 