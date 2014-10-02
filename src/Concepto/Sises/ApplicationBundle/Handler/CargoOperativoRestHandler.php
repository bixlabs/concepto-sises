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

use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class ServicioOperativoRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_operativo.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class CargoOperativoRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return '';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\CargoOperativo';
    }
}