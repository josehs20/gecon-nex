@extends('mercado::layouts.app', [
    'trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.cotacao.index'), 'titulo' => 'Cotações'], ['rota' => route('cadastro.cotacao.selecionar_pedidos'), 'titulo' => 'Seleção de pedidos'], ['titulo' => 'Realizar cotação']],
])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Cotação</h3>
            <p class="lead">
                Esta é a etapa onde você define o preço que cada fornecedor pode oferecer.
            </p>
        </div>
    </div>

    @include('mercado::pedido.cotacao.inc.form')
@endsection
