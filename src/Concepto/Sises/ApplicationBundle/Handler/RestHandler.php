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

use Concepto\Sises\ApplicationBundle\Entity\EntityRepository;
use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use Concepto\Sises\ApplicationBundle\Entity\PersonaCargo;
use Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador;
use Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Monolog\Logger;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;

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
     * @var Logger
     */
    private $logger;

    /**
     * @var SecurityContext
     */
    private $security;

    /**
     * @var PersonaCargo
     */
    private $director;

    /**
     * @var Coordinador
     */
    private $coordinador;


    /**
     * @InjectParams({
     *   "em" = @Inject("doctrine.orm.default_entity_manager"),
     *   "formfactory" = @Inject("form.factory"),
     *   "router" = @Inject("router"),
     *   "logger" = @Inject("logger"),
     *   "security" = @Inject("security.context")
     * })
     *
     * @param $em
     * @param $formfactory
     * @param $router
     * @param $logger
     * @param $security
     */
    function __construct($em, $formfactory, $router, $logger, $security)
    {
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->router = $router;
        $this->logger = $logger;
        $this->security = $security;
    }

    abstract protected function getTypeClassString();
    abstract protected function getOrmClassString();


    public function post($parameters)
    {
        $class = $this->getOrmClassString();
        $object = new $class();

        return $this->process($parameters, $object, 'POST');
    }

    private function cleanIds(&$parameters)
    {
        if (is_array($parameters)) {
            if (isset($parameters['id'])) {
                unset($parameters['id']);
            }

            foreach ($parameters as $key => $parameter) {
                $this->cleanIds($parameters[$key]);
            }
        }
    }

    public function put($id, $parameters)
    {
        /** @var OrmPersistible $object */
        $object = $this->getEm()->find($this->getOrmClassString(), $id);

        // fix: los objetos de angular envian el id no es necesario
        $this->cleanIds($parameters);

        return $this->process($parameters, $object, 'PUT');
    }

    public function patch($id, $parameters)
    {
        /** @var OrmPersistible $object */
        $object = $this->getEm()->find($this->getOrmClassString(), $id);

        return $this->process($parameters, $object, 'PATCH');
    }

    public function delete($id)
    {
        try {
            $object = $this->getEm()->find($this->getOrmClassString(), $id);

            if ($object) {
                $this->getEm()->remove($object);
                $this->getEm()->flush();
                return View::create(null, Codes::HTTP_NO_CONTENT);
            }
        } catch (DBALException $e) {
            throw new ConflictHttpException(null, $e);
        }

        throw new NotFoundHttpException("Object {$id} not found");
    }

    public function get($id)
    {
        return $this->getEm()->find($this->getOrmClassString(), $id);
    }

    public function cget($pagerParams = array(), $extraParams = array())
    {
        /** @var EntityRepository $repository */
        $repository = $this->getEm()->getRepository($this->getOrmClassString());
        $qb = $repository->findAllQueryBuilder($extraParams);

        $pager = new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));

        if ($pagerParams['limit'] < 0) {
            $pagerParams['limit'] = 9999;
        }

        $pager->setMaxPerPage($pagerParams['limit']);
        $pager->setCurrentPage($pagerParams['page']);

        return $pager;
    }

    protected function getRouteName()
    {
        $name = explode('\\', $this->getOrmClassString());

        return 'get_' . strtolower(end($name));
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
        $class = $this->getTypeClassString();

        $type = class_exists($this->getTypeClassString()) ?
            new $class(): $this->getTypeClassString();

        $bag = array();

        list($object, $bag, $parameters) = $this->preSubmit($object, $bag, $parameters);

        $form = $this->formfactory->create($type, $object);
        $form->submit($this->camelizeParamers($parameters), 'PATCH' !== $method);

        $url = $this->getRouteName();

        /** @var OrmPersistible $object */
        if ($form->isValid()) {
            $code = $object->getId() ? Codes::HTTP_NO_CONTENT : Codes::HTTP_CREATED;
            list($object, ) = $this->preFlush($object, $bag);
            $this->getEm()->persist($object);
            $this->getEm()->flush();

            $view = View::createRedirect(
                $this->router->generate($url, array('id' => $object->getId())),
                $code
            );

            return $view;
        }

        $this->logger->error((string)$form->getErrors(false, true) . " PARAMETERS: " . json_encode($parameters));

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormfactory()
    {
        return $this->formfactory;
    }

    /**
     * @return \Symfony\Component\Routing\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    protected function camelizeParamers($parameters)
    {
        if (!is_array($parameters)) {
            return $parameters;
        }

        $camelizedParams = [];
        foreach (array_keys($parameters) as $key) {
            $camelizedParams[Inflector::camelize($key)] = $this->camelizeParamers($parameters[$key]);
        }

        return $camelizedParams;
    }

    protected  function preSubmit($object, $bag = array(), $parameters = array())
    {
        return array($object, $bag, $parameters);
    }

    protected  function preFlush($object, $bag = array())
    {
        return array($object, $bag);
    }

    /**
     * @return SecurityContext
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * @return Usuario
     */
    public function getUser()
    {
        return $this->getSecurity()->getToken()->getUser();
    }

    public function isDirector()
    {
        $user = $this->getUser();

        return $user->getTipo() === Usuario::DIRECTOR;
    }

    /**
     * @return PersonaCargo|Coordinador
     */
    public function getRelatedUser()
    {
        $user = $this->getUser();
        switch ($user->getTipo()) {
            case Usuario::DIRECTOR:
                $this->director = $this->getEm()
                    ->getRepository('SisesApplicationBundle:PersonaCargo')
                    ->find($user->getRelated());
                return $this->director;
            case Usuario::COORDINADOR:
                $this->coordinador = $this->getEm()
                    ->getRepository('SisesApplicationBundle:Personal\Coordinador')
                    ->find($user->getRelated());
                return $this->coordinador;
            default:
                return null;
        }
    }

    /**
     * @return PersonaCargo
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * @return Coordinador
     */
    public function getCoordinador()
    {
        return $this->coordinador;
    }
}