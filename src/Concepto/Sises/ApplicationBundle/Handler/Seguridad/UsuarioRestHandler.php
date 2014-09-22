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

namespace Concepto\Sises\ApplicationBundle\Handler\Seguridad;


use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class UsuarioRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Seguridad
 * @Service(id="concepto_sises_usuario.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class UsuarioRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Seguridad\UsuarioType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario';
    }
}