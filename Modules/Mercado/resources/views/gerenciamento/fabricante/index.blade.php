@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Fabricante']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/gerenciamento/fabricante/index.js', 'build/.vite')

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
    <div id="dataView" data-get-fabricantes="{{ route('yajra.service.fabricante.get') }}"></div>
 
@endsection
