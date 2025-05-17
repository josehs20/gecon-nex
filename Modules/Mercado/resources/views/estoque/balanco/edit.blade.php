@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('estoque.balanco.index'), 'titulo' => 'Balanços'], ['titulo' => 'Editar Balanço']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar Balanço</h3>
            <p class="lead">
                Nesta tela você pode editar um balanço.
            </p>
        </div>
        <div>

            @if ($balanco && $balanco->status_id == config('config.status.aberto'))
                <button onclick="cancelarBalanco({{ $balanco->id }})" type="button" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Cancelar balanço
                </button>
            @endif
        </div>
    </div>

    @include('mercado::estoque.balanco.inc.form')
@endsection
