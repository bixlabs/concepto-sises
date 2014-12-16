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

namespace Concepto\Sises\ApplicationBundle\Controller;


use Concepto\Sises\ApplicationBundle\Handler\DashboardRestHandler;
use Concepto\Sises\ApplicationBundle\Serializer\Exclusion\ListExclusionStrategy;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DashboardController
 * @package Concepto\Sises\ApplicationBundle\Controller
 */
class DashboardController implements ClassResourceInterface {
    /**
     * @LookupMethod("concepto_sises_dashboard.handler")
     * @return DashboardRestHandler
     */
    public function getHandler() {}

    public function getInfoRawAction(Request $request)
    {
        $results = $this->getHandler()->calcule($request->query->all());

        return View::create($results);
    }

    public function getInfoAction(Request $request) {

        $results = $this->getHandler()->calculec3($request->query->all());

        return View::create($results);
    }

    public function getMoreInfoAction(Request $request) {
        $results = $this->getHandler()->detallesc3($request->query->all());

        return View::create($results);
    }

    public function postInfoAction(Request $request)
    {
        $results = $this->getHandler()->calculec3($request->request->all());

        return View::create($results);
    }

    public function getFilterAction()
    {
        $classes = array(
            'Concepto\Sises\ApplicationBundle\Entity\ServicioContratado' => array(
                'contrato'
            ),
            'Concepto\Sises\ApplicationBundle\Entity\Empresa' => array(
                'encargado', 'director'
            )
        );

        $context = SerializationContext::create();
        $context->enableMaxDepthChecks();
        $context->setGroups(array('list'));
        $context->addExclusionStrategy(new ListExclusionStrategy($classes));

        return View::create($this->getHandler()->filters())
            ->setSerializationContext($context);
    }
} 