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
use JMS\Serializer\Serializer;
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
     * @var Serializer
     */
    private $serializer;

    /**
     * @var DashboardQuery
     */
    private $query;

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
        $this->query = new DashboardQuery();
        $form = $this->formfactory->create(new DashboardQueryType(), $this->query);
        $form->submit($parameters);

        if (!$form->isValid()) {
            throw new BadRequestHttpException();
        }

        $now = new \DateTime();
        $start = $this->query->getStart();
        $end = $this->query->getEnd();

        if (!$end && !$start) {
            $end = $now;
            $start = clone $end;
            $start->sub(new \DateInterval('P1M'));
        } else if ($end && !$start) {
            $start = clone $end;
            $start->sub(new \DateInterval('P1M'));
        } else if ($start && !$end) {
            $end = clone $start;
            $end->add(new \DateInterval('P1M'));
            if ($end > $now) {
                $end = $now;
            }
        }

        $this->query->setStart($start);
        $this->query->setEnd($end);


        list($dql, $params) = $this->getDQL();

        return $this->em->createQuery($dql)
            ->execute($params, Query::HYDRATE_ARRAY);
    }

    public function calculec3($parameters)
    {
        $results = $this->calcule($parameters);

        $columns = array(
            // El primer valor siempre es la fecha
            'fecha' => array('fecha')
        );

        $names = array();

        $now = new \DateTime();

        $start = $this->query->getStart();
        $end = $this->query->getEnd();

        while ($start <= $end) {
            $columns['fecha'][] = clone $start;
            $start->add(new \DateInterval('P1D'));
        }

        foreach ($results as $result) {
            // Buscamos el indice de la fecha
            $dateIdx = array_search($result['fecha'], $columns['fecha']);

            if (!isset($columns[$result['id']])) {
                // El primer valor del array es el id
                $columns[$result['id']] = array($result['id']);
                // Nombre para la traduccion
                $names[$result['id']] = $result['nombre'];
            }

            // El valor debe ir en el mismo lugar que la fecha a la que pertenece
            $columns[$result['id']][$dateIdx] = (int)$result['total'];
        }

        // Se asegura de colocar zero donde sea necesario
        foreach (array_keys($columns) as $id) {
            // se ignora las fechas
            if ($id === 'fecha') {
                continue;
            }

            foreach (array_keys($columns['fecha']) as $index) {
                if (!isset($columns[$id][$index])) {
                    $columns[$id][$index] = 0;
                }
            }

            // Las llaves fueron agregadas en desorden, se organizan
            ksort($columns[$id]);
        }

        return array(
            'columns' => array_values($columns),
            'names' => $names
        );
    }

    private function getDQL()
    {
        $maindql = <<<DQL
SELECT
    _s.id, _s.nombre, _eb.fechaEntrega as fecha, COUNT(_d) as total
FROM
    SisesApplicationBundle:Entrega\EntregaBeneficioDetalle _d
        LEFT JOIN _d.entregaBeneficio _eb
        LEFT JOIN _eb.servicio _s
        LEFT JOIN _eb.entrega _ea
        LEFT JOIN _ea.entrega _e
        LEFT JOIN _e.contrato _c
        LEFT JOIN _c.empresa  _ce
:WHERE:
GROUP BY _s.id, _eb.fechaEntrega
ORDER BY _eb.fechaEntrega ASC
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
            if ($accessor->getValue($this->query, $property)) {
                $params[$property] = $accessor->getValue($this->query, $property);
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