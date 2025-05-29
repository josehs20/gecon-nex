@vite('Modules/Mercado/resources/assets/js/views/gerenciamento/produtos/inc/imposto_form.js', 'build/.vite')

<form id="form-fiscal-produto"
    action="{{ auth()->user()->getUserModulo->loja->nfeio && $produto ? route('cadastro.produto.post.ncms', ['estoque_id' => $produto->estoque->id]) : '#' }}"
    method="POST">
    @csrf
    <div class="row mt-2">
        @if (auth()->user()->getUserModulo->loja->nfeio && $produto)
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ncm" class="d-block">NCM: *</label>
                    <select style="width: 100%;" required id="ncm" name="ncm" class="form-control select2">
                    </select>
                </div>
            </div>
        @elseif (!$produto)
            <div class="alert alert-warning col-12" style="text-align: center !important" role="alert">
                Produto não cadastrado.
            </div>
        @else
            <div class="alert alert-warning col-12" style="text-align: center !important" role="alert">
                Loja não cadastrada para emissão de NFCE.
            </div>
        @endif
    </div>

    <div>
        <a href="{{ route('cadastro.produto.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (auth()->user()->getUserModulo->loja->nfeio && $produto)
            <button type="submit" class="btn btn-success mx-2">
                <i class="bi bi-floppy"></i> Salvar
            </button>
        @endif

    </div>
</form>
<div id="dataView" data-route-get-ncms ="{{ route('cadastro.produto.get.ncms') }}"></div>
