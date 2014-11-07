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

        .directive('sisesCalendar', ['$rootScope', function($r) {
            return {
                restrict: 'A',
                replace: true,
                templateUrl: G.template('directive/widget_calendar'),
                scope: {
                    events: '=sisesCalendar',
                    asignacion: '=',
                    selectedDate: '='
                },
                link: function(scope) {

                    /**
                     * @type {DateRange|null}
                     */
                    var range = null;

                    /** @namespace scope.asignacion.fechaInicio */
                    /** @namespace scope.asignacion.fechaCierre */
                    /** @namespace scope.asignacion.realizadas */

                    scope.buildCalendar = {};
                    scope.dates = {
                        min: null,
                        max: null
                    };

                    scope.showingDate = null;
                    scope.$watch('showingDate', function watch_showingDate(m, oldM) {
                        if (moment.isMoment(m) && !m.isSame(oldM)) {
                            _buildCalendar(m);
                        }
                    }, true);

                    scope.$watch('asignacion', _updateRange, true);

                    /**
                     * Actualiza el rango del calendario
                     * @private
                     */
                    function _updateRange() {
                        if (scope.asignacion && scope.asignacion.entrega) {
                            range = moment.range(
                                moment(scope.asignacion.entrega.fechaInicio).startOf('day').toDate(),
                                moment(scope.asignacion.entrega.fechaCierre).endOf('day').toDate()
                            );

                            if (!moment.isMoment(scope.showingDate) || !range.contains(scope.showingDate)) {
                                scope.showingDate = moment(scope.asignacion.entrega.fechaInicio).startOf('day');
                            } else {
                                _buildCalendar(scope.showingDate);
                            }
                        }
                    }

                    /**
                     * Determina si el moment indicado esta presente en la lista de entregas
                     * realizadas
                     * @private
                     *
                     * @param m
                     * @returns {boolean}
                     */
                    function _hasEntregas(m) {
                        var i, entrega;

                        /** @namespace entrega.fechaEntrega */

                        if (!scope.asignacion || !scope.asignacion.realizadas || scope.asignacion.realizadas.length === 0) {
                            return false;
                        }

                        for (i = 0; i < scope.asignacion.realizadas.length; i++) {
                            entrega = scope.asignacion.realizadas[i];

                            if (moment(entrega.fechaEntrega).isSame(m)) {
                                return true;
                            }
                        }

                        return false;
                    }

                    /**
                     * Construye el calendario a mostrar
                     * @param m
                     * @private
                     */
                    function _buildCalendar(m) {

                        var month = m.format('M'),
                            week, day, dayTitle,
                            startMonth = moment(m).startOf('month'),
                            endMonth = moment(m).endOf('month'),
                            looper = moment(startMonth);

                        // Define el mes actual
                        scope.month = m.format('MMMM');
                        // Define el año actual
                        scope.year = m.format('YYYY');

                        scope.days = [];
                        scope.dayTitle = [];
                        scope.weeks = [];
                        scope.buildCalendar = [];

                        // Se asegura que se inicie siempre al principio de semana y al principio del dia
                        looper.startOf('week');
                        looper.startOf('day');

                        // Construye calendario
                        while (looper.isBefore(endMonth)) {
                            week = looper.format('ww');
                            day = looper.format('E');
                            dayTitle = looper.format('ddd');

                            if (scope.weeks.indexOf(week) === -1) {
                                scope.weeks.push(week);
                            }

                            if (scope.days.indexOf(day) === -1) {
                                scope.dayTitle.push(dayTitle);
                                scope.days.push(day);
                            }

                            scope.buildCalendar['_day' + week + '-' + day] = {
                                currentMonth: looper.format('M') === month,
                                inRange: range !== null ? range.contains(looper.toDate()): false,
                                date: moment(looper),
                                realized: _hasEntregas(looper),
                                display: looper.format('D')
                            };

                            looper.add(1, 'days');
                        }
                    }

                    /**
                     * Selecciona la fecha como activa
                     * @param m
                     */
                    scope.setCurDate = function setCurDate(m) {
                        if (m.inRange) {
                            scope.selectedDate = moment(m.date).format('YYYY-MM-DDTHH:mm:ssZZ');
                        }
                    };

                    /**
                     * Devuelve las clasess (CSS) de la celda
                     * @param m
                     * @returns {string}
                     */
                    scope.getClass = function getClass(m) {
                        var classes = '';

                        if (m.inRange) {
                            classes = 'in-range';
                        }

                        if (m.realized) {
                            classes += ' has-realized';
                        }

                        return classes;
                    };

                    /**
                     * Devuelve la fecha apropiada
                     * @param week
                     * @param day
                     * @returns {*}
                     */
                    scope.getCal = function getCal(week, day) {
                        return scope.buildCalendar['_day' + week + '-' + day];
                    };

                    /**
                     * Mueve el calendario un mes hacia atras
                     */
                    scope.prev = function prev() {
                        var m = moment(scope.showingDate).subtract(1, 'month');
                        if (range && range.start && m.isBefore(range.start)) {
                            scope.showingDate = moment(range.start);
                        } else {
                            scope.showingDate = m;
                        }
                    };

                    /**
                     * Mueve el calendario un mes hacia delante
                     */
                    scope.next = function next() {
                        var m = moment(scope.showingDate).add(1, 'month');
                        if (range && range.end && m.isAfter(range.end)) {
                            scope.showingDate = moment(range.end);
                        } else {
                            scope.showingDate = m;
                        }
                    };

                    /**
                     * Devuelve un rango [] de valores
                     *
                     * @param min
                     * @param max
                     * @param step
                     * @returns {Array}
                     */
                    scope.range = function range(min, max, step) {
                        var input = [], i;

                        step = step || 1;

                        for (i = min; i <= max; i += step) {
                            input.push(i);
                        }

                        return input;
                    }
                }
            }
        }])
    ;
})();