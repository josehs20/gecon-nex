@extends('mercado::layouts.app', [
    'trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.compra.index'), 'titulo' => 'Compras'], ['titulo' => 'Visualizar compra']],
])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Compra</h3>
            <p class="lead">
                Você pode visualizar a compra realizada e, dependendo do status, também cancelá-la.            </p>
        </div>
    </div>
    <div class="d-flex justify-content-end mb-2">
        @if (
            $compra && $compra->status_id == config('config.status.comprado'))
         
            <button onclick="cancelarCompra({{ $compra->id }})" type="button" class="btn btn-danger">
                <i class="bi bi-trash"></i> Cancelar compra
            </button>
            <form action="{{ route('cadastro.compra.delete', ['compra_id' => $compra->id]) }}" id="cancelarCompra"
                method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" id="formMotivoCancelamento" name="motivo">

            </form>
        @endif
    </div>
    @include('mercado::pedido.compra.inc.form')

@endsection
