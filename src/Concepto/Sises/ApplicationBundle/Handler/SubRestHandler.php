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

namespace Concepto\Sises\ApplicationBundle\Handler;


use Doctrine\Common\Util\Inflector;

abstract class SubRestHandler extends RestHandler implements SubRestHandlerInterface
{
    abstract function getParentOrmClassString();

    public function put($id, $parameters, $parent = null)
    {
        return parent::put($id, $parameters);
    }

    public function post($parameters, $parent = null)
    {
        return parent::post($parameters);
    }

    public function patch($id, $parameters, $parent = null)
    {
        return parent::patch($id, $parameters);
    }

    public function delete($id, $parent = null)
    {
        return parent::delete($id);
    }

    public function get($id, $parent = null)
    {
        return parent::get($id);
    }

    public function cget($pagerParams, $extraParams, $parent = null)
    {
        if (isset($extraParams['_parent'])) {
            $extraParams[$this->getParentName()] = $extraParams['_parent'];
            unset($extraParams['_parent']);
        }

        return parent::cget($pagerParams, $extraParams);
    }

    protected function getParentName()
    {
        $name = explode('\\', $this->getParentOrmClassString());

        return Inflector::camelize(end($name));
    }
}