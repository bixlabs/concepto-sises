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

    G.modules.ADMIN_LIQUIDACION = 'ADMIN_LIQUIDACION';

    G.BuildModule(G.modules.ADMIN_LIQUIDACION, {
        register: true,
        label: 'Gestión de liquidaciones',
        category: 'liquidacion_category',
        controllers: {
            edit: {
                deps: ['$http', 'ngToast'],
                func: function(RR, scope, $http, ngToast) {
                    G.Base.BaseEntrega.call(this, RR, scope, $http, ngToast);
                    this._buildSave = function(servicios) {
                        return {
                            liquidacion: scope.element.id,
                            servicios: servicios,
                            observacion: scope.extra.observacion
                        };
                    };

                    this._routeCierre = function() {
                        return 'put_liquidacion_cierre';
                    };

                    this._routeDetalles = function () {
                        return 'get_liquidacion_detalles';
                    };

                    this._calcular = function(callback) {
                        $http.get(G.route('get_liquidacion_detalles', {id: scope.element.id}))
                            .success(function calcular_success(data) {
                                if (data.length === 0) {
                                    scope.element.estado = G.Base.ENTREGA.OPEN;
                                    ngToast.create({
                                        'content': '<i class="glyphicon glyphicon-exclamation-sign"></i> No hay datos para realizar cierre',
                                        'class': 'info',
                                        'verticalPosition': 'top',
                                        'horizontalPosition': 'center'
                                    });
                                } else {
                                    callback(data);
                                }
                            });

                    };
                }
            }
        }
    });
})();