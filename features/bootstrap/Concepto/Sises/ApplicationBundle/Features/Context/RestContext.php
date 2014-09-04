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


use Behat\Behat\Context\SnippetAcceptingContext;
use FOS\RestBundle\Util\Codes;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RestContext implements SnippetAcceptingContext
{
    private $object_created = array();

    private $objects = array();

    private $object = array();

    /**
     * @var Client
     */
    private $client;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accesor;

    /**
     * @var array
     */
    private $urls;

    function __construct()
    {
        $this->accesor = PropertyAccess::createPropertyAccessor();

        $this->client = new Client(array(
            'base_url' => 'http://concepto.sises/app_test.php/',
            'defaults' => array(
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                )
            ),
        ));

        $this->urls = array(
            'empresa' => array('api/empresas.json', 'api/empresas/{id}.json'),
            'contrato' => array('api/contratos.json', 'api/contratos/{id}.json'),
            'persona' => array('api/personas.json', 'api/personas/{id}.json'),
            'adjunto' => array('api/adjuntos.json', 'api/adjuntos/{id}.json'),
        );
    }

    /**
     * @Given una nueva :name
     * @Given un nuevo :name
     */
    public function newObject($name)
    {
        $this->object[$name] = array();
    }

    /**
     * @Given el :prop de la :name :value
     * @Given el :prop del :name :value
     * @Given la :prop del :name :value
     * @Given la :prop de la :name :value
     * @Given los :prop de la :name :value
     * @Given los :prop del :name :value
     */
    public function setProp($name, $prop, $value)
    {
        $this->accesor->setValue($this->object[$name], "[{$prop}]", $value);
    }

    /**
     * @Given la :arg1 del :arg2 obtenido de :arg3 en :arg4
     */
    public function laDelObtenidoDeEn($prop, $name, $name2, $prop2)
    {
        $this->setProp($name, $prop, $this->getObjectCreated($name2)[$prop2]);
    }

    /**
     * @Given el :arg1 del :arg2 obtenido del listado :arg3 :arg5 :arg4
     */
    public function elDelObtenidoDelListado($prop, $name, $name2, $index, $prop2)
    {
        $this->setProp($name, $prop, $this->getObjects($name2)[$index][$prop2]);
    }

    /**
     * @Then actualiza el :name
     * @Then actualiza la :name
     */
    public function actualizaEl($name)
    {
        $this->method($name, $this->urls[$name][1], 'patch', array(
            'id' => $this->getObjectCreated($name)['id']
        ));
    }


    /**
     * @Then crea un nuevo :name
     * @Then crea una nueva :name
     */
    public function post($name)
    {
        $this->method($name, $this->urls[$name][0]);
    }

    /**
     * @Then crea un nuevo :name invalido
     * @Then crea una nueva :name invalida
     */
    public function creaUnNuevoInvalido($name)
    {
        $this->postInvalid($name, $this->urls[$name][0]);
    }

    private function method($object, $url, $name = 'post', $args = null)
    {
        try {
            if (is_array($args)) {
                $url = array($url, $args);
            }
            switch ($name) {
                case 'post':
                    $response = $this->client->post($url, array('body' => $this->object[$object]));
                    break;
                case 'patch':
                    $response = $this->client->patch($url, array('body' => $this->object[$object]));
                    break;
                default:
                    throw new \Exception("Method isn't defined");
            }

            //Fetch recent created record
            $this->object_created[$object] = $this->client
                ->get($response->getHeader('location'))
                ->json();
        } catch (ClientException $e) {
            var_dump($e->getResponse()->json());
            \PHPUnit_Framework_TestCase::fail('CLIENT: ' . $e->getMessage());
        }
    }

    /**
     * @Given que obtengo un listado de :name
     */
    public function queObtengoUnListadoDe($name)
    {
        $this->cget($name, $this->urls[$name][0]);
    }

    /**
     * @Then existe un(a) :name de :prop :value
     */
    public function existeUnDe($name, $prop, $value)
    {
        $exist = false;

        foreach ($this->getObjects($name) as $obj) {
            if ($obj[$prop] == $value) {
                $exist = true;
                break;
            }
        }

        if (!$exist) {
            \PHPUnit_Framework_TestCase::fail("{$name} no existe");
        }
    }

    public function cget($name, $url)
    {
        $this->objects[$name] = $this->client->get($url)->json();
    }

    public function patch($name, $url, $args)
    {
        $this->method($name, $url, 'patch', $args);
    }

    public function put($name, $url)
    {
        $this->method($name, $url, 'put');
    }

    public function postInvalid($name, $url)
    {
        try {
            $this->client->post($url, array('body' => $this->object[$name]));
        } catch (ClientException $e) {
            \PHPUnit_Framework_TestCase::assertEquals(
                Codes::HTTP_BAD_REQUEST,
                $e->getResponse()->getStatusCode()
            );
        }
    }

    public function getObjects($name)
    {
        return $this->objects[$name];
    }

    public function getObject($name)
    {
        return $this->object[$name];
    }

    public function getObjectCreated($name)
    {
        return $this->object_created[$name];
    }

    public function existeUnObjetoDe($name, $arg1, $arg2)
    {
        foreach ($this->getObjects($name) as $objeto) {
            if ($objeto[$arg1] == $arg2) {
                return true;
            }
        }

        \PHPUnit_Framework_TestCase::fail("No existe {$arg1} == {$arg2}");

        return false;
    }
}