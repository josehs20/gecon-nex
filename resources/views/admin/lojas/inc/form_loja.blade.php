<div class="card card-body">
    <h5>{{ $loja ? 'Editar loja' : 'Cadastro de loja' }}</h5>
    @if ($loja)
        <form action="{{ route('admin.loja.update', ['empresa_id' => $empresa->id, 'loja_id' => $loja->id]) }}"
            method="POST">
            @method('PUT')
            @csrf
        @else
            <form action="{{ route('admin.loja.store', ['empresa_id' => $empresa->id]) }}" method="POST">
                @csrf
    @endif

    <div class="mb-3">
        <label for="cnpj" class="form-label">CNPJ *</label>

        <input type="text" class="form-control" id="cnpj"
            value="{{ $loja && $loja->cnpj ? formatCNPJ($loja->cnpj) : '' }}" name="cnpj" required>
    </div>

    <div class="mb-3">
        <label for="nome" class="form-label">Razão social *</label>
        <input readonly type="text" class="form-control" id="nome" value="{{ $loja->nome ?? '' }}"
            name="nome" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" value="{{$loja->email ?? ''}}" name="email">
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" value="{{$loja->telefone ?? ''}}" name="telefone">
    </div>

    <div class="form-check mb-2 d-flex">
        {{-- @if ($loja)
            <div class="mx-4">
                <input class="form-check-input" type="checkbox" {{ $loja->matriz ? 'checked disabled' : '' }}
                    name="matriz" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Matriz
                </label>
            </div>
        @endif --}}

    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Selecione o Status*</label>
        <select class="form-control select2" name="status" id="status" required>
            <option value="">Selecione um status</option>
            @foreach (config('config.status') as $nome => $id)
                <option value="{{ $id }}" {{ $loja && $loja->status_id == $id ? 'selected' : '' }}>
                    {{ $nome }}
                </option>
            @endforeach
        </select>
    </div>
    <br>
    <h5>Cadastro de endereço</h5>

    <div class="form-group">
        <label for="logradouro">Logradouro*</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->logradouro : '' }}"
            class="form-control" id="logradouro" name="logradouro" required>
    </div>

    <div class="form-group">
        <label for="numero">Número</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->numero : '' }}"
            class="form-control" id="numero" name="numero">
    </div>

    <div class="form-group">
        <label for="cidade">Cidade*</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->cidade : '' }}"
            class="form-control" id="cidade" name="cidade" required>
    </div>

    <div class="form-group">
        <label for="bairro">Bairro*</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->bairro : '' }}"
            class="form-control" id="bairro" name="bairro" required>
    </div>

    <div class="form-group">
        <label for="uf">UF*</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->uf : '' }}"
            class="form-control" id="uf" name="uf" maxlength="2" required>
    </div>

    <div class="form-group">
        <label for="cep">CEP*</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->cep : '' }}"
            class="form-control" id="cep" name="cep" required>
    </div>

    <div class="form-group">
        <label for="complemento">Complemento</label>
        <input readonly type="text"
            value="{{ $loja && $loja->lojaMercado->endereco ? $loja->lojaMercado->endereco->complemento : '' }}"
            class="form-control" id="complemento" name="complemento">
    </div>
    <a href="{{ route('admin.empresa.edit', ['empresa' => $empresa->id]) }}" class="btn btn-danger">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <button type="submit" class="btn btn-dark"> <i class="bi bi-floppy"></i> Salvar</button>
    </form>
    </form>
</div>

