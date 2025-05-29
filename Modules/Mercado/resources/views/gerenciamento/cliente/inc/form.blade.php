@vite('Modules/Mercado/resources/assets/js/views/gerenciamento/cliente/inc/form.js', 'build/.vite')

<div class="card card-body">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="nome">Nome: *</label>
                <input required type="text" id="nome" value="{{ $cliente ? $cliente->nome : '' }}" name="nome"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label id="labelDocumento" for="documento"></label>
                <input required type="text" id="documento" value="{{ $cliente ? $cliente->documento : '' }}"
                    name="documento" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="pessoa">Pessoa: (Física ou Juridica) *</label>
                <select required id="pessoa" name="pessoa" class="form-control">
                    <option value="J" {{ $cliente && $cliente->pessoa == 'J' ? 'selected' : '' }}>Jurídica</option>
                    <option value="F" {{ $cliente && $cliente->pessoa == 'F' ? 'selected' : '' }}>Física</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="status">Status: *</label>
                <select required id="status" name="status" class="form-control">
                    <option value="{{ config('config.status.em_dia') }}"
                        {{ $cliente && $cliente->status_id == config('config.status.em_dia') ? 'selected' : '' }}>Em dia
                    </option>
                    <option value="{{ config('config.status.em_atraso') }}"
                        {{ $cliente && $cliente->status_id == config('config.status.em_atraso') ? 'selected' : '' }}>Em
                        atraso
                    </option>
                    <option value="{{ config('config.status.quitado') }}"
                        {{ $cliente && $cliente->status_id == config('config.status.quitado') ? 'selected' : '' }}>
                        Quitado
                    </option>
                    <option value="{{ config('config.status.bloqueado') }}"
                        {{ $cliente && $cliente->status_id == config('config.status.bloqueado') ? 'selected' : '' }}>
                        Bloqueado</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="ativo">Ativo: *</label>
                <select required id="ativo" name="ativo" class="form-control">
                    <option value="true" {{ $cliente && $cliente->ativo == 1 ? 'selected' : '' }}>Ativo</option>
                    <option value="false" {{ $cliente && $cliente->ativo == 0 ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="celular">Celular: </label>
                <input type="text" id="celular" value="{{ $cliente ? $cliente->celular : '' }}" name="celular"
                    class="form-control">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="telefone_fixo">Telefone: </label>
                <input type="text" id="telefone_fixo" value="{{ $cliente ? $cliente->telefone_fixo : '' }}"
                    name="telefone_fixo" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="data_nascimento">Data de nascimento: </label>
                <input type="text" id="data_nascimento" value="{{ $cliente ? $cliente->data_nascimento : '' }}"
                    name="data_nascimento" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="limite_credito">Limite de crédito: </label>
                <input type="text" id="limite_credito"
                    value="{{ $cliente && $cliente->credito ? $cliente->credito->credito_loja : '' }}"
                    name="limite_credito" class="form-control">
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="email">E-mail: </label>
                <input type="text" id="email" value="{{ $cliente ? $cliente->email : '' }}" name="email"
                    class="form-control">
            </div>
        </div>
    </div>

    <div class="row" style="display: flex; align-items:center">
        <div class="col-md-2">
            <div class="form-group">
                <label for="cep">CEP: *</label>
                <input required type="text" id="cep" value="{{ $endereco ? $endereco->cep : '' }}"
                    name="cep" class="form-control">
            </div>
        </div>
        <button type="button" id="botaoBuscarCep" class="btn btn-success mt-3"
            style="height: fit-content">Buscar</button>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="logradouro">Logradouro: </label>
                <input required type="text" id="logradouro" value="{{ $endereco ? $endereco->logradouro : '' }}"
                    name="logradouro" class="form-control">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="numero">Número: </label>
                <input required type="text" id="numero" value="{{ $endereco ? $endereco->numero : '' }}"
                    name="numero" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cidade">Cidade: </label>
                <input required type="text" id="cidade" value="{{ $endereco ? $endereco->cidade : '' }}"
                    name="cidade" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bairro">Bairro: </label>
                <input required type="text" id="bairro" value="{{ $endereco ? $endereco->bairro : '' }}"
                    name="bairro" class="form-control">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="uf">UF: </label>
                <select required id="uf" name="uf" class="form-control">
                    @if ($endereco)
                        <option value="{{ $endereco->uf }}" selected>{{ $endereco->uf }}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="complemento">Complemento: </label>
                <input type="text" id="complemento" value="{{ $endereco ? $endereco->complemento : '' }}"
                    name="complemento" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="observacao">Observação: </label>
                <textarea type="text" name="observacao" id="observacao" rows="7" class="w-100">
                    {{ $cliente && $cliente->observacao ? $cliente->observacao : '' }}
                </textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('cadastro.cliente.index') }}" type="button" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <button id="bt-salvar-atualizar" type="submit" class="btn btn-dark mx-2">
            <i class="bi bi-floppy"></i> Salvar
        </button>
    </div>
</div>

<div id="dataViewForm"
data-endereco="{{ $endereco ?? null }}"
data-redirect="{{route('cadastro.cliente.index')}}"
></div>
