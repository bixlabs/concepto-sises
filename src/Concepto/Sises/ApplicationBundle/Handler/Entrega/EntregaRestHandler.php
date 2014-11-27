<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 22/10/14
 * Time: 12:27 PM
 */

namespace Concepto\Sises\ApplicationBundle\Handler\Entrega;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaDetalle;
use Concepto\Sises\ApplicationBundle\Handler\ContratoRestHandler;
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use Concepto\Sises\ApplicationBundle\Model\EntregaCierre;
use Concepto\Sises\ApplicationBundle\Model\EntregaCierreServicio;
use Concepto\Sises\ApplicationBundle\Model\Form\EntregaCierreType;
use Concepto\Sises\ApplicationBundle\Utils;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EntregaRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaRestHandler extends RestHandler
{

    /**
     * @var ContratoRestHandler
     */
    private $contrato;

    /**
     * @param ContratoRestHandler $contrato
     *
     * @InjectParams({"contrato" = @Inject("concepto_sises_contrato.handler")})
     * @return ContratoRestHandler
     */
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;
    }


    public function cget($pagerParams, $extraParams = array())
    {
        if ($this->isDirector()) {
            $contratos = $this->contrato->cget(array());
            $extraParams['contrato'] = Utils\Collection::buildQuery($contratos);
        }

        return parent::cget($pagerParams, $extraParams);
    }

    public function getCalcular($id)
    {
        $entrega = $this->get($id);

        if (!$entrega) {
            throw new NotFoundHttpException("Entrega no existe");
        }

        $results = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->calcular($id);

        return View::create(array('results' => $results));
    }

    public function getCalcularDetalle($id)
    {
        $results = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->calcularDetalle($id);

        return View::create(array('results' => $results));
    }

    public function realizarCierre($parameters)
    {
        $cierre = new EntregaCierre();
        $form = $this->getFormfactory()->create(new EntregaCierreType(), $cierre);
        $form->submit($parameters);

        if ($form->isValid()) {
            /** @var Entrega $entrega */
            $entrega = $this->get($cierre->getId());

            if (!$entrega) {
                throw new NotFoundHttpException("La entrega {$cierre->getId()} no existe");
            }

            $entrega->setEstado(Entrega::CLOSE);

            /** @var EntregaCierreServicio $servicio */
            foreach($cierre->getServicios() as $servicio) {
                $this->createOrUpdateDetalle($entrega, $servicio);
            }

            $this->getEm()->persist($entrega);
            $this->getEm()->flush();

            return View::create()->setStatusCode(Codes::HTTP_NO_CONTENT);
        }

        return View::create($form)->setStatusCode(Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @param Entrega $entrega
     * @param EntregaCierreServicio $servicio
     */
    public function createOrUpdateDetalle($entrega, EntregaCierreServicio $servicio)
    {
        $servicioRepo = $this->getEm()->getRepository('SisesApplicationBundle:ServicioContratado');


        $detalle = null;
        $servicioContratado = null;

        /** @var EntregaDetalle $entregaDetalle */
        foreach($entrega->getDetalles() as $entregaDetalle) {
            if ($entregaDetalle->getServicio()->getId() == $servicio->getServicio()) {
                $detalle = $entregaDetalle;
                $servicioContratado = $entregaDetalle->getServicio();
            }
        }

        if (!$detalle) {
            $detalle = new EntregaDetalle();
            $detalle->setEntrega($entrega);
            $servicioContratado = $servicioRepo->find($servicio->getServicio());
            if (!$servicioContratado) {
                throw new NotFoundHttpException("El servicio '{$servicio->getServicio()}' no existe'");
            }
        }

        $detalle->setServicio($servicioContratado);
        $detalle->setCantidad($servicio->getCantidad());
        $this->getEm()->persist($detalle);
    }

    public function getDetalles($id)
    {
        /** @var Entrega $entrega */
        $entrega = $this->get($id);

        if (!$entrega) {
            throw new NotFoundHttpException("La entrega {$id} no existe");
        }

        return View::create($entrega->getDetalles());
    }

    protected function getTypeClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Form\Entrega\EntregaType';
    }

    protected function getOrmClassString()
    {
        return 'Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega';
    }
}