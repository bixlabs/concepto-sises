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

namespace Concepto\Sises\ApplicationBundle\Archivos;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class ManagerService
 * @package Concepto\Sises\ApplicationBundle\Archivos
 * @Service(id="concepto_sise_archivos.manager")
 */
final class ManagerService {

    const DOCUMENTABLE = 'Concepto\Sises\ApplicationBundle\Archivos\Annotations\Documentable';
    const ARCHIVO = 'Concepto\Sises\ApplicationBundle\Archivos\Annotations\Archivo';

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @InjectParams({
     *      "em" = @Inject("doctrine.orm.default_entity_manager"),
     *      "reader" = @Inject("annotation_reader")
     * })
     */
    function __construct($em, $reader)
    {
        $this->em = $em;
        $this->reader = $reader;
    }

    /**
     * @param mixed $documentable
     */
    public function proccess($documentable)
    {
        $refObj = new \ReflectionObject($documentable);

        $documentableAnnotation = $this->reader->getClassAnnotation(
            $refObj, self::DOCUMENTABLE);

        // no hacer nada
        if (is_null($documentableAnnotation)) {
            return;
        }

        foreach($refObj->getProperties() as $refProp) {
            $archivoAnnotation = $this->reader->getPropertyAnnotation($refProp, self::ARCHIVO);

            if (is_null($archivoAnnotation)) {
                continue;
            }
        }
    }
}