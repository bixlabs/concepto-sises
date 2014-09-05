/**
 * Created by julian on 5/09/14.
 */
;(function() {
    "use strict";

    angular.module(G.modules.EMPRESA)
        .controller('EmpresaListadoController', ['$scope', 'Empresa', function($s, Empresa) {
            $s.empresas = Empresa.query();
        }])
})();