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
                    placeholder: '@'
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
                    optionLabel: '@'
                },
                link: linkFunc
            };
        })
})();