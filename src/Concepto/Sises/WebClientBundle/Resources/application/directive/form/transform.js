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

    angular.module(G.APP)
        .directive('transform', ['$filter', function($filter) {
            return {
                restrict: 'A',
                require: '?ngModel',
                scope: {
                    transform: '@'
                },
                link: function(scope, el, attrs, ngModel) {

                    var transformers = {
                        currency: {
                            formatter: function(value) {
                                return $filter('currency')(value, '$', 2);
                            },
                            parser: function(value) {
                                return +value.replace(/[^0-9.]+/g, '');
                            },
                            events: [
                                {
                                    name: 'focus',
                                    func: function() {
                                        var val = +ngModel.$modelValue;

                                        el.val(!isNaN(val) ? val : ngModel.$modelValue);
                                    }
                                },
                                {
                                    name: 'blur',
                                    func: function() {
                                        el.val(transformers.currency.formatter(ngModel.$modelValue));
                                    }
                                }
                            ]
                        }
                    };

                    if (typeof ngModel === 'undefined') {
                        return;
                    }

                    if (typeof scope.transform === 'undefined' || scope.transform === null || scope.transform === '') {
                        return;
                    }

                    var t = transformers[scope.transform];

                    if (!t) {
                        throw "No existe el transformer '" + scope.transform + "'";
                    }

                    ngModel.$formatters.push(t.formatter);
                    ngModel.$parsers.push(t.parser);

                    if (t.events && t.events.length > 0) {
                        angular.forEach(t.events, function(event) {
                            el.on(event.name, event.func)
                        })
                    }
                }
            }
        }]);
})();