<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="card card-body">
    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="caixaTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="caixa-tab" data-toggle="tab" href="#caixa" role="tab" aria-controls="caixa"
                aria-selected="true">
                Caixa
            </a>
        </li>
        @if ($caixa)
            <li class="nav-item">
                <a class="nav-link" id="recursos-tab" data-toggle="tab" href="#recursos" role="tab"
                    aria-controls="recursos" aria-selected="false">
                    Recursos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="permissoes-tab" data-toggle="tab" href="#permissoes" role="tab"
                    aria-controls="permissoes" aria-selected="false">
                    Permissões
                </a>
            </li>
        @endif

    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="caixaTabsContent">
        <!-- Aba Caixa -->
        <div class="tab-pane active" id="caixa" role="tabpanel" aria-labelledby="caixa-tab">
            <form action="{{ route('cadastro.caixa.store') }}" method="POST">
                @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nome">Nome: *</label>
                            <input type="text" id="nome" name="nome" value="{{ $caixa->nome ?? '' }}"
                                required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="loja_id">Loja: * <small>Loja logada selecionada</small></label>
                            <select disabled {{ $caixa ? 'disabled' : '' }} id="loja_id" name="lojas[]" multiple
                                class="form-control select2">
                                @foreach (auth()->user()->getUserModulo->lojas as $item)
                                    <option value="{{ $item->id }}"
                                        {{ auth()->user()->getUserModulo->loja_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($caixa)
                        <div class="col-md-4 mt-5">
                            <div class="form-check">
                                <input type="checkbox" name="ativo"
                                    {{ $caixa && $caixa->ativo == true ? 'checked' : '' }} class="form-check-input"
                                    id="ativo">
                                <label class="form-check-label" for="ativo">Ativo</label>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Footer -->
                <div class="card-footer">
                    <a href="{{ route('cadastro.caixa.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-dark mx-2">
                        <i class="bi bi-floppy"></i> Salvar
                    </button>
                </div>
            </form>
        </div>

        <div class="tab-pane" id="recursos" role="tabpanel" aria-labelledby="recursos-tab">
            @if ($caixa && isset($recursos))
                <form id="formRecursosCaixa"
                    action="{{ route('cadastro.caixa.store.recursos_caixa', ['id' => $caixa->id]) }}" method="POST">
                    @csrf
                    <br>
                    <div class="row">
                        @foreach ($recursos as $r)
                            <div class="col-md-4 mb-3">
                                <label for="recurso{{ $r->id }}"
                                    class="card recurso-card border shadow-sm cursor-pointer h-100">
                                    <div class="card-body" style="cursor: pointer;">
                                        <div class="form-check">
                                            <input {{ $caixa->recursos->contains('id', $r->id) ? 'checked' : '' }}
                                                type="checkbox" class="form-check-input recurso-checkbox"
                                                id="recurso{{ $r->id }}" name="recursos[]"
                                                value="{{ $r->id }}">
                                            <span class="font-weight-bold ml-2">{{ ucfirst($r->nome) }}</span>
                                            <p class="text-muted mb-0 mt-2" style="font-size: 0.875rem;">
                                                {{ $r->descricao }}
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('cadastro.caixa.index') }}" class="btn btn-danger">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-dark mx-2">
                            <i class="bi bi-floppy"></i> Salvar
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="tab-pane" id="permissoes" role="tabpanel" aria-labelledby="permissoes-tab">
            @if ($caixa && isset($recursos))
                <div class="">
                    <br>
                    <form id="formPermissaoCaixa"
                        action="{{ route('cadastro.caixa.salvar_permissao', ['id' => $caixa->id]) }}">
                        @csrf
                        <div class="row align-items-end mb-3">
                            <div class="col-md-8">
                                <label for="usuario_id">Selecione o usuário:</label>
                                <select id="usuario_id" name="usuario_id" class="form-control select2"
                                    required></select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="superior" name="superior"
                                        value="1">
                                    <label class="form-check-label" for="superior">Acesso Master (liberação de
                                        senha)</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-dark">
                                    <i class="bi bi-plus"></i> adicionar
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="permissoesTable" class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuário</th>
                                    <th>Permissão master</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('cadastro.caixa.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    var routeUsuariosCaixaPermissao = @json(route('cadastro.caixa.get_usuarios_permissao_caixa'));
    var routeUsuariosGet = @json(route('cadastro.caixa.get_usuarios'));
    var routeDeletePermissao = @json(route('cadastro.caixa.delete.permissao'));
    var caixa = @json($caixa);

    $(document).ready(function() {
        select2('loja_id');
        select2('usuario_id', routeUsuariosGet, false, {
            caixa_id: caixa.id
        });
        montaDatatable('permissoesTable', routeUsuariosCaixaPermissao, {
            caixa_id: caixa.id
        });

        $('#formRecursosCaixa').on('submit', function(e) {
            e.preventDefault();
            bloquear();

            const form = $(this);
            const action = form.attr('action');
            const data = form.serialize();

            $.ajax({
                url: action,
                method: 'POST',
                data: data,
                success: function(res) {
                    if (res.success == true) {
                        msgToastr(res.msg, 'success');
                    } else {
                        msgToastr(res.msg, 'warning');
                    }
                },
                error: function(err) {
                    msgToastr('Houve um problema ao salvar os recursos.', 'error');
                },
                complete: function() {
                    desbloquear();
                }
            });
        });
        $('#formPermissaoCaixa').on('submit', function(e) {
            e.preventDefault();
            bloquear();

            const form = $(this);
            const action = form.attr('action');
            const data = form.serialize();

            $.ajax({
                url: action,
                method: 'POST',
                data: data,
                success: function(res) {
                    if (res.success == true) {
                        msgToastr(res.msg, 'success');
                        $('#permissoesTable').DataTable().ajax
                            .reload(); // Atualiza a tabela
                        form.trigger("reset"); // Limpa o form
                        $('#usuario_id').val(null).trigger('change'); // Limpa o select2
                    } else {
                        msgToastr(res.msg, 'warning');
                    }
                },
                error: function(err) {
                    msgToastr('Houve um problema ao salvar a permissão.', 'error');
                },
                complete: function() {
                    desbloquear();
                }
            });
        });
    });

    function excluirPermissao(caixa_permissao_id) {
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Esta ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                cancelButton: 'btn btn-secondary mx-1',
                confirmButton: 'btn btn-dark',
            },
            buttonsStyling: false,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, excluir!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routeDeletePermissao,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Inclua o token CSRF se estiver usando Laravel
                    },
                    data: {
                        caixa_permissao_id: caixa_permissao_id
                    },
                    success: function(res) {
                        if (res.success == true) {
                            msgToastr(res.msg, 'success');
                            $('#permissoesTable').DataTable().ajax
                                .reload(); // Atualiza a tabela
                            form.trigger("reset"); // Limpa o form
                            $('#usuario_id').val(null).trigger('change'); // Limpa o select2
                        } else {
                            msgToastr(res.msg, 'warning');
                        }
                    },
                    error: function(err) {
                        msgToastr('Houve um problema ao excluir a permissão.', 'error');
                    },
                    complete: function() {
                        desbloquear();
                    }
                });
            }
        });
    }
</script>
