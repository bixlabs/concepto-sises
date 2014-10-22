/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */
;
(function () {
    "use strict";

    /**
     * Define los atributos base para el elemento
     * @param scope
     * @param defaultAttribValues
     */
    function setDefaultAttribValues(scope, defaultAttribValues, attrs)
    {
        angular.forEach(defaultAttribValues, function(defValue, attrib) {
            scope.formProperties[attrib] = attrs[attrib] ?
                scope.$eval(attrs[attrib]) : defValue;
        });
    }

    /**
     * Base para funcion 'link' de directivas, enfocado a inputs
     *
     * @param scope el contexto de Angular para la directiva actual
     * @param el el elemento devuelto por la funcion link
     * @param attrs los atributos html pasados como data-*
     * @param controllers las dependencias a controladores de otras directivas
     * @constructor
     */
    function InputFormLink(scope, el, attrs, controllers) {

        var defaultAttribValues;

        scope.formProperties = {
            id: G.guid()
        };

        // Si dependencias de controller
        if (angular.isArray(controllers)) {
            // Siempre se asigna el ultimo
            if (controllers[controllers.length - 1]) {
                scope.form = controllers[controllers.length - 1].scope;
            } else {
                scope.form = controllers[0].scope;
            }
        } else {
            throw "Debe definie una directiva padre (form or compound) para " + scope.property;
        }

        // Atributos soportados
        defaultAttribValues = {
            'placeholder': '',
            'label': '',
            'required': false,
            'type': 'text'
        };

        setDefaultAttribValues.call(this, scope, defaultAttribValues, attrs);

        // Getters
        scope.isRequired = function() {
            return scope.formProperties.required;
        };

        scope.getType = function() {
            return scope.formProperties.type;
        };

        scope.getLabel = function() {
            return scope.formProperties.label;
        };

        //Errors
        scope.errors = {};

        scope.hasErrors = function() {
            return scope.getErrors().length > 0;
        };

        scope.getErrors = function() {
            if (scope.form.errors
                && scope.form.errors[scope.property]
                && scope.form.errors[scope.property].errors) {
                return scope.form.errors[scope.property].errors;
            }

            return [];
        };

        //Gobal functions
        scope.template = scope.template || G.template;
    }

    G.Form = G.Form || {
        InputFormLink: InputFormLink,
        setDefaultAttribValues: setDefaultAttribValues
    };
})();