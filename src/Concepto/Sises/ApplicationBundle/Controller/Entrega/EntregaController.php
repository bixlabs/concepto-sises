<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 22/10/14
 * Time: 12:29 PM
 */

namespace Concepto\Sises\ApplicationBundle\Controller\Entrega;


use Concepto\Sises\ApplicationBundle\Controller\RestController;
use Concepto\Sises\ApplicationBundle\Handler\Entrega\EntregaRestHandler;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Symfony\Component\HttpFoundation\Request;

class EntregaController extends RestController
{

    /**
     * @return EntregaRestHandler
     * @LookupMethod("concepto_sises_entrega.handler")
     */
    public function getHandler() { }

    public function getCalcularAction($id)
    {
        return $this->getHandler()->getCalcular($id);
    }

    public function getCalcularDetalleAction($id)
    {
        return $this->getHandler()->getCalcularDetalle($id);
    }

    public function putCierreAction(Request $request)
    {
        return $this->getHandler()->realizarCierre($request->request->all());
    }
}