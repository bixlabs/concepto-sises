<?php
/**
 * The MIT License (MIT)
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the “Software”), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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

use Concepto\Sises\ApplicationBundle\EmpresaType;
use Concepto\Sises\ApplicationBundle\Entity\Empresa;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Router;

class EmpresaController implements ClassResourceInterface {

    /**
     * @var EntityManager
     * @Inject("doctrine.orm.default_entity_manager")
     */
    protected $em;

    /**
     * @var Router
     * @Inject("router")
     */
    protected $router;

    /**
     * @var FormFactoryInterface
     * @Inject("form.factory")
     */
    protected $formfactory;

    public function getAction($id)
    {
        $empresa = $this->em->getRepository('SisesApplicationBundle:Empresa')->find($id);

        return $empresa;
    }

    public function cgetAction()
    {
        $empresas = $this->em->getRepository('SisesApplicationBundle:Empresa')->findAll();

        return $empresas;
    }

    public function postAction(Request $request)
    {
        return $this->process($request->request->all(), new Empresa(), 'POST');
    }

    public function patchAction(Request $request, $id)
    {
        $empresa = $this->em->getRepository('SisesApplicationBundle:Empresa')->find($id);

        return $this->process($request->request->all(), $empresa, 'PATCH');
    }

    /**
     * @param array   $parameters
     * @param Empresa $object
     * @param string  $method
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @return View
     */
    private function process(array $parameters, $object = null, $method = 'PUT')
    {
        $form = $this->formfactory->create(new EmpresaType(), $object);
        $form->submit($parameters, 'PATCH' != $method);

        if (!$object->getId()) {
            $code = Codes::HTTP_CREATED;
        } else {
            $code = Codes::HTTP_NO_CONTENT;
        }

        if ($form->isValid()) {
            $this->em->persist($object);
            $this->em->flush();

            $view = View::createRedirect(
                $this->router->generate('get_empresa', array('id' => $object->getId())),
                $code
            );

            return $view;
        }

        throw new BadRequestHttpException($form->getErrors());
    }
}