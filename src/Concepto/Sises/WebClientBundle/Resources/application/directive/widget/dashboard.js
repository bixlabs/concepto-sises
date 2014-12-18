/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .directive('sisesDashboardEntregas', ['$http', '$q', function($http, $q) {

            /**
             * Construye una grafica con la configuracion especificada
             * @param config
             * @param data
             * @constructor
             */
            function BuildChart(config, data) {
                this.chart = null;
                this.chart_id = config.bindto ? config.bindto : '#chart';
                this.config = config;
                this.data = data || {};
            }

            /**
             * Actualiza la grafica
             * @param data nuevos datos a cargar en la grafica
             */
            BuildChart.prototype.reloadChart = function reloadChart(data) {
                var unload = [],
                    chart  = this.chart;
                angular.forEach(chart.data.names(), function(v, k) {
                    if (!data.names[k]) {
                        unload.push(k);
                    }
                });

                // Carga la nueva informacion
                chart.load(angular.extend({ unload: unload }, data));
                chart.data.names(data.names);
            };

            /**
             * Oculta la grafica
             */
            BuildChart.prototype.hide = function hide() {
                $(this.chart_id).hide();
            };

            /**
             * Muestra la grafica
             */
            BuildChart.prototype.show = function show() {
                $(this.chart_id).show();
            };

            /**
             * Carga información en la grafica y regresa una promesa con el resultado de la consulta
             * @param params parametros para la consulta
             * @returns {Promise} promesa de resultado de la consulta
             */
            BuildChart.prototype.loadData = function(params) {
                var that = this,
                    deferred = $q.defer();
                this.show();
                $http.get(G.route(this.config.data_url), {params: params})
                    .success(function BuildChart_loadData(response) {
                        var data = response.data,
                            query = response.query,
                            baseData = typeof that.data === "function" ? that.data() : that.data;

                        if (that.chart === null) {
                            var config = angular.extend({}, that.config, {
                                data: angular.extend(baseData, data)
                            });
                            that.chart = c3.generate(config);
                            that.hide();
                            that.show();
                        } else {
                            that.reloadChart(data);
                        }

                        deferred.resolve(query || {});
                    })
                    .error(function BuildChart_error() {
                        deferred.reject({});
                    });

                return deferred.promise;
            };

            return {
                restrict: 'AE',
                templateUrl: G.template('directive/widget_dashboard_entrega'),
                scope: true,
                link: function(scope) {

                    console.log("Init [dashboardEntregas]");
                    scope.ids = {
                        main: 'chart_' + G.guid(),
                        sub: 'chart_' + G.guid()
                    };

                    // Subgrafica
                    scope.subchart = new BuildChart({
                        data_url: 'get_dashboard_more_info',
                        bindto: '#' + scope.ids.sub,
                        axis: {
                            x: {
                                type: 'category'
                            },
                            rotated: true
                        }
                    }, function() {
                        return {colors: scope.chart.data.colors()};
                    });

                    // Grafica principal
                    scope.chart = new BuildChart({
                        data_url: 'get_dashboard_info',
                        bindto: '#' + scope.ids.main,
                        axis: { x: { type: 'category', tick: { format: '%d/%m/%Y' } } }
                    }, {
                        empty: { label: { text: "No hay datos para mostrar en las fechas seleccionadas" } },
                        x: 'fecha',
                        xFormat: '%Y-%m-%dT%H:%M:%S%Z',
                        type: 'bar',
                        labels: true,
                        onclick: function (d) {
                            scope.subquery = {
                                servicio: d.id,
                                fecha: moment(scope.chart.category(d.index)).format(G.date_format)
                            };
                            scope.subchart.loadData(scope.subquery).then(function(query) {
                                scope.subquery = query;
                            });
                        }
                    });

                    scope.element = {
                        start: null,
                        end: null,
                        empresa: null,
                        lugar: null,
                        servicio: null
                    };

                    scope.filters = {
                        empresas: [],
                        lugares: [],
                        servicios: []
                    };

                    scope.query = null;
                    scope.subquery = null;

                    // Carga los filtros
                    (function _loadFilters() {
                        $http.get(G.route('get_dashboard_filter'))
                            .success(function(data) {
                                scope.filters = data;

                                for (var prop in data) {
                                    if (data.hasOwnProperty(prop)) {
                                        scope.filters[prop] = data[prop];
                                    }
                                }
                            });
                    })();

                    scope.load = function() {
                        scope.chart.loadData(scope.element).then(function(query) {
                            scope.query = query;
                        });
                    };

                    scope.load();
                }
            };
        }]);
})();