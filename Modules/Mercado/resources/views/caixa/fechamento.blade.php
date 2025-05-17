@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Fechamento de caixa']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h4>Fechamento de Caixa: <u>{{ $caixa->caixa->nome }}</u></h4>
            <p class="lead">
                Nesta tela, você pode analisar as movimentações do caixa desde a última abertura e também
                realizar seu fechamento.
            </p>
        </div>
    </div>
    <style>
        .nav-link.active {
            background-color: #007bff !important;
            /* Cor primária do Bootstrap */
            color: #fff !important;
            /* Texto branco */
        }
    </style>
    <div class="card">
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active custom-tab" id="nav-info-tab" data-toggle="tab" data-target="#nav-info"
                        type="button" role="tab" aria-controls="nav-info" aria-selected="true">Informações do
                        caixa</button>
                    <button class="nav-link custom-tab" id="nav-vRealizadas-tab" data-toggle="tab"
                        data-target="#nav-vRealizadas" type="button" role="tab" aria-controls="nav-vRealizadas"
                        aria-selected="false">Vendas realizadas</button>
                    <button class="nav-link custom-tab" id="nav-devolucoes-tab" data-toggle="tab"
                        data-target="#nav-devolucoes" type="button" role="tab" aria-controls="nav-devolucoes"
                        aria-selected="false">Devoluções</button>
                    <button class="nav-link custom-tab" id="nav-sangrias-tab" data-toggle="tab" data-target="#nav-sangrias"
                        type="button" role="tab" aria-controls="nav-sangrias" aria-selected="false">Sangrias</button>
                    <button class="nav-link custom-tab" id="nav-recebiemntos-tab" data-toggle="tab"
                        data-target="#nav-recebiemntos" type="button" role="tab" aria-controls="nav-recebiemntos"
                        aria-selected="false">Recebimentos</button>
                    <button class="nav-link custom-tab" id="nav-resumo-receb-tab" data-toggle="tab"
                        data-target="#nav-resumo-receb" type="button" role="tab" aria-controls="nav-resumo-receb"
                        aria-selected="false">Formas de recebimento</button>
                    <button class="nav-link custom-tab" id="nav-resumo-tab" data-toggle="tab" data-target="#nav-resumo"
                        type="button" role="tab" aria-controls="nav-resumo" aria-selected="false">Resumo</button>
                    <button class="nav-link custom-tab" id="nav-finalizar-tab" data-toggle="tab"
                        data-target="#nav-finalizar" type="button" role="tab" aria-controls="nav-finalizar"
                        aria-selected="false">Finalizar</button>
                </div>
            </nav>

            <!-- Conteúdo das abas -->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active m-3" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                    <div class="row">
                        <!-- Informações do Caixa -->
                        <div class="col-md-6">
                            <section class="caixa-info mb-4">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>Operador:</strong>
                                        <span>{{ $caixa->usuario->master->name }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Data de abertura:</strong>
                                        <span>{{ formatarData($caixa->data_abertura) }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Valor de abertura:</strong>
                                        <span>{{ converterParaReais($caixa->valor_abertura) }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Data de fechamento:</strong>
                                        <span id="data-fechamento">
                                            {{ $caixa->data_fechamento ? formatarData($caixa->data_fechamento) : 'Ainda em aberto' }}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Valor de fechamento:</strong>
                                        <span>{{ $caixa->data_fechamento ? formatarData($caixa->data_fechamento) : 'Ainda em aberto' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Observação:</strong>
                                        <span>{{ $caixa->descricao }}</span>
                                    </li>
                                </ul>
                            </section>
                        </div>

                        <!-- Valores Totais -->
                        <div class="col-md-6">
                            <section class="caixa-totais">
                                <ul class="list-group">

                                    <li class="list-group-item">
                                        <strong>Total em vendas:</strong>
                                        <span>{{ converterParaReais($caixa->total_venda) }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total devoluções:</strong>
                                        <span>{{ converterParaReais($totalDevolucao) }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total atual em dinheiro:</strong>
                                        <span>{{ converterParaReais($totalDinheiroAtual) }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total recebido:</strong>
                                        <span>{{ converterParaReais($caixa->total_recebimento) }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Sangrias realizadas:</strong>
                                        <span>{{ $sangrias->count() }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total retirado em sangria:</strong>
                                        <span>{{ converterParaReais($sangrias->sum('valor_sangria')) }}</span>
                                    </li>
                                </ul>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade m-3" id="nav-vRealizadas" role="tabpanel" aria-labelledby="nav-vRealizadas-tab">
                    <div class="table-responsive">
                        <table id="tabela-vendas" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nº Venda</th>
                                    <th>Cliente</th>
                                    <th>Formas de pagamento</th>
                                    <th>Subtotal</th>
                                    <th>Desconto</th>
                                    <th>Total</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aqui você vai listar as vendas dinamicamente -->
                                @foreach ($vendas as $v)
                                    <tr>
                                        <td>{{ $v->n_venda }}</td>
                                        <td>{{ $v->cliente->nome ?? 'Não informado' }}</td>
                                        <td>
                                            {!! $v->venda_pagamentos->groupBy('forma_pagamento_id')->map(function ($items) {
                                                    // Pega o último item do grupo
                                                    $ultimoItem = $items->last();
                                                    return '<span class="badge badge-' .
                                                        ($ultimoItem->status_id == config('config.status.pendente') ? 'danger' : 'primary') .
                                                        ' ">' .
                                                        $ultimoItem->forma->descricao .
                                                        ' ' .
                                                        ($ultimoItem->parcela > 0 ? $ultimoItem->parcela . 'x de ' : '') .
                                                        converterParaReais($ultimoItem->valor_pago) .
                                                        ' | ' .
                                                        $ultimoItem->status->descricao .
                                                        ': ' .
                                                        converterParaReais($ultimoItem->valor) .
                                                        '</span>';
                                                })->join(' ') !!}
                                        </td>

                                        <td>{{ converterParaReais($v->sub_total) }}</td>
                                        <td>{{ converterParaReais($v->desconto_dinheiro) }}</td>
                                        <td>{{ converterParaReais($v->total) }}</td>
                                        <td> <button type="button" onclick="modalItensVenda({{ $v->id }})"
                                                class="btn btn-primary"><i class="bi bi-info-circle"></i></button>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Adicione as vendas dinamicamente aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade m-3" id="nav-devolucoes" role="tabpanel" aria-labelledby="nav-devolucoes-tab">
                    <div class="table-responsive">
                        <table id="tabela-devolucao" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nº Venda</th>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Data</th>
                                    <th>Formas de devolução</th>
                                    <th>Total</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aqui você vai listar as vendas dinamicamente -->
                                @foreach ($devolucoes as $d)
                                    <tr>
                                        <td>{{ $d->venda->n_venda }}</td>
                                        <td>{{ $d->venda->cliente->nome ?? 'Não informado' }}</td>
                                        <td>{{ $d->venda->status_id == config('condig.status.devolucao') ? 'Devolução total' : 'Devolução parcial' }}
                                        </td>
                                        <td>{{ formatarData($d->data_devolucao) }}</td>
                                        <td>
                                            {!! $d->venda_pagamentos_devolucao->map(function ($item) {
                                                    return '<span class="badge badge-danger">' .
                                                        $item->venda_pagamento->forma->descricao .
                                                        ' ' .
                                                        converterParaReais($item->valor) .
                                                        '</span>';
                                                })->implode(' ') !!}
                                        </td>
                                        <td>{{ converterParaReais($d->total_devolvido) }}</td>

                                        <td> <button type="button" onclick="modalDevolucaoItens({{ $d->id }})"
                                                class="btn btn-primary"><i class="bi bi-info-circle"></i></button>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Adicione as vendas dinamicamente aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade m-3" id="nav-sangrias" role="tabpanel" aria-labelledby="nav-sangrias-tab">
                    <div class="table-responsive">
                        <table id="tabela-sangrias" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº Sangria</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sangrias as $s)
                                    <tr>
                                        <td>{{ $s->id }}</td>
                                        <td>{{ formatarData($s->data_fechamento) }}</td>
                                        <td>{{ converterParaReais($s->valor_sangria) }}</td>
                                        <td>{{ $s->descricao }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade m-3" id="nav-recebiemntos" role="tabpanel"
                    aria-labelledby="nav-recebiemntos-tab">
                    <div class="table-responsive">
                        <table id="tabela-recebimentos" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº Venda</th>
                                    <th class="text-center">Data da Venda</th>
                                    <th class="text-center">Data do Recebimento</th>
                                    <th class="text-center">Forma de Venda</th>
                                    <th class="text-center">Forma de Recebimento</th>
                                    <th class="text-center">Valor a Receber</th>
                                    <th class="text-center">Valor Recebido</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recebimentos as $key => $r)
                                    <tr>
                                        <td class="text-center">{{ $r->venda->n_venda }}</td>
                                        <td class="text-center">{{ formatarData($r->venda->data_concluida) }}</td>
                                        <td class="text-center">{{ formatarData($r->data_pagamento) }}</td>
                                        <td class="text-center">{{ $r->forma->descricao }}</td>
                                        <td class="text-center">{{ $r->especie->nome }}</td>
                                        <td class="text-center">
                                            {!! converterParaReais($r->venda_pagamento->valor) .
                                                ' ' .
                                                ($r->venda_pagamento->venda_pagamento_devolucao->sum('valor') > 0
                                                    ? '<span class="badge badge-danger">-' .
                                                        converterParaReais($r->venda_pagamento->venda_pagamento_devolucao->sum('valor')) .
                                                        '</span>'
                                                    : '') !!}
                                        </td>

                                        <td class="text-center">{{ converterParaReais($r->valor) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade m-3" id="nav-resumo-receb" role="tabpanel"
                    aria-labelledby="nav-resumo-receb-tab">
                    <div class="table-responsive">
                        <table id="tabela-totais" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Formas</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($total_por_forma_pagamento as $key => $valor)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ converterParaReais($valor) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade m-3" id="nav-resumo" role="tabpanel" aria-labelledby="nav-resumo-tab">
                    <section class="">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Quantidade de vendas:</strong>
                                <span>{{ $caixa->vendas->count() }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Quantidade de devolucões:</strong>
                                <span>{{ $caixa->devolucoes->count() }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Sangrias realizadas:</strong>
                                <span>{{ $sangrias->count() }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Total retirado em sangria:</strong>
                                <span>{{ converterParaReais($sangrias->sum('valor_sangria')) }}</span>
                            </li>
                            {{-- <li class="list-group-item">
                        <strong>Total em vendas:</strong>
                        <span>{{ converterParaReais($caixa->total_venda) }}</span>
                    </li> --}}
                            <li class="list-group-item">
                                <strong>Total devoluções:</strong>
                                <span>{{ converterParaReais($totalDevolucao) }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Valor de abertura:</strong>
                                <span>{{ converterParaReais($caixa->valor_abertura) }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Total recebido:</strong>
                                <span>{{ converterParaReais($caixa->total_recebimento) }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Total atual em dinheiro:</strong>
                                <span>{{ converterParaReais($totalDinheiroAtual) }}</span>
                            </li>

                            <li
                                class="list-group-item {{ $total_fechamento < 0 ? 'bg-danger text-white' : 'bg-primary text-white' }}">
                                <strong>Valor final:</strong>
                                <span>{{ converterParaReais($total_fechamento) }}</span>
                            </li>
                        </ul>
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-finalizar" role="tabpanel" aria-labelledby="nav-finalizar-tab">
                    <form method="POST">

                        <input type="hidden" name="caixa_id" value="{{ $caixa->caixa_id }}">

                        <div class="card-body">
                            <label for="observacaoFechametoCaixa" class="form-label">Observação:</label>
                            <textarea class="form-control" name="observacao" id="observacaoFechametoCaixa" cols="30" rows="2"></textarea>


                            <div class="mb-3">
                                <label for="senhaFecharCaixa" class="form-label">Senha:</label>
                                <input type="password" required class="form-control" name="senha"
                                    id="senhaFecharCaixa">
                            </div>

                            <!-- Checkbox de confirmação -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="confirmacaoCaixa" required
                                    style="transform: scale(1.5)";>
                                <label class="form-check-label" for="confirmacaoCaixa">
                                    Declaro, para os devidos fins, que estou ciente e de acordo com os valores
                                    registrados no caixa, assumindo plena responsabilidade pelos mesmos.
                                </label>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex mt-5">
                                <a id="botaoVoltarCaixa" href="{{ route('caixa.venda') }}"
                                    class="btn btn-outline-danger mx-1">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>

                                <button type="button" onclick="fecharCaixa()" class="btn btn-success">
                                    <i class="bi bi-floppy"></i> Fechar caixa
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     {{-- modal itens de cada venda  --}}
     <div class="modal fade" id="modal-itens-venda" tabindex="-1" role="dialog"
     aria-labelledby="modal-itens-venda-label" aria-hidden="true">
     <div class="modal-dialog modal-xl" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modal-itens-venda-label">Itens da venda <b id="id-exibicao-venda"></b>
                 </h5>
                 {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button> --}}
             </div>
             <div class="modal-body">
                 <div class="thead-light">
                     <!-- Adiciona rolagem vertical -->

                     <table id="tabela-itens-venda" style="width: 100%;" class="table table-bordered">
                         <thead>
                             <tr>
                                 <th scope="col">#</th>
                                 <th scope="col">Produto</th>
                                 <th scope="col">Quantidade</th>
                                 <th scope="col">Preço</th>
                                 <th scope="col">Total</th>
                             </tr>
                         </thead>
                         <tbody>

                         </tbody>

                     </table>
                 </div>
                 <div class="text-right m-3">
                     <button type="button" class="btn btn-secondary" onclick="fechar_modal()"
                         id="fechar-modal">Fechar</button>
                 </div>
             </div>
         </div>
     </div>
 </div>

 {{-- modal devolução itens de cada venda  --}}
 <div class="modal fade" id="modal-devolucao-itens-venda" tabindex="-1" role="dialog"
     aria-labelledby="modal-devolucao-itens-venda-label" aria-hidden="true">
     <div class="modal-dialog modal-xl" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modal-devolucao-itens-venda-label">Itens da devolução <b
                         id="id-exibicao-venda-devolucao"></b>
                 </h5>
                 {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button> --}}
             </div>
             <div class="modal-body">
                 <div class="thead-light">
                     <!-- Adiciona rolagem vertical -->

                     <table id="tabela-devolucao-itens-venda" style="width: 100%;" class="table table-bordered">
                         <thead>
                             <tr>
                                 <th scope="col">#</th>
                                 <th scope="col">Produto</th>
                                 <th scope="col">Qtd</th>
                                 <th scope="col">Qtd devolvida</th>
                                 <th scope="col">Preco</th>
                                 <th scope="col">Desconto</th>
                                 <th scope="col">Total devolvido</th>
                             </tr>
                         </thead>
                         <tbody>

                         </tbody>

                     </table>
                 </div>
                 <div class="text-right m-3">
                     <button type="button" class="btn btn-secondary" onclick="fechar_modal()"
                         id="fechar-modal">Fechar</button>
                 </div>
             </div>
         </div>
     </div>
 </div>
    <script src="{{ asset('js/WebSocket/sockjs.js') }}"></script>
    <script src="{{ asset('js/WebSocket/stomp.js') }}"></script>
    <script>
        var routeFecharCaixa = @json(route('caixa.fechar.post'));
        var routeHome = @json(route('home.index'));
        var routeItensVenda = @json(route('caixa.fechar.itens.venda.get'));
        var routeItensVendaDevolucao = @json(route('caixa.fechar.itens.venda.devolucao.get'));

        montaDatatable('tabela-vendas');
        montaDatatable('tabela-devolucao');
        montaDatatable('tabela-sangrias');
        montaDatatable('tabela-totais');
        montaDatatable('tabela-valores-devolucao');
        montaDatatable('tabela-recebimentos');

        // montaDatatable('tabela-itens-venda')

        function fechar_modal(venda_id) {

            $('#modal-itens-venda').modal('hide');
            $('#modal-devolucao-itens-venda').modal('hide');
        }

        function modalItensVenda(venda_id) {
            $('#modal-itens-venda').modal('show');
            montaDatatable('tabela-itens-venda', routeItensVenda, {
                venda_id: venda_id
            })

        }

        function modalDevolucaoItens(devolucao_id) {
            $('#modal-devolucao-itens-venda').modal('show');
            montaDatatable('tabela-devolucao-itens-venda', routeItensVendaDevolucao, {
                devolucao_id: devolucao_id
            })
        }

        function fecharCaixa() {
            event.preventDefault();

            let formData = {
                _token: $('input[name="_token"]').val(),
                caixa_id: $('input[name="caixa_id"]').val(),
                observacao: $('#observacaoFechametoCaixa').val(),
                senha: $('#senhaFecharCaixa').val()
            };

            let confirmado = $('#confirmacaoCaixa').is(':checked');
            // Fazer a requisição AJAX

            if (!formData.senha) {
                msgToastr('Informe a senha.', 'info');
                $('#senhaFecharCaixa').focus();
                return
            }

            if (!confirmado) {
                msgToastr('Confirme o termo para fechar o caixa.', 'info');
                $('#confirmacaoCaixa').focus();

                return;
            }

            $.ajax({
                url: routeFecharCaixa,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success == true) {
                        // msgToastr(response.msg, 'success');
                        localStorage.clear();
                        imprimirFechamento(response.fechamento);
                        Swal.fire({
                            title: response.msg,
                            icon: 'success', // Define o ícone de sucesso
                            confirmButtonText: 'OK', // Texto do botão
                        }).then(() => {
                            // Redireciona para outra rota
                            window.location.href = routeHome;
                        });


                    } else {
                        msgToastr(response.msg, 'error')

                    }
                },
                error: function(xhr) {
                    // location.reload();
                    msgToastr('Erro ao fechar caixa: comunique-se ao suporte.', 'error')

                }
            });
        }

        //----------------------------------- WebSocket de impressão em máquina de cupom -----------------------//
        // Define o cliente STOMP como uma variável global
        let stompClient = null;

        // Função que inicializa a conexão WebSocket com STOMP
        function iniciarConexaoWebSocket() {
            msgToastr('Conectando à impressora...', 'info');
            const endpoint = 'http://localhost:8085/impressao'; // Endpoint do WebSocket configurado no servidor

            // Usando SockJS para conectar ao WebSocket com fallback
            const socket = new SockJS(endpoint);
            stompClient = Stomp.over(socket); // Criando o cliente STOMP

            // Conectar ao WebSocket com STOMP
            stompClient.connect({}, function(frame) {
                msgToastr('Conexão estabelecida.', 'success');

                // Enviar mensagem ao servidor assim que a conexão for estabelecida
                stompClient.send("/app/impressao", {}, JSON.stringify({
                    // mensagem: "Olá, servidor!",
                    // imgBase64Cupom : imgBase64Cupom
                }));

                // Subscriba a um destino no servidor para receber mensagens
                stompClient.subscribe("/topic/impressao", function(messageOutput) {
                    console.log("Mensagem recebida do servidor: " + messageOutput.body);
                });
            }, function(error) {
                console.error("Erro na conexão WebSocket: ", error);
                msgToastr('Erro na conexão WebSocket: ' + error, 'error');
                // Tenta reconectar em caso de erro
                setTimeout(iniciarConexaoWebSocket, 5000); // Recomeça a conexão após 5 segundos
            });

            // Evento chamado quando a conexão for fechada
            socket.onclose = function(event) {
                msgToastr('Tentando nova conexão.', 'warning');
                // Tenta reconectar se a conexão for fechada inesperadamente
                // setTimeout(iniciarConexaoWebSocket, 5000); // Recomeça a conexão após 5 segundos
            };
        }

        // Função para enviar mensagens ao servidor WebSocket
        function enviaParaImpressora(mensagem) {
            if (stompClient && stompClient.connected) {

                stompClient.send("/app/impressao", {}, JSON.stringify({
                    mensagem: mensagem,
                }));
                // msgToastr('Mensagem enviada ao servidor: ' + mensagem, 'success');
            } else {
                msgToastr('WebSocket não está conectado. Tentando reconectar...', 'warning');
                iniciarConexaoWebSocket(); // Tenta reconectar antes de enviar
            }
        }

        // Inicializa a conexão WebSocket
        iniciarConexaoWebSocket();
        // Função para enviar mensagens ao servidor WebSocket
        function enviaParaImpressora(mensagem) {
            if (stompClient && stompClient.connected) {

                stompClient.send("/app/impressao", {}, JSON.stringify({
                    mensagem: mensagem,
                }));
                // msgToastr('Mensagem enviada ao servidor: ' + mensagem, 'success');
            } else {
                msgToastr('WebSocket não está conectado. Tentando reconectar...', 'warning');
                iniciarConexaoWebSocket(); // Tenta reconectar antes de enviar
            }
        }

        function imprimirFechamento(dataFechamentoCaixa) {
            enviaParaImpressora(geraCupomFechamentoCaixa(dataFechamentoCaixa));
        }


        function geraCupomFechamentoCaixa(fechamento) {
            const LARGURA_CUPOM = 44; // Largura do cupom
            const linhas = [];

            // Função para adicionar uma seção centralizada
            const adicionar_secao = (texto) => {
                linhas.push(alinhar_texto(texto, "centro"));
            };

            // Função para alinhar texto
            const alinhar_texto = (texto, alinhamento) => {
                if (alinhamento === "centro") {
                    const espacos = Math.max(0, Math.floor((LARGURA_CUPOM - texto.length) / 2));
                    return " ".repeat(espacos) + texto;
                }
                return texto; // Outros alinhamentos podem ser adicionados aqui
            };

            // Função para quebrar texto longo em múltiplas linhas
            const quebrar_texto_em_linhas = (texto, maxLength) => {
                const linhas_Quebradas = [];
                for (let i = 0; i < texto.length; i += maxLength) {
                    linhas_Quebradas.push(texto.substring(i, i + maxLength));
                }
                return linhas_Quebradas;
            };

            // Função para formatar quantidade
            const formatar_quantidade = (item) => {
                // Converte a quantidade para número
                const quantidade_Num = parseFloat(item.quantidade);

                // Verifica se a quantidade é inteira
                if (Number.isInteger(quantidade_Num)) {
                    return `${quantidade_Num}`; // Exibe sem casas decimais
                } else {
                    return quantidade_Num.toFixed(3); // Exibe com 3 casas decimais
                }
            };
            const formatar_produto = (item) => {
                const linhas_produto = [];
                const nomeProduto = `${item.nome}`;
                const linhas_nome = quebrar_texto_em_linhas(nomeProduto, 18);

                const quantidadeFormatada = formatar_quantidade(item);
                const precoUnitario = centavosParaReais(item.preco);
                const totalProduto = centavosParaReais(item.total);

                // Primeira linha do produto
                linhas_produto.push(
                    `${item.cod_aux} ${linhas_nome[0]} (${quantidadeFormatada}x${precoUnitario})`
                    .padEnd(
                        LARGURA_CUPOM - 8) + `R$${totalProduto}`
                );

                // Demais linhas do nome do produto
                for (let i = 1; i < linhas_nome.length; i++) {
                    linhas_produto.push(`    ${linhas_nome[i]}`);
                }

                return linhas_produto;
            };

            // Cabeçalho
            adicionar_secao("==============================================");
            adicionar_secao("Loja 1 - (22) 999824464");
            adicionar_secao("================================================");
            console.log(fechamento);

            linhas.push("");
            // Informações da venda
            adicionar_secao("--------------FECHAMENTO DE CAIXA---------------");

            linhas.push("Operador: " + @json(auth()->user()->name));
            const now = new Date();
            const formattedDate = now.toLocaleDateString('pt-BR'); // Formata a data no padrão brasileiro
            const formattedTime = now.toLocaleTimeString('pt-BR'); // Formata a hora no padrão brasileiro
            linhas.push(`Data de abertura: ` + aplicarMascaraDataHora(fechamento.caixa.data_abertura));
            linhas.push(`Data de fechamento: ` + aplicarMascaraDataHora(fechamento.caixa.data_fechamento));
            linhas.push(`Observação: ` + fechamento.caixa.descricao);
            linhas.push(``);

            adicionar_secao("-----------FORMAS DE RECEBIMENTO----------------");
            Object.keys(fechamento.total_por_forma_pagamento).forEach((chave) => {
                if (fechamento.total_por_forma_pagamento[chave] != 0) {
                    linhas.push(`${chave} : R$ ${centavosParaReais(fechamento.total_por_forma_pagamento[chave])}`);
                }
            });
            
            linhas.push(``);

            adicionar_secao("-------------------TOTAIS--------------------");
            const totalSangrias = fechamento.sangrias.reduce((total, item) => {
                return total + (item.valor_sangria || 0); // Soma apenas se 'valor_sangria' existir
            }, 0);
            linhas.push('Quantidade de vendas: ' + fechamento.caixa.vendas.length);
            linhas.push('Quantidade de devolucões: ' + fechamento.caixa.devolucoes.length);
            linhas.push('Valor de abertura: R$' + centavosParaReais(fechamento.caixa.valor_abertura));
            linhas.push('Sangrias realizadas: ' + fechamento.sangrias.length);
            linhas.push('Total retirado em sangria: ' + centavosParaReais(totalSangrias));
            linhas.push('Total em vendas: R$' + centavosParaReais(fechamento.caixa.total_venda));
            linhas.push('Total devoluções: R$' + centavosParaReais(fechamento.totalDevolucao));
            linhas.push('Total recebido: R$' + centavosParaReais(fechamento.caixa.total_recebimento));
            linhas.push('Total atual em dinheiro: R$' +
                centavosParaReais(fechamento.totalDinheiroAtual));
            linhas.push('Valor final: R$' + centavosParaReais(fechamento.total_fechamento));

            linhas.push(``);
            linhas.push(``);

            adicionar_secao('Assinatura:____________________________________');

            linhas.push("");
            linhas.push('');
            adicionar_secao("================================================");
            adicionar_secao("GECON LTDA");
            adicionar_secao("Telefone: (xx) xxxx-xxxx");
            adicionar_secao("www.gecon.com.br");
            adicionar_secao("================================================");

            for (let i = 0; i < 10; i++) {
                linhas.push("");
            }

            return JSON.stringify(linhas.join("#NEWLINE#"));
        }
    </script>
@endsection
