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
use JMS\DiExtraBundle\Annotation\LookupMethod;
use Ps\PdfBundle\Annotation\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PDFController
 * @package Concepto\Sises\ApplicationBundle\Controller\PDF
 */
class PDFController extends Controller
{

    /**
     * @Pdf(stylesheet="SisesApplicationBundle:PDF\PDF:planilla_style.pdf.twig")
     * @return array
     */
    public function planillaAction($id)
    {
        $ea = $this->getEm()->getRepository('SisesApplicationBundle:Entrega\EntregaAsignacion')->find($id);

        if ($ea) {
            $asignacion = $ea->getAsignacion();
            $beneficios = $this->getEm()
                ->getRepository('SisesApplicationBundle:Beneficio')
                ->findAll(array('servicio' => $asignacion->getServicioId(), 'lugar' => $asignacion->getLugarId()));

            $date = new \DateTime();
            $start = new \DateTime($date->format('1-m-Y'));
            $end = new \DateTime($date->format('t-m-Y'));

            $days = array();

            while ($start <= $end) {
                $days[] = $start->format('d');
                $start->add(new \DateInterval('P1D'));
            }

            $response = new Response();
            $response->headers->set('Content-Type', 'application/pdf');

            return $this->render(sprintf('SisesApplicationBundle:PDF\PDF:planilla.pdf.twig'), [
                'per_page' => 20,
                'date' => $date,
                'days' => $days,
                'beneficios' => $beneficios
            ], $response);
        }

        throw new NotFoundHttpException("Entrega asignacion no encuentrada");
    }

    /**
     * @LookupMethod("doctrine.orm.default_entity_manager")
     * @return EntityManager
     */
    public function getEm() {}
} 