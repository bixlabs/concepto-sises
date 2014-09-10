/**
 * Created by julian on 8/09/14.
 */
;
(function () {
    "use strict";

    /**
     * Reused methods
     *
     * @param scope
     * @constructor
     */
    function EmpresaListado(scope) {
        scope.list = function() {
            scope.go('empresas.listado');
        };
    }

    /**
     * EmpresaListadoController
     *
     * @param scope
     * @param EmpresaFactory
     * @constructor
     */
    function EmpresaListadoController(scope, EmpresaFactory) {
        EmpresaListado.call(this, scope);
        G.Base.ListController.call(this, scope, EmpresaFactory);

        scope.add = function() {
            scope.go('empresas.nueva');
        };

        scope.details = function(id) {
            scope.go('empresas.detalles', {id: id});
        };
    }

    /**
     * EmpresaNuevaController
     *
     * @param scope
     * @param EmpresaFactory
     * @constructor
     */
    function EmpresaNuevaController(scope, EmpresaFactory) {
        G.Base.NewController.call(this, scope, EmpresaFactory);
        EmpresaListado.call(this, scope);

        scope.detailsLocation = function(location) {
            scope.go('empresas.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * EmpresaDetallesController
     *
     * @param scope
     * @param EmpresaFactory
     * @constructor
     */
    function EmpresaDetallesController(scope, EmpresaFactory) {
        G.Base.UpdateController.call(this, scope, EmpresaFactory);
        EmpresaListado.call(this, scope);

        scope.detailsLocation = function(location) {
            scope.refresh('empresas.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * Register like angular.js controllers
     */
    angular.module(G.modules.EMPRESA)
        .controller('EmpresaListadoController', ['$scope', 'Empresa', EmpresaListadoController]);
    angular.module(G.modules.EMPRESA)
        .controller('EmpresaNuevaController', ['$scope', 'Empresa', EmpresaNuevaController]);
    angular.module(G.modules.EMPRESA)
        .controller('EmpresaDetallesController', ['$scope', 'Empresa', EmpresaDetallesController]);
})();