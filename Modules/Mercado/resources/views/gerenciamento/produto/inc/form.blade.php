<div class="row mt-2">
    <div class="col-md-2">
        <div class="form-group">
            <label for="cod_barras">Código de barras: *</label>
            <input type="text" id="cod_barras" value="{{ $produto->cod_barras ?? '' }}" required name="cod_barras"
                class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nome">Nome: *</label>
            <input type="text" id="nome" value="{{ $produto->nome ?? '' }}" name="nome" required
                class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="preco_custo">Preço de custo: *</label>
            <input type="text" id="preco_custo" value="{{ $produto ? $produto->custo() : '' }}" required
                name="preco_custo" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="preco_venda">Preço de venda: *</label>
            <input type="text" id="preco_venda" value="{{ $produto ? $produto->preco() : '' }}" required
                name="preco_venda" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="cod_aux">Código auxiliar: *</label>
            <input type="text" id="cod_aux" value="{{ $produto->cod_aux ?? $cod_aux }}" required readonly
                name="cod_aux" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="unidade_medida">Unidade de medida: *</label>
            <select required id="unidade_medida" name="unidade_medida" class="form-control select2">
                @if ($produto)
                    <option value="{{ $produto->unidade_medida_id }}" selected>{{ $produto->sigla }}
                    </option>
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="classificacao_id">Classificação: *</label>
            <select required id="classificacao_id" name="classificacao_id" class="form-control select2">
                @if ($produto)
                    <option value="{{ $produto->classificacao_produto_id }}" selected>
                        {{ $produto->classificacao }}</option>
                @endif

            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="data_validade">Data de validade *</label>
            <input required type="date" id="data_validade"
                value="{{ $produto->data_validade ?? now()->addYear()->format('Y-m-d') }}" name="data_validade"
                class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="fabricante_id">Fabricante: *</label>
            <select id="fabricante_id" name="fabricante_id" class="form-control select2">
                <option disabled>
                    Selecione...</option>
                @foreach ($fabricantes as $f)
                    <option value="{{ $produto ? $produto->fabricante_id : $f->id }}"
                        {{ $produto && $produto->fabricante_id == $f->id ? 'selected' : '' }}>
                        {{ $f->nome }}</option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="loja_id">Lojas: *</label>

            <select required id="loja_id" name="lojas[]" multiple class="form-control select2">

                @foreach (auth()->user()->usuarioMercado->lojas as $item)
                    @if (auth()->user()->usuarioMercado->loja_id == $item->id)
                        <option value="{{ $item->id }}"
                            {{ $produto && $produto->lojas->contains($item->id) ? 'selected disabled' : '' }}>
                            {{ $item->nome }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

</div>

<div class="row mt-2">
    <div class="col-md-12">
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" class="form-control">{{ $produto->descricao ?? '' }}</textarea>
        </div>
    </div>
</div>
<div class="card-footer">
    <a href="{{ route('cadastro.produto.index') }}" class="btn btn-danger">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <button type="submit" class="btn btn-dark mx-2">
        <i class="bi bi-floppy"></i> Salvar
    </button>
</div>
<script>
    var rotaUnidadeMedida = @json(route('unidade_medida.select2'));
    var rotaClassificacaoProduto = @json(route('classificacao_produto.select2'));
    var routeBuscaGtin = @json(route('cadastro.produto.nfe.get.gtin'));
    // $(window).load(function() {
    maskDinheiro('preco_venda');
    maskDinheiro('preco_custo');
    // });
    $(document).ready(function() {
        $('#loja_id').on('select2:unselecting', function(e) {
            // Impede a remoção de opções protegidas
            var selected = $(e.params.args.data.element).prop('disabled');
            if (selected) {
                e.preventDefault();
            }
        });
        select2('loja_id');
        select2('fabricante_id');
        select2('unidade_medida', rotaUnidadeMedida);
        select2('classificacao_id', rotaClassificacaoProduto);

        $('#cod_barras').on('input', function() {
            let codigo = $(this).val();

            if (codigo.length > 8) {
                $.ajax({
                    url: routeBuscaGtin, // Defina a rota correta no Laravel
                    method: 'GET',
                    data: {
                        cod_barras: codigo
                    },
                    success: function(response) {
                        $('#captcha-container').html(response.html);
                    },
                    error: function() {
                        alert("Erro ao buscar produto.");
                    }
                });
            }
        });
    })
</script>
