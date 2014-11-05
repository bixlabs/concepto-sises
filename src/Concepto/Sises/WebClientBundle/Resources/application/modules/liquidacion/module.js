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

    //G.Base.Controller
    var module = G.BuildModule('LIQUIDACION', {
        register: 'liquidacion',
        label: 'Liquidaciones',
        states: [
            {
                suffix: '',
                templateUrl: G.template('liquidacion/index'),
                controller: 'LIIndex',
                url: '/liquidacion'
            }
        ]
    });

    module.controller('LIIndex', [
        'RestResources', '$scope',
        function(RR, scope) {
            scope.servicios = RR.serv_operativo.query();

            scope.seleccion = {
                servicio_id: null,
                entrega_id: null
            };
        }
    ]);
})();