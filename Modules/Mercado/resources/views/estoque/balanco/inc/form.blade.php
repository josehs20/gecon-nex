@vite('Modules/Mercado/resources/assets/js/views/estoque/balanco/inc/form.js', 'build/.vite')

@if (!$balanco || $balanco->status_id == config('config.status.aberto'))
    <div class="card card-body">
        <form id="form-movimentar-balanco" method="POST">
            @csrf
            <div class="row d-flex justify-content-between align-items-center">
                <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert"
                    style="width: auto;">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    <span style="color: black!important; font-size: 0.875rem;">
                        &nbsp; Ao repetir a operação para o mesmo item, ele será atualizado.</span>
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
                                <select id="tipo_movimentacao_select" class="form-control select2" disabled>
                                    <option value="{{ config('config.tipo_movimentacao_estoque.balanco') }}" selected>
                                        BALANÇO</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantidade">Quantidade no sistema: </label>
                                <input required type="text" id="quantidade_estoque_sistema"
                                    name="quantidade_estoque_sistema" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantidade_estoque_real">Quantidade no estoque real:</label>
                                <input required type="text" id="quantidade_estoque_real" value=""
                                    name="quantidade_estoque_real" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantidade_operacional">Resultado operacional:</label>
                                <input required type="text" id="quantidade_operacional" name="quantidade_operacional"
                                    class="form-control" readonly>
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
            {{-- <input type="hidden" name="balanco_id" value="{{ $balanco->id ?? '' }}"> --}}

        </form>
    </div>
@endif

<div class="card card-body">
    <div class="row">
        <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert" style="width: auto;">
            <i class="bi bi-info-circle-fill me-1"></i>
            <span style="color: black!important; font-size: 0.875rem;">
                &nbsp;O sistema sempre validará o resultado operacional com o estoque atual.
            </span>
        </div>

        <div class="col-md-10">
            <h5 style="color: black !important;">Produtos adicionados</h5>
        </div>
        <div class="col-md-2">
            <div class="px-3 text-center">
                <span class="{{ $balanco && $balanco->status ? $balanco->status->badge() : '' }}">
                    STATUS: {{ $balanco && $balanco->status ? $balanco->status->descricao() : '' }}
                </span>
            </div>
        </div>
    </div>
    <div class="row justify-content-start">
        <div class="col-auto">
            @if ($balanco)
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Movimentado por:</strong> {{ $balanco->usuario->master->name ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Loja:</strong> {{ $balanco->loja->nome ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data início:</strong> {{ formatarData($balanco->created_at) ?? '-' }}
                    </li>
                    @if ($balanco && $balanco->status_id != config('config.status.aberto'))
                        <li class="list-inline-item">
                            • <strong>Data fim:</strong> {{ formatarData($balanco->updated_at) ?? '-' }}
                        </li>
                    @endif
                </ul>
            @endif

        </div>
    </div>
    <br>
    <table id="tabela-balanco-item" class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd no sistema</th>
                <th>Qtd real</th>
                <th>Resultado operacional</th>

                @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
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
                <th>Qtd no sistema</th>
                <th>Qtd real</th>
                <th>Resultado operacional</th>
                @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
                    <th>Ação</th>
                @endif
            </tr>
        </tfoot>
    </table>
    <div class="row">
        <div class="m-2 col-md-12">
            <label for="observacao" class="form-label">Observação:*</label>
            <textarea {{ $balanco && $balanco->status_id != config('config.status.aberto') ? 'disabled' : '' }} required
                class="form-control" name="observacao" id="observacao" cols="30" rows="2">{{ $balanco && $balanco->observacao ? $balanco->observacao : '' }}</textarea>
        </div>
        <div class="mx-4 col-md-10">
            @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
                <input class="form-check-input" type="checkbox" id="confirmacaoBalanco" required
                    style="transform: scale(1.5);">
                <label class="form-check-label" for="confirmacaoBalanco">
                    Declaro, para os devidos fins, que estou ciente dos itens e suas quantidades no balanço.
                </label>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('estoque.balanco.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
            <button id="btn_salvar_balanco" type="button" class="btn btn-info">
                <i class="bi bi-floppy"></i> Salvar balanço
            </button>
            <button id="btn_finalizar_balanco" type="button" class="btn btn-dark">
                <i class="bi bi-check"></i> Finalizar balanço
            </button>
            @if ($balanco)
                <form action="{{ route('estoque.balanco.delete', ['balanco_id' => $balanco->id]) }}"
                    id="cancelarBalanco" method="POST">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="cancelar_balanco_id"
                        value="{{ empty($balanco) ? $balanco->id : '' }}">
                </form>
            @endif

        @endif

    </div>
</div>

<form action="{{ route('estoque.balanco.finalizar') }}" id="finalizar_balanco_post" method="POST">
    @method('POST')
    @csrf
    <input type="hidden" name="balanco_id" value="{{ $balanco ? $balanco->id : '' }}">
    <input type="hidden" name="itens" id="itens">
    <input type="hidden" name="observacao" id="observacaoFinalizar" value="">
    <input type="hidden" name="finalizar" value="{{ false }}">
    <input type="hidden" id="tipo_movimentacao" name="tipo_movimentacao"
        value="{{ config('config.tipo_movimentacao_estoque.balanco') }}">
</form>

<br>
<div id="dataView" data-url-get-produtos="{{ route('estoque.balanco.getProdutos') }}"
    data-url-get-estoque="{{ route('estoque.balanco.getEstoque') }}"
    data-pode-alterar-algo="{{ !$balanco || $balanco->status_id == config('config.status.aberto') }}"
    data-balanco="{{ $balanco }}"></div>

</script>
