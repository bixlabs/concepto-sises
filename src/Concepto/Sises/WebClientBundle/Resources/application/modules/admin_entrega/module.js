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

    G.modules.ADMIN_ENTREGA = 'ADMIN_ENTREGA';

    G.BuildModule(G.modules.ADMIN_ENTREGA, {
        register: true,
        label: 'Entregas',
        controllers: {
            edit: {
                func: function(RR, scope) {
                    scope.detalles = [];
                    scope.calcular = function calcular() {
                        RR.admin_entrega_calcular.get({id: scope.element.id}, function(data) {
                            scope.detalles = data.results;
                        });
                    };

                    scope.hasCierre = function hasCierre() {
                        return scope.detalles.length > 0;
                    };

                    scope.cancelarCierre = function cancelarCierre() {
                        scope.detalles = [];
                    }
                }
            }
        }
    });
})();