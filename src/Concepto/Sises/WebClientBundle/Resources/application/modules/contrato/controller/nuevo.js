;(function() {
    "use strict";

    angular.module(G.modules.CONTRATO)
        .controller('ContratoNuevoController', [
            '$scope',
            'Contrato',
            'Empresa',
            '$location',
            function($s, Contrato, Empresa, $l) {
                $s.contrato = new Contrato();
                $s.errors = {};

                $s.empresas = Empresa.query();

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
                        $s.go('contratos.listado');
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