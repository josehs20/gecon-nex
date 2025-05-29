import { constructSelect2, montaDatatable, bloquear, msgToastr, desbloquear } from '@/gerais.js'
var routeUsuariosCaixaPermissao = $('#dataView').data('routeUsuariosCaixaPermissao');
var routeUsuariosGet = $('#dataView').data('routeUsuariosGet');
var routeDeletePermissao = $('#dataView').data('routeDeletePermissao');
var caixa = $('#dataView').data('caixa');
console.log($('#dataView').data());

constructSelect2('loja_id');
constructSelect2('usuario_id', routeUsuariosGet, false, {
    caixa_id: caixa.id
});
montaDatatable('permissoesTable', routeUsuariosCaixaPermissao, {
    caixa_id: caixa.id
});

$('#formRecursosCaixa').on('submit', function (e) {
    e.preventDefault();
    bloquear();

    const form = $(this);
    const action = form.attr('action');
    const data = form.serialize();

    $.ajax({
        url: action,
        method: 'POST',
        data: data,
        success: function (res) {
            if (res.success == true) {
                msgToastr(res.msg, 'success');
            } else {
                msgToastr(res.msg, 'warning');
            }
        },
        error: function (err) {
            msgToastr('Houve um problema ao salvar os recursos.', 'error');
        },
        complete: function () {
            desbloquear();
        }
    });
});
$('#formPermissaoCaixa').on('submit', function (e) {
    e.preventDefault();
    bloquear();

    const form = $(this);
    const action = form.attr('action');
    const data = form.serialize();

    $.ajax({
        url: action,
        method: 'POST',
        data: data,
        success: function (res) {
            if (res.success == true) {
                msgToastr(res.msg, 'success');
                $('#permissoesTable').DataTable().ajax
                    .reload(); // Atualiza a tabela
                form.trigger("reset"); // Limpa o form
                $('#usuario_id').val(null).trigger('change'); // Limpa o select2
            } else {
                msgToastr(res.msg, 'warning');
            }
        },
        error: function (err) {
            msgToastr('Houve um problema ao salvar a permissão.', 'error');
        },
        complete: function () {
            desbloquear();
        }
    });
});

function excluirPermissao(caixa_permissao_id) {
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Esta ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            cancelButton: 'btn btn-secondary mx-1',
            confirmButton: 'btn btn-dark',
        },
        buttonsStyling: false,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sim, excluir!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: routeDeletePermissao,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Inclua o token CSRF se estiver usando Laravel
                },
                data: {
                    caixa_permissao_id: caixa_permissao_id
                },
                success: function (res) {
                    if (res.success == true) {
                        msgToastr(res.msg, 'success');
                        $('#permissoesTable').DataTable().ajax
                            .reload(); // Atualiza a tabela
                        form.trigger("reset"); // Limpa o form
                        $('#usuario_id').val(null).trigger('change'); // Limpa o select2
                    } else {
                        msgToastr(res.msg, 'warning');
                    }
                },
                error: function (err) {
                    msgToastr('Houve um problema ao excluir a permissão.', 'error');
                },
                complete: function () {
                    desbloquear();
                }
            });
        }
    });
}
