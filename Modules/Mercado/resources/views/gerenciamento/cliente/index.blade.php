@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Clientes']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Clientes </h3>
            <p class="lead">Nesta tela você pode realizar ações no que diz respeito aos clientes.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.cliente.create') }}" class="btn btn-success"><i class="bi bi-plus"></i>Cliente </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive elevated">
            <table class="table table-bordered" id="tabela-clientes" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Status</th>
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
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Status</th>
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

    {{-- Modal para exibir os dados do cliente com uma melhor leitura --}}
    @include('mercado::gerenciamento.cliente.show')

    <script>
        var clientesInativos = false;

        const columns = [
            ['id', '#'],
            ['nome', 'Nopme'],
            ['documento', 'Documento'],
            ['status.descricao', 'Status'], // Adicionado CNPJ que estava faltando
            ['ativo', 'Ativo'],
            ['celular', 'Celular'],
            ['email', 'E-mail'],
            ['acao', 'Ação', false, false]

        ];

        var routeGetClientes = @json(route('yajra.service.gerenciamento.clientes.get'));
        var routeGetCliente = @json(route('cadastro.cliente.get.cliente'));
        montaDatatableYajra('tabela-clientes', montaColunasParaYajra(columns), routeGetClientes);

        function mostrarDadosCliente(id) {
            $('#modalDadosCliente').modal('show');
            bloquear();
            $.ajax({
                url: routeGetCliente,
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    if (response.success == true) {
                        let cliente = response.cliente;

                        // Atualiza o título do modal
                        $('.modal-title', '#modalDadosCliente').text(cliente.nome);
                        $('#labelStatus')
                            .text('Status: ' + cliente.status.descricao)
                            .removeClass()
                            .addClass(cliente.status.badge);

                        // Organiza os dados em listas distintas
                        $('.modal-body', '#modalDadosCliente').html(`
    <div class="container">
        <!-- Lista de Dados Pessoais -->
        <ul class="list-group mb-3">
            <li class="list-group-item active">Dados Pessoais</li>
            <li class="list-group-item"><strong>Documento:</strong> ${aplicarMascaraDocumento(cliente.documento)}</li>
            <li class="list-group-item"><strong>Data de Nascimento:</strong> ${aplicarMascaraDataNascimento(cliente.data_nascimento) ?? ''}</li>
            <li class="list-group-item"><strong>Limite de Crédito:</strong> ${centavosParaReais(cliente.credito.credito_loja) ?? ''}</li>
            <li class="list-group-item"><strong>Celular:</strong> ${aplicarMascaraCelular(cliente.celular) ?? ''}</li>
            <li class="list-group-item"><strong>Telefone:</strong> ${aplicarMascaraTelefoneFixo(cliente.telefone_fixo) ?? ''}</li>
            <li class="list-group-item"><strong>Email:</strong> ${cliente.email ?? ''}</li>
        </ul>

        <!-- Lista de Endereço -->
        <ul class="list-group">
           <li class="list-group-item active">Endereço</li>
                    <li class="list-group-item"><strong>Logradouro:</strong> ${cliente.endereco.logradouro ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Número:</strong> ${cliente.endereco.numero ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Bairro:</strong> ${cliente.endereco.bairro ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Cidade:</strong> ${cliente.endereco.cidade ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Estado (UF):</strong> ${cliente.endereco.uf ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>CEP:</strong> ${cliente.endereco.cep ?? 'Não informado'}</li>
                    <li class="list-group-item"><strong>Complemento:</strong> ${cliente.endereco.complemento ?? 'Não informado'}</li>
        </ul>

        <!-- Observação -->
        <ul class="list-group mt-3">
            <li class="list-group-item active">Observação</li>
            <li class="list-group-item" style="word-wrap: break-word; overflow-wrap: break-word;">
                ${cliente.observacao ?? 'Nenhuma observação registrada.'}
            </li>
        </ul>
    </div>
`);
                        // Ajustar o modal para garantir que o conteúdo não ultrapasse os limites
                        // $('#modalDadosCliente').css('max-height', '80vh').css('overflow-y', 'auto');
                    } else {
                        msgToastr(response.msg, 'warning');

                    }
                },
                error: function(xhr) {
                    msgToastr('Erro ao carregar os dados do cliente.', 'warning');
                }
            }).always(function() {
                desbloquear(); // Chama a função desejada
            });;

        }

        $(document).ready(function() {
            $(document).on('click', '.mostrarDadosCliente', function() {
                let id = $(this).data('id'); // Obtém o ID do cliente
                mostrarDadosCliente(id); // Chama a função para buscar e exibir os dados
            });
            // Evento ao clicar no botão para fechar o modal
            $(document).on('click', '#fecharModal', function() {
                // Fecha o modal usando Bootstrap modal('hide')
                $('#modalDadosCliente').modal('hide');
            });
        });
    </script>
@endsection
