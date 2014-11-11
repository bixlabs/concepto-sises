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

use Concepto\Sises\ApplicationBundle\Model\DashboardQuery;
use Concepto\Sises\ApplicationBundle\Model\Form\DashboardQueryType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DashboardRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="concepto_sises_dashboard.handler")
 */
class DashboardRestHandler {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactory
     */
    private $formfactory;

    /**
     * @param $em
     * @param $formfactory
     * @param $serializer
     *
     * @InjectParams({
     *  "em" = @Inject("doctrine.orm.default_entity_manager"),
     *  "formfactory" = @Inject("form.factory")
     * })
     */
    function __construct($em, $formfactory, $serializer)
    {
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->serializer = $serializer;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function calcule($parameters)
    {
        $query = new DashboardQuery();
        $form = $this->formfactory->create(new DashboardQueryType(), $query);
        $form->submit($parameters);

        if (!$form->isValid()) {
            throw new BadRequestHttpException();
        }

        list($dql, $params) = $this->getDQL($query);

        return $this->em->createQuery($dql)
            ->execute($params, Query::HYDRATE_ARRAY);
    }

    private function getDQL($object)
    {
        $maindql = <<<DQL
SELECT
    _s.id, _s.nombre as name, _eb.fechaEntrega as fecha, _d.estado, COUNT(_d) as total
FROM
    SisesApplicationBundle:Entrega\EntregaBeneficioDetalle _d
        LEFT JOIN _d.entregaBeneficio _eb
        LEFT JOIN _eb.servicio _s
        LEFT JOIN _eb.entrega _ea
        LEFT JOIN _ea.entrega _e
        LEFT JOIN _e.contrato _c
        LEFT JOIN _c.empresa  _ce
:WHERE:
GROUP BY _s.id, _eb.fechaEntrega, _d.estado
DQL;
        $accessor = PropertyAccess::createPropertyAccessor();

        $properties = array(
            'empresa' => '_ce = :empresa',
            'contrato' => '_e.contrato = :contrato',
            'start' => '_eb.fechaEntrega >= :start',
            'end' => '_eb.fechaEntrega <= :end',
            'estado' => '_d.estado = :estado'
        );

        $params = array();
        $queryWhere = array();

        foreach ($properties as $property => $dql) {
            if ($accessor->getValue($object, $property)) {
                $params[$property] = $accessor->getValue($object, $property);
                $queryWhere[] = $dql;
            }
        }

        $dql = str_replace(
            ':WHERE:',
            'WHERE ' . (count($queryWhere) > 0 ? implode(' AND ', $queryWhere) : ''),
            $maindql
        );

        return array($dql, $params);
    }
} 