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
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacionDetalle;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacionRepository;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion;
use Concepto\Sises\ApplicationBundle\Entity\ServicioOperativo;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use Concepto\Sises\ApplicationBundle\Model\Entrega\Liquidacion\Cierre;
use Concepto\Sises\ApplicationBundle\Model\Entrega\Liquidacion\CierreDetalle;
use Concepto\Sises\ApplicationBundle\Model\Form\Entrega\Liquidacion\CierreType;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EntregaLiquidacionRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega_liquidacion.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaLiquidacionRestHandler extends RestHandler
{
    public function cget($pagerParams = array(), $extraParams = array())
    {
        if (!$this->getSecurity()->isGranted('ROLE_DIRECTOR')) {
            $extraParams[EntregaLiquidacionRepository::ONLY_OPEN] = true;
        }

        return parent::cget($pagerParams, $extraParams);
    }

    public function realizarCierre($parameters)
    {
        $cierre = new Cierre();
        $form = $this->getFormfactory()->create(new CierreType(), $cierre);
        $form->submit($parameters);

        if ($form->isValid()) {
            /** @var EntregaLiquidacion $liquidacion */
            $liquidacion = $this->get($cierre->getLiquidacion());

            if (!$liquidacion) {
                throw new NotFoundHttpException("La liquidacion {$cierre->getLiquidacion()} no existe");
            }

            $liquidacion->setEstado(Entrega::CLOSE);

            /** @var CierreDetalle $servicio */
            foreach ($cierre->getServicios() as $servicio) {
                $this->createOrUpdateDetalle($liquidacion, $servicio);
            }

            $this->getEm()->persist($liquidacion);
            $this->getEm()->flush();

            return View::create()->setStatusCode(Codes::HTTP_NO_CONTENT);
        }

        return View::create($form)->setStatusCode(Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @param EntregaLiquidacion $liquidacion
     * @param CierreDetalle $servicio
     */
    public function createOrUpdateDetalle($liquidacion, CierreDetalle $servicio)
    {
        $servicioRepo = $this->getEm()->getRepository('SisesApplicationBundle:ServicioOperativo');

        $detalle = null;
        $operativo = null;

        /** @var EntregaLiquidacionDetalle $liquidacionDetalle */
        foreach ($liquidacion->getDetalles() as $liquidacionDetalle) {
            if ($liquidacionDetalle->getServicioId() == $servicio->getServicio()) {
                $detalle = $liquidacionDetalle;
            }
        }

        if (!$detalle) {
            $detalle = new EntregaLiquidacionDetalle();
            $detalle->setLiquidacion($liquidacion);

            $operativo = $servicioRepo->find($servicio->getServicio());

            if (!$operativo) {
                throw new NotFoundHttpException("El servicio '{$servicio->getServicio()}' no existe'");
            }

            $detalle->setServicio($operativo);
        }

        $detalle->setCantidad($servicio->getCantidad());
        $this->getEm()->persist($detalle);
    }

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

        switch ($liquidacion->getEstado()) {
            case Entrega::CLOSE:
                $repository = $this->getEm()->getRepository('SisesApplicationBundle:Entrega\EntregaLiquidacionDetalle');
                break;
            case Entrega::OPEN:
                $repository = $this->getEm()->getRepository('SisesApplicationBundle:Entrega\EntregaOperacion');
                break;
            default:
                throw new \Exception("El estado {$liquidacion->getEstado()} no es manejable");
        }

        $results =$repository->calcularDetalle($liquidacion);

        return View::create($results);
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