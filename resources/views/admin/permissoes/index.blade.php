@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Permissões de usuários']]])

@section('content')
@vite('resources/js/views/admin/permissoes.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Permissões de usuários</h3>
            <p class="lead">Nesta tela você pode trabalhar com as permissões de usuários no sistema GECON.</p>
        </div>
    </div>

    <div class="card card-body table-responsive elevated">
        <div class="form-group col-12">
            <label for="tipo_usuario_id">Tipo de usuário <span style="color: red">*</span></label>
            <select required class="form-control" name="tipo_usuario_id" id="tipo_usuario_id">
                <option value="0" {{ !isset($user) ? 'selected' : '' }}>Selecione um tipo de usuário </option>
                @foreach (config('config.tipo_usuarios') as $tipo_usuario_chave => $tipo_usuario_valor)
                    @if ($tipo_usuario_valor['id'] != 1)
                        <option value="{{ $tipo_usuario_valor['id'] }}"
                            {{ isset($user) && $user->tipo_usuario_id == $tipo_usuario_valor['id'] ? 'selected' : '' }}>
                            {{ strtoupper($tipo_usuario_valor['descricao']) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-around">
            @include('admin.permissoes.tabelaPermissoes')
            @include('admin.permissoes.tabelaPermissoesUsuario')
        </div>
    </div>

@endsection

