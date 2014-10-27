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
                    events: '=sisesCalendar'
                },
                link: function(scope) {

                    scope.buildCalendar = {};
                    scope.days = {};
                    scope.dates = {
                        min: null,
                        max: null
                    };

                    scope.currentDate = moment();

                    scope.$watch('currentDate', function(m) {
                        if (!moment.isMoment(m)) {
                            return;
                        }

                        buildCalendar(m);
                    }, true);

                    function buildCalendar(m) {
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

                        // Se asegura que se inicie siempre al principio de seman
                        looper.startOf('week');

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
                                display: looper.format('D')
                            };

                            looper.add(1, 'days');
                        }
                    }

                    scope.getCal = function getCal(week, day) {
                        return scope.buildCalendar[week + '-' + day];
                    };

                    scope.debug = function debug() {
                        console.log("calendar", scope.buildCalendar);
                        console.log("weeks", scope.weeks);
                        console.log("days", scope.days);
                    };

                    scope.prev = function prev() {
                        scope.currentDate = scope.currentDate.subtract(1, 'month');
                    };
                    scope.next = function next() {
                        scope.currentDate = scope.currentDate.add(1, 'month');
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