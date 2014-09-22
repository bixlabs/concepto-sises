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

namespace Concepto\Sises\ApplicationBundle\Controller\Seguridad;

use Concepto\Sises\ApplicationBundle\Seguridad\ApiAuthenticator;
use Concepto\Sises\ApplicationBundle\Seguridad\Provider\UsuarioProvider;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\Request;

class LoginController {

    /**
     * @var UsuarioProvider
     * @Inject("concepto_sises_usuario.provider")
     */
    protected $up;

    /**
     * @View()
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postCheckAction(Request $request)
    {
        $username = $request->request->get('_user');
        $password = $request->request->get('_pass');

        $token = $this->up->validate($username, $password);

        $view = \FOS\RestBundle\View\View::createRedirect('/', Codes::HTTP_NO_CONTENT);
        $view->setHeader(ApiAuthenticator::API_HEADER, $token);

        return $view;
    }
}