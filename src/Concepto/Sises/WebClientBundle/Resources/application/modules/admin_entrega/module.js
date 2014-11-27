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
        label: 'Gestión de entregas',
        category: 'entrega_category',
        controllers: {
            edit: {
                deps: ['$http', 'ngToast'],
                func: function(RR, scope, $http, ngToast) {
                    G.Base.BaseEntrega.call(this, RR, scope, $http, ngToast);
                    this._buildSave = function(servicios) {
                        return {
                            id: scope.element.id,
                            servicios: servicios,
                            observacion: scope.extra.observacion
                        };
                    };

                    this._routeCierre = function() {
                        return 'put_entrega_cierre';
                    };

                    this._routeDetalles = function () {
                        return 'get_entrega_detalles';
                    };

                    this._calcular = function(callback) {
                        RR.admin_entrega_calcular.get({id: scope.element.id}, function(data) {
                            callback(data.results);
                        });
                    };
                }
            }
        }
    });
})();