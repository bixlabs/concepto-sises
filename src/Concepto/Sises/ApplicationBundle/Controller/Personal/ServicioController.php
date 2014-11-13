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

namespace Concepto\Sises\ApplicationBundle\Controller\Personal;


use Concepto\Sises\ApplicationBundle\Controller\RestController;
use Concepto\Sises\ApplicationBundle\Handler\Personal\ServicioRestHandler;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Symfony\Component\HttpFoundation\Request;

class ServicioController extends RestController
{

    /**
     * @return ServicioRestHandler
     * @LookupMethod("concepto_sises_serv_operativo.handler")
     */
    public function getHandler() { }

    public function postLiquidacionAction(Request $request, $id)
    {
        return $this->getHandler()->liquidacion($request, $id);
    }
}