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
        .directive('sisesUsuario', [
            'RestResources',
            function sisesUsuario_directive(RR) {
                return {
                    restrict: 'AEC',
                    replace: true,
                    templateUrl: G.template('directive/widget_usuario'),
                    scope: {
                        element: '=sisesUsuario'
                    },
                    link: function sisesUsuario_link(scope) {

                    }
                };
            }
        ])
    ;
})();