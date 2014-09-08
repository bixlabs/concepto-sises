;(function() {
    "use strict";

    function ContratoNuevoController(scope, Factory, EmpresaFactory) {
        G.Base.NewController.call(this, scope, Factory);
        scope.empresas = EmpresaFactory.query();

        scope.list = function() {
            scope.go('contratos.listado');
        };
    }

    ContratoNuevoController.prototype = Object.create(G.Base.NewController.prototype);

    angular.module(G.modules.CONTRATO)
        .controller('ContratoNuevoController', [
            '$scope',
            'Contrato',
            'Empresa',
            ContratoNuevoController]);
})();