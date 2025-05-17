@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.caixa.index'), 'titulo' => 'Caixas'], ['titulo' => 'Editar caixa']]])

@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar caixa: <u>{{$caixa->nome}}</u></h3>
            <p class="lead">Nesta tela você pode editar um caixa.</p>
        </div>
    </div>

    <form action="{{ route('cadastro.caixa.update', ['id' => $caixa->id]) }}" method="POST">
        @method('PUT')
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->

        @include('mercado::gerenciamento.caixa.inc.form')
    </form>
@endsection
