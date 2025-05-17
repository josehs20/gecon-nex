@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.pedido.index'), 'titulo' => 'Pedidos'], ['titulo' => 'Novo pedido']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar Pedido</h3>
            <p class="lead">
                Nesta tela, você pode alterar pedidos de mercadorias. O próximo passo será realizar a cotação de cada um deles.
            </p>
        </div>


    </div>
    <div class="d-flex justify-content-end mb-2">
        @if ($pedido && $pedido->status_id == config('config.status.aberto') || $pedido && $pedido->status_id == config('config.status.aguardando_cotacao'))
            <button onclick="cancelarPedido({{ $pedido->id }})" type="button" class="btn btn-danger">
                <i class="bi bi-trash"></i> Cancelar pedido
            </button>
            <form action="{{ route('pedido.cadastro.delete', ['pedido_id' => $pedido->id]) }}"
                id="cancelarPedido" method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" id="formMotivoCancelamento" name="motivo">

            </form>
        @endif
    </div>
   @include('mercado::pedido.pedido.inc.form')
@endsection
