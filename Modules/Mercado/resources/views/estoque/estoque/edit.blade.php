@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.estoque.index'), 'titulo' => 'Estoques'], ['titulo' => 'Editar parâmetros de estoque']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar parâmetros de estoque</h3>
            <p class="lead">Nesta tela você pode gerenciar as quantidades mínimas e máximas dos estoques.</p>
        </div>
    </div>
    <form action="{{ route('cadastro.estoque.update', ['id' => $estoque->id]) }}" method="POST">
        @method('PUT')
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
        @include('mercado::estoque.estoque.inc.form')

    </form>

    {{--
    <form action="{{ route('cadastro.estoque.update', ['id' => $estoque->id]) }}" method="POST">
        @method('PUT')
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
        @include('mercado::gerenciamento.estoque.inc.form')
    </form> --}}
@endsection
