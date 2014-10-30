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


use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficio;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use Concepto\Sises\ApplicationBundle\Model\EntregaBeneficioQuery;
use Concepto\Sises\ApplicationBundle\Model\Form\EntregaBeneficioQueryType;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;


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

        if (count($entregas) == 0) {
            $code = Codes::HTTP_CREATED;
            /** @var EntregaAsignacion $entregaAsignacion */
            $entregaAsignacion = $this->get($query->getId());
            $asignacion = $entregaAsignacion->getAsignacion();
            $beneficios = $this->getEm()
                ->getRepository('SisesApplicationBundle:Beneficio')
                ->findAll(array('servicio' => $asignacion->getServicioId(), 'lugar' => $asignacion->getLugarId()));

            foreach($beneficios as $beneficio) {
                $entrega = new EntregaBeneficio();
                $entrega->setBeneficio($beneficio);
                $entrega->setEntrega($entregaAsignacion);
                $entrega->setFechaEntrega($query->getFecha());
                $entrega->setIsEntregado(false);
                $this->getEm()->persist($entrega);
            }

            $this->getEm()->flush();

            $entregas = $repositoryEntrega->fechaEntrega($query);
        }

        return View::create($entregas, $code);
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