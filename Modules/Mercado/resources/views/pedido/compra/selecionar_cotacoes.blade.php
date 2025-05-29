@extends('mercado::layouts.app', [
    'trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.compra.index'), 'titulo' => 'Compras'], ['titulo' => 'Selecionar cotações']],
])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pedido/compra/selecionar_cotacoes.js', 'build/.vite')

    <style>
        .expander {
            width: 30px;
            cursor: pointer;
        }

        td.details-control,
        td.details-control-sub {
            cursor: pointer;
        }

        .sub-slider {
            padding: 10px;
        }

        table.table-sm tr.shown+tr td {
            padding: 0;
        }
    </style>

    <div class="cabecalho">
        <div class="row align-items-center justify-content-between mb-3">
            <div class="col">
                <h3>Seleção de cotações</h3>
                <p class="lead mb-0">
                    Selecione a cotação com os pedidos relacionados e avance para escolher as melhores condições de compra.
                </p>
            </div>
            {{-- <div class="col-auto">
                <button type="button" class="btn btn-success" id="btnCriarCompra" data-toggle="modal"
                    data-target="#modalCotacoes">
                    Cotações selecionadas
                    <span class="badge badge-dark ml-1" id="qtdCotacoesComprar">0</span>
                </button>
            </div> --}}
        </div>
    </div>

    <div class="card card-body">
        <table id="tabela-cotacoes" class="table table-bordered">
            <thead>
                <tr>
                    <th class="expander"></th>
                    <th>Nº Pedidos</th>
                    <th>Usuário</th>
                    <th>Data de abertura</th>
                    <th>Status</th>
                    <th>Qtd Itens</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotacoes_aguardando_compra as $c)
                    <tr class="cotacao-row" data-id="{{ $c->id }}">
                        <td class="details-control">
                            <i class="bi bi-chevron-right expand-icon"></i>
                        </td>
                        <td>{{ $c->cot_for_itens->pluck('pedido_id')->unique()->join('/') }}</td>
                        <td>{{ $c->usuario->master->name }}</td>
                        <td>{{ aplicarMascaraDataNascimento($c->data_abertura) }}</td>
                        <td><span class="{{ $c->status->badge() }}">{{ $c->status->descricao() }}</span></td>
                        <td>{{ $c->cot_for_itens->pluck('estoque_id')->unique()->count() }}</td>
                        <td>{{ $c->descricao }}</td>
                        <td>
                            <a href="{{ route('cadastro.compra.create', ['cotacao_id' => $c->id]) }}"
                                class="btn btn-warning">
                                <i class="bi-arrow-right-circle"></i>
                            </a>
                            {{-- <button class="btn btn-sm btn-dark btn-adicionar" data-id="{{ $c->id }}">
                                <i class="bi bi-cart-plus"></i> Adicionar
                            </button>
                            <button class="btn btn-sm btn-danger btn-remover" data-id="{{ $c->id }}"
                                style="display: none;">
                                <i class="bi bi-cart-dash"></i> Remover
                            </button> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-footer">
            <a href="{{ route('cadastro.compra.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div id="dataView" data-cotacoes="{{ $cotacoes_aguardando_compra }}"></div>

@endsection
