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


use Behat\Behat\Context\SnippetAcceptingContext;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class EmpresaContext implements SnippetAcceptingContext
{
    private $empresa = array();

    /**
     * @var Client
     */
    private $client;

    function __construct()
    {
        $this->client = new Client(array(
            'base_url' => array('http://concepto.sises/app_test.php/', array()),
        ));
    }


    /**
     * @Then crea una nueva empresa
     */
    public function creaUnaNuevaEmpresa()
    {
        $response = $this->client->post('api/empresa', array('body' => $this->empresa));

        assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }


    private function setEmpresaProp($name, $value)
    {
        $this->empresa[$name] = $value;
    }

    /**
     * @Given una nueva empresa
     */
    public function unaNuevaEmpresa()
    {
        $this->empresa = array();
    }

    /**
     * @Given el nombre :arg1
     */
    public function elNombre($arg1)
    {
        $this->setEmpresaProp('nombre', $arg1);
    }

    /**
     * @Given el nit :arg1
     */
    public function elNit($arg1)
    {
        $this->setEmpresaProp('nit', $arg1);
    }

    /**
     * @Given el logo :arg1
     */
    public function elLogo($arg1)
    {
        $this->setEmpresaProp('logo', $arg1);
    }

    /**
     * @Given el telefono :arg1
     */
    public function elTelefono($arg1)
    {
        $this->setEmpresaProp('telefono', $arg1);
    }

    /**
     * @Given la dirección :arg1
     */
    public function laDireccion($arg1)
    {
        $this->setEmpresaProp('direccion', $arg1);
    }

    /**
     * @Given el correo electrónico :arg1
     */
    public function elCorreoElectronico($arg1)
    {
        $this->setEmpresaProp('email', $arg1);
    }

    /**
     * @Then existe una empresa :arg1
     */
    public function existeUnaEmpresa($arg1)
    {
        if (isset($this->empresa['nit']) && $this->empresa['nit'] == $arg1) {
            return true;
        }

        return new \Exception("La empresa no existe!");
    }

}