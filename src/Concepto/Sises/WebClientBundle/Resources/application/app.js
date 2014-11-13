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
        'ui.bootstrap'
    ];

    angular.module(G.APP, M.concat(modules))
        .config(['plUploadServiceProvider', '$provide', function (plUploadServiceProvider, $provide) {
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