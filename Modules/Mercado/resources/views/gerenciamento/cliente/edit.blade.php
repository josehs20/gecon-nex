@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.cliente.index'), 'titulo' => 'Clientes'], ['titulo' => 'Editar cliente']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar cliente</h3>
            <p class="lead">Nesta tela você pode editar os dados do cliente.</p>
        </div>
    </div>

    <form id="cliente" data-identifier="form-update" action="{{ route('cadastro.cliente.update', $cliente->id) }}"
        method="POST">
        @csrf
        @include('mercado::gerenciamento.cliente.inc.form')
    </form>
@endsection
