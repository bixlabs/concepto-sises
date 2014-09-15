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

use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RestController
 * @package Concepto\Sises\ApplicationBundle\Controller
 */
abstract class RestController implements ClassResourceInterface
{
    /**
     * @return RestHandlerInterface
     */
    abstract public function getHandler();

    /**
     * @View(serializerGroups={"details"})
     * @QueryParam(name="extra", requirements="^list$")
     */
    public function getAction(ParamFetcher $paramFetcher, $id)
    {
        $object = $this->getHandler()->get($id);

        if (!$object) {
            throw new NotFoundHttpException("resource '{$id}' not found!");
        }

        if (!empty($paramFetcher->get('extra'))) {
            $view = \FOS\RestBundle\View\View::create($object);
            $context = SerializationContext::create();
            $context->setGroups(array('list'));

            $view->setSerializationContext($context);

            return $view;
        }

        return $object;

    }

    /**
     * @View(serializerGroups={"list"})
     * @QueryParam(name="page", requirements="\d+", default="1")
     * @QueryParam(name="limit", requirements="\d+", default="7")
     */
    public function cgetAction(Request $request, ParamFetcher $paramFetcher)
    {
        $params = array_intersect_assoc($paramFetcher->all(), $request->query->all());

        /** @var Pagerfanta $pager */
        $pager = $this->getHandler()->cget($paramFetcher->all(), array_diff_assoc($request->query->all(), $params));

        $view = \FOS\RestBundle\View\View::create(
            $pager->getCurrentPageResults(),
            Codes::HTTP_OK,
            array(
                'X-Current-Page' => $pager->getCurrentPage(),
                'X-Total-Count' => $pager->getNbResults(),
                'X-Total-Pages' => $pager->getNbPages(),
                'X-Per-Page' => $pager->getMaxPerPage()
            )
        );

        return $view;
    }

    public function postAction(Request $request)
    {
        return $this->getHandler()->post($request->request->all());
    }

    public function patchAction(Request $request, $id)
    {
        return $this->getHandler()->patch($id, $request->request->all());
    }

    public function putAction(Request $request, $id)
    {
        return $this->getHandler()->put($id, $request->request->all());
    }

    public function deleteAction($id)
    {
        return $this->getHandler()->delete($id);
    }
}