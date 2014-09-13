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

interface SubRestHandlerInterface
{
    public function patch($id, $parameters, $parent = null);

    public function delete($id, $parent = null);

    public function cget($pagerParams, $extraParams, $parent = null);

    public function get($id, $parent = null);

    public function post($parameters, $parent = null);

    public function put($id, $parameters, $parent = null);
}