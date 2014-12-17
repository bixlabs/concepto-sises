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

namespace Concepto\Sises\WebClientBundle\Controller;


use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebController {

    /**
     * @LookupMethod("templating")
     * @return TimedTwigEngine
     */
    public function getTemplating() {}

    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    public function viewAction($name)
    {
        $name = "SisesWebClientBundle:Partial:{$name}.html.twig";

        if ($this->getTemplating()->exists($name)) {
            return View::create()->setTemplate($name);
        }

        throw new NotFoundHttpException();
    }

    public function menuAction()
    {
        $modules = array(
            'ROLE_ADMIN' => array(
                'EMPRESA',
                'DASHBOARD',
                'CONTRATO',
                'BENEFICIARIO',
                'RRHH',
                'COORDINADOR',
                'DIRECTOR'
            ),
            'ROLE_DIRECTOR' => array(
                'DASHBOARD',
                'CONTRATO',
                'BENEFICIARIO',
                'RRHH',
                'COORDINADOR'
            ),
        );

        $granted_modules = [];

        if ($this->getSecurityContext()->isGranted('ROLE_ADMIN')) {
            $granted_modules = $modules['ROLE_ADMIN'];
        } else if ($this->getSecurityContext()->isGranted('ROLE_DIRECTOR')) {
            $granted_modules = $modules['ROLE_DIRECTOR'];
        }

        return View::create($granted_modules);
    }

    /**
     * @Template()
     */
    public function actionsAction($name, $singular, $plural = '')
    {
        $action_save = array(
            'icon' => 'save',
            'action' => 'save()',
            'label' => 'Guardar',
            'style' => 'primary',
            'test' => 'testSave()'
        );

        $action_list = array(
            'icon' => 'list',
            'action' => 'list()',
            'label' => "Volver al listado",
            'style' => 'default'
        );

        $action_delete = array(
            'icon' => 'remove-sign',
            'action' => 'remove()',
            'label' => 'Eliminar',
            'style' => 'danger',
            'test' => 'testRemove()'
        );

        $action_new = array(
            'icon' => 'plus-sign',
            'action' => 'add()',
            'label' => 'Agregar %singular%',
            'style' => 'primary'
        );

        $action_edit = array(
            'icon' => 'eye-open',
            'action' => 'details(' . strtolower($singular) . '.id)',
            'label' => 'Ver detalles',
            'style' => 'info'
        );

        $actions = array(
            'new' => array($action_list, $action_save),
            'update' => array($action_list, $action_save, $action_delete),
            'list' => array($action_new),
            'edit' => array($action_edit)
        );

        return array(
            'actions' => $actions[$name],
            'plural' => $plural,
            'singular' => $singular
        );
    }
}