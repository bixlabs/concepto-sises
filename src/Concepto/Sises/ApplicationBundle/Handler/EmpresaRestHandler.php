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
 * Class EmpresaRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_empresa.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EmpresaRestHandler extends RestHandler
{
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

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Empresa  $object
     * @param array                                             $bag
     *
     * @return array
     */
    protected function preSubmit($object, $bag = array())
    {
        $archivos = array();

        foreach ($object->getArchivos() as $s) {
            $archivos[] = $s;
        }

        $bag['archivos'] = $archivos;

        return parent::preSubmit($object, $bag);
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Empresa  $object
     * @param array                                             $bag
     *
     * @return array
     */
    protected function preFlush($object, $bag = array())
    {
        $archivos = $bag['archivos'];

        /**
         * @var OrmPersistible $archivo
         * @var OrmPersistible $oArchivo
         */
        foreach($object->getArchivos() as $archivo) {
            foreach($archivos as $oKey => $oArchivo) {
                if ($oArchivo->getId() === $archivo->getId()) {
                    unset($archivos[$oKey]);
                }
            }
        }

        foreach ($archivos as $toDel) {
            $object->removeArchivo($toDel);
            $this->getEm()->remove($toDel);
        }

        return parent::preFlush($object, $bag);
    }

}