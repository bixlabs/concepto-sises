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
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SeguridadRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Seguridad
 * @Service(id="concepto.sises.seguridad.handler", abstract=true)
 */
abstract class SeguridadRestHandler
{
    /**
     * @var UserManipulator
     */
    protected $userManager;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param $userManager
     * @param $formFactory
     * @param $manager
     *
     * @InjectParams({
     *  "userManager" = @Inject("concepto.sises.user_manipulator"),
     *  "formFactory" = @Inject("form.factory"),
     *  "manager" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    function __construct($userManager, $formFactory, $manager)
    {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
    }

    public function post($parameters)
    {
        return $this->process($parameters, 'POST');
    }

    public function put($id, $parameters)
    {
        throw new HttpException(Codes::HTTP_NOT_IMPLEMENTED);
    }

    public function patch($id, $parameters)
    {
        throw new HttpException(Codes::HTTP_NOT_IMPLEMENTED);
    }

    public function delete($username)
    {
        $this->userManager->delete($username);

        return View::create(null, Codes::HTTP_NO_CONTENT);
    }

    public function get($id)
    {
        throw new HttpException(Codes::HTTP_NOT_IMPLEMENTED);
    }

    public function cget($pagerParams = array(), $extraParams = array())
    {
        throw new HttpException(Codes::HTTP_NOT_IMPLEMENTED);
    }

    abstract protected function process($parameters, $method = 'POST');
} 