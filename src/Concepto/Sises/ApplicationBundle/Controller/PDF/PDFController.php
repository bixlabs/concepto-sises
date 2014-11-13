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

namespace Concepto\Sises\ApplicationBundle\Controller\PDF;


use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Ps\PdfBundle\Annotation\Pdf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PDFController
 * @package Concepto\Sises\ApplicationBundle\Controller\PDF
 */
class PDFController
{

    /**
     * @Pdf(
     *    stylesheet="SisesApplicationBundle:PDF\PDF:planilla_style.pdf.twig",
     *    enableCache=true,
     *    headers={"Content-Type":"application/pdf"}
     * )
     *
     * @param $id
     * @param $date
     *
     * @return array
     */
    public function planillaAction($id, $date = null)
    {
        $ea = $this->getEm()->getRepository('SisesApplicationBundle:Entrega\EntregaAsignacion')->find($id);

        if ($ea) {
            $asignacion = $ea->getAsignacion();
            $personas = $this->getEm()
                ->getRepository('SisesApplicationBundle:Beneficio')
                ->getPersonasDeAsignacion($asignacion);

            if ($date) {
                try {
                    $date = new \DateTime($date . '-1');
                } catch (\Exception $e) {
                    throw new NotFoundHttpException("Fecha no disponible");
                }
            } else {
                $date = new \DateTime();
            }

            $start = new \DateTime($date->format('1-m-Y'));
            $end = new \DateTime($date->format('t-m-Y'));

            $days = array();

            while ($start <= $end) {
                $days[] = $start->format('d');
                $start->add(new \DateInterval('P1D'));
            }

            return View::create(array(
                'contrato' => $asignacion->getServicio()->getContrato(),
                'lugar' => $asignacion->getLugar()->getNombre(),
                'ubicacion' => $asignacion->getLugar()->getUbicacion()->getNombreDetallado(),
                'servicio' => $asignacion->getServicio()->getNombre(),
                'per_page' => 25,
                'date' => $date,
                'days' => $days,
                'personas' => $personas
            ))->setTemplate('SisesApplicationBundle:PDF\PDF:planilla.pdf.twig');
        }

        throw new NotFoundHttpException("Entrega asignacion no encontrada");
    }

    /**
     * @LookupMethod("doctrine.orm.default_entity_manager")
     * @return EntityManager
     */
    public function getEm() {}
} 