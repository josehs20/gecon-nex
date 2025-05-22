@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Permissões de usuários']]])

@section('content')
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
                    @if (
                        $tipo_usuario_valor['id'] != config('config.tipo_usuarios.admin.id') &&
                            $tipo_usuario_valor['id'] != config('config.tipo_usuarios.cliente_master.id'))
                        <option value="{{ $tipo_usuario_valor['id'] }}"
                            {{ isset($user) && $user->tipo_usuario_id == $tipo_usuario_valor['id'] ? 'selected' : '' }}>
                            {{ strtoupper($tipo_usuario_valor['descricao']) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-around">
            @include('mercado::gerenciamento.permissoes.tabelaPermissoes')
            @include('mercado::gerenciamento.permissoes.tabelaPermissoesUsuario')
        </div>
    </div>
    <script>
        const ROTA_BUSCAR_PERMISSOES = @json(route('gecon.usuarios.permissao.buscar', 'TIPO_USUARIO_ID'));
        const ROTA_BUSCAR_PERMISSOES_POR_TIPO_USUARIO = @json(route('gecon.usuarios.permissao.buscar_por_tipo_usuario', 'TIPO_USUARIO_ID'));

        $(document).ready(function() {
            iniciarTabelas();
            selecionarTipoUsuario();
        });

        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function selecionarTipoUsuario() {
            $('#tipo_usuario_id').on('change', async function() {
                let tipo_usuario_id = $(this).val();
                let tipo_usuario_label = $(this).find("option:selected").text();
                $('#label-permissao-do-tipo-usuario').text(tipo_usuario_label);

                if(tipo_usuario_id == 0){
                    limparTabelas();
                    $('#label-permissao-do-tipo-usuario').text('');
                } else {
                    await buscarPermissoes(tipo_usuario_id);
                }
            });
        }

        async function buscarPermissoes(tipo_usuario_id) {
            try {
                const URL_TODAS_PERMISSOES = ROTA_BUSCAR_PERMISSOES.replace('TIPO_USUARIO_ID', tipo_usuario_id);
                const URL_PERMISSAO_TIPO_USUARIO = ROTA_BUSCAR_PERMISSOES_POR_TIPO_USUARIO.replace('TIPO_USUARIO_ID', tipo_usuario_id);

                limparTabelas();

                montaDatatable("tabela-permissoes-sistema", URL_TODAS_PERMISSOES);
                montaDatatable("tabela-permissoes-do-usuario", URL_PERMISSAO_TIPO_USUARIO);
            } catch (error) {
                limparTabelas();
                iniciarTabelas();
                console.error('Não foi possível buscar as permissões: ', error);
                return [];
            }
        }

        function iniciarTabelas() {
            montaDatatable("tabela-permissoes-sistema");
            montaDatatable("tabela-permissoes-do-usuario");
        }

        function limparTabelas(){
            $("#tabela-permissoes-sistema").DataTable().clear().draw();
            $("#tabela-permissoes-do-usuario").DataTable().clear().draw();
        }

        async function adicionarPermissao(processo_id, tipo_usuario_id) {
            try {
                const ROTA_ADICIONAR = @json(route('gecon.usuarios.permissao.adicionar', [
                        'processo_id' => '__processo_id__',
                        'tipo_usuario_id' => '__tipo_usuario_id__'
                    ]));
                const URL = ROTA_ADICIONAR.replace('__processo_id__', processo_id).replace('__tipo_usuario_id__',
                    tipo_usuario_id);

                const response = await fetch(URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken()
                    }
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success(data.msg);
                    await buscarPermissoes(data.tipo_usuario_id);
                } else {
                    toastr.warning(data.msg);
                }
            } catch (error) {
                toastr.error('Não foi possível adicionar permissão:');
            }
        }

        async function removerPermissao(processo_id, tipo_usuario_id) {
            try {
                const ROTA_REMOVER = @json(route('gecon.usuarios.permissao.remover', [
                        'processo_id' => '__processo_id__',
                        'tipo_usuario_id' => '__tipo_usuario_id__'
                    ]));
                const URL = ROTA_REMOVER.replace('__processo_id__', processo_id).replace('__tipo_usuario_id__',
                    tipo_usuario_id);

                const response = await fetch(URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken()
                    }
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success(data.msg);
                    await buscarPermissoes(data.tipo_usuario_id);
                } else {
                    toastr.warning(data.msg);
                }
            } catch (error) {
                toastr.error('Não foi possível remover permissão!');
            }
        }
    </script>
@endsection
