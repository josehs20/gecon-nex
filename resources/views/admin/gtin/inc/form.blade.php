<style>
    .nav-link.active {
        background-color: #007bff !important;
        /* Cor primária do Bootstrap */
        color: #fff !important;
        /* Texto branco */
    }
</style>
<div class="card card-body">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="empresa-tab" data-toggle="tab" href="#empresa" role="tab"
                aria-controls="empresa" aria-selected="true">Empresa</a>
        </li>
        @if ($empresa)
            <li class="nav-item">
                <a class="nav-link" id="lojas-tab" data-toggle="tab" href="#lojas" role="tab" aria-controls="lojas"
                    aria-selected="false">Lojas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab"
                    aria-controls="usuarios" aria-selected="false">Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nfe-tab" data-toggle="tab" href="#nfe" role="tab" aria-controls="nfe"
                    aria-selected="false">NFE</a>
            </li>
        @endif
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="empresa" role="tabpanel" aria-labelledby="empresa-tab">
            @if ($empresa)
                <form action="{{ route('admin.empresa.update', ['empresa' => $empresa->id]) }}" method="POST"
                    style="padding-top: 50px">
                    @method('PUT')
                    @csrf
                @else
                    <form action="{{ route('admin.empresa.store') }}" method="POST">
                        @csrf
            @endif
            <div class="mb-3">
                <label for="razao_social" class="form-label">Razão Social *</label>
                <input type="text" class="form-control" id="razao_social" value="{{ $empresa->razao_social ?? '' }}"
                    name="razao_social" required>
            </div>

            <div class="mb-3">
                <label for="nome_fantasia" class="form-label">Nome Fantasia *</label>
                <input type="text" class="form-control" id="nome_fantasia"
                    value="{{ $empresa->nome_fantasia ?? '' }}" name="nome_fantasia" required>
            </div>

            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ *</label>
                <input type="text" class="form-control" id="cnpj"
                    {{ $empresa && $empresa->cnpj ? 'readonly' : '' }}
                    value="{{ $empresa && $empresa->cnpj ? formatCNPJ($empresa->cnpj) : '' }}" name="cnpj" required>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" {{ $empresa && $empresa->ativo ? 'checked' : '' }}
                    name="ativo" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Ativo*
                </label>
            </div>

            <div class="mb-3">
                <label for="modulo_id" class="form-label">Selecione o Módulo*</label>
                <select class="form-control select2" name="modulo_id" {{ $empresa ? 'disabled' : '' }} id="modulo_id">
                    <option value="">Selecione um módulo</option>
                    @foreach (config('config.modulos') as $nome => $id)
                        <option value="{{ $id }}"
                            {{ $empresa && $empresa->matriz->modulo_id == $id ? 'selected' : '' }}>
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.empresa.index') }}" class="btn btn-outline-danger">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-dark"> <i class="bi bi-floppy"></i> Salvar</button>
            </div>
            </form>
        </div>

        <div class="tab-pane fade" id="lojas" role="tabpanel" aria-labelledby="lojas-tab">
            @if ($empresa)
                <div class="d-flex justify-content-end m-3">
                    <a href="{{ route('admin.loja.create', ['empresa_id' => $empresa->id]) }}" class="btn btn-dark">
                        <i class="bi bi-plus"></i> Adicionar Loja
                    </a>
                </div>
                <div class="table-responsive mt-1">
                    <table style="cursor: pointer;" class="table table-bordered" id="tabela-lojas" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>status</th>
                                <th>NFE</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($empresa->lojas as $l)
                                <tr>
                                    <td>{{ $l->id }}</td>
                                    <td>{{ $l->nome }}</td>
                                    <td>{{ formatCNPJ($l->cnpj) }}</td>

                                    <td><span
                                            class="badge bg-{{ $l->status_id == config('config.status.ativo') ? 'primary' : 'danger' }}">{{ $l->status->descricao }}</span>
                                    </td>
                                    <td>{{ $l->nfeio ? 'Sim' : 'Não' }}</td>

                                    <td class="text-center">
                                        {{-- <a href="{{ route('admin.empresa.show', ['empresa' => $l->id]) }}"
                                            class="btn btn-light">
                                            <i class="bi bi-person"></i> Usuários
                                        </a> --}}
                                        <a href="{{ route('admin.loja.edit', ['loja_id' => $l->id]) }}"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>status</th>
                                <th>NFE</th>
                                <th>Ação</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.empresa.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            @endif
        </div>

        <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">
            <h3 class="mt-3">Conteúdo dos Usuários</h3>
            <p>Aqui você pode adicionar o conteúdo relacionado aos usuários.</p>
        </div>


        <div class="tab-pane fade" id="nfe" role="tabpanel" aria-labelledby="nfe-tab">
            <h5 class="m-1 mb-3">Conteúdo sobre a NFE de cada loja da empresa.</h5>

            @if ($empresa && $empresa->lojas->count())
                @foreach ($empresa->lojas as $l)
                    @include('admin.empresas.inc.form_nfe', ['loja' => $l])
                @endforeach
            @endif
        </div>
    </div>
</div>
<script>
    montaDatatable('tabela-lojas');
    select2('modulo_id');
</script>
