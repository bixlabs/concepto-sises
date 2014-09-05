/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";
    angular.module(G.APP)
        .service('modalService', ['$rootScope', '$q', function($r, $q) {

            var open_modal = function(text, callback) {
                var deferred = $q.defer();

                $r.$on('modal.close.ok', function() {
                    deferred.resolve(true);
                });

                $r.$on('modal.close.cancel', function() {
                    deferred.reject(false);
                });

                $r.$emit('modal.open.recive', {text: text, title: 'Alerta'});

                deferred.promise.then(callback);
            };

            return {
                alert: function(text, callback) {
                    return open_modal(text, callback);
                }
            }
        }])
})();