import jQuery from 'jquery';
const $ = jQuery;
import { montaDatatableYajra } from '../../gerais.js';

const getGtinsYajra = '/admin/admin/gtin/yajra';
const getGtinsId = '/admin/admin/gtin/show';
const routeUpdateGtin = '/admin/admin/gtin/update';
const routeStoreGtin = '/admin/admin/gtin/post';
const routeDeleteGtin = '/admin/admin/gtin/delete';
$('#CadastrarGtin').on('click', function () {
    $('#modalCadastrar').modal('show');
});
// Enviar o formulário via AJAX
$('#formCadastrar').on('submit', function (e) {
    e.preventDefault(); // Evita o envio do formulário padrão

    let formData = $(this).serialize(); // Serializa os dados do formulário

    $.ajax({
        url: routeStoreGtin, // Rota para cadastrar o GTIN
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                msgToastr(response.msg, 'success');

                $('#modalEditar').modal('hide');
            } else {
                msgToastr(response.msg, 'error');
            }
        }
    }).always(function () {
        desbloquear();
        $('#tabela-gtin-admin').DataTable().ajax.reload(null, false);

    });
});
// Função para abrir o modal de edição e preencher os dados
$(document).on('click', '.btn-warning', function () {
    let id = $(this).data('id'); // Obtém o ID do atributo data-id do botão
    $.ajax({
        url: getGtinsId,
        data: {
            id: id
        },
        method: 'GET',
        success: function (data) {
            $('#id_gtin').val(data.id);
            $('#gtin').val(data.gtin);
            $('#descricao').val(data.descricao);
            $('#tipo').val(data.tipo);
            $('#quantidade').val(data.quantidade);
            $('#comprimento').val(data.comprimento);
            $('#altura').val(data.altura);
            $('#largura').val(data.largura);
            $('#peso_bruto').val(data.peso_bruto);
            $('#peso_liquido').val(data.peso_liquido);
            $('#ncm').val(data.ncm);

            $('#modalEditar').modal('show');
        }
    });
});

// Função para salvar a edição
$(document).on('submit', '#formEditar', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário
    bloquear();
    let formData = $(this).serialize();

    $.ajax({
        url: routeUpdateGtin,
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                msgToastr(response.msg, 'success');

                $('#modalEditar').modal('hide');
            } else {
                msgToastr(response.msg, 'error');
            }
        }
    }).always(function () {
        desbloquear();
        $('#tabela-gtin-admin').DataTable().ajax.reload(null, false);

    });
});

// Função para excluir um item
$(document).on('click', '.btn-danger', function () {
    let id = $(this).data('id'); // Obtém o ID do atributo data-id do botão
    if ($(this).find('i.bi-trash').length > 0) {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                bloquear();
                $.ajax({
                    url: routeDeleteGtin,
                    method: 'POST',
                    data: {
                        id: id,
                        _token: $('meta[name="csrf-token"]').attr(
                            'content') // 🔹 Adiciona o CSRF Token
                    },
                    success: function (response) {
                        if (response.success) {
                            msgToastr(response.msg, 'success');

                            $('#modalEditar').modal('hide');
                        } else {
                            msgToastr(response.msg, 'error');
                        }
                    }
                }).always(function () {
                    desbloquear();
                    $('#tabela-gtin-admin').DataTable().ajax.reload(null,
                        false);

                });
            }
        });
    }
});
const columns = [
    ['id', 'ID'],
    ['gtin', 'GTIN'],
    ['descricao', 'Descrição'],
    ['ncm', 'NCM'],
    ['ultima_verificacao', 'Última validação'],
    ['prioridade', 'Prioridade'],
    ['acao', 'Ação'],

];
montaDatatableYajra('tabela-gtin-admin', columns, getGtinsYajra);
