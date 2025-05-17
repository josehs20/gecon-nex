<div class="card card-body">
    @php
        $classificacao = isset($classificacao) ? $classificacao : null; // ou qualquer valor padrão adequado
    @endphp
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="nome">Nome: *</label>
                <input type="text" id="nome" name="nome" value="{{ $classificacao->descricao ?? '' }}" required
                    class="form-control">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('cadastro.classificacao_produto.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <button type="submit" class="btn btn-dark mx-2">
           <i class="bi bi-floppy"></i> Salvar
        </button>
    </div>
</div>
