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
        .directive('sisesEntregaAsignada', [
            'RestResources',
            function(RR) {
                return {
                    restrict: 'A',
                    replace: true,
                    templateUrl: G.template('directive/widget_entrega_asignada'),
                    scope: {
                        entrega: '=sisesEntregaAsignada'
                    },
                    link: function(scope) {
                        scope.asignaciones = RR.coordinador_entrega.query({
                            parent: scope.entrega
                        });
                    }
                };
            }
        ])
    ;
})();