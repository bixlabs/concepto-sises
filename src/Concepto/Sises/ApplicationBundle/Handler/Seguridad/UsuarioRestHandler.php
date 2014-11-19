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


use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class UsuarioRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Seguridad
 * @Service(id="concepto.sises.user.handler")
 */
class UsuarioRestHandler {

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @param $manager
     * @InjectParams({"manager" = @Inject("doctrine.orm.entity_manager")})
     */
    function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function getByRelated($id)
    {
        $usuario = $this->manager
            ->getRepository('SisesApplicationBundle:Seguridad\Usuario')
            ->findOneBy(array(
                'related' => $id,
            ));
        $data = null;
        $code = Codes::HTTP_NO_CONTENT;

        if ($usuario) {
            $data = array(
                'username' => $usuario->getUsername(),
                'email' => $usuario->getEmail()
            );
            $code = COdes::HTTP_OK;
        }

        return View::create($data)->setStatusCode($code);
    }
}