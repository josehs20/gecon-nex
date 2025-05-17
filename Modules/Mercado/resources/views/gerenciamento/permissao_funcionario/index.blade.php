@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Permissão de usuários']]])

@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Permissão de acesso para os grupos de usuários</h3>
            <p class="lead">Nesta tela você tem a listagem de permissões de acessso dos grupos de usuários.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Selecione um grupo de usuário: </h5>
            <select required id="funcionario" name="funcionario" class="form-control w-100">
                <option value="" selected>Selecione ... </option>
                @foreach (config('config.tipo_usuarios') as $tipo_funcionario => $dados)
                    @if (($dados['id'] != config('config.tipo_usuarios.admin.id')) && $dados['id'] != config('config.tipo_usuarios.cliente_master.id'))
                        <option value="{{$dados['id']}}">{{$dados['descricao']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-flex align-items-center mt-5">
        <h5 id="label_tipo_usuario"></h5>
    </div>

    <div class="row justify-content-between mx-1">
        {{-- TABELA QUE ADICIONA PERMISSOES --}}
        <div class="card col-12 col-xl-6">
            <div class="card-body px-0">
                <div class="col-12">
                    <h5 class="mb-0">Permissões</h5>
                    <small>Adicione permissões para o grupo de usuário selecionado</small>

                        <table class="table table-bordered" id="tabela_adicionar_permissao_por_grupo_usuario">
                            <thead>
                                <tr>
                                    <th>Telas</th>
                                    <th width="100px">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Telas</th>
                                    <th width="100px">Ação</th>
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        </div>
        {{-- TABELA QUE REMOVE PERMISSOES --}}
        <div class="card col-12 col-xl-6">
            <div class="card-body px-0">
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Permissões atuais para o grupo </h5>
                        <h5 class="mb-0" id="grupo_permissao_atual"></h5>
                    </div>
                    <small>Remova permissões adicionadas para o grupo de usuário selecionado</small>

                        <table class="table table-bordered" id="tabela_permissao_por_grupo_usuario">
                            <thead>
                                <tr>
                                    <th>Telas</th>
                                    <th width="100px">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Telas</th>
                                    <th width="100px">Ação</th>
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        var rota_buscar_permissoes = @json(route('funcionarios.buscar.permissoes'));
        var rota_buscar_todas_permissoes = @json(route('funcionarios.buscar.todas.permissoes'));

        $(document).ready(function() {
            select2('funcionario');
            montaDatatable('tabela_permissao_por_grupo_usuario');
            montaDatatable('tabela_adicionar_permissao_por_grupo_usuario');
            buscar_permissoes();
        });

        function buscar_permissoes(){
            $('#funcionario').on('change', function() {
                var valor_do_select = $(this).val();
                var texto_do_select = $(this).find('option:selected').text();
                $('#label_tipo_usuario').html(`Listagem das permissões de <strong style="font-size: 1.3rem">${texto_do_select}</strong>`);
                $('#grupo_permissao_atual').html(` <strong style="font-size: 1.3rem; margin-left: 7px">${texto_do_select}</strong>`);
                montaDatatable('tabela_permissao_por_grupo_usuario', rota_buscar_permissoes, {
                    tipo_usuario_id: valor_do_select
                });
                montaDatatable('tabela_adicionar_permissao_por_grupo_usuario', rota_buscar_todas_permissoes, {
                    tipo_usuario_id: valor_do_select
                });
            });
        }

        async function adicionarPermissao(tipo_usuario_id, processo_id){
            let rota = @json(route('funcionarios.adicionar.permissoes'));
            $(`.btn_adicionar_permissoes`).prop('disabled', true);
            await $.ajax({
                url: rota,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'tipo_usuario_id': tipo_usuario_id,
                    'processo_id': processo_id
                },
                success: function(response) {
                    $(`.btn_adicionar_permissoes`).prop('disabled', false);
                    if(response.success){
                        toastr.success(response.mensagem);
                    } else {
                        toastr.error(response.mensagem);
                    }
                    recarregarDatatable(tipo_usuario_id, 'tabela_adicionar_permissao_por_grupo_usuario', rota_buscar_todas_permissoes);
                    recarregarDatatable(tipo_usuario_id, 'tabela_permissao_por_grupo_usuario', rota_buscar_permissoes);

                },
                error: function(xhr, response, error) {
                    console.log(xhr, response, error);
                    $(`.btn_adicionar_permissoes`).prop('disabled', false);
                    toastr.warning('Não foi possível adicionar a permissão');
                }
            });
        }

        async function removerPermissao(processo_usuario_id){
            let rota = @json(route('funcionarios.deletar.permissoes', ['processo_usuario_id' => 'processo_usuario_label']));
            rota = rota.replace('processo_usuario_label', processo_usuario_id);
            $(`.btn_remover_permissoes`).prop('disabled', true);
            await $.ajax({
                url: rota,
                type: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'processo_usuario_id': processo_usuario_id,
                },
                success: function(response) {
                    $(`.btn_remover_permissoes`).prop('disabled', false);
                    if(response.success){
                        toastr.success(response.mensagem);
                    } else {
                        toastr.error(response.mensagem);
                    }

                    recarregarDatatable(response.tipo_usuario_id, 'tabela_permissao_por_grupo_usuario', rota_buscar_permissoes);
                    recarregarDatatable(response.tipo_usuario_id, 'tabela_adicionar_permissao_por_grupo_usuario', rota_buscar_todas_permissoes);

                },
                error: function(xhr, response, error) {
                    console.log(xhr, response, error);
                    $(`.btn_remover_permissoes`).prop('disabled', false);
                    toastr.warning('Não foi possível remover a permissão');
                }
            });
        }

        function recarregarDatatable(tipo_usuario_id, tabela_id, rota){
            montaDatatable(tabela_id, rota, {
                tipo_usuario_id: tipo_usuario_id
            });
        }
    </script>
@endsection
