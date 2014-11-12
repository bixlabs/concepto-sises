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

namespace Concepto\Sises\ApplicationBundle\Handler\Empresa;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class EmpresaRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_empresa_publica.handler", parent="conceptos_sises_abstract_rest.handler")
 */

class PublicaRestHandler extends RestHandler {

    public function cget($pagerParams, $extraParams = array())
    {
        $extraParams['privada'] = false;

        return parent::cget($pagerParams, $extraParams);
    }

    protected function process(array $parameters, $object, $method = 'PUT')
    {
        $parameters['privada'] = false;

        return parent::process($parameters, $object, $method);
    }

    /**
     * @return string
     */
    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Empresa';
    }

    /**
     * @return string
     */
    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\EmpresaType';
    }

    protected function getRouteName()
    {
        return 'get_publica';
    }
}