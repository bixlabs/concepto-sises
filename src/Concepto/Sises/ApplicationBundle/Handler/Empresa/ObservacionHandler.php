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


use Concepto\Sises\ApplicationBundle\Entity\Entrega\Observacion;
use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ObservacionHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Empresa
 * @Service(id="concepto.sises.observacion.handler")
 */
class ObservacionHandler
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param $manager
     * @param $serializer
     *
     * @InjectParams({
        "manager" = @Inject("doctrine.orm.default_entity_manager"),
     *  "serializer" = @Inject("serializer")
     * })
     */
    function __construct($manager, $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @param object $entidad
     * @param string $contenido
     */
    public function store($entidad, $contenido)
    {
        if (!method_exists($entidad, 'getDetalles')) {
            throw new HttpException(400, "No el objeto no tiene detalles serializables");
        }

        $observacion = new Observacion();
        $observacion->setContenido($contenido);
        $observacion->setRelated($entidad->getId());

        $observacion->setLastState($this->serializer->serialize($entidad->getDetalles(), 'json'));

        $this->manager->persist($observacion);
    }

} 