import * as gerais from '@/gerais.js';

gerais.maskDinheiro('valorInicial')
var routeHome = $('#dataView').data('routeHome');
var routeValidate = $('#dataView').data('routeValidate');
var routGetCaixa = $('#dataView').data('routGetCaixa');
var routeUpdateStatusCaixa = $('#dataView').data('routeUpdateStatusCaixa');
var rodarFeath = $('#dataView').data('rodarFeath');
var statusValido = $('#dataView').data('statusValido');
var removeStrorages = $('#dataView').data('removeStrorages');
pedir_validacao();

if (removeStrorages) {
    localStorage.removeItem('estoques');
    localStorage.removeItem('visualizarVoltarVenda');
    localStorage.removeItem('obj_venda');
}


function pedir_validacao() {
    $('#modalAbrirCaixa').modal('show');
}

function forcarMudancaDeStatus() {
    $('#botaoForcarMudancaDeStatus').text('Aguarde...').attr('disabled', true);
    $.ajax({
        url: routeUpdateStatusCaixa, // Substitua com a URL da sua rota
        type: 'POST', // Método POST
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                'content') // Inclua o token CSRF se estiver usando Laravel
        },
        data: {
            status_id: statusValido
        },
        success: function (response) {
            if (response.success == true) {
                $('#statusCaixaVenda').text(response.status);
                msgToastr(response.msg, 'success');
                statuAtualCaixa = response.caixa.status_id
                fetchData();
            } else {
                msgToastr(response.msg, 'error')

            }
        },
        error: function (xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}

function fetchData() {
    $.ajax({
        url: routGetCaixa, // Substitua pela sua rota no Laravel
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            // Manipule a resposta aqui
            if (response.caixa.status_id == statusValido) {
                $('#botaoForcarMudancaDeStatus').addClass('d-none');

                $('#botaoSubmitAbriCaixa').attr('disabled', false);
            } else {
                $('#botaoSubmitAbriCaixa').attr('disabled', true);
            }
            $('#statuAtual').text(response.status);
            $('#ultimaAtualizacao').text(response.hora);
            // Exiba os dados ou faça o que precisar com eles

        },
        error: function (xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}
if (rodarFeath) {
    setInterval(fetchData, 10000);
}
// Configura o intervalo para chamar a função a cada 5 segundos
// Função para exibir o alerta após a página carregar
// Aqui você pode verificar condições antes de exibir o alerta, se necessário

// // Executar algo após o modal ser fechado completamente
$('#modalAbrirCaixa').on('hidden.bs.modal', function (e) {
    pedir_validacao();
    //  window.location.href = routeHome; // Em caso de erro, redireciona para a página inicial

});

