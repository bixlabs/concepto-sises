/**
 * Created by julian on 16/09/14.
 */
;
(function () {
    "use strict";
    angular.module(G.APP)
        .directive('sisesWidgetBeneficios', [function() {
            return {
                restricti: 'A',
                templateUrl: G.template('directive/widget_beneficios'),
                link: function(scope) {

                }
            };
        }])
    ;
})();