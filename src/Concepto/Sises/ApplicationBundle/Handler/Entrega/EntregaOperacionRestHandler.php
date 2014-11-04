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


use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EntregaOperacionRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega_operacion.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaOperacionRestHandler extends RestHandler
{

    protected function getTypeClassString()
    {
        return '';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion';
    }

    /**
     * @param string $id uuid de la entrega
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getParaEntrega($id)
    {
        $entrega = $this->getEm()->find('SisesApplicationBundle:Entrega\Entrega', $id);

        if (!$entrega) {
            throw new NotFoundHttpException("Entrega '{$id}' no existe");
        }

        $operaciones = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaOperacion')
            ->getByEntrega($entrega);

        return View::create($operaciones);
    }
}