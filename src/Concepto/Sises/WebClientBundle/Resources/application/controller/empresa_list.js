/**
 * Created by julian on 5/09/14.
 */
;(function() {
    "use strict";

    angular.module(G.APP)
        .controller('EmpresaController', ['$scope', 'Empresa', function($s, Empresa) {
            $s.empresas = Empresa.query();
        }])
})();