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

namespace Concepto\Sises\ApplicationBundle\Handler\Financiera;


use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class EntidadRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Financiera
 * @Service(id="concepto_sises_entidad.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntidadRestHandler extends RestHandler
{
    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Financiera\EntidadType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Financiera\Entidad';
    }
}