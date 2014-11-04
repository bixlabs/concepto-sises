<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 22/10/14
 * Time: 12:27 PM
 */

namespace Concepto\Sises\ApplicationBundle\Handler\Entrega;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class EntregaRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaRestHandler extends RestHandler
{

    public function getCalcular($id)
    {
        $results = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->calcular($id);

        return View::create(array('results' => $results));
    }

    public function getCalcularDetalle($id)
    {
        $results = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->calcularDetalle($id);

        return View::create(array('results' => $results));
    }

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Entrega\EntregaType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega';
    }
}