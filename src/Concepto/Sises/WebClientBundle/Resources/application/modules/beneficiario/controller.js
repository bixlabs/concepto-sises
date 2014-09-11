/**
 * Created by julian on 10/09/14.
 */
;
(function () {
    "use strict";

    function Common(scope, Factory, $r, propertyName, propertiesName) {
        var id = G.guid();

        scope[propertiesName] = Factory.query();

        scope[propertyName + '_element'] = {};
        scope[propertyName + '_errors'] = {};
        scope[propertyName + '_handler'] = {
            id: id,
            actions: {
                ok: {
                    label: 'Agregar',
                    style: 'primary'
                },
                cancel: {
                    dismiss: true,
                    label: 'Volver'
                }
            }
        };

        $r.$on('modalize.action.' + id, function(event, data) {
            switch (data) {
                case 'ok':
                    scope[propertyName + '_element']['$save'](function(data, headers) {
                        scope[propertiesName] = Factory.query();
                        scope.element[propertyName] = G.extractGuid(headers('Location'));
                        scope[propertyName + '_handler'].hide();
                    }, function(response) {
                        switch (response.data.code) {
                            case 400:
                                scope[propertyName + '_errors'] = response.data.errors.children;
                                break;
                            default:
                                console.error(response);
                                break;
                        }
                    });
                    break;
            }
        });

        scope[propertyName + '_add'] = function() {
            scope[propertyName + '_element'] = new Factory();
            scope[propertyName + '_handler'].show();
        };
    }

    /**
     * BeneficiarioNuevoController
     */
    function BeneficiarioNuevoController(scope, $r, BeneficiarioFactory, PersonaFactory, LugarFactory, UF) {
        Common.call(this, scope, PersonaFactory, $r, 'persona', 'personas');
        Common.call(this, scope, LugarFactory, $r, 'lugar', 'lugares');

        scope.ubicaciones = UF.query();

        G.Base.NewController.call(this, scope, BeneficiarioFactory);

        scope.list = function() {
            scope.go('beneficiarios.listado');
        };

        scope.detailsLocation = function(location) {
            scope.go('beneficiarios.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * BeneficiarioListadoController
     *
     * @param scope
     * @param BeneficiarioFactory
     * @constructor
     */
    function BeneficiarioListadoController(scope, BeneficiarioFactory) {
        G.Base.ListController.call(this, scope, BeneficiarioFactory);

        scope.details = function (id) {
            scope.go('beneficiarios.detalles', {id: id});
        };

        scope.add = function() {
            scope.go('beneficiarios.nuevo');
        };
    }

    /**
     * BeneficiarioDetallesController
     *
     * @param scope
     * @param BeneficiarioFactory
     * @param PersonaFactory
     * @param $r
     * @constructor
     */
    function BeneficiarioDetallesController(scope, BeneficiarioFactory, PersonaFactory, $r) {
        G.Base.UpdateController.call(this, scope, BeneficiarioFactory);

        scope.list = function() {
            scope.go('beneficiarios.listado');
        };

        scope.detailsLocation = function(location) {
            scope.refresh('beneficiarios.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * Register like angular.js controllers
     */
    angular.module(G.modules.BENEFICIARIO)
        .controller('BeneficiarioListadoController', ['$scope', 'Beneficiario', BeneficiarioListadoController]);

    angular.module(G.modules.BENEFICIARIO)
        .controller('BeneficiarioNuevoController', [
            '$scope',
            '$rootScope',
            'Beneficiario',
            'Persona',
            'Lugar',
            'Ubicacion',
            BeneficiarioNuevoController
        ]);

    angular.module(G.modules.BENEFICIARIO)
        .controller('BeneficiarioDetallesController', [
            '$scope',
            'Beneficiario',
            'Persona',
            '$rootScope',
            BeneficiarioDetallesController
        ]);
})();