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


use Concepto\Sises\ApplicationBundle\Controller\SubRestController;
use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;

class AsignacionController extends SubRestController
{
    /**
     * @return RestHandlerInterface
     * @LookupMethod("concepto_sises_entrega_asignacion.handler")
     */
    public function getHandler() {}
}