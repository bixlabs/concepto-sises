;(function() {
    "use strict";

    angular.module(G.APP)
        .controller('EmpresaNuevaController', ['$scope', 'Empresa', '$location', function($s, Empresa, $l) {
            $s.empresa = new Empresa();
            $s.errors = {};

            var canSave = true;

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors && $s.errors[name].errors.length;
            };

            $s.canSave = function() {
                return canSave;
            };

            $s.guardarEmpresa = function() {
                canSave = false;
                $s.empresa.$save(function () {
                    $l.path('/empresas')
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