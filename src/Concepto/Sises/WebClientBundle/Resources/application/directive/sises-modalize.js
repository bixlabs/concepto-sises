/**
 * Created by julian on 9/09/14.
 */
;
(function () {
    "use strict";
    angular.module(G.APP)

        .directive('sisesModalize', ['$rootScope', function($r) {
            return {
                restrict: 'A',
                replace: true,
                transclude: true,
                templateUrl: G.template('modalize'),
                scope: {
                    handler: '=sisesModalize',
                    title: '@'
                },
                link: function(scope, el) {

                    scope.name = G.guid();
                    scope.actions = {};

                    var dissmiss_key = '';

                    scope.dismiss = function() {
                        el.modal('hide');
                    };

                    el.on('hide.bs.modal', function(){
                        if (dissmiss_key) {
                            $r.$emit('modalize.action.' + scope.handler.id, dissmiss_key);
                        }
                    });

                    angular.forEach(scope.handler.actions, function(value, key) {
                        scope.actions[key] = angular.extend({ style: 'default' }, value);

                        if (value.dismiss) {
                            dissmiss_key = key;
                        }
                    });

                    scope.call_action = function(key) {
                        if (dissmiss_key === key) {
                            scope.dismiss()
                        } else {
                            $r.$emit('modalize.action.' + scope.handler.id, key);
                        }
                    };

                    scope.handler.show = function() {
                        el.modal('show');
                    };

                    scope.handler.hide = function() {
                        el.modal('hide');
                    };
                }
            }
        }])
})();