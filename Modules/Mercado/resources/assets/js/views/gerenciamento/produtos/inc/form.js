import * as gerais from '@/gerais.js';

var rotaUnidadeMedida = $('#dataView').data('rota-unidade-medida');
var rotaClassificacaoProduto = $('#dataView').data('rota-classificacao-produto')
var routeBuscaGtin = $('#dataView').data('route-busca-gtin')
// $(window).load(function() {
gerais.maskDinheiro('preco_venda');
gerais.maskDinheiro('preco_custo');
// });
$('#loja_id').on('select2:unselecting', function (e) {
    // Impede a remoção de opções protegidas
    var selected = $(e.params.args.data.element).prop('disabled');
    if (selected) {
        e.preventDefault();
    }
});
gerais.constructSelect2('loja_id');
gerais.constructSelect2('fabricante_id');
gerais.constructSelect2('unidade_medida', rotaUnidadeMedida);
gerais.constructSelect2('classificacao_id', rotaClassificacaoProduto);

$('#cod_barras').on('input', function () {
    let codigo = $(this).val();

    if (codigo.length > 8) {
        $.ajax({
            url: routeBuscaGtin, // Defina a rota correta no Laravel
            method: 'GET',
            data: {
                cod_barras: codigo
            },
            success: function (response) {
                $('#captcha-container').html(response.html);
            },
            error: function () {
                alert("Erro ao buscar produto.");
            }
        });
    }
});
