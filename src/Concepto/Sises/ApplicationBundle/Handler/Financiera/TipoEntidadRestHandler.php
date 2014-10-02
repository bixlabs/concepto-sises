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

namespace Concepto\Sises\ApplicationBundle\Handler\Financiera;


use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class TipoEntidadRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Financiera
 * @Service(id="concepto_sises_tipo_entidad.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class TipoEntidadRestHandler extends RestHandler
{
    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Financiera\TipoEntidadType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Financiera\TipoEntidad';
    }

    protected function getRouteName()
    {
        return 'get_tipo';
    }
}