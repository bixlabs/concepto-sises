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

namespace Concepto\Sises\ApplicationBundle\Features\Context;

use Behat\Behat\Tester\Exception\PendingException;

class ContratoContext extends RestContext
{

    /**
     * @Given un nuevo contrato
     */
    public function unNuevoContrato()
    {
        $this->newObject();
    }

    /**
     * @Then crea un nuevo contrato
     */
    public function creaUnNuevoContrato()
    {
        $this->post('api/contratos.json');
    }

    /**
     * @Then crea un nuevo contrato invalido
     */
    public function creaUnNuevoContratoInvalido()
    {
        $this->postInvalid('api/contratos.json');
    }

    /**
     * @Then actualiza el contrato
     */
    public function actualizaElContrato()
    {
        $this->patch('api/contratos/{id}.json', array('id' => $this->getObjectCreated()['id']));
    }

    /**
     * @Given que obtengo un listado de contratos
     */
    public function queObtengoUnListadoDeContratos()
    {
        $this->cget('api/contratos.json');
    }

    /**
     * @Then existe un contrato de :arg1 :arg2
     */
    public function existeUnObjetoDe($arg1, $arg2)
    {
        return parent::existeUnObjetoDe($arg1, $arg2);
    }


    /**
     * @Given la :arg1 del contrato :arg2
     * @Given el :arg1 del contrato :arg2
     */
    public function propiedadDelContrato($arg1, $arg2)
    {
        $this->setProp($arg1, $arg2);
    }

}