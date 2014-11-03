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

namespace Concepto\Sises\ApplicationBundle\Controller\Entrega;


use Concepto\Sises\ApplicationBundle\Controller\RestController;
use Concepto\Sises\ApplicationBundle\Handler\Entrega\EntregaAsignacionRestHandler;
use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Symfony\Component\HttpFoundation\Request;

class AsignacionController extends RestController
{
    /**
     * @return EntregaAsignacionRestHandler
     * @LookupMethod("concepto_sises_entrega_asignacion.handler")
     */
    public function getHandler() {}

    public function postEntregaAction(Request $request)
    {
        $parameters = $request->request->all();
        return $this->getHandler()->getOrCreateEntregaBeneficio($parameters);
    }

    public function postRealizaAction(Request $request)
    {
        $parameters = $request->request->all();
        return $this->getHandler()->realizaEntrega($parameters);
    }
}