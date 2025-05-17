<div class=" card card-body">
    <div class="row align-items-center">
        <div class="col-md-4">
            <div class="form-group">
                <label for="nome">Nome: *</label>
                <input type="text" id="nome" name="nome" value="{{ $unidadeMedida->descricao ?? '' }}" required
                    class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="nome">Sigla: *</label>
                <input type="text" id="sigla" name="sigla" value="{{ $unidadeMedida->sigla ?? '' }}" required
                    class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input type="checkbox" name="pode_ser_float"
                    {{ $unidadeMedida && $unidadeMedida->pode_ser_float == true ? 'checked' : '' }} class="form-check-input"
                    id="pode_ser_float">
                <label class="form-check-label" for="pode_ser_float">Pode ser fracionado</label>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div>
            <a href="{{ route('cadastro.unidade_medida.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-dark mx-1">
                <i class="bi bi-floppy"></i> Enviar
            </button>
        </div>
    </div>
</div>

