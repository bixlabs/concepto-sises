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
 * Class RecursoHumanoRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_rrhh.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class RecursoHumanoRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\RecursoHumanoType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\RecursoHumano';
    }

    protected function getRouteName()
    {
        return 'get_recurso';
    }
}