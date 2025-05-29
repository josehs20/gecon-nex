@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Caixas']]])

@section('content')
@vite('Modules/Mercado/resources/assets/js/views/gerenciamento/caixa/index.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Caixas</h3>
            <p class="lead">Nesta tela você pode realizar ações em relação ao caixa.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.caixa.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Caixa
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive ">
            <table class="table table-bordered" id="tabela-caixas" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Loja</th>
                        <th>Ativo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>


                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Loja</th>
                        <th>Ativo</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    <div id="objetoView" data-route-yajra-caixa-get="{{route('yajra.service.gerenciamento.caixa.get')}}"></div>
    <br>

@endsection
