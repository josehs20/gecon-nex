import * as gerais from '@/gerais.js';
const columns = [
    ['id', 'ID'],
    ['nome', 'Nome'],
    ['cnpj', 'CNPJ'],
    ['razao_social', 'Razão social'],
    ['inscricao_estadual', 'Inscrição estadual'],
    ['email', 'Email'],
    ['ativo', 'Ativo'],
    ['acao', 'Ação', false, false]
];
var getFabricantes = $('#dataView').data('getFabricantes');

gerais.montaDatatableYajra('tabela-unidade-media', columns, getFabricantes);
$(document).on('click', '.btn-mostrar-fabricante', function() {
    const fabricante = JSON.parse($(this).attr('data-fabricante'));
    mostrarFabricante(fabricante);
});

function mostrarFabricante(fabricante) {

    renderizarTitulo(fabricante);
    renderizarBody(fabricante);
    $('.cep-show-fabricante').mask('00000-000');
    $('#modalFabricanteShow').modal('show');

}

function renderizarBody(fabricante) {
    var endereco = renderizarEndereco(fabricante);
    $('#modalFabricanteBody').html(`
            <span> <strong> Razão social </strong>: ${fabricante.razao_social ?? 'Não informado'}</span> <br>
            <span> <strong> Descrição </strong>: ${fabricante.descricao ?? 'Não informado'}</span> <br>
            <span> <strong> Documento </strong>: ${gerais.formatarDocumento(fabricante.cnpj) ?? 'Não informado'}</span> <br>
            <span> <strong> Inscrição estadual </strong>: ${fabricante.inscricao_estadual ?? 'Não informado'}</span> <br>
            <span> <strong> Celular </strong>: ${gerais.aplicarMascaraCelular(fabricante.celular) ?? 'Não informado'}</span> <br>
            <span> <strong> Telefone </strong>: ${gerais.aplicarMascaraTelefoneFixo(fabricante.telefone) ?? 'Não informado'}</span> <br>
            <span> <strong> Email </strong>: ${fabricante.email}</span> <br>
            <span> <strong> Site </strong>: ${fabricante.site}</span> <br>
            <hr style="background: #fff">
            <h5>Endereço</h5>
            <span> ${endereco} </span> <br>
        `);
}

function renderizarEndereco(fabricante) {
    let endereco = fabricante.endereco;
    if (!endereco) {
        return 'Não informado';
    }

    return `
        ${endereco.logradouro},
        ${endereco.numero ? endereco.numero + ' - ' : ''}
        ${endereco.bairro},
        ${endereco.cidade} - ${endereco.uf},
        ${endereco.complemento ? endereco.complemento + ', ' : ''}
        <span class="cep-show-fabricante">${endereco.cep}</span>.
    `;
}

function renderizarTitulo(fabricante) {
    $('#modalFabricanteTitulo').html(`
        <h5>
            ${fabricante.nome}
        </h5>
    `);
}


$(document).on('click', '#fecharModal', function () {
    // Fecha o modal usando Bootstrap modal('hide')
    $('#modalFabricanteShow').modal('hide');
});

