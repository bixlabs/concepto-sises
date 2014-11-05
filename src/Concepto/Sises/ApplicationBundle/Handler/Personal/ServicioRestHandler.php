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


use Concepto\Sises\ApplicationBundle\Form\Entrega\EntregaOperacionType;
use Concepto\Sises\ApplicationBundle\Form\ServicioOperativoType;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ServicioRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Personal
 * @Service(id="concepto_sises_serv_operativo.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class ServicioRestHandler extends RestHandler
{
    public function liquidacion($request, $id)
    {
        $servicio = $this->get($id);

        if (!$servicio) {
            throw new NotFoundHttpException("No existe el servicio '{$id}'");
        }

        $form = $this->getLiquidacionForm();
        $form->submit($request);

        if ($form->isValid()) {
            $data = $form->getData();
        }

        return View::create($form)->setStatusCode(Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function getLiquidacionForm()
    {
        return $this->getFormfactory()
            ->createNamedBuilder('liquidacion', 'form', null)
            ->add('liquidaciones', 'collection', array(
                'type' => new EntregaOperacionType()
            ))->getForm();
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