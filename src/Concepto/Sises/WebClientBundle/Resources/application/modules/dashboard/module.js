/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    G.modules.DASHBOARD = 'DASHBOARD';

    angular.module(G.modules.DASHBOARD, ['ngRoute' ,'ngResource', 'ui.router'])
        .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
            $stateProvider
                .state('dashboard', {
                    url : '/dashboard',
                    controller: 'DashboardController',
                    templateUrl: G.template('dashboard/info')
                });
            $urlRouterProvider
                .otherwise('/dashboard');
        }])

        .run(['MenuService', function(MS) {
            MS.register({
                name: G.modules.DASHBOARD,
                url: 'dashboard',
                label: 'Cuadro de mando',
                priority: 0
            });
        }])

        .controller('DashboardController', ['$http', '$scope', function($http, scope) {

            var base_data = {
                empty: {
                    label: { text: "No hay datos para mostrar en las fechas seleccionadas" }
                },
                x: 'fecha',
                xFormat: '%Y-%m-%dT%H:%M:%S%Z',
                type: 'bar',
                labels: true,
                onclick: function (d) { console.log("onclick", d); },
            };

            function _load() {
                $http.get(G.route('get_dashboard_info'), {params: scope.element})
                    .success(function load_succes(response) {
                        var data = response.data;

                        if (response.query) {
                            scope.query = response.query;
                        }

                        if (scope.chart === null) {
                            scope.chart = c3.generate({
                                data: angular.extend(base_data, data),
                                axis: {
                                    x: {
                                        type: 'category', //category timeseries
                                        tick: {
                                            format: '%d/%m/%Y'
                                        }
                                    }
                                }
                            });
                            if (G.DEBUG) {
                                window.chart = scope.chart;
                            }
                        } else {
                            // Procesa los nombres que deben ser descargados
                            var unload = [];
                            angular.forEach(scope.chart.data.names(), function(v, k) {
                                if (!data.names[k]) {
                                    unload.push(k);
                                }
                            });

                            // Carga la nueva informacion
                            scope.chart.load(angular.extend({ unload: unload }, data));
                            scope.chart.data.names(data.names);
                        }
                    });
            }

            scope.element = {
                start: null,
                end: null
            };
            scope.query = null;
            scope.chart = null;
            scope.load = function scope_load() {
                _load();

            };

            _load();
        }])
    ;
})();