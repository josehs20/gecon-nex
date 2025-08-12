@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Fechamento caixa']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pdv/caixa_fechado.js', 'build/.vite')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Fechamento caixa</h3>
            <p class="lead">Nesta tela você acompanhar todas as movimentações de abertura até o fechamento do caixa:
                <u>{{ $caixa->nome }}</u>
            </p>
        </div>
    </div>

    <div class="card card-body">
        <div class="row justify-content-start mb-4">
            <div class="col-auto">
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Data abertura:</strong> {{ formatarData($diario->data_abertura) }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data fechamento:</strong>{{ formatarData($diario->data_fechamento) }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Total:</strong> {{ converterParaReais($caixafechamento->valor_total ?? 0) }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Crédito loja:</strong>  {{ converterParaReais($caixafechamento->total_credito_loja ?? 0) }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Dinheiro:</strong>  {{ converterParaReais($caixafechamento->valor_dinheiro ?? 0) }}
                    </li>
                </ul>
            </div>
        </div>
        <table id="tabela-fechamento" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Espécie</th>
                    <th>Data</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalhesFechamentoCaixa as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->recurso->descricao }}</td>
                        <td>
                            @if ($d->especies && $d->especies->count() > 0)
                                @foreach ($d->especies as $e)
                                    <span class="badge bg-primary">{{ $e->nome }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ formatarData($d->created_at) }}</td>
                        <td>{{ converterParaReais($d->valor_movimentado) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Espécie</th>
                    <th>Data</th>
                    <th>Valor</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('caixa.fechamento.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@endsection
