@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.produto.index'), 'titulo' => 'Produto'], ['titulo' => 'Cadastrar produto']]])

@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastro de produto</h3>
            <p class="lead">Nesta tela você pode cadastrar um novo produto.</p>
        </div>
    </div>
    <div class="card card-body">
        <div class="row">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="produto-tab" data-bs-toggle="tab" href="#produto" role="tab"
                        aria-controls="produto" aria-selected="true">Produto</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="fiscal-tab" data-bs-toggle="tab" href="#fiscal" role="tab"
                        aria-controls="fiscal" aria-selected="false">Fiscal</a>
                </li>
            </ul>
        </div>
        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Formulário Produto -->
            <div class="tab-pane fade show active" id="produto" role="tabpanel" aria-labelledby="produto-tab">
                <form action="{{ route('cadastro.produto.store') }}" method="POST">
                    @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
                    @include('mercado::gerenciamento.produto.inc.form')
                </form>
            </div>

            <!-- Formulário Fiscal -->
            <div class="tab-pane fade" id="fiscal" role="tabpanel" aria-labelledby="fiscal-tab">

                    @include('mercado::gerenciamento.produto.inc.imposto_form', ['produto' => false])

            </div>
        </div>
    </div>
@endsection
