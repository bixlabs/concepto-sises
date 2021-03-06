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

use Concepto\Sises\ApplicationBundle\Model\Dashboard\LiquidacionQuery;
use Concepto\Sises\ApplicationBundle\Model\Dashboard\SubQuery;
use Concepto\Sises\ApplicationBundle\Model\DashboardQuery;
use Concepto\Sises\ApplicationBundle\Model\Form\Dashboard\LiquidacionQueryType;
use Concepto\Sises\ApplicationBundle\Model\Form\Dashboard\SubQueryType;
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
use Concepto\Sises\ApplicationBundle\Utils\C3;

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

    private function rangeTime(&$query)
    {
        $now = new \DateTime();
        $now->setTime(0, 0, 0); // Inicializa al principio del dia
        $start = $query->getStart();
        $end = $query->getEnd();

        if (!$end && !$start) {
            $end = clone $now;
            $start = clone $end;
            $start->sub(new \DateInterval('P1M'));
        } else if ($end && !$start) {
            $start = clone $end;
            $start->sub(new \DateInterval('P1M'));
        } else if ($start && !$end) {
            $end = clone $start;
            $end->add(new \DateInterval('P1M'));
        }

        $query->setStart($start);
        $query->setEnd($end);
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

        $this->rangeTime($this->query);

        list($dql, $params) = $this->getDQL();

        return $this->em->createQuery($dql)
            ->execute($params, Query::HYDRATE_ARRAY);
    }

    public function calculec3($parameters)
    {
        $results = $this->calcule($parameters);

        return C3\Utils::calculec3('fecha', $results, $this->query);
    }

    private function getDQL()
    {
        $maindql = <<<DQL
SELECT
    _s.id, _s.nombre, DATE(_eb.fechaEntrega) as fecha, COUNT(_d) as total
FROM
    SisesApplicationBundle:Entrega\EntregaBeneficioDetalle _d
        LEFT JOIN _d.entregaBeneficio _eb
        LEFT JOIN _eb.servicio _s
        LEFT JOIN _eb.entrega _ea
        LEFT JOIN _ea.entrega _e
        LEFT JOIN _ea.asignacion _a
        LEFT JOIN _a.lugar _l
        LEFT JOIN _e.contrato _c
        LEFT JOIN _c.empresa  _ce
:WHERE:
GROUP BY _s.id, _eb.fechaEntrega
ORDER BY _eb.fechaEntrega ASC
DQL;

        $properties = array(
            'empresa' => '_ce = :empresa',
            'contrato' => '_e.contrato = :contrato',
            'start' => '_eb.fechaEntrega >= :start',
            'end' => '_eb.fechaEntrega <= :end',
            'estado' => '_d.estado = :estado',
            'servicio' => '_s = :servicio',
            'lugar' => '_l = :lugar',
        );

        return $this->processConditions($this->query, $properties, $maindql);
    }

    /**
     * @param SubQuery $subquery
     * @return array
     */
    private function getSubDQL($subquery) {
        $dql = <<<DQL
SELECT
    _s.id,
    _s.nombre,
    CONCAT(CONCAT(CONCAT(CONCAT(_l.nombre, ' - '), _um.nombre), ', '), _ud.nombre) as lugar,
    COUNT(_d) as total
FROM
    SisesApplicationBundle:Entrega\EntregaBeneficioDetalle _d
        LEFT JOIN _d.entregaBeneficio _eb
        LEFT JOIN _eb.servicio _s
        LEFT JOIN _eb.entrega _ea
        LEFT JOIN _ea.asignacion _a
        LEFT JOIN _a.lugar _l
        LEFT JOIN _l.ubicacion _u
        LEFT JOIN _u.municipio _um
        LEFT JOIN _um.departamento _ud
WHERE _eb.fechaEntrega = :fecha
AND _d.estado = :estado
GROUP BY _s.id, lugar
ORDER BY lugar ASC
DQL;

        return array($dql, array(
            'fecha' => $subquery->getFecha()->setTime(0, 0, 0),
            'estado' => true
        ));
    }

    public function detallesc3($parameters)
    {
        $results = $this->detalles($parameters);

        return C3\Utils::calculec3('lugar', $results, $parameters);
    }

    public function detalles($parameters)
    {
        $subquery = new SubQuery();
        $form = $this->formfactory->create(new SubQueryType(), $subquery);
        $form->submit($parameters);

        if (!$form->isValid()) {
            throw new BadRequestHttpException();
        }

        list ($dql, $params) = $this->getSubDQL($subquery);

        return $this->em->createQuery($dql)
            ->execute($params, Query::HYDRATE_ARRAY);
    }

    public function filters()
    {
        $asignaciones = $this->em
            ->getRepository('SisesApplicationBundle:CoordinadorAsignacion')
            ->findAll();

        $lugares = array();
        $servicios = array();
        $empresas = array();

        foreach ($asignaciones as $asignacion) {
            $lugares[$asignacion->getLugarId()] = $asignacion->getLugar();
            $servicios[$asignacion->getServicioId()] = $asignacion->getServicio();
            $empresa = $asignacion->getServicio()->getContrato()->getEmpresa();
            $empresas[$empresa->getId()] = $empresa;

        }

        return [
            'empresas' => array_values($empresas),
            'lugares' => array_values($lugares),
            'servicios' => array_values($servicios)
        ];
    }

    public function getFilterLiquidacion()
    {
        $servicios = $this->em
            ->getRepository('SisesApplicationBundle:ServicioOperativo')
            ->findAll();

        $lugares = array();
        $recursos = array();
        $empresas = array();

        foreach ($servicios as $servicio) {
            $lugares[$servicio->getLugarId()] = $servicio->getLugar();
            $recursos[$servicio->getRecursoHumano()->getId()] = $servicio->getRecursoHumano();
            $empresa = $servicio->getRecursoHumano()->getContrato()->getEmpresa();

            $empresas[$empresa->getId()] = $empresa;
        }

        return array(
            'lugares' => array_values($lugares),
            'recursos' => array_values($recursos),
            'empresas' => array_values($empresas),
        );
    }

    public function calculec3Liquidacion($parameters)
    {
        $query = new LiquidacionQuery();
        $form = $this->formfactory->create(new LiquidacionQueryType(), $query);
        $form->submit($parameters);

        if (!$form->isValid()) {
            throw new BadRequestHttpException();
        }

        $this->rangeTime($query);

        list ($dql, $params) = $this->getLiquidacionDQL($query);

        $results = $this->em->createQuery($dql)
            ->execute($params, Query::HYDRATE_ARRAY);

        return C3\Utils::calculec3('fecha', $results, $query);
    }

    private function getLiquidacionDQL($query)
    {

        $maindql = <<<DQL
SELECT
    _s.id, CONCAT(_s.nombre, CONCAT(' - ', CONCAT(_cargo.nombre, CONCAT(', ', CONCAT(_p.nombre, CONCAT(' ', _p.apellidos)))))) as nombre, DATE(_eo.fechaEntrega) as fecha, _eo.cantidad as total
FROM
    SisesApplicationBundle:Entrega\EntregaOperacion _eo
        LEFT JOIN _eo.servicio _s
        LEFT JOIN _s.recursoHumano _rh
        LEFT JOIN _rh.cargo _cargo
        LEFT JOIN _rh.persona _p
        LEFT JOIN _s.lugar _l
        LEFT JOIN _eo.liquidacion _li
        LEFT JOIN _li.contrato _c
        LEFT JOIN _c.empresa _e
:WHERE:
ORDER BY _eo.fechaEntrega ASC
DQL;

        $properties = array(
            'empresa' => '_e = :empresa',
            'recurso' => '_rh = :recurso',
            'start' => '_eo.fechaEntrega >= :start',
            'end' => '_eo.fechaEntrega <= :end',
            'lugar' => '_l = :lugar',
        );

        return $this->processConditions($query, $properties, $maindql);
    }

    private function processConditions($query, $properties, $maindql)
    {
        $params = array();
        $queryWhere = array();

        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($properties as $property => $dql) {
            if ($accessor->getValue($query, $property)) {
                $params[$property] = $accessor->getValue($query, $property);
                $queryWhere[] = $dql;
            }
        }

        $maindql = str_replace(
            ':WHERE:',
            'WHERE ' . (count($queryWhere) > 0 ? implode(' AND ', $queryWhere) : ''),
            $maindql
        );

        return array($maindql, $params);
    }
} 