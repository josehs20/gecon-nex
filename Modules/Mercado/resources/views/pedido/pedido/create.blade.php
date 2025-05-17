@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.pedido.index'), 'titulo' => 'Pedidos'], ['titulo' => 'Novo pedido']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Novo Pedido</h3>
            <p class="lead">
                Nesta tela, você pode registrar pedidos de mercadorias. O próximo passo será realizar a cotação de cada um deles.
            </p>
        </div>
    </div>

   @include('mercado::pedido.pedido.inc.form')
@endsection
