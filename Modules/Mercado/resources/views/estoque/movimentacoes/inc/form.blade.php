@vite('Modules/Mercado/resources/assets/js/views/estoque/movimentacoes/inc/form.js', 'build/.vite')

@if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
    <div class="card card-body">
        <form id="form-movimentar-movimentacao" method="POST">
            @csrf
            <div class="row d-flex justify-content-between align-items-center">
                <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert"
                    style="width: auto;">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    <span style="color: black!important; font-size: 0.875rem;">
                        Ao repetir a operação para o mesmo item, ele será atualizado.
                    </span>
                </div>
                <div class="col-md-10">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="estoque_id">Selecione o produto: *</label>
                                <select required id="estoque_id" name="estoque_id" class="form-control select2">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_movimentacao">Tipo de movimentação: *</label>
                                <select required id="tipo_movimentacao" name="tipo_movimentacao"
                                    class="form-control select2">
                                    <option value="{{ config('config.tipo_movimentacao_estoque.entrada') }}">ENTRADA
                                    </option>
                                    <option value="{{ config('config.tipo_movimentacao_estoque.saida') }}">SAÍDA
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantidade_disponivel">Quantidade disponível: </label>
                                <input required type="text" id="quantidade_disponivel" name="quantidade_disponivel"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantidade">Quantidade a movimentar: *</label>
                                <input required type="text" id="quantidade" name="quantidade" value=""
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark" style="float: right">
                            <i class="bi bi-check"></i> Adicionar
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="movimentacao_id" value="{{ $movimentacao->id ?? '' }}">
        </form>
    </div>
@endif

<div class="card card-body">
    <div class="row">
        <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert" style="width: auto;">
            <i class="bi bi-info-circle-fill me-1"></i>
            <span style="color: black!important; font-size: 0.875rem;">
                O sistema sempre manterá sincronizado as quantidades com o estoque atual.
            </span>
        </div>
        <div class="col-md-10">
            <h5 style="color: black !important;">Produtos movimentados</h5>
        </div>
        <div class="col-md-2">
            <div class="px-3 text-center">
                <span class="{{ $movimentacao && $movimentacao->status ? $movimentacao->status->badge() : '' }}">
                    STATUS: {{ $movimentacao && $movimentacao->status ? $movimentacao->status->descricao() : '' }}
                </span>
            </div>
        </div>
    </div>
    <div class="row justify-content-start">
        <div class="col-auto">
            @if ($movimentacao)
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Movimentado por:</strong> {{ $movimentacao->usuario->master->name ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Loja:</strong> {{ $movimentacao->loja->nome ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data início:</strong> {{ formatarData($movimentacao->created_at) ?? '-' }}
                    </li>
                    @if ($movimentacao && $movimentacao->status_id != config('config.status.aberto'))
                        <li class="list-inline-item">
                            • <strong>Data fim:</strong> {{ formatarData($movimentacao->updated_at) ?? '-' }}
                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <br>
    <table id="tabela-movimentacao-item" class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd disponível</th>
                <th>Qtd movimentada</th>
                <th>Tipo</th>
                @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
                    <th>Ação</th>
                @endif
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd disponível</th>
                <th>Qtd movimentada</th>
                <th>Tipo</th>
                @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
                    <th>Ação</th>
                @endif
            </tr>
        </tfoot>
    </table>
    <div class="row">
        <div class="m-2 col-md-12">
            <label for="observacao" class="form-label">Observação:*</label>
            <textarea {{$movimentacao && $movimentacao->status_id != config('config.status.aberto') ? 'disabled' : ''}} required class="form-control" name="observacao" id="observacao" cols="30" rows="2">{{ $movimentacao && $movimentacao->observacao ? $movimentacao->observacao : '' }}</textarea>
        </div>
        <div class="mx-4 col-md-10">
            @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
                <input class="form-check-input" type="checkbox" id="confirmacaoMovimentacao" required
                    style="transform: scale(1.5);">
                <label class="form-check-label" for="confirmacaoMovimentacao">
                    Declaro, para os devidos fins, que estou ciente dos itens e suas quantidades na movimentação.
                </label>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('estoque.movimentacao.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
            <button id="btn_salvar_movimentacao" type="button" class="btn btn-info">
                <i class="bi bi-floppy"></i> Salvar movimentação
            </button>
            <button id="btn_finalizar_movimentacao" type="button" class="btn btn-dark">
                <i class="bi bi-check"></i> Finalizar movimentação
            </button>
        @endif
    </div>
</div>

<form action="{{ route('estoque.movimentacao.finalizar') }}" id="finalizar_movimentacao_post" method="POST">
    @method('POST')
    @csrf
    <input type="hidden" name="movimentacao_id" value="{{ $movimentacao ? $movimentacao->id : '' }}">
    <input type="hidden" name="itens" id="itens">
    <input type="hidden" name="observacao" id="observacaoFinalizar" value="">
    <input type="hidden" name="finalizar" value="{{ false }}">
</form>

<div id="dataView"
data-url-get-produtos="{{route('estoque.movimentacao.getProdutos')}}"
data-url-get-estoque="{{route('estoque.movimentacao.getEstoque')}}"
data-pode-alterar-algo="{{!$movimentacao || $movimentacao->status_id == config('config.status.aberto')}}"
data-itens-na-movimentacao="{{session()->has('itens_na_movimentacao') ? session()->get('itens_na_movimentacao') : null}}"
data-movimentacao="{{$movimentacao}}"
data-tipo-movimentacao-id="{{config('config.tipo_movimentacao_estoque.entrada')}}"
></div>
