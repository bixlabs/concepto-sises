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
        'RestResources', '$scope', '$http',
        function(RR, scope, $http) {
            scope.seleccion = {
                asignacion: null,
                now: null
            };

            // Carga la asignacion
            scope.$watch('seleccion.asignacion_id', function cargaAsignacion(id) {
                if (id) {
                    scope.seleccion.asignacion = RR.coordinador_entrega.get({'id': id});
                }
            });

            //
            scope.$watch('seleccion.now', function(now) {
                if (now) {
                    var id = scope.seleccion.asignacion.id;
                    $http.post(G.route('post_asignacion_entrega'), {
                        id: id,
                        fecha: moment(now).format('YYYY-MM-DDTHH:mm:ssZZ')
                    }).success(function(data) {
                        scope.seleccion.asignacion = RR.coordinador_entrega.get({'id': id});
                    });
                }
            });
        }
    ]);

})();