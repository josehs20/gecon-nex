@extends('mercado::layouts.app')

@section('content')
    <div class="trilha-paginas-acessadas">
        <a href="{{ route('home.index') }}">Página incial</a>
        <span>&nbsp;-&nbsp;</span>
        <a href="{{ route('cadastro.fornecedor.index') }}">Fornecedores</a>
        <span>&nbsp;-&nbsp;</span>
        <p>Editar fornecedor</p>
    </div>

    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar fornecedor</h3>
            <p class="lead">Nesta tela você pode editar os dados do fornecedor.</p>
        </div>
    </div>


    <form id="fornecedor" data-identifier="form-update" action="{{ route('cadastro.fornecedor.update', $fornecedor->id) }}"
        method="POST">
        @csrf
        @include('mercado::gerenciamento.fornecedor.inc.form')
    </form>
@endsection
