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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class WebController {

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

        return View::create()->setTemplate($name);
    }
}