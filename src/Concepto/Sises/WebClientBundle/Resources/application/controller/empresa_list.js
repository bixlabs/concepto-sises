/**
 * Created by julian on 5/09/14.
 */
;(function() {
    "use strict";

    angular.module(G.APP)
        .controller('EmpresaController', ['$scope', 'Empresa', 'MenuService', function($s, Empresa, MS) {
            $s.empresas = Empresa.query();

            MS.register({ url: '/empresas', label: 'Empresas'});
        }])
})();