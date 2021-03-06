/**
 * Created by julian on 11/09/14.
 */
;
(function () {
    "use strict";
    angular.module('RESOURCE', [])
        .factory('RestResources', ['$resource', function($r) {
            var factory = function(url) {
                return $r(G.json_route(url), { id: '@id' }, {
                    update: { method: 'PUT'}
                }, {
                    stripTrailingSlashes: false
                });
            };

            var factories = {
                'empresa':                 '/api/empresas/:id.json',
                'empresa_publica':         '/api/empresas/publicas/:id.json',
                'contrato':                '/api/contratos/:id.json',
                'beneficiario':            '/api/beneficiarios/:id.json',
                'encargado':               '/api/encargados/:id.json',
                'persona':                 '/api/personas/:id.json',
                'lugar':                   '/api/lugars/:id.json',
                'ubicacion':               '/api/ubicacions/:id.json',
                'servicio':                '/api/contratos/:parent/servicios/:id.json',
                'cargo':                   '/api/cargos/:id.json',
                'recurso_humano':          '/api/recursos/:id.json',
                'cargo_operativo':         '/api/operativos/:id.json',
                'entidad_financiera':      '/api/financiera/entidads/:id.json',
                'entidad_financiera_tipo': '/api/financiera/tipos/:id.json',
                'coordinador':             '/api/coordinadors/:id.json',
                'director':                '/api/directors/:id.json',
                'admin_entrega':           '/api/entregas/:id.json',
                'admin_entrega_calcular':  '/api/entregas/:id/calcular.json',
                'admin_entrega_calcular_more':  '/api/entregas/:id/calcular/detalle.json',
                'coordinador_entrega':     '/api/asignacions/:id.json',
                'serv_operativo':          '/api/operativo/servicios/:id.json',
                'admin_liquidacion':     '/api/entrega/liquidacions/:id.json',
                'liquidacion':           '/api/entrega/liquidacion/listado/:id.json'
            };

            var methods = {};

            angular.forEach(factories, function(url, name) {
                methods[name] = factory(url);
                methods[name + '_printable'] = url.replace(':id.json', 'printable.html');
            });

            return methods;
        }])

        .factory('FilterResources', function() {
            return {
                persona: {
                    documento: {label: 'Documento', value: 'documento', comp: 'L'},
                    nombre: {label: 'Nombre', value: 'nombre', comp: 'L'},
                    apellidos: {label: 'Apellidos', value: 'apellidos', comp: 'L'}
                },
                beneficiario: {
                    documento: {label: 'Documento', value: 'persona..documento', comp: 'L'},
                    nombre: {label: 'Nombre', value: 'persona..nombre', comp: 'L'},
                    apellidos: {label: 'Apellidos', value: 'persona..apellidos', comp: 'L'}
                },
                lugar: {
                    nombre: {label: 'Nombre', value: 'nombre', comp: 'L'}
                },
                cargo: {
                    nombre: {label: 'Nombre', value: 'nombre', comp: 'L'}
                },
                coordinador: {
                    documento: {label: 'Documento', value: 'persona..documento', comp: 'L'},
                    nombre: {label: 'Nombre', value: 'persona..nombre', comp: 'L'},
                    apellidos: {label: 'Apellidos', value: 'persona..apellidos', comp: 'L'}
                },
                director: {
                    documento: {label: 'Documento', value: 'persona..documento', comp: 'L'},
                    nombre: {label: 'Nombre', value: 'persona..nombre', comp: 'L'},
                    apellidos: {label: 'Apellidos', value: 'persona..apellidos', comp: 'L'}
                },
                empresa: {
                    nit: {label: 'NIT', value: 'nit', comp: 'L'},
                    nombre: {label: 'Nombre', value: 'nombre', comp: 'L'}
                },
                recurso_humano: {
                    documento: {label: 'Documento', value: 'persona..documento', comp: 'L'},
                    nombre: {label: 'Nombre', value: 'persona..nombre', comp: 'L'},
                    apellidos: {label: 'Apellidos', value: 'persona..apellidos', comp: 'L'}
                },
                contrato: {
                    nombre: {label: 'Nombre', value: 'nombre', comp: 'L'},
                    resolucion: {label: 'Resolución', value: 'resolucion', comp: 'L'}
                },
                admin_entrega: {
                    estado: {label: 'Estado', value: 'estado', comp: 'L'}
                },
                admin_liquidacion: {
                    estado: {label: 'Estado', value: 'estado', comp: 'L'}
                }

            };
        })
    ;
})();