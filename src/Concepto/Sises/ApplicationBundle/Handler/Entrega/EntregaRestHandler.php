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
use Concepto\Sises\ApplicationBundle\Handler\RestHandler;
use Concepto\Sises\ApplicationBundle\Model\EntregaCierre;
use Concepto\Sises\ApplicationBundle\Model\EntregaCierreServicio;
use Concepto\Sises\ApplicationBundle\Model\Form\EntregaCierreType;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EntregaRestHandler
 * @package Concepto\Sises\ApplicationBundle\Handler\Entrega
 * @Service(id="concepto_sises_entrega.handler", parent="conceptos_sises_abstract_rest.handler")
 */
class EntregaRestHandler extends RestHandler
{

    public function getCalcular($id)
    {
        $results = $this->getEm()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->calcularv2($id);

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

            $servicioRepo = $this->getEm()->getRepository('SisesApplicationBundle:ServicioContratado');

            /** @var EntregaCierreServicio $servicio */
            foreach($cierre->getServicios() as $servicio) {
                $servicioContratado = $servicioRepo->find($servicio->getId());
                if (!$servicioContratado) {
                    throw new NotFoundHttpException("El servicio '{$servicio->getId()}' no existe'");
                }

                $detalle = new EntregaDetalle();
                $detalle->setEntrega($entrega);
                $detalle->setServicio($servicioContratado);
                $detalle->setCantidad($servicio->getCantidad());
                $this->getEm()->persist($detalle);
            }

            $this->getEm()->persist($entrega);
            $this->getEm()->flush();

            return View::create()->setStatusCode(Codes::HTTP_NO_CONTENT);
        }

        return View::create($form)->setStatusCode(Codes::HTTP_BAD_REQUEST);
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