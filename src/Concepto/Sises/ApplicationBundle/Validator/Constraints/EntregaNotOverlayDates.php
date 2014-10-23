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

namespace Concepto\Sises\ApplicationBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class EntregaNotOverlayDates
 * @package Concepto\Sises\ApplicationBundle\Validator\Constraints
 * @Annotation
 */
class EntregaNotOverlayDates extends Constraint
{

    public $message = "Las entregas de un mismo contrato no pueden cruzarse entre si.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return "entrega_not_overlay";
    }
} 