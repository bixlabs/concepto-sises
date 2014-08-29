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
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

class EmpresaContext implements SnippetAcceptingContext
{
    private $empresa = array();

    private $empresas = array();

    /**
     * @var Client
     */
    private $client;

    function __construct()
    {
        $this->client = new Client(array(
            'base_url' => 'http://concepto.sises/app_test.php/',
            'defaults' => array(
                //'exceptions' => false,
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                )
            ),
        ));
    }

    /**
     * @Then crea una nueva empresa
     */
    public function creaUnaNuevaEmpresa()
    {
        try {
            $response = $this->client->post('api/empresas.json', array('body' => $this->empresa));
            \PHPUnit_Framework_TestCase::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        } catch (ClientException $e) {
            \PHPUnit_Framework_TestCase::fail($e->getResponse()->getBody());
        }
    }

    /**
     * @Then crea una nueva empresa respuesta invalida
     */
    public function creaUnaNuevaEmpresaRespuestaInvalida()
    {
        try {
            $this->client->post('api/empresas', array('body' => $this->empresa));
        } catch (ClientException $e) {
            \PHPUnit_Framework_TestCase::assertEquals(
                Response::HTTP_BAD_REQUEST,
                $e->getResponse()->getStatusCode()
            );
        }
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
     * @Then existe una empresa de nombre :arg1
     */
    public function existeUnaEmpresa($arg1)
    {
        foreach ($this->empresas as $empresa) {
            if ($empresa['nombre'] == $arg1) {
                return;
            }
        }

        return new \Exception("La empresa no existe!");
    }

    /**
     * @Given que obtengo un listado de empresas
     */
    public function queObtengoUnListadoDeEmpresas()
    {
        $response = $this->client->get('api/empresas.json');
        $this->empresas = $response->getBody();
    }

    /**
     * @Then existe la empresa :arg1
     */
    public function existeLaEmpresa($arg1)
    {
        if ($this->empresas) {
            foreach($this->empresas as $empresa) {
                if ($empresa['nombre'] == $arg1) {
                    return;
                }
            }

            \PHPUnit_Framework_TestCase::fail('La empresa no existe en ' . $this->empresas);
        }

        \PHPUnit_Framework_TestCase::fail('No se obtuvieron empresas');
    }

    /**
     * @Then actualiza la empresa de nombre :arg1
     */
    public function actualizaLaEmpresaDeNombre($arg1)
    {

    }


}