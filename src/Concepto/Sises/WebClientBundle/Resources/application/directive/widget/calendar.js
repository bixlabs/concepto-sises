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

        .directive('sisesCalendar', function() {
            return {
                restrict: 'A',
                replace: true,
                templateUrl: G.template('directive/widget_calendar'),
                scope: {
                    events: '=sisesCalendar',
                    asignacion: '='

                },
                link: function(scope) {

                    /** @namespace scope.asignacion.fechaInicio */
                    /** @namespace scope.asignacion.fechaCierre */

                    scope.buildCalendar = {};
                    scope.days = {};
                    scope.dates = {
                        min: null,
                        max: null
                    };

                    scope.showingDate = moment().startOf('day');
                    scope.$watch('showingDate', function(m) {
                        if (!moment.isMoment(m)) {
                            return;
                        }

                        buildCalendar(m);
                    }, true);

                    scope.$watch('asignacion', function() {
                        if (scope.asignacion) {
                            scope.showingDate = moment(scope.asignacion.fechaInicio).startOf('day');
                        }
                    }, true);

                    function alignDate() {
                        if (scope.asignacion) {
                            var _fechaInicio = moment(scope.asignacion.fechaInicio).startOf('day'),
                                _fechaCierre = moment(scope.asignacion.fechaCierre).endOf('day');
                            if (scope.showingDate.isBefore(_fechaInicio)) {
                                scope.showingDate = _fechaInicio;
                            } else if (scope.showingDate.isAfter(_fechaCierre)) {
                                scope.showingDate = _fechaCierre;
                            }
                        }
                    }

                    /**
                     * Construye el calendario a mostrar
                     * @param m
                     */
                    function buildCalendar(m) {

                        var month = m.format('M'),
                            week, day, dayTitle,
                            startMonth = moment(m).startOf('month'),
                            endMonth = moment(m).endOf('month'),
                            looper = moment(startMonth),
                            range = null;

                        // Define el mes actual
                        scope.month = m.format('MMMM');
                        // Define el año actual
                        scope.year = m.format('YYYY');

                        if (scope.asignacion) {
                            range = moment.range(
                                moment(scope.asignacion.fechaInicio).startOf('day').toDate(),
                                moment(scope.asignacion.fechaCierre).endOf('day').toDate()
                            );
                        }

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
                            dayTitle = looper.format('dddd');

                            if (scope.weeks.indexOf(week) === -1) {
                                scope.weeks.push(week);
                            }

                            if (scope.days.indexOf(day) === -1) {
                                scope.dayTitle.push(dayTitle);
                                scope.days.push(day);
                            }

                            scope.buildCalendar[week + '-' + day] = {
                                currentMonth: looper.format('M') === month,
                                inRange: range !== null ? range.contains(looper.toDate()): false,
                                display: looper.format('D')
                            };

                            looper.add(1, 'days');
                        }
                    }

                    scope.getCal = function getCal(week, day) {
                        return scope.buildCalendar[week + '-' + day];
                    };

                    scope.prev = function prev() {
                        scope.showingDate.subtract(1, 'month');
                        alignDate();
                    };
                    scope.next = function next() {
                        scope.showingDate.add(1, 'month');
                        alignDate();
                    };

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
        })
    ;
})();