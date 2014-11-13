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


use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class EntregaLiquidacionRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega_liquidacion.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaLiquidacionRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Entrega\EntregaLiquidacionType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacion';
    }

    protected function getRouteName()
    {
        return 'get_liquidacion';
    }


}