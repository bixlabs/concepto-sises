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

    //G.Base.Controller
    var module = G.BuildModule('LIQUIDACION', {
        register: 'liquidacion',
        label: 'Realizar Liquidaciones',
        category: 'liquidacion_category',
        states: [
            {
                suffix: '',
                templateUrl: G.template('liquidacion/index'),
                controller: 'LIIndex',
                url: '/liquidacion'
            }
        ]
    });

    module.controller('LIIndex', [
        'RestResources', '$scope', '$http', 'ngToast',
        function(RR, scope, $http, ngToast) {
            var $form = $('.form-single');

            scope.servicios = RR.serv_operativo.query();

            // Otros campos
            scope.seleccion = {
                servicio: null,
                servicio_id: null,
                entrega: null,
                curDate: null,
                servicios: {}
            };

            // Estructura del calendario
            scope.calendar = {
                showingDate: null,
                dates: {},
                range: null,
                month: null,
                year: null,
                start: null,
                end: null,
                days: [],
                weeks: [],
                dayTitle: [],
                prev: function _prevMonth() {
                    var m = moment(scope.calendar.showingDate).subtract(1, 'month');
                    if (scope.calendar.start && m.isBefore(scope.calendar.start)) {
                        scope.calendar.showingDate = moment(scope.calendar.start);
                    } else {
                        scope.calendar.showingDate = m;
                    }
                    scope.buildCalendar();
                },
                next: function _nextMonth() {
                    var m = moment(scope.calendar.showingDate).add(1, 'month');
                    if (scope.calendar.end && m.isAfter(scope.calendar.end)) {
                        scope.calendar.showingDate = moment(scope.calendar.end);
                    } else {
                        scope.calendar.showingDate = m;
                    }
                    scope.buildCalendar();
                }
            };

            /**
             * Devuelve los datos de la fecha
             * @param week
             * @param day
             * @returns {*}
             */
            scope.getCal = function getCal(week, day) {
                var m = scope.calendar.dates[week + '-' + day];
                return m ? m : null;
            };

            /**
             * Devuelve el almacen para la fecha
             * @param week
             * @param day
             * @returns {*}
             */
            scope.getStore = function getStore(week, day) {
                return scope.seleccion.servicios[week + '-' + day];
            };

            /**
             * Muestra la cantidad en el almacen
             * @param week
             * @param day
             * @returns {string}
             */
            scope.displayStore = function displayStore(week, day) {
                var store = scope.getStore(week, day);

                if (store) {
                    return " (" + store.cantidad + ")";
                }

                return '';
            };

            /**
             * Almacena la informacion del formulario
             */
            scope.saveDate = function saveDate() {
                $form.fadeOut(function saveData_fadeOut() {
                    if (!scope.seleccion.curDate.cantidad || isNaN(scope.seleccion.curDate.cantidad)) {
                        scope.seleccion.curDate = null;
                        return;
                    }

                    scope.$apply(function saveData_apply() {
                        var storeId = scope.seleccion.curDate.date.format('ww-E'),
                            store = scope.seleccion.servicios[storeId];

                        scope.seleccion.servicios[storeId] = {
                            liquidacion: scope.seleccion.entrega.id,
                            servicio: scope.seleccion.servicio_id,
                            cantidad: scope.seleccion.curDate.cantidad,
                            fechaEntrega: scope.seleccion.curDate.date.format('YYYY-MM-DDTHH:mm:ssZZ')
                        };

                        if (store) {
                            scope.seleccion.servicios[storeId].id = store.id;
                        }

                        scope.seleccion.curDate = null;
                    });
                });
            };

            scope.guardar = function guardar() {
                var servicios = [];

                angular.forEach(scope.seleccion.servicios, function(s) {
                    servicios.push(s);
                });

                if (servicios.length === 0) {
                    ngToast.create({
                        'content': '<i class="glyphicon glyphicon-exclamation-sign"></i> No hay datos que guardar',
                        'class': 'info',
                        'verticalPosition': 'top',
                        'horizontalPosition': 'center'
                    });
                }

                window.SS = scope.seleccion;
                $http.post(G.route('post_servicio_liquidacion', {
                    id: scope.seleccion.servicio_id
                }), {
                    liquidaciones: servicios
                }).success(function() {
                    scope.servicios = RR.serv_operativo.query(function() {
                        angular.forEach(scope.servicios, function(servicio) {
                            if (servicio.id === scope.seleccion.servicio_id) {
                                scope.seleccion.servicio = servicio;
                            }
                        });
                    });


                });
            };

            /**
             * Oculta el formulario sin modificar
             */
            scope.hideDate = function hideDate() {
                $form.fadeOut(function hideDate_fadeOut() {
                    scope.seleccion.curDate = null;
                });
            };

            /**
             * Muestra la fecha para el formulario
             * @returns {*}
             */
            scope.showCurDate = function showCurDate() {
                if (null !== scope.seleccion.curDate) {
                    return scope.seleccion.curDate.date.format('ll');
                }

                return null;
            };

            /**
             * Muestra el formulario para la fecha espeficiada
             * @param week
             * @param day
             * @param $event
             */
            scope.openDate = function openDate(week, day, $event) {

                if (scope.seleccion.curDate !== null) {
                    return;
                }

                var data = scope.getStore(week, day),
                    info = scope.getCal(week, day);

                if (!info.inRange) {
                    return;
                }

                scope.seleccion.curDate = angular.extend(
                    data ? {cantidad: data.cantidad}: {},
                    scope.getCal(week, day)
                );

                // Calcula la esquina sup/izq de la fecha
                var top = $event.target.offsetTop + $event.target.offsetParent.offsetTop,
                    left = $event.target.offsetLeft + $event.target.offsetParent.offsetLeft,
                    h = $event.target.clientHeight / 2,
                    w = $event.target.clientWidth / 2,
                    f_w = $form.width() / 2,
                    f_h = $form.height() / 2;

                // Mueve el formulario a la posicion de la fecha
                $form.css({
                    position: 'absolute',
                    top: top + h - f_h,
                    left: left + w - f_w});
                $form.fadeIn();
            };

            scope.$watch('seleccion.servicio', function(s) {
                if (s) {
                    scope.seleccion.servicio_id = s.id;

                    scope.seleccion.servicios = {};

                    angular.forEach(s.liquidaciones, function(l) {
                        var m = moment(l.fechaEntrega);
                        scope.seleccion.servicios[m.format('ww-E')] = l;
                    })

                }
            });

            // Actualiza los datos necesario para construir el calendario
            scope.$watch('seleccion.entrega', function(entrega) {
                if (!entrega) {
                    return;
                }

                var startDate = moment(entrega.fechaInicio).startOf('day'),
                    endDate = moment(entrega.fechaCierre).endOf('day'),
                    range = moment.range(
                        moment(startDate).toDate(),
                        moment(endDate).toDate()
                    );

                scope.calendar.showingDate = startDate;
                scope.calendar.range = range;
                scope.calendar.start = startDate;
                scope.calendar.end = endDate;
            });

            scope.showCalendar = function _showCalendar() {
                return scope.calendar.weeks.length > 0;
            };

            scope.disableRefresh = function  disableRefresh() {
                return !(scope.seleccion.servicio_id && scope.seleccion.entrega);
            };

            /**
             * Inicializa el calendario
             * @private
             */
            function _resetCalendar() {
                scope.calendar.days = [];
                scope.calendar.dayTitle = [];
                scope.calendar.weeks = [];
                scope.calendar.dates = [];
            }

            /**
             * Construye el calendario
             */
            scope.buildCalendar = function _buildCalendar() {
                if (!scope.calendar.showingDate) {
                    return; // No contruir ante null
                }

                var m = moment(scope.calendar.showingDate),
                    month = m.format('M'),
                    startMonth = moment(m).startOf('month'),
                    endMonth = moment(m).endOf('month'),
                    looper = moment(startMonth);

                // Define el mes actual
                scope.calendar.month = m.format('MMMM');
                // Define el año actual
                scope.calendar.year = m.format('YYYY');

                // Inicializa los datos del calendario
                _resetCalendar();

                // Se asegura que se inicie siempre al principio de semana y al principio del dia
                looper.startOf('week');
                looper.startOf('day');

                // Construye calendario
                while (looper.isBefore(endMonth)) {
                    var week = looper.format('ww');
                    var day = looper.format('E');
                    var dayTitle = looper.format('dddd');

                    if (scope.calendar.weeks.indexOf(week) === -1) {
                        scope.calendar.weeks.push(week);
                    }

                    if (scope.calendar.days.indexOf(day) === -1) {
                        scope.calendar.dayTitle.push(dayTitle);
                        scope.calendar.days.push(day);
                    }

                    scope.calendar.dates[week + '-' + day] = {
                        currentMonth: looper.format('M') === month,
                        inRange: scope.calendar.range !== null ? scope.calendar.range.contains(looper.toDate()): false,
                        date: moment(looper),
                        display: looper.format('D')
                    };

                    looper.add(1, 'days');
                }
            };

            _resetCalendar();
        }
    ]);
})();