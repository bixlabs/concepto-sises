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

    var InputFormDate = function(scope, el, attrs) {
        scope.formProperties.type = "date";

        var defaultAttribValues = {
            'openedCalendar': false
        };

        G.Form.setDefaultAttribValues.call(this, scope, defaultAttribValues, attrs);

        scope.clearValue = function date_clear($event) {
            if ($event) {
                $event.preventDefault();
                $event.stopPropagation();
            }
            scope.form.model[scope.property] = null;
        };

        scope.openCalendar = function($event) {
            if ($event) {
                $event.preventDefault();
                $event.stopPropagation();
            }

            scope.formProperties.openedCalendar = true;
        };
    };

    angular.module(G.APP)
    /**
     * directive sisesFormInput
     */
        .directive('sisesFormInput', function() {
            return {
                restrict: 'A',
                replace: true,
                scope: {
                    property: '@sisesFormInput'
                },
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_input'),
                link: function(scope, el, attrs, controllers) {
                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);

                    if (scope.formProperties.type === 'date') {
                        InputFormDate.call(this, scope, el, attrs);
                    }
                }
            };
        })
    /**
     * directive siesFormStatic
     */
        .directive('sisesFormStatic', function() {
            return {
                restrict: 'A',
                replace: true,
                scope: {
                    property: '=sisesFormStatic',
                    label: '='
                },
                templateUrl: G.template('directive/form_static')
            }
        })
    /**
     * directive sisesFormEmail
     */
        .directive('sisesFormEmail', function() {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_input'),
                scope: {
                    property: '@sisesFormEmail'
                },
                link: function(scope, el, attrs, controllers) {
                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);

                    if ('' === scope.formProperties.placeholder) {
                        scope.formProperties.placeholder = "nombre@ejemplo.com";
                    }

                    scope.formProperties.type = "email";
                }
            };
        })

    /**
     * directive sisesFormDate
     */
        .directive('sisesFormDate', function() {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_input'),
                scope: {
                    property: '@sisesFormDate',
                    minDate: '@',
                    maxDate: '@'
                },
                link: function(scope, el, attrs, controllers) {
                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);
                    InputFormDate.call(this, scope, el, attrs);
                }
            };
        })
    /**
     * directive sisesFormTextArea
     */
        .directive('sisesFormTextArea', function() {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_textarea'),
                scope: {
                    property: '@sisesFormTextArea'
                },
                link: G.Form.InputFormLink
            };
        })
    ;
})();