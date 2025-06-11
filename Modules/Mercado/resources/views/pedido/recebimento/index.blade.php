@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Recebimentos']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pedido/recebimento/index.js', 'build/.vite')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Recebimentos</h3>
            <p class="lead">Nesta tela você pode ver os recebimentos realizados</p>
        </div>
        <div>
            <a href="{{ route('pedido.recebimento.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> 
                Recebimento 
                <span class="badge badge-dark ml-1">{{ $qtd_compras_para_receber }}</span>
            </a>
        </div>
    </div>

    <div class="card card-body">

        <table id="tabela-recebimentos" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>status</th>
                    <th>Data recebimento</th>
                    <th>Qtd Itens</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recebimentos as $recebimento)
                    <tr>
                        <td>{{$recebimento->id}}</td>
                        <td>{{$recebimento->usuario->master->name}}</td>
                        <td><span class="{{$recebimento->status->badge}}">{{$recebimento->status->descricao}}</span></td>
                        <td>{{dataBancoDeDadosParaDataString($recebimento->data_recebimento)}}</td>
                        <td>{{count($recebimento->recebimento_itens)}}</td>
                        <td>{{$recebimento->observacoes}}</td>
                        <td>
                            <a href="{{route('pedido.recebimento.receber', ['compra_id' => $recebimento->compra_id])}}" class="btn btn-dark" type="button">
                                <i class="bi bi-eye"></i> Detalhes
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>status</th>
                    <th>Data recebimento</th>
                    <th>Qtd Itens</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
 
@endsection
