import * as gerais from '@/gerais.js';
import jQuery from 'jquery';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';


$(document).on('click', '#btn-receber', function () {
    var data = $('#data_recebimento').val();
    if (data == '') {
        event.preventDefault();
        msgToastr('Selecione a data do recebimento ...', 'info');
        $('#data_recebimento').focus();
        return;
    }
});