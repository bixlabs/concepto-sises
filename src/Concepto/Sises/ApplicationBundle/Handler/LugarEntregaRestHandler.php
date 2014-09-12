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

namespace Concepto\Sises\ApplicationBundle\Handler;

use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class LugarEntregaRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_lugar_entrega.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class LugarEntregaRestHandler extends RestHandler {

    protected function  getTypeClassString()
    {
        return 'lugar';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\LugarEntrega';
    }

    protected function getRouteName()
    {
        return 'get_lugar';
    }
}