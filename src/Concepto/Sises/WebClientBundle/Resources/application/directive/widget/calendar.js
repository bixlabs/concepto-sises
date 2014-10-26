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
                        var week,
                            day,
                            month = m.format('M');

                        scope.month = m.format('MMMM');
                        scope.year = m.format('YYYY');
                        scope.firstWeek = m.startOf('month').week();
                        scope.lastWeek = m.endOf('month').week();

                        if (scope.lastWeek < scope.firstWeek) {
                            scope.lastWeek += m.weeksInYear();
                        }

                        for (week = scope.firstWeek; week <= scope.lastWeek; week++) {
                            for (day = 1; day <= 7; day++) {
                                var _m = moment(scope.year + '-W' + ("0" + week).slice(-2)).day(day);
                                if (typeof scope.days[day] === 'undefined') {
                                    scope.days[day] = _m.format('ddd');
                                }

                                scope.buildCalendar[week + '-' + day] = {
                                    currentMonth: _m.format('M') === month,
                                    display: _m.format('D')
                                };
                            }
                        }
                    }

                    scope.getCal = function getCal(week, day) {
                        return scope.buildCalendar[week + '-' + day];
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