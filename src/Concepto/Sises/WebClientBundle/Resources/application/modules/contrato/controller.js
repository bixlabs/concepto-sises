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
     * @param EmpresaFactory
     * @constructor
     */
    function ContratoNuevoController(scope, ContratoFactory, EmpresaFactory) {
        G.Base.NewController.call(this, scope, ContratoFactory);
        scope.empresas = EmpresaFactory.query();

        scope.list = function() {
            scope.go('contratos.listado');
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

    function ContratoDetallesController(scope, ContratoFactory, EmpresaFactory) {
        G.Base.UpdateController.call(this, scope, ContratoFactory);
        scope.empresas = EmpresaFactory.query();

        scope.list = function() {
            scope.go('contratos.listado');
        };
    }

    angular.module(G.modules.CONTRATO)
        .controller('ContratoListadoController', ['$scope', 'Contrato', ContratoListadoController])

    angular.module(G.modules.CONTRATO)
        .controller('ContratoNuevoController', ['$scope', 'Contrato', 'Empresa', ContratoNuevoController]);

    angular.module(G.modules.CONTRATO)
        .controller('ContratoDetallesController', ['$scope', 'Contrato', 'Empresa', ContratoDetallesController]);
})();