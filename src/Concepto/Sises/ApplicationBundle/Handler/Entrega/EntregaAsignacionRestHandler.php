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

namespace Concepto\Sises\ApplicationBundle\Handler\Entrega;


use Concepto\Sises\ApplicationBundle\Entity\Beneficio;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficio;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficioDetalle;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use Concepto\Sises\ApplicationBundle\Model\EntregaBeneficioQuery;
use Concepto\Sises\ApplicationBundle\Model\EntregaRealizada;
use Concepto\Sises\ApplicationBundle\Model\Form\EntregaBeneficioQueryType;
use Concepto\Sises\ApplicationBundle\Model\Form\EntregaRealizadaType;
use Concepto\Sises\ApplicationBundle\Serializer\Exclusion\ListExclusionStrategy;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class EntregaAsignacionRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega_asignacion.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaAsignacionRestHandler extends RestHandler
{
    public function getOrCreateEntregaBeneficio($parameters)
    {
        $query = new EntregaBeneficioQuery();
        $form = $this->getFormfactory()->create(new EntregaBeneficioQueryType(), $query);

        $form->submit($parameters);

        if (!$form->isValid()) {
            return View::create($form, Codes::HTTP_BAD_REQUEST);
        }

        $repositoryEntrega = $this->getEm()->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficio');
        $entregas = $repositoryEntrega->fechaEntrega($query);
        $code = Codes::HTTP_OK;

        // Si no hay entregas no existe la EntregaBeneficio para el dia
        if (count($entregas) == 0) {
            $code = Codes::HTTP_CREATED;

            /** @var EntregaAsignacion $entregaAsignacion */
            $entregaAsignacion = $this->get($query->getId());

            if (!$entregaAsignacion) {
                throw new NotFoundHttpException("No existe la asignacion {$query->getId()}");
            }

            $entregaBeneficio = new EntregaBeneficio();
            $entregaBeneficio->setEntrega($entregaAsignacion);
            $entregaBeneficio->setServicio($entregaAsignacion->getAsignacion()->getServicio());
            $entregaBeneficio->setFechaEntrega($query->getFecha());

            $asignacion = $entregaAsignacion->getAsignacion();
            $beneficios = $this->getEm()
                ->getRepository('SisesApplicationBundle:Beneficio')
                ->findAll(array('servicio' => $asignacion->getServicioId(), 'lugar' => $asignacion->getLugarId()));

            /** @var Beneficio $beneficio */
            foreach($beneficios as $beneficio) {
                $entrega = new EntregaBeneficioDetalle();
                $entrega->setEntregaBeneficio($entregaBeneficio);
                $entrega->setBeneficiario($beneficio->getBeneficiario());
                $entrega->setEstado(false);
                $this->getEm()->persist($entrega);
            }

            $this->getEm()->persist($entregaBeneficio);

            $this->getEm()->flush();

            $entregas = $repositoryEntrega->fechaEntrega($query);
        }

        $view = View::create($entregas, $code);
        $context = SerializationContext::create();
        $context->enableMaxDepthChecks();

        $classes = array('Concepto\Sises\ApplicationBundle\Entity\Beneficiario' => array('beneficios'));
        //$classes = array();

        $context->addExclusionStrategy(new ListExclusionStrategy($classes));

        $view->setSerializationContext($context);

        return $view;
    }

    public function realizaEntrega($parameters)
    {
        $realizada = new EntregaRealizada();
        $form = $this->getFormfactory()->create(new EntregaRealizadaType(), $realizada);
        $form->submit($parameters);

        if ($form->isValid()) {
            $this->getEm()
                ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficio')
                ->realizarEntrega($realizada);

            return View::create()->setStatusCode(Codes::HTTP_NO_CONTENT);
        }

        return View::create($form)->setStatusCode(Codes::HTTP_BAD_REQUEST);
    }

    public function getDetalles($id)
    {
        /** @var EntregaBeneficio $entrega */
        $result = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaAsignacion')
            ->findBy(array('entrega' => $id));

        $fields = array(
            'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion' => array('realizadas')
        );

        $context = SerializationContext::create();
        $context->enableMaxDepthChecks();
        $context->addExclusionStrategy(new ListExclusionStrategy($fields));

        return View::create($result)->setSerializationContext($context);
    }

    protected function getTypeClassString()
    {
        return '';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion';
    }
}