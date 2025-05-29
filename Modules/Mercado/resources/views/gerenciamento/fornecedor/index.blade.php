@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Fornecedores']]])


@section('content')
@vite('Modules/Mercado/resources/assets/js/views/gerenciamento/fornecedores/index.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Fornecedores </h3>
            <p class="lead">Nesta tela você pode realizar ações no que diz respeito aos fornecedores.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.fornecedor.create') }}" class="btn btn-success"><i class="bi bi-plus"></i>fornecedor
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tabela-fornecedores" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome&nbsp;Fantasia</th>
                            <th>Documento</th>
                            <th>Ativo</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome&nbsp;Fantasia</th>
                            <th>Documento</th>
                            <th>Ativo</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                            <th>Ação</th>
                        </tr>
                    </tfoot>
                </table>

                <div class="card-footer">
                    <a href="{{ route('home.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
        {{-- Modal para exibir os dados do fornecedor com uma melhor leitura --}}
        @include('mercado::gerenciamento.fornecedor.show')

       <div id="dataView"
       data-get-fornecedor="{{route('cadastro.fornecedor.get')}}"
       data-yajra-get-fornecedores="{{route('yajra.service.gerenciamento.fornecedor.get')}}"
       ></div>
    @endsection
