<div class="card card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nome">Produto: *</label>
                <input type="text" id="produto" disabled
                    value="{{ $estoque ? $estoque->produto->nome . ' - ' . $estoque->produto->unidade_medida->sigla . ' - ' . $estoque->produto->fabricante->nome : '' }}"
                    name="produto" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="quantidade_total">Quantidade Total: *</label>
                <input disabled type="text" id="quantidade_total" value="{{ $estoque->quantidade_total ?? '' }}"
                    name="quantidade_total" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="quantidade_disponivel">Quantidade Disponível: *</label>
                <input disabled type="text" id="quantidade_disponivel"
                    value="{{ $estoque->quantidade_disponivel ?? '' }}" name="quantidade_disponivel"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="quantidade_minima">Quantidade Mínima: *</label>
                <input type="text" required id="quantidade_minima" value="{{ $estoque->quantidade_minima ?? '' }}"
                    name="quantidade_minima" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="quantidade_maxima">Quantidade Máxima: *</label>
                <input type="text" required id="quantidade_maxima" value="{{ $estoque->quantidade_maxima ?? '' }}"
                    name="quantidade_maxima" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="localizacao">Localização: </label>
                <input type="text" id="localizacao" value="{{ $estoque->localizacao ?? '' }}" name="localizacao"
                    class="form-control">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('cadastro.estoque.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <button type="submit" class="btn btn-dark mx-2">
            <i class="bi bi-floppy"></i> Salvar
        </button>
    </div>
</div>



<script>
    $(window).on('load', function() {
        maskQtd('quantidade_total');
        maskQtd('quantidade_disponivel');
        maskQtd('quantidade_minima');
        maskQtd('quantidade_maxima');
    });

    $(document).ready(function() {
        maskQtd('quantidade_total');
        maskQtd('quantidade_disponivel');
        maskQtd('quantidade_minima');
        maskQtd('quantidade_maxima');
    });
</script>
</div>
