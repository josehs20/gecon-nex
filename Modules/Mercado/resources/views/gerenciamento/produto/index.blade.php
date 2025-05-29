@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Produtos']]])

@section('content')
@vite('Modules/Mercado/resources/assets/js/views/gerenciamento/produtos/index.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Lista de produtos</h3>
            <p class="lead">Nesta tela você pode vidualizar, cadastrar e editar os produtos.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.produto.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Produto
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tabela-produto" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Loja</th>
                            <th>Custo</th>
                            <th>Preço</th>
                            <th>Fabricante</th>
                            <th>Código Auxiliar</th>
                            <th>UN</th>
                            <th>Classificação</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Loja</th>
                            <th>Custo</th>
                            <th>Preço</th>
                            <th>Fabricante</th>
                            <th>Código Auxiliar</th>
                            <th>UN</th>
                            <th>Classificação</th>
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

    <br>
    <div id="dataView"
    data-get-produtos-yajra ="{{route('cadastro.produto.get.yajra')}}"
    ></div>

@endsection
