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


use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

abstract class SubRestController extends RestController
{

    /**
     * @View(serializerGroups={"details"})
     * @QueryParam(name="extra", requirements="^list$")
     */
    public function getAction(ParamFetcher $paramFetcher, $parent, $id = null)
    {
        return parent::getAction($paramFetcher, $id);
    }

    /**
     * @View(serializerGroups={"list"}, serializerEnableMaxDepthChecks=true)
     * @QueryParam(name="page", requirements="\d+", default="1")
     * @QueryParam(name="limit", requirements="\d+", default="7")
     */
    public function cgetAction(Request $request, ParamFetcher $paramFetcher, $parent = null)
    {
        $request->query->add(array('_parent' => $parent));

        return parent::cgetAction($request, $paramFetcher);
    }

    public function postAction(Request $request, $parent = null)
    {
        return parent::postAction($request);
    }

    public function patchAction(Request $request, $parent, $id = null)
    {
        return parent::patchAction($request, $id);
    }

    public function putAction(Request $request, $parent, $id = null)
    {
        return parent::putAction($request, $id);
    }

    public function deleteAction($parent, $id = null)
    {
        return parent::deleteAction($id);
    }
}