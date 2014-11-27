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

namespace Concepto\Sises\ApplicationBundle\Handler\Entrega;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacion;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EntregaLiquidacionRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega_liquidacion.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaLiquidacionRestHandler extends RestHandler
{
    protected function process(array $parameters, $object, $method = 'PUT')
    {
        if (!isset($parameters['estado'])) {
            $parameters['estado'] = Entrega::OPEN;
        }

        return parent::process($parameters, $object, $method);
    }


    public function calcularLiquidacion($id)
    {
        /** @var EntregaLiquidacion $liquidacion */
        $liquidacion = $this->get($id);

        if (!$liquidacion) {
            throw new NotFoundHttpException("No existe la liquidacion");
        }

        $results = $this->getEm()->getRepository('SisesApplicationBundle:Entrega\EntregaOperacion')
            ->calcularDetalle($liquidacion);

        return View::create($results);

        $result = array();

        /** @var EntregaOperacion $d */
        foreach($liquidacion->getDetalles() as $d) {
            $recurso = $d->getServicio()->getRecursoHumano();
            if (!isset($result[$recurso->getId()])) {
                $result[$recurso->getId()] = array(
                    'nombre' => $recurso->getPersona()->getNombreCompleto(),
                    'documento' => $recurso->getPersona()->getDocumento(),
                    'servicios' => array(),
                    'total' => array(),
                    'gran_total' => 0,
                );
            }

            if (!isset($result[$recurso->getId()]['total'][$d->getServicioId()])) {
                $result[$recurso->getId()]['total'][$d->getServicioId()] = array(
                    'servicio' => $d->getServicioId(),
                    'cant' => 0
                );
            }

            $result[$recurso->getId()]['total'][$d->getServicioId()]['cant'] += (int)$d->getCantidad();
            $result[$recurso->getId()]['gran_total'] += (int)$d->getCantidad() * (float)$d->getServicio()->getValorUnitario();

            $result[$recurso->getId()]['servicios'][] = array(
                'id' => $d->getServicioId(),
                'nombre' => $d->getServicio()->getNombre(),
                'cant' => $d->getCantidad(),
                'valor' => $d->getServicio()->getValorUnitario()
            );
        }

        return View::create(array_values($result));
    }

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Entrega\EntregaLiquidacionType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacion';
    }

    protected function getRouteName()
    {
        return 'get_liquidacion';
    }


}