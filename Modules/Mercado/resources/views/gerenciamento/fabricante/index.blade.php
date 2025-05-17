@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Fabricante']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Fabricante</h3>
            <p class="lead">Nesta tela você pode visualizar, cadastrar e editar os fabricantes.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.fabricante.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Fabricante
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover" id="tabela-unidade-media" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Razão social</th>
                            <th>Inscrição estadual</th>
                            <th>Email</th>
                            <th>Ativo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Razão social</th>
                            <th>Inscrição estadual</th>
                            <th>Email</th>
                            <th>Ativo</th>
                            <th>Ação</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @include('mercado::gerenciamento.fabricante.show')

    <script>
        var getUnidadeMedidas = @json(route('yajra.service.fabricante.get'));
        const columns = [{
                data: 'id',
                title: 'ID'
            },
            {
                data: 'nome',
                title: 'Nome'
            },
            {
                data: 'cnpj',
                title: 'CNPJ'
            },
            {
                data: 'razao_social',
                title: 'Razão social'
            },
            {
                data: 'inscricao_estadual',
                title: 'Inscrição estadual'
            },
            {
                data: 'email',
                title: 'Email'
            },
            {
                data: 'ativo',
                title: 'Ativo'
            },
            {
                data: 'acao',
                title: 'Ação',
                orderable: false,
                searchable: false
            },
        ];

        montaDatatableYajra('tabela-unidade-media', columns, getUnidadeMedidas);

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
                    <span> <strong> Documento </strong>: ${formatarDocumento(fabricante.cnpj) ?? 'Não informado'}</span> <br>
                    <span> <strong> Inscrição estadual </strong>: ${fabricante.inscricao_estadual ?? 'Não informado'}</span> <br>
                    <span> <strong> Celular </strong>: ${aplicarMascaraCelular(fabricante.celular) ?? 'Não informado'}</span> <br>
                    <span> <strong> Telefone </strong>: ${aplicarMascaraTelefoneFixo(fabricante.telefone) ?? 'Não informado'}</span> <br>
                    <span> <strong> Email </strong>: ${fabricante.email}</span> <br>
                    <span> <strong> Site </strong>: ${fabricante.site}</span> <br>
                    <hr style="background: #fff">
                    <h5>Endereço</h5>
                    <span> ${endereco} </span> <br>
                `);
        }

        function renderizarEndereco(fabricante){
            let endereco = fabricante.endereco;
            if(!endereco){
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

        $(document).ready(function() {
            $(document).on('click', '#fecharModal', function() {
                // Fecha o modal usando Bootstrap modal('hide')
                $('#modalFabricanteShow').modal('hide');
            });
        });
    </script>
@endsection
