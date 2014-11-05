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
        label: 'Liquidaciones',
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
        'RestResources', '$scope',
        function(RR, scope) {
            var $form = $('.form-single');

            scope.servicios = RR.serv_operativo.query();

            scope.seleccion = {
                servicio_id: null,
                entrega_id: null,
                entrega: null,
                curDate: null
            };

            scope.calendar = {
                showingDate: moment(),
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
                },
                next: function _nextMonth() {
                    var m = moment(scope.calendar.showingDate).add(1, 'month');
                    if (scope.calendar.end && m.isAfter(scope.calendar.end)) {
                        scope.calendar.showingDate = moment(scope.calendar.end);
                    } else {
                        scope.calendar.showingDate = m;
                    }
                }
            };

            scope.getCal = function getCal(week, day) {
                var m = scope.calendar.dates[week + '-' + day];
                return m ? m : null;
            };

            scope.saveDate = function saveDate() {
                $form.fadeOut(function() {
                    scope.seleccion.curDate = null;
                });
            };

            scope.showCurDate = function showCurDate() {
                if (null !== scope.seleccion.curDate) {
                    return scope.seleccion.curDate.date.format('ll');
                }

                return null;
            };

            scope.$watch('seleccion.curDate', function(val) {
                console.log(val);
            });


            scope.openDate = function openDate(week, day, $event) {

                if (scope.seleccion.curDate !== null) {
                    return;
                }

                scope.seleccion.curDate = angular.extend({}, scope.getCal(week, day));

                // Calcula la esquina sup/izq de la fecha
                var top = $event.target.offsetTop + $event.target.offsetParent.offsetTop,
                    left = $event.target.offsetLeft + $event.target.offsetParent.offsetLeft,
                    h = $event.target.clientHeight / 2,
                    w = $event.target.clientWidth / 2,
                    f_w = $form.width() / 2,
                    f_h = $form.height() / 2;

                console.log($event);

                // Mueve el formulario a la posicion de la fecha
                $form.css({
                    position: 'absolute',
                    top: top + h - f_h,
                    left: left + w - f_w});
                $form.fadeIn();
            };

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

            scope.$watch('calendar.showingDate', buildCalendar);

            function buildCalendar(current) {
                if (!current) {
                    return; // No contruir ante null
                }

                var m = moment(current),
                    month = m.format('M'),
                    week, day, dayTitle,
                    startMonth = moment(m).startOf('month'),
                    endMonth = moment(m).endOf('month'),
                    looper = moment(startMonth);

                // Define el mes actual
                scope.calendar.month = m.format('MMMM');
                // Define el año actual
                scope.calendar.year = m.format('YYYY');

                scope.calendar.days = [];
                scope.calendar.dayTitle = [];
                scope.calendar.weeks = [];
                scope.calendar.dates = [];

                // Se asegura que se inicie siempre al principio de semana y al principio del dia
                looper.startOf('week');
                looper.startOf('day');

                // Construye calendario
                while (looper.isBefore(endMonth)) {
                    week = looper.format('ww');
                    day = looper.format('E');
                    dayTitle = looper.format('dddd');

                    if (scope.calendar.weeks.indexOf(week) === -1) {
                        scope.calendar.weeks.push(week);
                    }

                    if (scope.calendar.days.indexOf(day) === -1) {
                        scope.calendar.dayTitle.push(dayTitle);
                        scope.calendar.days.push(day);
                    }

                    scope.calendar.dates[week + '-' + day] = {
                        currentMonth: looper.format('M') === month,
                        //inRange: range !== null ? range.contains(looper.toDate()): false,
                        date: moment(looper),
                        display: looper.format('D')
                    };

                    looper.add(1, 'days');
                }
            }
        }
    ]);
})();