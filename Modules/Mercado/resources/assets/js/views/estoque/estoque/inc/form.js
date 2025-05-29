import * as gerais from '@/gerais.js';
    $(window).on('load', function() {
        gerais.maskQtd('quantidade_total');
        gerais.maskQtd('quantidade_disponivel');
        gerais.maskQtd('quantidade_minima');
        gerais.maskQtd('quantidade_maxima');
    });

    $(document).ready(function() {
        gerais.maskQtd('quantidade_total');
        gerais.maskQtd('quantidade_disponivel');
        gerais.maskQtd('quantidade_minima');
        gerais.maskQtd('quantidade_maxima');
    });
