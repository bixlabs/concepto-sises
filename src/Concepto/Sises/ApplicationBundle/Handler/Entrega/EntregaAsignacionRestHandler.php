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

namespace Concepto\Sises\ApplicationBundle\Handler\Entrega;


use Concepto\Sises\ApplicationBundle\Handler\SubRestHandler;
use JMS\DiExtraBundle\Annotation\Service;


/**
 * Class EntregaAsignacionRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega_asignacion.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaAsignacionRestHandler extends SubRestHandler
{

    protected function getTypeClassString()
    {
        return '';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion';
    }

    function getParentOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega';
    }
}