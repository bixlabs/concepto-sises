/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    var modules = [
        'RESOURCE',
        'ui.router',
        'localytics.directives',
        'plupload.directive',
        'ui.bootstrap',
        'ngToast'
    ];

    angular.module(G.APP, M.concat(modules))
        .factory('sisesToastOnError', ['$q', 'ngToast', function($q, ngToast) {
            return {
                response: function(response) {
                    if (response.config && response.config.method) {

                        switch (response.config.method) {
                            case "POST":
                            case "PUT":
                                ngToast.create({
                                    'content': '<i class="glyphicon glyphicon-ok"></i> Peticion procesada',
                                    'class': 'success',
                                    'verticalPosition': 'top',
                                    'horizontalPosition': 'center'
                                });
                                break;
                            default:
                                break;
                        }

                    }
                    return response || $q.when(response);
                },
                responseError: function(response) {
                    var msg = '<i class="glyphicon glyphicon-remove"></i>'
                        + ' <strong>' + response.status + '</strong> '
                        + 'Ha ocurrido un error y no se pudo procesar la informacion';

                    ngToast.create({
                        'content': msg,
                        'class': 'danger',
                        'verticalPosition': 'top',
                        'horizontalPosition': 'center'
                    });

                    window.toast = ngToast;

                    return $q.reject(response);
                }
            };
        }])
        .config([
            'plUploadServiceProvider', '$provide', '$httpProvider',
            function (plUploadServiceProvider, $provide, $httpProvider) {
                //plUploadServiceProvider.setConfig('flashPath', 'bower_components/plupload-angular-directive/plupload.flash.swf');
                //plUploadServiceProvider.setConfig('silverLightPath', 'bower_components/plupload-angular-directive/plupload.silverlight.xap');
                plUploadServiceProvider.setConfig('uploadPath', G.route('_uploader_upload_documentable'));

                // Format monkeypatch angular ui bootstrap datepicker
                $provide.decorator('dateParser', function($delegate){

                    var oldParse = $delegate.parse;
                    $delegate.parse = function(input, format) {
                        if ( !angular.isString(input) || !format ) {
                            return input;
                        }
                        return oldParse.apply(this, arguments);
                    };

                    return $delegate;
                });

                $httpProvider.interceptors.push('sisesToastOnError');
            }])
        .run([
            '$rootScope', '$state', '$stateParams', 'modalService', 'MenuService',
            function ($r, $state, $sP, mS, MS) {
                $r.authState = false;
                $r.go = $state.go;
                $r.moment = moment;
                G.stateGo = $state.go;
                $r.refresh = function(state, params) {
                    $state.go(state, params, {
                        reload: true,
                        inherit: false,
                        notify: true
                    })
                };
                $r.template = G.template;
                $r.routeParams = $sP;
                $r.modal = mS;

                MS.register({
                    name: 'empresas',
                    label: 'Empresa',
                    is_category: true
                });
                MS.register({
                    name: 'entrega_category',
                    label: 'Entregas',
                    is_category: true
                });
                MS.register({
                    name: 'liquidacion_category',
                    label: 'Liquidaciones',
                    is_category: true
                });
            }
        ])
    ;

    var setContent = function setContent() {
        $('.main-container').css('margin-top', $('.navbar').height());
    };

    // Se asegura que el contenido siempre sea visible
    $(window).resize(setContent);
    setContent();
})();