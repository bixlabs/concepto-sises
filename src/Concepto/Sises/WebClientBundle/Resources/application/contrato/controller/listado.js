/**
 * Created by julian on 5/09/14.
 */
;(function() {
    "use strict";

    angular.module(G.modules.CONTRATO)
        .controller('ContratoListadoController', ['$scope', 'Contrato', function($s, Contrato) {
            $s.contratos = Contrato.query();
        }])
})();