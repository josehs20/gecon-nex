@extends('mercado::layouts.app')

@section('content')
    <style>

    </style>

    <div class="trilha-paginas-acessadas">
        <a href="{{ route('home.index') }}">Página inicial</a>
        <span>&nbsp;-&nbsp;</span>
        <a href="{{ route('cadastro.produto.index') }}">Estoque</a>
        <span>&nbsp;-&nbsp;</span>
        <p>Novo estoque</p>
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <div class="cabecalho">
                        <div class="page-header">
                            <h3>Cadastro de estoques</h3>
                            <p class="lead">Nesta tela você pode cadastrar um novo esstoque.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('cadastro.estoque.store') }}" method="POST">
                        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
                        @include('mercado::estoque.estoque.inc.form')

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
