@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Fornecedores']]])


@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Fornecedores </h3>
            <p class="lead">Nesta tela você pode realizar ações no que diz respeito aos fornecedores.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.fornecedor.create') }}" class="btn btn-success"><i class="bi bi-plus"></i>fornecedor
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tabela-fornecedores" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome&nbsp;Fantasia</th>
                            <th>Documento</th>
                            <th>Ativo</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome&nbsp;Fantasia</th>
                            <th>Documento</th>
                            <th>Ativo</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                            <th>Ação</th>
                        </tr>
                    </tfoot>
                </table>

                <div class="card-footer">
                    <a href="{{ route('home.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
        {{-- Modal para exibir os dados do fornecedor com uma melhor leitura --}}
        @include('mercado::gerenciamento.fornecedor.show')

        <script>
            const columns = [{
                    data: 'id',
                    title: '#'
                },
                {
                    data: 'nome_fantasia',
                    title: 'Nome Fantasia'
                },
                {
                    data: 'documento',
                    title: 'Dcoumento'
                },
                {
                    data: 'ativo',
                    title: 'Ativo'
                },
                {
                    data: 'celular',
                    title: 'Celular'
                },
                {
                    data: 'email',
                    title: 'E-mail'
                },
                {
                    data: 'acao',
                    title: 'Ação',
                    orderable: false,
                    searchable: false
                }
            ];

            var routeGetFornecedor = @json(route('cadastro.fornecedor.get'));
            var routeGetFornecedores = @json(route('yajra.service.gerenciamento.fornecedor.get'));
            montaDatatableYajra('tabela-fornecedores', columns, routeGetFornecedores);

            function mostrarDadosFornecedor(id) {
                if ($('#modalDadosFornecedor').hasClass('show')) {
                    return; // Se o modal já está aberto, não faz nada
                }

                $('#modalDadosFornecedor').modal('show');
                bloquear();
                $.ajax({
                    url: routeGetFornecedor,
                    type: 'GET',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);

                        if (response.success == true) {
                            let fornecedor = response.fornecedor;

                            // Atualiza o título do modal
                            $('.modal-title', '#modalDadosFornecedor').text(fornecedor.nome_fantasia);
                            // $('#labelStatus')
                            //     .text('Status: ' + cliente.status.descricao)
                            //     .removeClass()
                            //     .addClass(cliente.status.badge);

                            // Organiza os dados em listas distintas
                            $('.modal-body', '#modalDadosFornecedor').html(`
    <div class="container">
        <!-- Lista de Dados Pessoais -->
        <ul class="list-group mb-3">
            <li class="list-group-item active">Dados Pessoais</li>
            <li class="list-group-item"><strong>Nome :</strong> ${fornecedor.nome}</li>
            <li class="list-group-item"><strong>Nome Fantasia :</strong> ${fornecedor.nome_fantasia}</li>
            <li class="list-group-item"><strong>Documento :</strong> ${fornecedor.documento}</li>
            <li class="list-group-item"><strong>Celular :</strong> ${fornecedor.celular}</li>
            <li class="list-group-item"><strong>Telefone :</strong> ${fornecedor.telefone}</li>
            <li class="list-group-item"><strong>Email :</strong> ${fornecedor.email}</li>
        </ul>

        <!-- Lista de Endereço -->
        <ul class="list-group">
           <li class="list-group-item active">Endereço</li>
                    <li class="list-group-item"><strong>Logradouro:</strong> ${fornecedor.endereco.logradouro ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Número:</strong> ${fornecedor.endereco.numero ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Bairro:</strong> ${fornecedor.endereco.bairro ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Cidade:</strong> ${fornecedor.endereco.cidade ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Estado (UF):</strong> ${fornecedor.endereco.uf ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>CEP:</strong> ${fornecedor.endereco.cep ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Complemento:</strong> ${fornecedor.endereco.complemento ?? 'Não informado'}</li>
        </ul>

    </div>
`);
                            // Ajustar o modal para garantir que o conteúdo não ultrapasse os limites
                            // $('#modalDadosFornecedor').css('max-height', '80vh').css('overflow-y', 'auto');
                        } else {
                            msgToastr(response.msg, 'warning');

                        }
                    },
                    error: function(xhr) {
                        msgToastr('Erro ao carregar os dados do cliente.', 'warning');
                    }
                }).always(function() {
                    desbloquear(); // Chama a função desejada
                });

            }

            $(document).ready(function() {
                $(document).on('click', '.mostrarDadosFornecedor', function() {
                    let id = $(this).data('id'); // Obtém o ID do cliente
                    mostrarDadosFornecedor(id); // Chama a função para buscar e exibir os dados
                });
                // Evento ao clicar no botão para fechar o modal
                $(document).on('click', '#fecharModal', function() {
                    // Fecha o modal usando Bootstrap modal('hide')
                    $('#modalDadosFornecedor').modal('hide');
                });
            });
        </script>
    @endsection
