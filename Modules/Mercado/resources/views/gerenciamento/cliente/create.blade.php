@extends('mercado::layouts.app')

@section('content')

    <div class="trilha-paginas-acessadas">
        <a href="{{ route('home.index') }}">Página incial</a>
        <span>&nbsp;-&nbsp;</span>
        <a href="{{ route('cadastro.cliente.index') }}">Clientes</a>
        <span>&nbsp;-&nbsp;</span>
        <p>Novo cliente</p>
    </div>

    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastro de cliente</h3>
            <p class="lead">Nesta tela você pode cadastrar um novo cliente.</p>
        </div>
    </div>

    <form id="cliente" data-identifier="form-store" action="{{ route('cadastro.cliente.store') }}" method="POST">
        @csrf
        @include('mercado::gerenciamento.cliente.inc.form')
    </form>
@endsection
