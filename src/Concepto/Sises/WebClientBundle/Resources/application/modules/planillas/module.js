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

    var module = G.BuildModule('PLANILLAS', {
        register: 'planillas',
        label: 'Planillas',
        category: 'entrega_category',
        states: [
            {
                suffix: '',
                templateUrl: G.template('planillas/index'),
                controller: 'PlanillasIndex',
                url: '/planillas'
            }
        ]
    });

    module.controller('PlanillasIndex', function planillasIndex() {

    });
})();