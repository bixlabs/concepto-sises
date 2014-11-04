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


use Concepto\Sises\ApplicationBundle\Handler\Entrega\EntregaOperacionRestHandler;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;

class OperacionController implements ClassResourceInterface
{
    /**
     * @return EntregaOperacionRestHandler
     * @LookupMethod("concepto_sises_entrega_operacion.handler")
     */
    public function getHandler() { }

    public function cgetAction($id)
    {
        return $this->getHandler()->getParaEntrega($id);
    }
} 