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

namespace Concepto\Sises\ApplicationBundle\Controller\Seguridad;


use Concepto\Sises\ApplicationBundle\Handler\Seguridad\UsuarioRestHandler;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;

class UsuarioController implements ClassResourceInterface {

    /**
     * @return UsuarioRestHandler
     * @LookupMethod("concepto.sises.user.handler")
     */
    public function getHandler() {}

    public function getAction($id)
    {
        return $this->getHandler()->getByRelated($id);
    }

    public function checkUserName($username)
    {

    }
} 