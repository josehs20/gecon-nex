@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Fechamento de caixas']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pdv/fechamento_caixa_index.js', 'build/.vite')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Fechamento de caixas</h3>
            <p class="lead">Nesta tela você acompanhar todas as movimentações de abertura até o fechamento de cada caixa.</p>
        </div>
    </div>

    <div class="card card-body">

        <table id="tabela-fechamento-caixas" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Caixa</th>
                    <th>Status</th>
                    <th>Data abertura</th>
                    <th>Data fechamento</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($caixas_diario as $diario)
                    <tr>
                        <td>{{$diario->id}}</td>
                        <td>{{$diario->caixa->nome}}</td>
                        <td><span class="{{$diario->status->badge}}">{{$diario->status->descricao}}</span></td>
                        <td>{{formatarData($diario->data_abertura)}}</td>
                        <td>{{formatarData($diario->data_fechamento)}}</td>
                        <td>
                            <a href="{{route('caixa.fechamento.caixa_diario', ['caixa_diario_id' => $diario->id])}}" class="btn btn-dark" type="button">
                                <i class="bi bi-eye"></i> Detalhes
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Caixa</th>
                    <th>Status</th>
                    <th>Data abertura</th>
                    <th>Data fechamento</th>
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
