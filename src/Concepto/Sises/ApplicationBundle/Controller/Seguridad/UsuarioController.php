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
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
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

    /**
     * @return \FOS\RestBundle\View\View
     * @QueryParam(name="email")
     * @QueryParam(name="username")
     */
    public function getCheckAction(ParamFetcher $paramFetcher)
    {
        return $this->getHandler()->check($paramFetcher->get('username'), $paramFetcher->get('email'));
    }
} 