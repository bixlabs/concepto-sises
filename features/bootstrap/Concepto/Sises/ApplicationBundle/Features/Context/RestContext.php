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
    private $object_created;

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
    }

    public function newObject()
    {
        $this->object = array();
    }

    public function setProp($prop, $value)
    {
        $this->accesor->setValue($this->object, "[{$prop}]", $value);
    }

    public function post($url)
    {
        $this->method($url);
    }

    private function method($url, $name = 'post', $args = null)
    {
        try {
            if (is_array($args)) {
                $url = array($url, $args);
            }
            switch ($name) {
                case 'post':
                    $response = $this->client->post($url, array('body' => $this->object));
                    break;
                case 'patch':
                    $response = $this->client->patch($url, array('body' => $this->object));
                    break;
                default:
                    throw new \Exception("Method isn't defined");
            }

            //Fetch recent created record
            $this->object_created = $this->client
                ->get($response->getHeader('location'))
                ->json();
        } catch (ClientException $e) {
            var_dump($e->getResponse()->json());
            \PHPUnit_Framework_TestCase::fail($e->getMessage());
        } catch (ServerException $e) {
            \PHPUnit_Framework_TestCase::fail($e->getResponse()->json()[0]['message']);
        }
    }

    public function cget($url)
    {
        $this->objects = $this->client->get($url)->json();
    }

    public function patch($url, $args)
    {
        $this->method($url, 'patch', $args);
    }

    public function put($url)
    {
        $this->method($url, 'put');
    }

    public function postInvalid($url)
    {
        try {
            $this->client->post($url, array('body' => $this->object));
        } catch (ClientException $e) {
            \PHPUnit_Framework_TestCase::assertEquals(
                Codes::HTTP_BAD_REQUEST,
                $e->getResponse()->getStatusCode()
            );
        }
    }

    /**
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @return array
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return mixed
     */
    public function getObjectCreated()
    {
        return $this->object_created;
    }

    public function existeUnObjetoDe($arg1, $arg2)
    {
        foreach ($this->getObjects() as $objeto) {
            if ($objeto[$arg1] == $arg2) {
                return true;
            }
        }

        \PHPUnit_Framework_TestCase::fail("No existe {$arg1} == {$arg2}");
    }
}