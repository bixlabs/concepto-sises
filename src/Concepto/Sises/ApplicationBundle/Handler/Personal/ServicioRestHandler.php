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

namespace Concepto\Sises\ApplicationBundle\Handler\Personal;


use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use Concepto\Sises\ApplicationBundle\Model\Form\LiquidacionType;
use Concepto\Sises\ApplicationBundle\Model\Liquidacion;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ServicioRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Personal
 * @Service(id="concepto_sises_serv_operativo.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class ServicioRestHandler extends RestHandler
{
    public function liquidacion(Request $request, $id)
    {
        $servicio = $this->get($id);


        if (!$servicio) {
            throw new NotFoundHttpException("No existe el servicio '{$id}'");
        }

        $liquidacion = new Liquidacion();
        $form = $this->getFormfactory()->create(new LiquidacionType(), $liquidacion);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            foreach($liquidacion->getLiquidaciones() as $entregaOperativo) {
                $this->getEm()->persist($entregaOperativo);
            }

            $this->getEm()->flush();

            return View::create()->setStatusCode(Codes::HTTP_NO_CONTENT);
        }

        return View::create($form)->setStatusCode(Codes::HTTP_BAD_REQUEST);
    }

    protected function getTypeClassString()
    {
        return '';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\ServicioOperativo';
    }
}