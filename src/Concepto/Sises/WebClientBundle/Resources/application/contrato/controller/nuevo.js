;(function() {
    "use strict";

    angular.module(G.modules.CONTRATO)
        .controller('ContratoNuevoController', ['$scope', 'Contrato', '$location', function($s, Contrato, $l) {
            $s.contrato = new Contrato();
            $s.errors = {};

            var canSave = true;

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors && $s.errors[name].errors.length;
            };

            $s.canSave = function() {
                return canSave;
            };

            $s.guardarContrato = function() {
                canSave = false;
                $s.contrato.$save(function () {
                    $l.path('/contratos')
                }, function(response) {
                    switch (response.data.code) {
                        case 400:
                            $s.errors = response.data.errors.children;
                            break;
                        default:
                            console.error(response);
                            break;
                    }
                    canSave = true;
                })
            };
        }])
})();