@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Fechamento de caixa']]])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="cabecalho">
                <div class="page-header">
                    <h3>Listagem de fechamento de caixas</h3>
                    <p class="lead">Nesta tela você pode visualizar todas as operações de caixa realizadas por você.
                    </p>
                </div>

                @if (auth()->user()->getUserModulo->caixa &&
                        auth()->user()->getUserModulo->caixa->status_id != config('config.status.fechado'))
                    <div>
                        <a href="{{ route('caixa.fechar.index', ['caixa_id' => auth()->user()->getUserModulo->caixa->id]) }}"
                            class="btn btn-dark"> <i
                                class="bi bi-lock"></i>&nbsp;{{ 'Fechar caixa: ' . auth()->user()->getUserModulo->caixa->nome . ' está aberto' }}
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5></h5>
            <br>
            <div class="table-responsive mt-1">
                <table class="table table-hover" id="tabela-fechamentos-caixa" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Caixa</th>
                            <th>Loja</th>
                            <th>Data abertura</th>
                            <th>Data fechamento</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($fechamentos))
                            @foreach ($fechamentos as $f)
                                <tr>

                                    <td>{{ $f->id }}</td>
                                    <td>{{ $f->caixa->nome }}</td>
                                    <td>{{ $f->caixa->loja->nome }}</td>
                                    <td>{{ formatarData($f->data_abertura) }}</td>
                                    <td>{{ formatarData($f->data_fechamento) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('caixa.fechamento.show', ['evidencia_id' => $f->id]) }}"
                                            class="btn btn-info">
                                            <i class="bi bi-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
 
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        montaDatatable('tabela-fechamentos-caixa');
    </script>
@endsection
