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


class BeneficiarioContext extends RestContext
{
    /**
     * @Given un nuevo beneficiario
     */
    public function unNuevoBeneficiario()
    {
        $this->newObject();
    }

    /**
     * @Given el :arg1 del beneficiario :arg2
     * @Given los :arg1 del beneficario :arg2
     */
    public function setProp($arg1, $arg2)
    {
        parent::setProp($arg1, $arg2);
    }

    /**
     * @Then crea un nuevo beneficiario
     */
    public function creaUnNuevoBeneficiario()
    {
        $this->post('api/beneficiarios.json');
    }

}