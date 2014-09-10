/**
 * Created by julian on 10/09/14.
 */
;
(function () {
    "use strict";

    /**
     * BeneficiarioNuevoController
     *
     * @param scope
     * @param BeneficiarioFactory
     * @param PersonaFactory
     * @constructor
     */
    function BeneficiarioNuevoController(scope, BeneficiarioFactory, PersonaFactory) {
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
     * @param PersonaFactory
     * @constructor
     */
    function BeneficiarioListadoController(scope, BeneficiarioFactory, PersonaFactory) {
        G.Base.ListController.call(this, scope, BeneficiarioFactory);

        scope.personas = PersonaFactory.query();

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
     * @constructor
     */
    function BeneficiarioDetallesController(scope, BeneficiarioFactory) {
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
        .controller('BeneficiarioNuevoController', ['$scope', 'Beneficiario', 'Persona', BeneficiarioNuevoController]);

    angular.module(G.modules.BENEFICIARIO)
        .controller('BeneficiarioDetallesController', ['$scope', 'Beneficiario', 'Persona', BeneficiarioDetallesController]);
})();