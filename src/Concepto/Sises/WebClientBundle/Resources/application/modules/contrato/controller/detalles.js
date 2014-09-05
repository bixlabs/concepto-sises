;(function() {
    "use strict";

    angular.module(G.modules.CONTRATO).controller('ContratoDetallesController', [
        '$scope',
        'Contrato',
        'Empresa',
        '$location',
        '$stateParams',
        'modalService',
        function($s, Contrato, Empresa, $l, $p, mS) {
            $s.errors = {};
            $s.contrato = Contrato.get({id: $p.id});
            $s.empresas = Empresa.query();

            var canSave = true;
            var canRemove = true;

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors && $s.errors[name].errors.length;
            };

            $s.canSave = function() {
                return canSave;
            };

            $s.eliminarContrato = function() {
                canRemove = false;
                mS.alert('Esta seguro de eliminar esta contrato?', function() {
                    $s.contrato.$delete(function() {
                        $s.go('contratos');
                    }, function (response) {
                        console.error(response);
                        canRemove = true;
                    });
                })
            };

            $s.guardarContrato = function() {
                canSave = false;
                $s.contrato.$update(function() {
                    canSave = true;
                    $s.go('contratos.detalles', {id: $s.contrato.id}, {reload: true});
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
                });
            };
        }])
})();