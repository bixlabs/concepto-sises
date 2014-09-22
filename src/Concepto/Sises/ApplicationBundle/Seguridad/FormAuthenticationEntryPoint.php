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

namespace Concepto\Sises\ApplicationBundle\Seguridad;

use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Class FormAuthenticationEntryPoint
 * @package Concepto\Sises\ApplicationBundle\Seguridad
 * @Service(id="concepto_sises_form_auth.entry")
 */
class FormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new Response("", Response::HTTP_UNAUTHORIZED);

        return $response;
    }
}