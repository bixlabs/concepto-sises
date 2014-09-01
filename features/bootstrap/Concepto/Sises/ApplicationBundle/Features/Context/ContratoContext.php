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
     * @Given el contrato de nombre :arg1
     */
    public function elContratoDeNombre($arg1)
    {
        $this->setProp('nombre', $arg1);
    }

    /**
     * @Given la descripcion del contrato :arg1
     */
    public function laDescripcionDelContrato($arg1)
    {
        $this->setProp('descripcion', $arg1);
    }

    /**
     * @Given la resolucion del contrato :arg1
     */
    public function laResolucionDelContrato($arg1)
    {
        $this->setProp('resolucion', $arg1);
    }

    /**
     * @Given el valor :arg1
     */
    public function elValor($arg1)
    {
        $this->setProp('valor', $arg1);
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
}