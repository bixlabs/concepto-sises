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

            $urlRouterProvider.when('/', '/dashboard');
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
            var $subchart = $('#subchart'),
                base_data = {
                    empty: {
                        label: { text: "No hay datos para mostrar en las fechas seleccionadas" }
                    },
                    x: 'fecha',
                    xFormat: '%Y-%m-%dT%H:%M:%S%Z',
                    type: 'bar',
                    labels: true,
                    onclick: function (d) {
                        // Subchart
                        //{x: 2, value: 45, id: "d40e74c5-78e0-11e4-bada-1867b083cd22", index: 2, name: "Almuerzos"}
                        scope.subquery = {
                            servicio: d.id,
                            fecha: moment(scope.chart.category(d.index)).format(G.date_format)
                        };
                        _subload();
                    }
                },
                base_subdata = {
                    empty: {
                        label: { text: "No hay datos para mostrar en las fechas seleccionadas" }
                    },
                    x: 'lugar',
                    type: 'bar',
                    labels: true
                };

            function _reloadData(chart, data) {
                // Procesa los nombres que deben ser descargados
                var unload = [];
                angular.forEach(chart.data.names(), function(v, k) {
                    if (!data.names[k]) {
                        unload.push(k);
                    }
                });

                // Carga la nueva informacion
                chart.load(angular.extend({ unload: unload }, data));
                chart.data.names(data.names);
            }

            function _load() {
                $subchart.hide();
                $http.get(G.route('get_dashboard_info'), {params: scope.element})
                    .success(function load_succes(response) {
                        var data = response.data;

                        if (response.query) {
                            scope.query = response.query;
                        }

                        if (scope.chart === null) {
                            window.chart = scope.chart = c3.generate({
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
                            _reloadData(scope.chart, data);
                        }
                    });
            }

            function _loadFilters() {
                $http.get(G.route('get_dashboard_filter'))
                    .success(function(data) {
                        scope.filters = data;

                        for (var prop in data) {
                            if (data.hasOwnProperty(prop)) {
                                scope.filters[prop] = data[prop];
                            }
                        }
                    });
            }

            function _subload() {
                $subchart.show();
                $http.get(G.route('get_dashboard_more_info'), {params: scope.subquery})
                    .success(function subload_success(response) {
                        var data = response.data;

                        if (response.query) {
                            scope.subquery = response.query;
                        }

                        if (scope.subchart == null) {
                            scope.subchart = c3.generate({
                                bindto: '#subchart',
                                data: angular.extend(base_subdata, data),
                                axis: {
                                    x: {
                                        type: 'category'
                                    },
                                    rotated: true
                                }
                            });
                            scope.subchart.hide();
                            scope.subchart.show();
                        } else {
                            _reloadData(scope.subchart, data);
                        }
                    });
            }

            scope.element = {
                start: null,
                end: null,
                empresa: null,
                lugar: null,
                servicio: null
            };

            scope.query = null;
            scope.chart = null;
            scope.subchart = null;
            scope.subquery = null;
            scope.filters = {
                empresas: [],
                lugares: [],
                servicios: []
            };

            scope.load = function scope_load() {
                _load();
            };

            _load();
            _loadFilters();
        }])
    ;
})();