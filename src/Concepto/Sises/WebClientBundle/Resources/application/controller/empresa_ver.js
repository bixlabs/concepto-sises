;(function() {
    "use strict";

    angular.module(G.APP).controller('EmpresaVerController', [
        '$scope',
        'Empresa',
        '$location',
        '$routeParams',
        'modalService',
        function($s, Empresa, $l, $p, mS) {
            $s.errors = {};
            $s.empresa = Empresa.get({id: $p.id});

            var canSave = true;
            var canRemove = true;

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors && $s.errors[name].errors.length;
            };

            $s.canSave = function() {
                return canSave;
            };

            $s.eliminarEmpresa = function() {
                canRemove = false;
                mS.open('Borrar?').then(function() {
                    $s.empresa.$delete(function() {
                        $l.path('/empresas')
                    }, function (response) {
                        console.error(response);
                        canRemove = true;
                    });
                })
            };

            $s.guardarEmpresa = function() {
                canSave = false;
                $s.empresa.$update(function() {
                    $l.path('/empresas/' + $s.empresa.id)
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