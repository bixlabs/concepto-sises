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

use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class ContratoRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_contrato.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class ContratoRestHandler extends RestHandler
{
    public function cget($pagerParams, $extraParams = array())
    {
        if ($this->isDirector()) {
            $director = $this->getRelatedUser();
            $empresas = $this->getEm()->getRepository('SisesApplicationBundle:Empresa')->findBy(array(
                'encargado' => $director
            ));

            $_e = array();

            foreach ($empresas as $empresa) {
                $_e[] = $empresa->getId();
            }

            if (count($_e) > 0) {
                $extraParams['empresa'] = 'A,' . implode(';', $_e);
            } else {
                $extraParams['empresa'] = '-1';
            }
        }

        return parent::cget($pagerParams, $extraParams);
    }

    /**
     * @return string
     */
    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Contrato';
    }

    /**
     * @return string
     */
    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\ContratoType';
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Contrato $object
     * @param array                                             $bag
     * @param array                                             $parameters
     *
     * @return array
     */
    protected function preSubmit($object, $bag = array(), $parameters = array())
    {
        $servicios = array();

        // Servicios antes de bind
        foreach ($object->getServicios() as $s) {
            $servicios[] = $s;
        }

        $bag['servicios'] = $servicios;

        return parent::preSubmit($object, $bag, $parameters);
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Contrato $object
     * @param array                                             $bag
     *
     * @return array
     */
    protected function preFlush($object, $bag = array())
    {
        $servicios = $bag['servicios'];

        /**
         * @var OrmPersistible $servicio
         * @var OrmPersistible $oServicio
         */
        foreach($object->getServicios() as $servicio) {
            foreach($servicios as $oKey => $oServicio) {
                if ($oServicio->getId() === $servicio->getId()) {
                    unset($servicios[$oKey]);
                }
            }
        }

        foreach ($servicios as $toDel) {
            $object->removeServicio($toDel);
            $this->getEm()->remove($toDel);
        }

        return parent::preFlush($object, $bag);
    }
}