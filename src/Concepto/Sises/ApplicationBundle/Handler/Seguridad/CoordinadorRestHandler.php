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

namespace Concepto\Sises\ApplicationBundle\Handler\Seguridad;

use Concepto\Sises\ApplicationBundle\Model\Seguridad\Coordinador;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class CoordinadorRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Seguridad
 * @Service(id="concepto.sises.user_coordinador.handler", parent="concepto.sises.seguridad.handler")
 */
class CoordinadorRestHandler extends SeguridadRestHandler
{
    protected function process($parameters, $method = 'POST')
    {
        $object = new Coordinador();
        $form = $this->formFactory->create('usuario_coordinador', $object);

        $form->submit($parameters, $method != 'PATCH');

        if ($form->isValid()) {
            $this->userManager->findOrCreate($object->getUsername(), $object);

            return View::create(null, Codes::HTTP_NO_CONTENT);
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }
}