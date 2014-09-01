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