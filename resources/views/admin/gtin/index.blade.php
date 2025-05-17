@extends('layouts.app', ['trilhaPaginas' => [['rota' => route('admin.empresa.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Lista de Gtins']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Lista de Gtins</h3>

            <p class="lead">Nesta tela você pode ver todos os Gtins cadastrado em base</p>
        </div>
        <a id="CadastrarGtin" class="btn btn-success">
            <i class="bi bi-plus"></i> GTIN
        </a>
    </div>

    <div class="card card-body table-responsive">
        <h5 class="card-title" style="color: black !important;">Links sugeridos</h5>

        <ul>
            <li><u><a target="_blank" style="color: black !important;" href="https://cosmos.bluesoft.com.br/">Cosmos</a></u>
            </li>
            <li><u><a target="_blank" style="color: black !important;"
                        href="https://dfe-portal.svrs.rs.gov.br/Nfe/gtin">Receita</a></u> </li>
        </ul>
        <br>
        <table class="table table-bordered" id="tabela-gtin-admin" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gtin</th>
                    <th>Descricao</th>
                    <th>NCM</th>
                    <th>Última validação</th>
                    <th>Prioridade</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> --}}
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Gtin</th>
                    <th>Descricao</th>
                    <th>NCM</th>
                    <th>Última validação</th>
                    <th>Prioridade</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('admin.empresa.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="modal fade" id="modalCadastrar" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastrarLabel">Cadastrar GTIN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCadastrar" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="gtin">GTIN</label>
                            <input type="text" class="form-control" name="gtin" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" class="form-control" name="tipo" required>
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" required>
                        </div>
                        <div class="form-group">
                            <label for="ncm">NCM</label>
                            <input type="text" class="form-control" name="ncm">
                        </div>
                        <div class="form-group">
                            <label for="comprimento">Comprimento</label>
                            <input type="text" class="form-control" name="comprimento">
                        </div>
                        <div class="form-group">
                            <label for="altura">Altura</label>
                            <input type="text" class="form-control" name="altura">
                        </div>
                        <div class="form-group">
                            <label for="largura">Largura</label>
                            <input type="text" class="form-control" name="largura">
                        </div>
                        <div class="form-group">
                            <label for="peso_bruto">Peso Bruto</label>
                            <input type="text" class="form-control" name="peso_bruto">
                        </div>
                        <div class="form-group">
                            <label for="peso_liquido">Peso Líquido</label>
                            <input type="text" class="form-control" name="peso_liquido">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                onclick="fecharModal('modalCadastrar')">Fechar</button>
                            <button type="submit" class="btn btn-dark">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Edição -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar GTIN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        @csrf
                        <input type="hidden" id="id_gtin">
                        <div class="form-group">
                            <label for="gtin">GTIN</label>
                            <input type="text" class="form-control" id="gtin" name="gtin">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao">
                        </div>

                        <div class="form-group">
                            <label for="ncm">NCM</label>
                            <input type="text" class="form-control" id="ncm" name="ncm">
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" class="form-control" id="tipo" name="tipo">
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade">
                        </div>
                        <div class="form-group">
                            <label for="comprimento">Comprimento</label>
                            <input type="text" class="form-control" id="comprimento" name="comprimento">
                        </div>
                        <div class="form-group">
                            <label for="altura">Altura</label>
                            <input type="text" class="form-control" id="altura" name="altura">
                        </div>
                        <div class="form-group">
                            <label for="largura">Largura</label>
                            <input type="text" class="form-control" id="largura" name="largura">
                        </div>
                        <div class="form-group">
                            <label for="peso_bruto">Peso Bruto</label>
                            <input type="text" class="form-control" id="peso_bruto" name="peso_bruto">
                        </div>
                        <div class="form-group">
                            <label for="peso_liquido">Peso Líquido</label>
                            <input type="text" class="form-control" id="peso_liquido" name="peso_liquido">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                onclick="fecharModal('modalEditar')">Fechar</button>
                            <button type="submit" class="btn btn-dark">Salvar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        var getGtinsYajra = @json(route('admin.gtin.yajra'));
        var getGtinsId = @json(route('admin.gtin.show'));
        var routeUpdateGtin = @json(route('admin.gtin.update'));
        var routeStoreGtin = @json(route('admin.gtin.post'));
        var routeDeleteGtin = @json(route('admin.gtin.delete'));
        $(document).ready(function() {
            $('#CadastrarGtin').on('click', function() {
                $('#modalCadastrar').modal('show');
            });
            // Enviar o formulário via AJAX
            $('#formCadastrar').on('submit', function(e) {
                e.preventDefault(); // Evita o envio do formulário padrão

                let formData = $(this).serialize(); // Serializa os dados do formulário

                $.ajax({
                    url: routeStoreGtin, // Rota para cadastrar o GTIN
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            msgToastr(response.msg, 'success');

                            $('#modalEditar').modal('hide');
                        } else {
                            msgToastr(response.msg, 'error');
                        }
                    }
                }).always(function() {
                    desbloquear();
                    $('#tabela-gtin-admin').DataTable().ajax.reload(null, false);

                });
            });
            // Função para abrir o modal de edição e preencher os dados
            $(document).on('click', '.btn-warning', function() {
                let id = $(this).data('id'); // Obtém o ID do atributo data-id do botão
                $.ajax({
                    url: getGtinsId,
                    data: {
                        id: id
                    },
                    method: 'GET',
                    success: function(data) {
                        $('#id_gtin').val(data.id);
                        $('#gtin').val(data.gtin);
                        $('#descricao').val(data.descricao);
                        $('#tipo').val(data.tipo);
                        $('#quantidade').val(data.quantidade);
                        $('#comprimento').val(data.comprimento);
                        $('#altura').val(data.altura);
                        $('#largura').val(data.largura);
                        $('#peso_bruto').val(data.peso_bruto);
                        $('#peso_liquido').val(data.peso_liquido);
                        $('#ncm').val(data.ncm);

                        $('#modalEditar').modal('show');
                    }
                });
            });

            // Função para salvar a edição
            $(document).on('submit', '#formEditar', function(event) {
                event.preventDefault(); // Impede o envio padrão do formulário
                bloquear();
                let formData = $(this).serialize();

                $.ajax({
                    url: routeUpdateGtin,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            msgToastr(response.msg, 'success');

                            $('#modalEditar').modal('hide');
                        } else {
                            msgToastr(response.msg, 'error');
                        }
                    }
                }).always(function() {
                    desbloquear();
                    $('#tabela-gtin-admin').DataTable().ajax.reload(null, false);

                });
            });

            // Função para excluir um item
            $(document).on('click', '.btn-danger', function() {
                let id = $(this).data('id'); // Obtém o ID do atributo data-id do botão
                if ($(this).find('i.bi-trash').length > 0) {
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: 'Esta ação não pode ser desfeita!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            bloquear();
                            $.ajax({
                                url: routeDeleteGtin,
                                method: 'POST',
                                data: {
                                    id: id,
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content') // 🔹 Adiciona o CSRF Token
                                },
                                success: function(response) {
                                    if (response.success) {
                                        msgToastr(response.msg, 'success');

                                        $('#modalEditar').modal('hide');
                                    } else {
                                        msgToastr(response.msg, 'error');
                                    }
                                }
                            }).always(function() {
                                desbloquear();
                                $('#tabela-gtin-admin').DataTable().ajax.reload(null,
                                false);

                            });
                        }
                    });
                }
            });
        });
        const columns = [{
                data: 'id',
                title: 'ID'
            },
            {
                data: 'gtin',
                title: 'GTIN'
            },
            {
                data: 'descricao',
                title: 'Descrição'
            },
            {
                data: 'ncm',
                title: 'NCM'
            },
            {
                data: 'ultima_verificacao',
                title: 'Última validação'
            },
            {
                data: 'prioridade',
                title: 'Prioridade'
            }, // Classificação do produto
            {
                data: 'acao',
                title: 'Ação',
                orderable: false, // Desabilita ordenação
                searchable: false // Desabilita pesquisa na coluna
            }, // Ação
        ];
        montaDatatableYajra('tabela-gtin-admin', columns, getGtinsYajra);
    </script>
@endsection
