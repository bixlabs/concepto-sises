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
    var module = G.BuildModule('COORD_ENTREGA', {
        register: 'coord_entrega',
        label: 'Realizar entrega',
        states: [
            {
                suffix: '',
                templateUrl: G.template('coord_entrega/index'),
                controller: 'CEIndex',
                url: '/entregas'
            }
        ]
    });


    module.controller('CEIndex', [
        'RestResources', '$scope',
        function(RR, scope) {
            scope.seleccion = {
                asignacion: null
            };
        }
    ]);

})();