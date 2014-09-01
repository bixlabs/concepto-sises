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

namespace Concepto\Sises\ApplicationBundle\Handler;


use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Instantiator\Instantiator;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;

/**
 * Class RestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler
 * @Service(id="conceptos_sises_abstract_rest.handler", abstract=true)
 */
abstract class RestHandler implements RestHandlerInterface {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FormFactory
     */
    private $formfactory;

    /**
     *
     * @InjectParams({
     *   "em" = @Inject("doctrine.orm.default_entity_manager"),
     *   "formfactory" = @Inject("form.factory"),
     *   "router" = @Inject("router")
     * })
     */
    function __construct($em, $formfactory, $router)
    {
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->router = $router;
    }

    abstract protected function  getTypeClassString();
    abstract protected function getOrmClassString();


    public function post($parameters)
    {
        $instantiator = new Instantiator();
        $object = $instantiator->instantiate($this->getOrmClassString());

        return $this->process($parameters, $object, 'POST');
    }

    public function put($id, $parameters)
    {
        /** @var OrmPersistible $object */
        $object = $this->em->find($this->getOrmClassString(), $id);

        return $this->process($parameters, $object, 'PUT');
    }

    public function patch($id, $parameters)
    {
        /** @var OrmPersistible $object */
        $object = $this->em->find($this->getOrmClassString(), $id);

        return $this->process($parameters, $object, 'PATCH');
    }

    public function delete($id)
    {
        $object = $this->em->find($this->getOrmClassString(), $id);

        if ($object) {
            $this->em->remove($object);
            $this->em->flush();
            return View::create(null, Codes::HTTP_NO_CONTENT);
        }

        throw new NotFoundHttpException("Object {$id} not found");
    }

    public function get($id)
    {
        return $this->em->find($this->getOrmClassString(), $id);
    }

    public function cget()
    {
        return $this->em->getRepository($this->getOrmClassString())->findAll();
    }


    /**
     * @param array  $parameters
     * @param OrmPersistible|null   $object
     * @param string $method
     *
     * @return View
     */
    protected function process(array $parameters, $object, $method = 'PUT')
    {
        $instantiator = new Instantiator();
        $type = $instantiator->instantiate($this->getTypeClassString());

        $form = $this->formfactory->create($type, $object);
        $form->submit($parameters, 'PATCH' != $method);

        $name = explode('\\', $this->getOrmClassString());
        $url = 'get_' . strtolower(end($name));

        if (!$object->getId()) {
            $code = Codes::HTTP_CREATED;
        } else {
            $code = Codes::HTTP_NO_CONTENT;
        }

        if ($form->isValid()) {
            $this->em->persist($object);
            $this->em->flush();

            $view = View::createRedirect(
                $this->router->generate($url, array('id' => $object->getId())),
                $code
            );

            return $view;
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }
}