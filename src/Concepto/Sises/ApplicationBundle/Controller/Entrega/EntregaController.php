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
use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use JMS\DiExtraBundle\Annotation\LookupMethod;

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
}