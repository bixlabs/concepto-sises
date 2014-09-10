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
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestController
 * @package Concepto\Sises\ApplicationBundle\Controller
 */
abstract class RestController implements ClassResourceInterface
{
    /**
     * @return RestHandlerInterface
     */
    abstract public function getHandler();

    /**
     * @View(serializerGroups={"details"})
     */
    public function getAction($id)
    {
        return $this->getHandler()->get($id);
    }

    /**
     * @View(serializerGroups={"list"})
     */
    public function cgetAction()
    {
        return $this->getHandler()->cget();
    }

    public function postAction(Request $request)
    {
        return $this->getHandler()->post($request->request->all());
    }

    public function patchAction(Request $request, $id)
    {
        return $this->getHandler()->patch($id, $request->request->all());
    }

    public function putAction(Request $request, $id)
    {
        return $this->getHandler()->put($id, $request->request->all());
    }

    public function deleteAction($id)
    {
        return $this->getHandler()->delete($id);
    }
}