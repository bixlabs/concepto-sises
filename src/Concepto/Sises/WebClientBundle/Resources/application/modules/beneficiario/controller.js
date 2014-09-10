/**
 * Created by julian on 10/09/14.
 */
;
(function () {
    "use strict";

    function PersonaCommon(scope, PersonaFactory, $r) {

        var id = G.guid();

        scope.persona_element = {};
        scope.persona_errors = {};
        scope.persona_handler = {
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
                    scope.persona_element['$save'](function(data, headers) {
                        scope.personas = PersonaFactory.query();
                        scope.element.persona = G.extractGuid(headers('Location'));
                        scope.persona_handler.hide();
                    }, function(response) {
                        switch (response.data.code) {
                            case 400:
                                scope.persona_errors = response.data.errors.children;
                                break;
                            default:
                                console.error(response);
                                break;
                        }
                    });
                    break;
            }
        });

        scope.persona_add = function() {
            scope.persona_element = new PersonaFactory();
            scope.persona_handler.show();
        };
    }

    /**
     * BeneficiarioNuevoController
     *
     * @param scope
     * @param BeneficiarioFactory
     * @param PersonaFactory
     * @param $r
     * @constructor
     */
    function BeneficiarioNuevoController(scope, BeneficiarioFactory, PersonaFactory, $r) {
        PersonaCommon.call(this, scope, PersonaFactory, $r);
        G.Base.NewController.call(this, scope, BeneficiarioFactory);

        scope.personas = PersonaFactory.query();

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
        PersonaCommon.call(this, scope, PersonaFactory, $r);
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
            'Beneficiario',
            'Persona',
            '$rootScope',
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