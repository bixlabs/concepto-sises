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


interface RestHandlerInterface {
    public function put($id, $parameters);
    public function post($parameters);
    public function patch($id, $parameters);
    public function delete($id);
    public function get($id);
    public function cget();
}