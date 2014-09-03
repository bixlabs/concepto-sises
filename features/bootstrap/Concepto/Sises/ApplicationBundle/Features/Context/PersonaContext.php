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


class PersonaContext extends RestContext
{
    /**
     * @Given un nuevo persona
     */
    public function unNuevoPersona()
    {
        $this->newObject();
    }

    /**
     * @Given el :arg1 del persona :arg2
     * @Given los :arg1 del persona :arg2
     */
    public function setProp($arg1, $arg2)
    {
        parent::setProp($arg1, $arg2);
    }

    /**
     * @Then crea un nuevo persona
     */
    public function creaUnNuevoPersona()
    {
        $this->post('api/personas.json');
    }

    /**
     * @Then crea un nuevo persona invalido
     */
    public function creaUnNuevoPersonaInvalido()
    {
        $this->postInvalid('api/personas.json');
    }

    /**
     * @Given que obtengo un listado de personas
     */
    public function queObtengoUnListadoDePersonas()
    {
        $this->cget('api/personas.json');
    }

    /**
     * @Then existe un persona de :arg1 :arg2
     */
    public function existeUnObjetoDe($arg1, $arg2)
    {
        return parent::existeUnObjetoDe($arg1, $arg2);
    }
}