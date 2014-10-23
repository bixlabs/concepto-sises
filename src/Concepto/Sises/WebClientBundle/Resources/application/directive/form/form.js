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
    /**
     * directive sisesForm
     */
        .directive('sisesForm', function() {
            return {
                restrict: 'A',
                transclude: true,
                replace: true,
                templateUrl: G.template('directive/form'),
                scope: {
                    model: '=sisesForm',
                    errors: '='
                },
                controller: function($scope) {
                    this.scope = $scope;
                }
            };
        })
    /**
     * directive sisesCompound
     */
        .directive('sisesCompound', function() {
            return {
                restrict: 'A',
                transclude: true,
                replace: true,
                require: '^sisesForm',
                template: '<div data-ng-transclude></div>',
                scope: {
                    model: '=sisesCompound'
                },
                controller: function($scope) {
                    this.scope = $scope;
                }
            };
        })
    ;
})();