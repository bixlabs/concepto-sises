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
                templateUrl: G.template('modal'),
                link: function(scope, el) {
                    var m = el.find('#sises-modal');
                    scope.text = '';

                    scope.ok = function() {
                        m.modal('hide');
                        $r.$emit('modal.close.ok');
                    };

                    scope.cancel = function() {
                        m.modal('hide');
                        $r.$emit('modal.close.cancel');
                    };

                    $r.$on('modal.open.recive', function(event, data) {
                        scope.text = data.text;
                        m.modal('show');
                    });
                }
            };
        }])
    ;
})();