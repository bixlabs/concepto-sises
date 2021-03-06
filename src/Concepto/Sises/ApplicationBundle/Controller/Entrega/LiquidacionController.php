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

namespace Concepto\Sises\ApplicationBundle\Controller\Entrega;


use Concepto\Sises\ApplicationBundle\Controller\RestController;
use Concepto\Sises\ApplicationBundle\Handler\Entrega\EntregaLiquidacionRestHandler;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class LiquidacionController extends RestController
{

    /**
     * @return EntregaLiquidacionRestHandler
     * @LookupMethod("concepto_sises_entrega_liquidacion.handler")
     */
    public function getHandler() { }

    public function getDetallesAction($id)
    {
        return $this->getHandler()->calcularLiquidacion($id);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     * @Template()
     */
    public function getImpresionAction($id)
    {
        $view = $this->getDetallesAction($id);

        return array(
            'liquidacion' => $this->getHandler()->get($id),
            'data' => $view->getData()
        );
    }

    public function putCierreAction(Request $request)
    {
        return $this->getHandler()->realizarCierre($request->request->all());
    }

    public function getListadoAction()
    {
        return $this->getHandler()->getListado();
    }
}