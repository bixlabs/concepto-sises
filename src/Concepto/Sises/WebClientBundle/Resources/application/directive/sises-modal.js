/**
 * Created by julian on 5/09/14.
 */
;(function() {
    "use strict";

    angular.module(G.APP)

    /**
     * modal.open.recive
     * modal.close.ok
     * modal.close.cancel
     */
        .directive('sisesModal', ['$rootScope', function($r) {
            return {
                restrict: 'A',
                scope: true,
                replace: true,
                templateUrl: G.template('modal'),
                link: function(scope, el) {
                    scope.text = '';

                    scope.ok = function() {
                        el.modal('hide');
                        $r.$emit('modal.close.ok');
                    };

                    scope.cancel = function() {
                        el.modal('hide');
                        $r.$emit('modal.close.cancel');
                    };

                    $r.$on('modal.open.recive', function(event, data) {
                        scope.text = data.text;
                        scope.title = data.title;
                        el.modal('show');
                    });
                }
            };
        }])
    ;
})();