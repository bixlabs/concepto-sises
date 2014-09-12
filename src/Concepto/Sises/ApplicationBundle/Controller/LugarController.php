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

namespace Concepto\Sises\ApplicationBundle\Controller;


use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;

class LugarController extends RestController
{

    /**
     * @return RestHandlerInterface
     * @LookupMethod("concepto_sises_lugar_entrega.handler")
     */
    public function getHandler() {}
}