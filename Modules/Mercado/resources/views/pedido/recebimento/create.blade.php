@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('pedido.recebimento.index'), 'titulo' => 'Recebimentos'], ['titulo' => 'Novo recebimento']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pedido/recebimento/create.js', 'build/.vite')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Novo recebimento</h3>
            <p class="lead">Nesta tela você pode fazer novos recebimentos.</p>
        </div>
    </div>

    <div class="card card-body">
        <table id="tabela-compras" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data criação</th>
                    <th>Previsão entrega</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compras as $compra)
                    <tr class="linha-compra" data-itens='@json($compra->compra_itens)'>
                        <td>{{ $compra->id }}</td>
                        <td>{{ $compra->usuario->master->name }}</td>
                        <td><span class="{{ $compra->status->badge }}">{{ $compra->status->descricao }}</span></td>
                        <td>{{ dataBancoDeDadosParaDataString($compra->created_at) }}</td>
                        <td>{{ dataBancoDeDadosParaDataString($compra->cot_fornecedor->previsao_entrega) }}</td>
                        <td>{{ $compra->cot_fornecedor->observacao }}</td>
                        <td>
                            <button class="btn btn-outline-info btn-expandir" type="button">
                                <i class="bi bi-arrow-down"></i> Expandir
                            </button>
                            <a href="{{route('pedido.recebimento.receber', ['compra_id' => $compra->id])}}" class="btn btn-dark" type="button">
                                <i class="bi bi-arrow-right"></i> Receber
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-footer">
            <a href="{{ route('pedido.recebimento.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    
@endsection
