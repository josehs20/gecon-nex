import * as gerais from '@/gerais.js';

const columns = [
    ['id', '#'],
    ['nome_fantasia', 'Nome Fantasia'],
    ['documento', 'Dcoumento'],
    ['ativo', 'Ativo'],
    ['celular', 'Celular'],
    ['email', 'E-mail'],
    ['acao', 'Ação', false, false] // Assumindo que 'orderable: false' e 'searchable: false' se tornariam parte do array
];

var routeGetFornecedor = $('#dataView').data('getFornecedor');
var yajraGetFornecedores = $('#dataView').data('yajraGetFornecedores');
gerais.montaDatatableYajra('tabela-fornecedores', columns, yajraGetFornecedores);

function mostrarDadosFornecedor(id) {
    if ($('#modalDadosFornecedor').hasClass('show')) {
        return; // Se o modal já está aberto, não faz nada
    }

    $('#modalDadosFornecedor').modal('show');
    gerais.bloquear();
    $.ajax({
        url: routeGetFornecedor,
        type: 'GET',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);

            if (response.success == true) {
                let fornecedor = response.fornecedor;

                // Atualiza o título do modal
                $('.modal-title', '#modalDadosFornecedor').text(fornecedor.nome_fantasia);
                // $('#labelStatus')
                //     .text('Status: ' + cliente.status.descricao)
                //     .removeClass()
                //     .addClass(cliente.status.badge);

                // Organiza os dados em listas distintas
                $('.modal-body', '#modalDadosFornecedor').html(`
    <div class="container">
        <!-- Lista de Dados Pessoais -->
        <ul class="list-group mb-3">
            <li class="list-group-item active">Dados Pessoais</li>
            <li class="list-group-item"><strong>Nome :</strong> ${fornecedor.nome}</li>
            <li class="list-group-item"><strong>Nome Fantasia :</strong> ${fornecedor.nome_fantasia}</li>
            <li class="list-group-item"><strong>Documento :</strong> ${fornecedor.documento}</li>
            <li class="list-group-item"><strong>Celular :</strong> ${fornecedor.celular}</li>
            <li class="list-group-item"><strong>Telefone :</strong> ${fornecedor.telefone}</li>
            <li class="list-group-item"><strong>Email :</strong> ${fornecedor.email}</li>
        </ul>

        <!-- Lista de Endereço -->
        <ul class="list-group">
           <li class="list-group-item active">Endereço</li>
                    <li class="list-group-item"><strong>Logradouro:</strong> ${fornecedor.endereco.logradouro ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Número:</strong> ${fornecedor.endereco.numero ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Bairro:</strong> ${fornecedor.endereco.bairro ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Cidade:</strong> ${fornecedor.endereco.cidade ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Estado (UF):</strong> ${fornecedor.endereco.uf ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>CEP:</strong> ${fornecedor.endereco.cep ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Complemento:</strong> ${fornecedor.endereco.complemento ?? 'Não informado'}</li>
        </ul>

    </div>
`);
                // Ajustar o modal para garantir que o conteúdo não ultrapasse os limites
                // $('#modalDadosFornecedor').css('max-height', '80vh').css('overflow-y', 'auto');
            } else {
                gerais.msgToastr(response.msg, 'warning');

            }
        },
        error: function (xhr) {
            gerais.msgToastr('Erro ao carregar os dados do cliente.', 'warning');
        }
    }).always(function () {
        gerais.desbloquear(); // Chama a função desejada
    });

}

$(document).on('click', '.mostrarDadosFornecedor', function () {
    let id = $(this).data('id'); // Obtém o ID do cliente
    mostrarDadosFornecedor(id); // Chama a função para buscar e exibir os dados
});
// Evento ao clicar no botão para fechar o modal
$(document).on('click', '#fecharModal', function () {
    // Fecha o modal usando Bootstrap modal('hide')
    $('#modalDadosFornecedor').modal('hide');
});
