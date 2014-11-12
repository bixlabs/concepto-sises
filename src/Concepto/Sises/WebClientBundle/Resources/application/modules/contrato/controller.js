/**
 * Created by julian on 8/09/14.
 */
;
(function () {
    "use strict";

    /**
     * ContratoNuevoController
     *
     * @param scope
     * @param ContratoFactory
     * @constructor
     */
    function ContratoNuevoController(scope, ContratoFactory) {
        G.Base.NewController.call(this, scope, ContratoFactory);

        scope.list = function() {
            scope.go('contratos.listado');
        };

        scope.detailsLocation = function(location) {
            scope.go('contratos.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * ContratoListadoController
     *
     * @param scope
     * @param ContratoFactory
     * @constructor
     */
    function ContratoListadoController(scope, ContratoFactory) {
        G.Base.ListController.call(this, scope, ContratoFactory);

        scope.details = function (id) {
            scope.go('contratos.detalles', {id: id});
        };

        scope.add = function() {
            scope.go('contratos.nuevo');
        };
    }

    /**
     * ContratoDetallesController
     *
     * @param scope
     * @param ContratoFactory
     * @param EmpresaFactory
     * @constructor
     */
    function ContratoDetallesController(scope, ContratoFactory) {
        G.Base.UpdateController.call(this, scope, ContratoFactory);

        scope.list = function() {
            scope.go('contratos.listado');
        };

        scope.detailsLocation = function(location) {
            scope.refresh('contratos.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * Register like angular.js controllers
     */
    angular.module(G.modules.CONTRATO)
        .controller('ContratoListadoController', ['$scope', 'Contrato', ContratoListadoController]);

    angular.module(G.modules.CONTRATO)
        .controller('ContratoNuevoController', ['$scope', 'Contrato', ContratoNuevoController]);

    angular.module(G.modules.CONTRATO)
        .controller('ContratoDetallesController', ['$scope', 'Contrato', ContratoDetallesController]);
})();