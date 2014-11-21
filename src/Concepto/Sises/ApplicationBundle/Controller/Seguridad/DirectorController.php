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


use Concepto\Sises\ApplicationBundle\Controller\RestController;
use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;

class DirectorController extends RestController {

    /**
     * @return RestHandlerInterface
     * @LookupMethod("concepto.sises.user_director.handler")
     */
    public function getHandler() { }
}