@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('estoque.movimentacao.index'), 'titulo' => 'Movimentações'], ['titulo' => 'Nova movimentação']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Nova movimentação</h3>
            <p class="lead">
                Nesta tela você pode realizar novas movimentações de estoque.
            </p>
        </div>
        <div>

            @if ($movimentacao && $movimentacao->status_id == config('config.status.aberto'))
                <button onclick="cancelarMovimentacao({{ $movimentacao->id }})" type="button" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Cancelar movimentação
                </button>
                @if ($movimentacao)
                    <form action="{{ route('estoque.movimentacao.delete', ['movimentacao_id' => $movimentacao->id]) }}"
                        id="cancelarMovimentacao" method="POST">
                        @method('DELETE')
                        @csrf
                  
                    </form>
                @endif
            @endif
        </div>
    </div>

    @include('mercado::estoque.movimentacoes.inc.form')
@endsection
