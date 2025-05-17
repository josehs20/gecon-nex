@extends('mercado::layouts.app')

@section('content')
    <div class="trilha-paginas-acessadas">
        <a href="{{ route('home.index') }}">Página incial</a>
        <span>&nbsp;-&nbsp;</span>
        <a href="{{ route('cadastro.fornecedor.index') }}">Fornecedores</a>
        <span>&nbsp;-&nbsp;</span>
        <p>Novo fornecedor</p>
    </div>

    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastro de fornecedor</h3>
            <p class="lead">Nesta tela você pode cadastrar um novo fornecedor.</p>
        </div>
    </div>

    <form id="fornecedor" data-identifier="form-store" action="{{ route('cadastro.fornecedor.store') }}" method="POST">
        @csrf
        @include('mercado::gerenciamento.fornecedor.inc.form')
    </form>
@endsection
