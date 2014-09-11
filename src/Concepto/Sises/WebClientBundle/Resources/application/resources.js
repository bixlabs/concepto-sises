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
                'empresa':      '/api/empresas/:id.json',
                'contrato':     '/api/contratos/:id.json',
                'beneficiario': '/api/beneficiarios/:id.json',
                'persona':      '/api/personas/:id.json',
                'lugar':        '/api/lugares/:id.json',
                'ubicacion':    '/api/ubicacions/:id.json'
            };

            var methods = {};

            angular.forEach(factories, function(url, name) {
                methods[name] = factory(url);
            });

            return methods;
        }])
    ;
})();