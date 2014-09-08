/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";
    angular.module(G.APP)
        .directive('sisesInput', function() {
            return {
                replace: true,
                restrict: 'A',
                templateUrl: G.template('directive_input'),
                scope: {
                    errors: '=',
                    model: '=',
                    propery: '@',
                    label: '@',
                    placeholder: '@'
                },
                link: function(scope) {
                    scope.name = G.guid();
                    scope.hasError = function(name) {
                        return scope.errors[name]
                            && scope.errors[name].errors
                            && scope.errors[name].errors.length;
                    };
                }
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
                    propery: '@',
                    label: '@',
                    optionsModel: '=',
                    optionKey: '@',
                    optionLabel: '@'
                },
                link: function(scope) {
                    scope.name = G.guid();
                }
            };
        })
})();