/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    var linkFunc = function(scope) {
        scope.name = G.guid();

        scope.hasError = function(name) {
            return scope.errors[name]
                && angular.isObject(scope.errors[name])
                && scope.errors[name].errors
                && scope.errors[name].errors.length;
        };
    };
    angular.module(G.APP)
        .directive('sisesForm', function() {
            return {
                restrict: 'A',
                transclude: true,
                replace: true,
                template: '<form class="form-horizontal sises-form" data-ng-transclude></form>',
                scope: {
                    model: '=sisesForm',
                    errors: '='
                },
                controller: function($scope) {}
            };
        })
        .directive('sisesFormInput', function() {
            return {
                restrict: 'A',
                replace: true,
                require: '^sisesForm',
                templateUrl: G.template('directive/form_input'),
                scope: {
                    property: '@sisesFormInput'
                },
                link: function(scope, el, attrs, form) {

                    scope.id = G.guid();

                    scope.form = form;

                    // Proccess extra attributes
                    angular.forEach(['placeholder', 'label', 'required'], function(attr) {
                        scope[attr] = attrs[attr] ? scope.$eval(attrs[attr]) : '';
                    });

                    scope.isRequired = function() {
                        if (scope.required) {
                            return scope.required;
                        }

                        return false;
                    };

                    scope.hasErrors = function() {
                        return scope.getErrors().length > 0;
                    };

                    scope.getErrors = function() {
                        if (form.errors
                            && form.errors[scope.property]
                            && form.errors[scope.property].errors) {
                            return form.errors[scope.property].errors;
                        }

                        return [];
                    };
                }
            };
        })
        .directive('sisesInput', function() {
            return {
                replace: true,
                restrict: 'A',
                templateUrl: G.template('directive_input'),
                scope: {
                    errors: '=',
                    model: '=',
                    property: '@',
                    label: '@',
                    placeholder: '@',
                    required: '='
                },
                link: linkFunc
            };
        })

        .directive('sisesSelect', function() {
            return {
                replace: true,
                restrict: 'A',
                templateUrl: G.template('directive_select'),
                scope: {
                    errors: '=',
                    model: '=',
                    property: '@',
                    label: '@',
                    optionsModel: '=',
                    optionKey: '@',
                    optionLabel: '@',
                    required: '='
                },
                link: linkFunc
            };
        })
})();