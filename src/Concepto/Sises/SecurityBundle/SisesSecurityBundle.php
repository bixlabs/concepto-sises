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

namespace Concepto\Sises\SecurityBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class SisesSecurityBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
} 