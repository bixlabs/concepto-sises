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
use Concepto\Sises\ApplicationBundle\Entity\Contrato;
use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Instantiator\Instantiator;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class ContratoRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_contrato.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class ContratoRestHandler extends RestHandler
{
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
     * @param array    $parameters
     * @param Contrato $object
     * @param string   $method
     *
     * @return View
     */
    protected function process(array $parameters, $object, $method = 'PUT')
    {
        $instantiator = new Instantiator();
        $type = $instantiator->instantiate($this->getTypeClassString());

        $servicios = array();

        // Servicios antes de bind
        foreach ($object->getServicios() as $s) {
            $servicios[] = $s;
        }

        $form = $this->getFormfactory()->create($type, $object);
        $form->submit($this->camelizeParamers($parameters), 'PATCH' !== $method);

        $name = explode('\\', $this->getOrmClassString());
        $url = 'get_' . strtolower(end($name));

        if ($form->isValid()) {
            $code = $object->getId() ? Codes::HTTP_NO_CONTENT : Codes::HTTP_CREATED;
            $this->getEm()->persist($object);

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

            $this->getEm()->flush();

            $view = View::createRedirect(
                $this->getRouter()->generate($url, array('id' => $object->getId())),
                $code
            );

            return $view;
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }
}