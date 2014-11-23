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
    G.modules.RRHH = 'RRHH';

    G.BuildModule(G.modules.RRHH, {
        register: true,
        label: 'RRHH',
        category: 'empresas',
        resource: 'recurso_humano'
    });
})();