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

class EmpresaContext extends RestContext
{
    /**
     * @Given una nueva empresa
     */
    public function unaNuevaEmpresa()
    {
        $this->newObject();
    }

    /**
     * @Then crea una nueva empresa
     */
    public function creaUnaNuevaEmpresa()
    {
        $this->post('api/empresas.json');
    }

    /**
     * @Then crea una nueva empresa respuesta invalida
     */
    public function creaUnaNuevaEmpresaRespuestaInvalida()
    {
        $this->postInvalid('api/empresas.json');
    }

    /**
     * @Given el :arg1 de la empresa :arg2
     */
    public function elDeLaEmpresa($arg1, $arg2)
    {
        $this->setProp($arg1, $arg2);
    }

    /**
     * @Given que obtengo un listado de empresas
     */
    public function queObtengoUnListadoDeEmpresas()
    {
        $this->cget('api/empresas.json');
    }

    /**
     * @Then actualiza la empresa
     */
    public function actualizaLaEmpresaDeNombre()
    {
        $this->patch('api/empresas/{id}.json', array('id' => $this->getObjectCreated()['id']));
    }

    /**
     * @Then existe una empresa de nombre :arg1
     */
    public function existeUnaEmpresaDeNombre($arg1)
    {
        foreach ($this->getObjects() as $empresa) {
            if ($empresa['nombre'] == $arg1) {
                return true;
            }
        }

        \PHPUnit_Framework_TestCase::fail("No existe la empresa {$arg1}");
    }
}