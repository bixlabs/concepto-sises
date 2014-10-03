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

namespace Concepto\Sises\ApplicationBundle\Handler\Personal;


use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class CoordinadorRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Personal
 * @Service(id="concepto_sises_coordinador.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class CoordinadorRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Personal\CoordinadorType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador';
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador $object
     * @param array                                                         $bag
     *
     * @return array
     */
    protected function preSubmit($object, $bag = array())
    {
        $servicios = array();

        // Servicios antes de bind
        foreach ($object->getAsignacion() as $s) {
            $servicios[] = $s;
        }

        $bag['asignacion'] = $servicios;

        return parent::preSubmit($object, $bag);
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador $object
     * @param array                                                         $bag
     *
     * @return array
     */
    protected function preFlush($object, $bag = array())
    {
        $servicios = $bag['asignacion'];

        /**
         * @var OrmPersistible $servicio
         * @var OrmPersistible $oServicio
         */
        foreach($object->getAsignacion() as $servicio) {
            foreach($servicios as $oKey => $oServicio) {
                if ($oServicio->getId() === $servicio->getId()) {
                    unset($servicios[$oKey]);
                }
            }
        }

        foreach ($servicios as $toDel) {
            $object->removeAsignacion($toDel);
            $this->getEm()->remove($toDel);
        }

        return parent::preFlush($object, $bag);
    }
}