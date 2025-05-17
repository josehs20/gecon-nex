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
    <div class="d-flex justify-content-end mb-2">
        @if (
            $cotacao &&
                $cotacao->status_id != config('config.status.comprado') &&
                $cotacao->status_id != config('config.status.cancelado') &&
                $cotacao->status_id != config('config.status.cotado'))
            <button onclick="cancelarCotacao({{ $cotacao->id }})" type="button" class="btn btn-danger">
                <i class="bi bi-trash"></i> Cancelar cotação
            </button>
            <form action="{{ route('cadastro.cotacao.delete', ['cotacao_id' => $cotacao->id]) }}" id="cancelarCotacao"
                method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" id="formMotivoCancelamento" name="motivo">

            </form>
        @endif
    </div>
    @include('mercado::pedido.cotacao.inc.form')
@endsection
