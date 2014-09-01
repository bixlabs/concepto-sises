<?php
/**
 * The MIT License (MIT)
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the “Software”), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
}