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
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;


/**
 * Class DirectorRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Personal
 * @Service(id="concepto_sises_director.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class DirectorRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Personal\DirectorType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Personal\Director';
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Personal\Director $object
     * @param array                                                      $bag
     * @param array                                                      $parameters
     *
     * @return array
     */
    protected function preSubmit($object, $bag = array(), $parameters = array())
    {
        $servicios = array();

        // Servicios antes de bind
        foreach ($object->getEmpresas() as $s) {
            $servicios[] = $s;
        }

        $bag['empresas'] = $servicios;
        if (isset($parameters['empresas'])) {
            $bag['empresas_parameters'] = $parameters['empresas'];
            unset($parameters['empresas']);
        }

        return parent::preSubmit($object, $bag, $parameters);
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Personal\Director $object
     * @param array                                                      $bag
     *
     * @return array
     */
    protected function preFlush($object, $bag = array())
    {
        $servicios = $bag['empresas'];

        /**
         * @var OrmPersistible $servicio
         * @var OrmPersistible $oServicio
         */
        foreach($object->getEmpresas() as $servicio) {
            foreach($servicios as $oKey => $oServicio) {
                if ($oServicio->getId() === $servicio->getId()) {
                    unset($servicios[$oKey]);
                }
            }
        }

        foreach ($servicios as $toDel) {
            $object->removeEmpresa($toDel);
        }

        $empresas = array_map(function($val) {
            return $val['id'];
        }, $bag['empresas_parameters']);

        $empresas = "A," . implode(';', $empresas);

        $empresas = $this->getEm()->getRepository('SisesApplicationBundle:Empresa')->findAll(array('id' => $empresas));

        foreach($empresas as $empresa) {
            $object->addEmpresa($empresa);
        }

        return parent::preFlush($object, $bag);
    }
}