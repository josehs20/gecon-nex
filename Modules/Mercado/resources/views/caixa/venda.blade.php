@extends('mercado::layouts.app')

@section('content')
    <style>
        /* Estilo para a seleção de linhas na tabela */
        #produtos-busca-tabela tr.selected,
        #tabela-produtos-selecionados tr.selected {
            background-color: #dfe6e9;
            /* Cinza claro suave, para destaque sem ser agressivo */
        }

        /* Estilo para o container de cartões */
        .cardsCaixa {
            background-color: #ffffff;
            /* Fundo Branco */
            border: 1px solid #e0e0e0;
            /* Borda suave cinza claro */
            border-radius: 8px;
            /* Bordas arredondadas */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            /* Sombra suave */
        }

        /* Estilo para containers select2 */
        .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            width: 100% !important;
        }

        /* Indicador de código de barras */
        #barcode-indicator {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: transparent;
            border-bottom: 2px solid #e74c3c;
            /* Vermelho claro para destacar */
            pointer-events: none;
        }

        /* Cabeçalho de cartões */
        .header-card {
            padding: 5px 15px;
            text-align: center;
            background-color: #404040;
            /* Azul Claro */
            font-weight: bold;
            color: #ffffff;
        }

        .list-group-item.active {
            background-color: #404040 !important;
            /* Define a cor de fundo */
        }

        .header-card h3 {
            color: #ffffff;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Estilo para cabeçalhos de tabela */
        thead th {
            background-color: #f8f9fa;
            /* Cinza claro */
            text-align: left;
            padding: 10px;
            font-weight: bold;
        }

        /* Estilo para o corpo da tabela */
        #produtos-busca-tabela tbody td,
        #tabela-produtos-selecionados tbody td {
            text-align: left;
            padding: 10px;
        }

        /* Estilo para o relógio */
        .clock-container {
            background-color: #404040;
            /* Cinza escuro */
            border-radius: 20%;
            padding: 20px;
            max-width: 100%;
            margin: auto;
            margin-top: 45%;
        }

        .clock {
            font-size: 1.5rem;
            font-family: 'Arial', sans-serif;
            color: #ffffff;
            letter-spacing: 2px;
        }

        .date {
            color: #aaa;
            font-size: 1.0rem;
            margin-top: 20px;
        }

        /* Estilo para previsão do tempo */
        #weather h3,
        #weather p {
            color: #ffffff;
        }

        /* Estilo para texto sublinhado */
        u {
            font-size: 1.5rem;
        }

        /* Botões com cores específicas */
        .btn-success {
            background-color: #00b87c;
            /* Verde Claro */
            border: none;
            color: #ffffff;
        }

        .btn-secondary {
            background-color: #f39c12;
            /* Laranja Claro */
            border: none;
            color: #ffffff;
        }

        .btn-danger {
            background-color: #e74c3c;
            /* Vermelho Claro */
            border: none;
            color: #ffffff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Conteúdo com fundo suave */
        .content {
            background-color: #f1f1f1;
            /* Cinza Claro */
            padding: 20px;
        }

        /* Estilo para campos de entrada */
        .input-field {
            background-color: #f9f9f9;
            /* Leve cinza para campos de entrada */
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 10px;
            width: 100%;
        }

        /* Cores de Destaque */
        .highlight {
            background-color: #dfe6e9;
            /* Cinza Claro Suave */
        }
    </style>

    <div style="margin-top: -1%;" class="d-flex justify-content-between">
        <h4>OPERADOR: {{ strtoupper(auth()->user()->name) }}</h4>
        <h4 id="statusCaixaVenda" style="margin-left: -11%;">{{ $caixa->getStatus() }}</h4>
        <h4> <i id="fullscreen-btn" class="bi bi-arrows-fullscreen" title="Tela Cheia"></i> {{ $caixa->nome }} </h4>
    </div>

    <div class="row g-0">
        <div class="col-md-6">
            {{-- TABELA BUSCAR PRODUTOS --}}
            {{-- <button onclick="teste()">teste</button> --}}
            @include('mercado::caixa.inc.tabela_buscar_produtos')

            {{-- BUSCA PRODUTOS --}}
            @include('mercado::caixa.inc.busca_produtos')


            {{-- MENU --}}
            @if (!$mobile)
                @include('mercado::caixa.inc.menu_botoes')
            @endif

        </div>

        <div class="col-md-6">
            {{-- TABELA ONDE MOSTRA PRODUTOS SELECIONADOS --}}
            @include('mercado::caixa.inc.tabela_produtos_selecionados')

            {{-- INPUTS DOS VALORES SOBRE A VENDA --}}
            @include('mercado::caixa.inc.inputs_valores')


            @if ($mobile)
                @include('mercado::caixa.inc.menu_botoes')
            @endif
        </div>
    </div>

    {{-- AUDIO --}}
    <audio id="beepSound" src="{{ asset('audio/bip.mp3') }}" preload="auto"></audio>

    {{-- Modais --}}
    {{-- Modal cadastro de cliente caixa --}}
    @include('mercado::caixa.inc.modal_cadastro_cliente')
    @include('mercado::caixa.inc.modal_salvar_venda')
    @include('mercado::caixa.inc.modal_voltar_venda')
    @include('mercado::caixa.inc.modal_sangria')
    <script src="{{ asset('js/scaners/zxing.js') }}"></script> <!-- CDN atualizado -->
    <script src="{{ asset('js/WebSocket/sockjs.js') }}"></script>
    <script src="{{ asset('js/WebSocket/stomp.js') }}"></script>



    <script>
        var routeHome = @json(route('home.index'));
        var routeValidate = @json(route('caixa.abrir'));
        var routeBuscaProdutos = @json(route('caixa.produto.get'));
        var routeClientesGet = @json(route('caixa.clientes.get'));
        var routeUpdateStatusCaixa = @json(route('caixa.status.update'));
        var routeFinalizarVenda = @json(route('caixa.finalizar.venda'));
        var routeSalvarVenda = @json(route('caixa.salvar.venda'));
        var routeSelectVendaVoltar = @json(route('caixa.get.vendas'));
        var routeVoltarVenda = @json(route('caixa.voltar.venda'));
        var routeCancelarVendaSalva = @json(route('caixa.cancelar.venda'));
        var routeVendaDevolucao = @json(route('caixa.devolucao.venda.get'));
        var routeGetClienteRecebimento = @json(route('caixa.recebimento.venda.get'));
        var routeGetPagamentoCliente = @json(route('caixa.recebimento.cliente.venda.get'));
        var routePostRecebimento = @json(route('caixa.recebimento.cliente.venda.post'));
        var routeDevolucao = @json(route('caixa.devolucao.venda'));
        var routeGetSangria = @json(route('caixa.sangria.get'));
        var routeSegundaViaSangria = @json(route('caixa.sangria.segunda_via'));
        var routePostSangria = @json(route('caixa.sangria.post'));
        var routeInicio = @json(route('home.index'));

        //nome storages
        var itensVendaAtual = 'estoques';
        var nameOperador = @json(auth()->user()->name);
        var itensVisualizarVoltarVenda = 'visualizarVoltarVenda';
        var obj_venda = 'obj_venda';
        var itensDevolucao = 'itensDevolucao';
        var obj_venda_devolucao = 'obj_venda_devolucao';
        var currentIndex = 0;
        var tokenGia = `guia_unica_caixa_`;
        var mobile = @json($mobile);
        var leitorBr = false;
        var aviso = true;
        var vendaVoltarSelecionada;
        var dataSangria;
        var statuAtualCaixa = @json($caixa->status_id);
        // Função para atualizar o relógio
        manipulaCardPrincipal('relogio')

        function relogio() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const dateOptions = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('clock').textContent = now.toLocaleTimeString('pt-BR', options);
            document.getElementById('date').textContent = now.toLocaleDateString('pt-BR', dateOptions);
        }

        // Atualiza o relógio a cada segundo
        setInterval(relogio, 1000);
        // Atualiza o relógio imediatamente ao carregar a página
        relogio();

        function valida_response(response) {
            if (typeof response === 'string' && response.startsWith('<!doctype html>')) {
                // Atualiza a página atual
                location.reload();
            }

        }

        function n_venda_exibir() {
            let venda = getStorage(obj_venda);
            if (venda) {
                $('#nVenda').removeClass('d-none');
                $('#n_venda_exibir').text(venda.n_venda)
                $('#cliente_nome_card').text(venda.cliente.nome)
            } else {
                let selecionado = $('#buscarClienteFinalizarVenda option:selected');
                if (selecionado.val()) {
                    let somenteNome = selecionado.text().split('-');
                    $('#cliente_nome_card').text(somenteNome[0].trim());
                } else {
                    $('#nVenda').addClass('d-none');
                    $('#n_venda_exibir').text('Não informado');
                    $('#cliente_nome_card').text('Não informado');
                }


            }
        }

        //funcao finalizar venda
        function finalizarVenda() {
            let itens = getStorage(itensVendaAtual);
            if (itens.length <= 0) {
                msgToastr('Adicione ao menos um item na venda.', 'info')
                return;
            }

            manipulaCardPrincipal('div-finalizar-venda');
            let venda = getStorage(obj_venda) ?? false;

            if (venda) {

                // Adiciona a nova opção diretamente em HTML
                $('#buscarClienteFinalizarVenda').empty().append(
                    `<option value="${venda.cliente_id}" selected disabled>${venda.cliente.nome}</option>`
                ).attr('disabled', true);

            } else {
                // Habilita o select caso não haja venda salva ou retornada
                $('#buscarClienteFinalizarVenda').empty().attr('disabled', false);
            }
            $('#formaPagamento').val('').change().focus();
            $('#valor-recebido').val('');
            $('#valor-recebido').attr('readonly', true);

            n_venda_exibir();
            montaTabelaVenda(itensVendaAtual);
        }

        function resetaCarBuscaProduto() {
            select2('buscarClienteFinalizarVenda', routeClientesGet);
            $('#buscarClienteFinalizarVenda').attr('disabled', false);
            $('#desconto').val('');
            $('#desconto-digitado').val('');
            $('#valor-recebido').val('');
            $('#valor-recebido').val('');
            $('#input-troco').val("").trigger('change');
            manipulaCardPrincipal('relogio');
            $('#formaPagamento').find('option').prop('selected', false);
            let container = $('#divValorPagoDinamico').empty();
            // montaTabelaVenda()
        }

        function darDesconto() {
            let itens = getStorage(itensVendaAtual);
            if (itens.length) {
                manipulaCardPrincipal('div-finalizar-venda');
                $('#desconto-digitado').focus();
                $('#desconto').val('');
                $('#desconto-digitado').val('');
            } else {
                msgToastr('Nenhuma item adicionado a venda para realizar o desconto.', 'info');
            }
        }

        function montaRequestFinalizarVenda(params) {
            let selectedOptionsPagamento = $('#formaPagamento').find(':selected');
            let buscarCliente = $('#buscarClienteFinalizarVenda').val();
            let desconto = $('#desconto-digitado').val();
            let valorRecebido = $('#valor-recebido').val();
            let valorTotalComDesconto = $('#total').val();
            let subTotal = $('#subtotal').val();
            let erros = [];
            let pagamentos = [];
            /**
             * validacoes
             */
            valorTotalComDesconto = converteParaFloat(valorTotalComDesconto);
            if (selectedOptionsPagamento.length == 0) {
                msgToastr('Selecione a forma de pagamento.', 'warning');
                return false;
            }
            let afetaTroco = false;
            let contem_parcela = false;
            let parcelas = 0;
            let especie_pagamento_com_parcela_id = @json(config('especie_pagamento.cartao_credito.id'));
            let objPagamento = {};
            let optionAfetaTroco;
            // Verifica se alguma das opções selecionadas afeta o troco
            selectedOptionsPagamento.each(function() {
                if ($(this).data('afeta-troco')) {
                    afetaTroco = true;
                    optionAfetaTroco = $(this);
                }
                if ($(this).data('contem-parcela')) {
                    contem_parcela = true;
                    parcelas = $('#numero-parcelas').val() == '' ? 0 : parseInt($('#numero-parcelas').val());
                }
            });

            if (contem_parcela == true && parcelas == 0) {
                erros.push({
                    msg: 'Adicione a quantidade de parcelas.'
                });
            }
            //caso aja mais de uma forma de pagamento
            // if (selectedOptionsPagamento.length > 1) {

            let totalValorRecebido = 0;
            $('.valor-recebido').each(function() {
                let valorRecebidoEmCadaInput = converteParaFloat($(this).val());
                let nome = $(this).data('pagamento-nome');
                let pagamentoId = $(this).data('pagamento-id');

                if (!isNanOrEmpty(valorRecebidoEmCadaInput)) {
                    totalValorRecebido += valorRecebidoEmCadaInput;
                    objPagamento = {
                        pagamentoId: pagamentoId,
                        valor: valorRecebidoEmCadaInput
                    }
                    if ($(this).data('contem-parcela')) {
                        objPagamento.parcelas = parcelas;
                    }
                    pagamentos.push(objPagamento);
                } else {
                    erros.push({
                        msg: 'Adicione o valor para ' + nome
                    });
                }
            });
            //caso uma forma de pagamento só e for dinheiiro
            if (selectedOptionsPagamento.length == 1 && afetaTroco) {
                pagamentos.push({
                    pagamentoId: optionAfetaTroco.val(),
                    valor: valorTotalComDesconto
                });

                totalValorRecebido = valorTotalComDesconto;
                if (valorRecebido < totalValorRecebido) {
                    erros.push({
                        msg: 'Valor recebido menor que valor da venda.'
                    });
                }
            } else if (selectedOptionsPagamento.length == 1) {
                //caso outra forma de pagamento
                objPagamento = {
                    pagamentoId: selectedOptionsPagamento.val(),
                    valor: valorTotalComDesconto
                }

                if (selectedOptionsPagamento.data('contem-parcela')) {
                    objPagamento.parcelas = parcelas;
                }

                pagamentos.push(objPagamento);

                totalValorRecebido = valorTotalComDesconto;
            }

            // Calcular a diferença entre o valor recebido e o total com desconto

            let diferenca = totalValorRecebido - valorTotalComDesconto;
            diferenca = Math.round(diferenca * 100) / 100;

            if (totalValorRecebido != valorTotalComDesconto) {
                // Exibir mensagem de valor faltante ou excedido

                if (diferenca < 0) {
                    let valorFaltante = maskDinehiroReturnVal(Math.abs(diferenca));
                    erros.push({
                        msg: 'Faltam R$ ' + valorFaltante + ' para atingir o valor total.'
                    });
                } else if (diferenca > 0) {
                    let valorExcedido = maskDinehiroReturnVal(diferenca);
                    erros.push({
                        msg: 'Você excedeu o valor em R$ ' + valorExcedido + '.'
                    });
                }
            }
            let existeCliente = getStorage(obj_venda);
            buscarCliente = existeCliente && existeCliente.cliente_id ? existeCliente.cliente_id : buscarCliente;
            if (!buscarCliente) {
                erros.push({
                    msg: 'Selecione o cliente'
                });
            }

            if (erros.length > 0) {
                erros.forEach(element => {
                    msgToastr(element.msg, 'info');
                });
                //retorna falso caso tenha algum erro
                return false;
            }

            if (desconto && desconto != '' && desconto != 0) {
                desconto = converteParaFloat(desconto);
            } else {
                desconto = null;
            }

            return {
                formaPagamento: pagamentos,
                cliente: buscarCliente,
                desconto: desconto,
                valorRecebido: valorRecebido,
                subTotal: converteParaFloat(subTotal),
                total: valorTotalComDesconto,
                itens: JSON.stringify(getStorage(itensVendaAtual)),
                venda_id: getStorage(obj_venda) ? getStorage(obj_venda).id : null,
                _token: '{{ csrf_token() }}' // Inclua o token CSRF se necessário
            };

        }

        function finalizarVendaPost() {
            let request = montaRequestFinalizarVenda();

            if (!request) {
                return false;
            }

            // Envia os valores para a rota usando AJAX
            $.ajax({
                url: routeFinalizarVenda, // Substitua pela rota correta
                type: 'POST',
                data: request,
                success: function(response) {
                    // Faça algo com a resposta se necessário
                    valida_response(response);
                    if (response.success == true) {
                        imprimirVenda(response);
                        rezetaStorage();
                        resetaCarBuscaProduto();
                        montaTabelaVenda(itensVendaAtual);
                        msgToastr(response.msg, 'success')
                        $('#statusCaixaVenda').text(response.status);
                        $('#div-tabela-produtos-selecionados').addClass('d-none');
                        $('#imagemPadraoCaixa').removeClass('d-none');
                        statuAtualCaixa = response.caixa.status_id;
                        $('#buscaProdutos').focus();
                        aplicarAutocompleteOff();

                    } else {
                        msgToastr(response.msg, 'error')

                    }
                    // Exemplo: Redirecionar para outra página ou mostrar uma mensagem de sucesso
                },
                error: function(xhr, status, error) {
                    // Lidar com o erro, se necessário
                    msgToastr('Erro ao finalizar a venda:' + error, 'error');
                }
            });
        }

        function imprimirVenda(dataVeda) {
            enviaParaImpressora(gerarComprovanteVenda(dataVeda.venda));
        }

        function imprimirSangria(dataSangria) {
            enviaParaImpressora(gerarCupomSangria(dataSangria));
        }

        function imprimirRecebimento(dataRecebimento) {
            enviaParaImpressora(gerarCupomRecebimento(dataRecebimento));
        }

        function imprimirDevolucao(dataDevolucao) {
            enviaParaImpressora(gerarCupomDevolucao(dataDevolucao));
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

        //-----------------------Montagem layout cupom-------------------------------//
        function gerarCupomDevolucao(devolucao) {
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

            linhas.push("");
            // Informações da venda
            adicionar_secao("----------------DEVOLUÇÃO-----------------");

            linhas.push("Operador: " + nameOperador);
            linhas.push('Nª Devolução: ' + devolucao.id);
            linhas.push(`Cliente: ${devolucao.cliente}`);
            linhas.push('Nª Venda: ' + devolucao.n_venda);
            const now = new Date();
            const formattedDate = now.toLocaleDateString('pt-BR'); // Formata a data no padrão brasileiro
            const formattedTime = now.toLocaleTimeString('pt-BR'); // Formata a hora no padrão brasileiro
            linhas.push(`Data: ${formattedDate} Hora: ${formattedTime}`);
            linhas.push(`Total: R$${centavosParaReais(devolucao.total_devolvido)}`);
            linhas.push('');

            // Produtos
            adicionar_secao("-------------PRODUTOS DEVOLVIDOS----------------");
            linhas.push(`#   Nome               (Qtd x Preço)       Total`);
            linhas.push("------------------------------------------------");

            devolucao.itens.forEach((item) => {
                const produtoFormatado = formatar_produto(item);
                linhas.push(...produtoFormatado);
            });
            linhas.push('');
            adicionar_secao("------------------MOTIVO------------------");
            linhas.push(devolucao.motivo);
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

        function gerarCupomRecebimento(recebimentos) {
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

            // Cabeçalho
            adicionar_secao("==============================================");
            adicionar_secao("Loja 1 - (22) 999824464");
            adicionar_secao("================================================");

            // Informações da venda
            adicionar_secao("-------------PAGAMENTO DE CONTA-----------------");
            linhas.push("");
            linhas.push("Operador: " + nameOperador);
            linhas.push(`Cliente: ${recebimentos[0].cliente}`);
            const now = new Date();
            const formattedDate = now.toLocaleDateString('pt-BR'); // Formata a data no padrão brasileiro
            const formattedTime = now.toLocaleTimeString('pt-BR'); // Formata a hora no padrão brasileiro
            linhas.push(`Data: ${formattedDate} Hora: ${formattedTime}`);

            recebimentos.forEach(element => {
                adicionar_secao("-------------------PAGAMENTO------------------");
                linhas.push("Conta: " + element.n_conta)
                linhas.push("Valor da Conta: " + element.v_conta)
                linhas.push("Já Pago: " + element.v_ja_pago)
                linhas.push("Valor Pago: " + element.v_pago)
                linhas.push("Valor Faltante: " + element.v_restante)
                linhas.push("Espécie: " + element.especie);
                linhas.push("");

            });

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

        function gerarCupomSangria(sangria) {
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
            // Inicialização de totalDinheiro antes de usar
            let totalDinheiro = 0;
            let totalDevolucao = 0;
            let devolucaoDinheiro = sangria?.total_por_forma_devolucao?.Dinheiro ?? 0
            if (sangria.total_por_forma_devolucao && typeof sangria
                .total_por_forma_devolucao === 'object') {
                for (let chave in sangria.total_por_forma_devolucao) {
                    if (sangria.total_por_forma_devolucao.hasOwnProperty(chave)) {
                        totalDevolucao += sangria.total_por_forma_devolucao[
                            chave]; // Soma o valor de cada chave
                    }
                }
            }
            // Cabeçalho
            adicionar_secao("==============================================");
            adicionar_secao("Loja 1 - (22) 999824464");
            adicionar_secao("================================================");
            // Informações da venda
            adicionar_secao("--------------------SANGRIA---------------------");
            // Construção de informações totais
            if (sangria.total_por_forma_pagamento && typeof sangria
                .total_por_forma_pagamento === 'object') {
                for (let [forma, total] of Object.entries(sangria.total_por_forma_pagamento)) {
                    if (forma === 'Dinheiro') {
                        totalDinheiro = total; // Atualizando o totalDinheiro
                    } else {
                        linhas.push(
                            `${forma}: R$ ${centavosParaReais(total)}`);

                    }
                }
                totalDinheiro -= devolucaoDinheiro;

                linhas.push(
                    `Total: R$ ${centavosParaReais(sangria.total_recebimento + sangria.ultimo_registro.valor_abertura - totalDevolucao)}`
                );

                if (devolucaoDinheiro > 0) {
                    linhas.push(
                        `Devoluções: R$ ${centavosParaReais(totalDevolucao)}`);
                    linhas.push(
                        `Devoluções: R$ ${centavosParaReais(devolucaoDinheiro)}`);
                }
                linhas.push(
                    `Dinheiro: R$ ${centavosParaReais(totalDinheiro + sangria.ultimo_registro.valor_abertura)}`);
                // Atualiza o valor de dinheiro disponível após as devoluções
            }
            linhas.push(
                `Operador: ${nameOperador}`);

            const now = new Date();
            const formattedDate = now.toLocaleDateString('pt-BR'); // Formata a data no padrão brasileiro
            const formattedTime = now.toLocaleTimeString('pt-BR'); // Formata a hora no padrão brasileiro
            linhas.push(`Data: ${formattedDate} Hora: ${formattedTime}`);
            linhas.push("");
            adicionar_secao('Assinatura:____________________________________');
            // Finalização
            linhas.push('');

            adicionar_secao("================================================");
            adicionar_secao("GECON LTDA");
            adicionar_secao("Telefone: (xx) xxxx-xxxx");
            adicionar_secao("www.gecon.com.br");
            adicionar_secao("================================================");

            // Adicionar linhas em branco para corte térmico
            for (let i = 0; i < 10; i++) {
                linhas.push("");
            }

            return JSON.stringify(linhas.join("#NEWLINE#"));

        }

        function gerarComprovanteVenda(venda) {
            const LARGURA_CUPOM = 44; // Largura do cupom
            const linhas = [];
            let pedirAssinatura = false;
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
                return texto;
            };

            // Função para quebrar texto longo em múltiplas linhas
            const quebrar_texto_em_linhas = (texto, maxLength) => {
                const linhasQuebradas = [];
                for (let i = 0; i < texto.length; i += maxLength) {
                    linhasQuebradas.push(texto.substring(i, i + maxLength));
                }
                return linhasQuebradas;
            };

            // Função para formatar quantidade
            const formatar_quantidade = (item) => {
                const quantidadeNum = parseFloat(item.quantidade);
                return Number.isInteger(quantidadeNum) ?
                    `${quantidadeNum}` :
                    quantidadeNum.toFixed(3);
            };

            // Formatar produto
            const formatar_produto = (estoque, item) => {
                const linhas_produto = [];
                const nomeProduto = `${estoque.produto.nome} ${estoque.produto.unidade_medida.sigla}`;
                const linhas_nome = quebrar_texto_em_linhas(nomeProduto, 18);

                const quantidadeFormatada = formatar_quantidade(item);
                const precoUnitario = centavosParaReais(item.preco);
                const totalProduto = centavosParaReais(item.total);

                // Primeira linha do produto
                linhas_produto.push(
                    `${estoque.produto.cod_aux} ${linhas_nome[0]}     (${quantidadeFormatada}x${precoUnitario})`
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
            adicionar_secao("Loja 1 - (22) 99982-4464");
            adicionar_secao("==============================================");

            // Informações da venda
            adicionar_secao("--------------Informações da venda--------------");
            linhas.push(`Cliente: ${venda.cliente_id} - ${venda.cliente.nome}`);
            linhas.push(`Emissão: ${new Date().toISOString().slice(0, 19).replace("T", " ")}`);
            linhas.push(`Nº_venda: ${venda.n_venda}`);
            const now = new Date();
            const formattedDate = now.toLocaleDateString('pt-BR'); // Formata a data no padrão brasileiro
            const formattedTime = now.toLocaleTimeString('pt-BR'); // Formata a hora no padrão brasileiro
            linhas.push(`Data: ${formattedDate} Hora: ${formattedTime}`);
            linhas.push("");

            // Produtos
            adicionar_secao("-------------------Produtos---------------------");
            linhas.push(`#   Nome               (Qtd x Preço)       Total`);
            linhas.push("------------------------------------------------");

            venda.venda_itens.forEach((item) => {
                const produtoFormatado = formatar_produto(item.estoque, item);
                linhas.push(...produtoFormatado);
            });

            // Totais
            adicionar_secao("-------------------Totais-----------------------");
            linhas.push(`Subtotal:                              R$${centavosParaReais(venda.sub_total)}`);
            linhas.push(
                `Desconto:                      R$${centavosParaReais(venda.desconto_dinheiro)} (${venda.desconto_porcentagem.toFixed(2)}%)`
            );
            linhas.push(`Total:                                 R$${centavosParaReais(venda.total)}`);
            linhas.push("");

            // Pagamentos
            adicionar_secao("-------------------Pagamentos-------------------");
            let trocoTotal = 0.0;
            let recebido = 0.0;

            venda.venda_pagamentos.forEach((pagamento) => {
                if (pagamento.especie.credito_loja) {
                    pedirAssinatura = true;
                    linhas.push(
                        `${pagamento.especie.nome.padEnd(20)}                  R$${centavosParaReais(pagamento.valor)}`
                    );
                } else {
                    linhas.push(
                        `${pagamento.especie.nome.padEnd(20)}                  R$${centavosParaReais(pagamento.valor_pago)}`
                    );
                }

                if (pagamento.especie.afeta_troco) {
                    trocoTotal += pagamento.troco;
                    recebido += pagamento.valor_pago + pagamento.troco;
                }
            });

            if (trocoTotal > 0) {
                linhas.push(`Troco:                                 R$${centavosParaReais(trocoTotal)}`);
                linhas.push(`Valor Recebido:                        R$${centavosParaReais(recebido)}`);
            }
            linhas.push("");
            if (pedirAssinatura) {
                linhas.push(`Assinatura:_____________________________________ `);
            }

            // Rodapé
            linhas.push('');

            adicionar_secao("-------------------Regulamento------------------");
            adicionar_secao("Não aceitamos devolução após 10 dias da venda");
            adicionar_secao(`Operador: ${venda.usuario.master.name}`);
            adicionar_secao("------------------------------------------------");
            adicionar_secao("Válido como comprovante de venda");
            adicionar_secao("*********** Obrigado, volte sempre *************");

            // Finalização
            linhas.push('');

            adicionar_secao("================================================");
            adicionar_secao("GECON LTDA");
            adicionar_secao("Telefone: (xx) xxxx-xxxx");
            adicionar_secao("www.gecon.com.br");
            adicionar_secao("================================================");

            // Adicionar linhas em branco para corte térmico
            for (let i = 0; i < 10; i++) {
                linhas.push("");
            }

            return JSON.stringify(linhas.join("#NEWLINE#"));
        }



        //---------------------------------updates status caixa ----------------------------------------------//

        function updateStatusCaixa(status_id) {
            if (status_id == @json(config('config.status.livre')) && statuAtualCaixa == @json(config('config.status.livre'))) {
                msgToastr('Caixa livre', 'info');
                $('#statusCaixaVenda').text('LIVRE');
                return;
            }
            $.ajax({
                url: routeUpdateStatusCaixa, // Substitua com a URL da sua rota
                type: 'POST', // Método POST
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Inclua o token CSRF se estiver usando Laravel
                },
                data: {
                    status_id: status_id
                },
                success: function(response) {
                    valida_response(response);

                    // Manipule a resposta bem-sucedida aqui
                    if (response.success == true) {
                        $('#statusCaixaVenda').text(response.status);
                        msgToastr(response.msg, 'success');
                        statuAtualCaixa = response.caixa.status_id

                    } else {
                        msgToastr(response.msg, 'error')

                    }
                },
                error: function(xhr) {
                    // Manipule erros aqui
                    msgToastr('Erro ao atualizar o status do caixa:' + xhr.responseText, 'error');
                }
            });
        }

        //---------------------------------interacao com menus -------------------------------------//
        function manipulaCardPrincipal(divAparecer) {
            // Oculta todos os elementos possíveis
            $('#produtos-busca-tabela-nao-encontrato').addClass('d-none');
            $('#div-produtos-busca-tabela').addClass('d-none');
            $('#carregando').addClass('d-none');
            $('#div-finalizar-venda').addClass('d-none');
            $('#div-finalizar-venda').addClass('d-none');
            $('#relogio').addClass('d-none');
            $('#divDevolucao').addClass('d-none');
            $('#divRecebimento').addClass('d-none');
            $('#formaPagamento').val('').change();
            $('#divValorPagoDinamico').empty();


            if (divAparecer) {
                // Exibe o elemento necessário
                $('#' + divAparecer).removeClass('d-none');
            } else {
                $('#relogio').removeClass('d-none');

            }
        }

        function manipulaCardOperador(divAparecer) {
            $('#imagemPadraoCaixa').addClass('d-none');
            $('#div-tabela-produtos-selecionados').addClass('d-none');
            $('#div-tabela-recebimento').addClass('d-none');
            $('#' + divAparecer).removeClass('d-none');
            // n_venda_exibir();

        }

        //-----------------------------------storage--------------------------------------------------------------//
        function getStorage(nomeCookieGet) {
            // Obtenha o valor do cookie
            var storageValue = localStorage.getItem(nomeCookieGet);
            let retornoVazio;

            if (nomeCookieGet == 'obj_venda') {
                //pois a venda é um objeto e os ites são um array de objetos
                retornoVazio = false;
            } else {
                retornoVazio = [];
            }
            // Verifique se o valor do cookie não é undefined e se pode ser analisado como JSON
            if (storageValue) {
                try {
                    // Tente analisar o valor do cookie como JSON
                    return JSON.parse(storageValue);
                } catch (e) {
                    msgToastr("Erro ao analisar o JSON do cookie: " + nomeCookieGet, 'error');
                    // Retorne um array vazio se a análise falhar
                    return retornoVazio;
                }
            }

            // Retorne um array vazio se o cookie não estiver definido
            return retornoVazio;
        }

        function setStorage(key, value) {
            localStorage.setItem(key, value);
        }

        function removeStorage(key) {
            localStorage.removeItem(key);
        }

        function adicionarItem(row) {
            // Buscar o cookie existente
            let qtd = $('#quantidadeItem').val();
            if (qtd == '' || qtd == null || qtd == 0) {
                $('#quantidadeItem').focus();
                msgToastr('Adicione a quantidade!', 'info'); //passar para mensage na tela
                return
            }

            let itensVenda = getStorage(itensVendaAtual);

            // Atualiza o status do caixa se necessário
            if (itensVenda.length == 0) {
                updateStatusCaixa(@json(config('config.status.ocupado')));
            }
            // Obter dados dos atributos data- da linha da tabela
            let cod_aux = $(row).data('cod-aux');
            let nome = $(row).data('nome');
            let preco = $(row).data('preco');
            let total = 0;
            let estoqueId = $(row).data('estoque-id');

            // Converte quantidade para número
            qtd = parseFloat(converteParaFloat(qtd));

            // Verifica se o item já existe no array
            let itemIndex = itensVenda.findIndex(item => item.estoqueId === estoqueId);

            if (itemIndex !== -1) {
                // Atualiza a quantidade e o total do item existente
                let itemExiste = itensVenda[itemIndex];

                // Atualiza a quantidade
                itemExiste.qtd += qtd; // Soma diretamente
                itemExiste.qtd = parseFloat(itemExiste.qtd.toFixed(3)); // Limita a 3 casas decimais

                // Atualiza o total
                itemExiste.total = itemExiste.preco * itemExiste.qtd
            } else {
                // Cria um novo objeto para o item
                total = parseFloat((preco * qtd).toFixed(2)); // Calcula total e limita a 2 casas decimais

                let novoObjeto = {
                    estoqueId: estoqueId,
                    qtd: parseFloat(qtd.toFixed(3)), // Limita a 3 casas decimais
                    cod_aux: cod_aux,
                    nome: nome,
                    preco: preco,
                    total: total,
                };

                // Adiciona o novo objeto ao final do array
                itensVenda.push(novoObjeto);
            }

            // Salvar o array atualizado de volta no cookie
            setStorage(itensVendaAtual, JSON.stringify(itensVenda));
            $('#quantidadeItem').val(1);
            $('#buscaProdutos').focus();
            $('#buscaProdutos').val('');
            // manipulaCardPrincipal('imagemPadraoCaixa')
            montaTabelaVenda(itensVendaAtual)
        }
        // Função para remover um item do cookie
        function removeItem(estoqueIdParaRemover) {
            // Recupera o cookie e faz o parse
            let itens = getStorage(itensVendaAtual);

            // Filtra a lista para remover o item com o estoqueId especificado
            let itensAtualizados = itens.filter(item => item.estoqueId !== estoqueIdParaRemover);
            setStorage(itensVendaAtual, JSON.stringify(itensAtualizados));
            if (itensAtualizados.length == 0) {
                updateStatusCaixa(@json(config('config.status.livre')));
            }

        }
        //--------tabela de itens selecionados ----------------//
        function montaTabelaVenda(nomeCookieItens, podeExcluir = true, type) {
            let itens = getStorage(nomeCookieItens)

            let tabelaBody = $('#tabela-produtos-selecionados tbody');
            tabelaBody.empty(); // Limpa a tabela antes de adicionar novos dados
            let body = '';
            let button = '';
            let inputQtd = '';

            if (itens.length > 0) {
                manipulaCardOperador('div-tabela-produtos-selecionados');

                itens.forEach(produto => {

                    //monta os botões caso necessário
                    if (podeExcluir) {
                        //caso seja uma tabela de devolucao usa ckeck para selecionar os itens a serem devolvidos
                        if (type == 'check') {

                            button = `<div class="form-check">
                                        <input type="checkbox" ${produto.qtd == 0 ? 'disabled' : ''} class="form-check-input devolucao-checkbox" style="width: 20px; height: 20px;">
                                    </div>`;
                            inputQtd = `
                                    <div class="input-group">
                                        <input
                                            placeholder="Qtd"
                                            data-max-qtd="${produto.qtd}"
                                            data-preco="${produto.preco}"
                                            readonly
                                            type="text"
                                            name="qtdDevolucao"
                                            value="${produto.qtd}"
                                            required
                                            class="form-control item-qtd-devolucao">
                                    </div>
                                    <div class="d-flex mt-2">
                                             <span class="badge badge-success mx-1" style="font-size: 12px; padding: 2px 6px;">Qtd Vendida: ${produto.qtdJaDevolvida + produto.qtd} </span>
                                         ${produto.qtdJaDevolvida > 0
                                            ? `<span class="badge badge-warning mr-2" style="font-size: 12px; padding: 2px 6px;">Já devolvido: ${produto.qtdJaDevolvida} </span>`
                                            : ' '}
                                    </div>`;

                        } else {
                            //caso seja uma tabela comum de intens inseridos na venda
                            button = `<button class="btn btn-danger btn-sm delete-btn d-none">
             <i class="bi bi-trash"></i>
          </button>`;

                        }
                    }

                    if (type != 'check') {
                        inputQtd = produto.qtd.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 3
                        });
                    }

                    body += `<tr data-estoque-id="${produto.estoqueId}">
                                 <td>${produto.cod_aux}</td>
                                 <td>
                                <div style="display: flex; justify-content: space-between; ">
                                <div>
                                <span>${produto.nome.trim()}
                                                                        ${!produto.ncm_id ? '<i class="bi bi-exclamation-triangle-fill text-warning" title="Produto sem NCM"></i>' : ''}</td>
</span>
                                </div>
                             <div>
                                ${button}
                            </div>
                            </div>
                            </td>
                    <td>${inputQtd}</td>
                    <td>${centavosParaReais(produto.preco)}</td>
                    <td>${centavosParaReais(produto.total)}</td>
                </tr>`;
                });

                tabelaBody.append(body);
            }

            atualizaValores(nomeCookieItens);
        }

        function countItens(nomeCookieItens) {
            let itens = getStorage(nomeCookieItens);
            let qtdItens = itens.length;
            $('#qtdItensCardProdutos').text(qtdItens)
        }

        function sumValorTotal(nomeCookieItens) {
            let itens = getStorage(nomeCookieItens);
            let somaTotal = itens.reduce((acc, item) => acc + item.total, 0);
            return somaTotal;
        }

        function selectcLinhaProdutoBusca(rows) {
            // Remove a classe 'selected' de todas as linhas
            rows.removeClass('selected');

            // Verifica se o índice está dentro dos limites e se não está selecionando o cabeçalho
            if (currentIndex >= 0 && currentIndex < rows.length) {
                let row = rows.eq(currentIndex);

                // Verifica se a linha não é o cabeçalho
                if (!row.hasClass('thead')) {
                    row.addClass('selected');
                }
            }
        }

        inicializaCardVenda()

        function inicializaCardVenda() {
            montaTabelaVenda(itensVendaAtual);
            n_venda_exibir();
        }

        function selecionaItemVenda(row) {
            montaTabelaVenda(itensVendaAtual);
        }

        function atualizaValores(itensVendaAtual) {
            countItens(itensVendaAtual);

            //interage com a atualizacao de valores caso não seja devolução
            if ($('#divDevolucao').hasClass('d-none')) {
                $('#div-valor-devolver').addClass('d-none');
                let desconto = $('#desconto-digitado').val() == '' ? 0 : converteParaFloat($('#desconto-digitado')
                    .val());

                let troco = 0;
                let valorTotal = sumValorTotal(itensVendaAtual);
                let valorDesconto = (desconto / 100) * valorTotal;
                let totalComDesconto = Math.round(valorTotal - valorDesconto);

                // Atualiza os campos sem o símbolo 'R$'
                $('#subtotal').val(centavosParaReais(valorTotal));
                $('#desconto').val(desconto);
                $('#desconto-digitado').val($('#desconto-digitado').val());
                $('#total').val(centavosParaReais(totalComDesconto));

                let selectedOptionsPagamento = $('#formaPagamento').find(':selected');
                let afetaTroco = false;
                let optionAfetaTroco;
                // Verifica se alguma das opções selecionadas afeta o troco
                selectedOptionsPagamento.each(function() {
                    if ($(this).data('afeta-troco')) {
                        afetaTroco = true;
                        optionAfetaTroco = $(this);
                        return false; // Para de iterar assim que encontra uma que afeta o troco
                    }
                });

                //caso aja mais de uma forma de pagamento
                // if (selectedOptionsPagamento.length > 1) {

                let totalValorRecebido = 0;
                $('.valor-recebido').each(function() {
                    let valorRecebidoEmCadaInput = $(this).val() != '' ? floatParaCentavos(converteParaFloat($(
                            this)
                        .val())) : converteParaFloat($(this).val());
                    let nome = $(this).data('pagamento-nome');
                    let pagamentoId = $(this).data('pagamento-id');

                    if (!isNanOrEmpty(valorRecebidoEmCadaInput)) {
                        totalValorRecebido += valorRecebidoEmCadaInput;

                    }

                });

                //caso uma forma de pagamento só e for dinheiro

                if (selectedOptionsPagamento.length == 1 && afetaTroco) {

                    // totalValorRecebido = totalComDesconto;
                    totalValorRecebido = $('#valor-recebido').val() != '' ? floatParaCentavos(converteParaFloat($(
                        '#valor-recebido').val())) : 0;

                    troco = totalValorRecebido - totalComDesconto;
                    if (troco < 0) {
                        // msgToastr('Valor recebido em dinheiro menor que valor da venda.', 'info');
                        troco = 0;
                    }
                } else if (selectedOptionsPagamento.length == 1) {
                    //caso outra forma de pagamento que não envolva troco ou seja o valor exato com cartão pix etc
                    totalValorRecebido = totalComDesconto;
                } else {
                    //varias formas de pagamento
                    if (afetaTroco) {
                        //teve dinheiro
                        let totalValorRecebido = $('#valor-recebido').val() != '' ? floatParaCentavos(
                            converteParaFloat($('#valor-recebido').val())) : 0;
                        let valorParaDinheiro = $('#valor-recebido-' + optionAfetaTroco.val()).val() != '' ?
                            floatParaCentavos(converteParaFloat($('#valor-recebido-' + optionAfetaTroco.val()).val())) :
                            0;
                        troco = totalValorRecebido - valorParaDinheiro;
                    }
                }


                $('#input-troco').val(centavosParaReais(troco));
                // Calcula o valor restante$
                let valorRestante = totalComDesconto - totalValorRecebido;
                // Atualiza o conteúdo do elemento com o valor restante e a cor apropriada
                $('#input-recebido').val(centavosParaReais(totalValorRecebido));
            } else {
                //esta em fase de devolucao
                let desconto = getStorage(obj_venda_devolucao).desconto_porcentagem;
                let troco = 0;
                let valorTotal = sumValorTotal(itensDevolucao);
                let valorDesconto = (desconto / 100) * valorTotal;
                let totalComDesconto = valorTotal - valorDesconto;

                // Atualiza os campos sem o símbolo 'R$'
                $('#subtotal').val(centavosParaReais(valorTotal));
                $('#desconto').val(desconto);
                $('#desconto-digitado').val(desconto);
                $('#total').val(centavosParaReais(totalComDesconto));

            }
        }

        //---------------parte de salvar venda ---------------------//
        function salvarVendaPost() {
            let cliente = $('#clienteSalvarVenda').val();
            let vendaSalva = getStorage(obj_venda) ?? null;
            if (vendaSalva) {
                cliente = vendaSalva.cliente_id;
            } else {
                cliente = cliente;
            }
            if (!cliente) {
                msgToastr('Informe o cliente', 'info');
                return;
            }


            $.ajax({
                url: routeSalvarVenda, // Substitua com a URL da sua rota
                type: 'POST', // Método POST
                dataType: 'json',
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Inclua o token CSRF se estiver usando Laravel
                },
                data: {
                    itens: JSON.stringify(getStorage(itensVendaAtual)),
                    venda_id: vendaSalva.id,
                    cliente: cliente
                },
                success: function(response) {
                    valida_response(response);

                    // Manipule a resposta bem-sucedida aqui
                    if (response.success == true) {
                        removeStorage(itensVendaAtual);
                        removeStorage(obj_venda);
                        montaTabelaVenda(itensVendaAtual);
                        msgToastr(response.msg, 'success')
                        $('#statusCaixaVenda').text(response.status);
                        $('#div-tabela-produtos-selecionados').addClass('d-none');
                        $('#imagemPadraoCaixa').removeClass('d-none');
                        $('#modal-salvar-venda').modal('hide');
                        resetaCarBuscaProduto();
                        statuAtualCaixa = response.caixa.status_id;
                    } else {
                        msgToastr(response.msg, 'error')
                    }
                },
                error: function(xhr) {
                    // Manipule erros aqui
                    msgToastr('Erro ao salvar venda: Comunique-se ao suporte', 'error')
                }
            });
        }

        function salvarVendaCaixa() {

            let itens = getStorage(itensVendaAtual);

            if (itens.length == 0) {
                msgToastr('Nenhum item a ser salvo na venda', 'info')

                return;
            }

            $('#modal-salvar-venda').modal('show');
            let venda = getStorage(obj_venda) ?? false;
            $('#clienteSalvarVenda').empty();

            if (venda) {
                // Adiciona a nova opção diretamente em HTML
                $('#clienteSalvarVenda').append(
                    `<option value="${venda.cliente_id}" selected disabled>${venda.cliente.nome}</option>`);
                $('#clienteJaPertenceAVenda').removeClass('d-none');
                // Desabilita o select (opcional, já que a opção está desabilitada)
                $('#clienteSalvarVenda').attr('disabled', true);
            } else {
                $('#clienteJaPertenceAVenda').addClass('d-none');
                // Desabilita o select (opcional, já que a opção está desabilitada)
                $('#clienteSalvarVenda').attr('disabled', false);
            }
        }

        //cadastro de cliente
        function modalCadastrarCliente() {
            $('#cadastroModal').modal('show');
            $('#modal-salvar-venda').modal('hide');
        }
        //------------------Voltar venda --------------------------//
        function voltarVenda() {
            $('#modal-voltar-venda').modal('show');
            $('#voltarVenda').empty();
        }

        function cancelarVoltarVenda() {

            $('#modal-voltar-venda').modal('hide');
            $('#voltarVenda').empty();
            let itens = getStorage(itensVendaAtual);
            montaTabelaVenda(itensVendaAtual);

            if (!itens.length) {
                manipulaCardOperador('imagemPadraoCaixa');

            }
        }

        function confirmarVoltaVenda() {
            let itensVenda = getStorage(itensVisualizarVoltarVenda);
            if (itensVenda.length == 0) {
                msgToastr('Nenhuma venda selecioanda!.', 'info')
                return;
            }

            const venda = vendaVoltarSelecionada;

            removeStorage(itensVisualizarVoltarVenda);
            setStorage(itensVendaAtual, JSON.stringify(itensVenda));
            setStorage(obj_venda, JSON.stringify(venda));

            updateStatusCaixa(@json(config('config.status.ocupado')));
            msgToastr('Venda retomada com sucesso.', 'success');
            resetaCarBuscaProduto();
            montaTabelaVenda(itensVendaAtual);
            $('#voltarVenda').empty();
            $('#modal-voltar-venda').modal('hide');
            n_venda_exibir();
        }

        function fecharSalvarVenda() {
            $('#modal-salvar-venda').modal('hide');
            montaTabelaVenda(itensVendaAtual);
            removeStorage(itensVisualizarVoltarVenda);
            $('#voltarVenda').empty();
        }

        //-------------Cacancelar venda ----------------//
        function cancelarVenda() {
            let itensVenda = getStorage(itensVendaAtual);
            let venda = getStorage(obj_venda);

            //valida se a venda é uma das que veio salva
            if (venda) {
                Swal.fire({
                    title: `Cancelar venda salva ?`,
                    html: `<h4>Cliente: ${venda.cliente.nome}</h4><h4>Nº Venda: ${venda.n_venda} </h4>`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#ff0000",
                    confirmButtonText: "Sim, cancelar!",
                    cancelButtonText: "Não!"
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: routeCancelarVendaSalva, // Substitua com a URL da sua rota
                            type: 'POST', // Método POST
                            dataType: 'json',
                            async: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Inclua o token CSRF se estiver usando Laravel
                            },
                            data: {
                                // itens: JSON.stringify(getStorage(itensVendaAtual)),
                                n_venda: venda.n_venda,
                            },
                            success: function(response) {
                                // Manipule a resposta bem-sucedida aqui
                                valida_response(response);

                                if (response.success == true) {
                                    rezetaStorage();
                                    msgToastr(response.msg, 'success')
                                    $('#statusCaixaVenda').text(response.caixa.status.descricao);
                                    $('#div-tabela-produtos-selecionados').addClass('d-none');
                                    $('#imagemPadraoCaixa').removeClass('d-none');
                                    n_venda_exibir();
                                    resetaCarBuscaProduto();
                                    statuAtualCaixa = response.caixa.status_id;

                                } else {
                                    msgToastr(response.msg, 'error')


                                }
                            },
                            error: function(xhr) {
                                msgToastr('Erro ao cancelar venda: Comunique-se ao suporte',
                                    'error')
                            }
                        });
                    }
                });

            } else {
                if (itensVenda.length == 0) {
                    msgToastr('Nenhuma item da venda a ser cancelado!', 'info');
                    return;
                }
                //caso caia aqui pq a venda não foi salva no banco
                Swal.fire({
                    title: `Cancelar venda atual?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, cancelar!",
                    cancelButtonText: "Não!"
                }).then((result) => {

                    if (result.isConfirmed) {
                        rezetaStorage();
                        msgToastr('Caixa livre para realizar venda.', 'info');
                        montaTabelaVenda(itensVendaAtual);
                        updateStatusCaixa(@json(config('config.status.livre')));
                        $('#div-tabela-produtos-selecionados').addClass('d-none');
                        $('#imagemPadraoCaixa').removeClass('d-none');

                    }
                })
            }


        }

        //--------------interacao com as teclas para selecionar o produto----------------//
        function interacaoComTeclasParaSelecionarProduto(rows, e) {
            if (rows.length === 0) {
                return;
            }

            const container = $('#div-principal-produtos-busca-tabela'); // Container que precisa rolar
            const containerTop = container.offset().top; // Offset top do container
            const containerBottom = containerTop + container.outerHeight(); // Offset bottom do container

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentIndex = (currentIndex + 1) % rows.length;
                selectcLinhaProdutoBusca(rows);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentIndex = (currentIndex - 1 + rows.length) % rows.length;
                selectcLinhaProdutoBusca(rows);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentIndex >= 0) {
                    let selectedRow = rows.eq(currentIndex);
                    if (selectedRow.length > 0) {
                        selecionaItemVenda(selectedRow);
                        selectedRow.trigger('click'); // Adiciona esta linha
                    } else {
                        console.error('Linha selecionada não encontrada.');
                    }
                }
            }

            // Verifica se a linha selecionada está visível no container
            let selectedRow = rows.eq(currentIndex);
            if (selectedRow.length === 0) {
                console.error('Linha selecionada não encontrada, pegando a primeira linha.');
                selectedRow = rows.first(); // Pega a primeira linha se não houver linha selecionada válida
            }

            const rowOffset = selectedRow.offset();
            if (!rowOffset) {
                console.error('Não foi possível obter o offset da linha selecionada.');
                return;
            }

            const rowTop = rowOffset.top;
            const rowBottom = rowTop + selectedRow.outerHeight();

            // Ajusta a rolagem para garantir que a linha selecionada fique visível
            if (rowTop < containerTop) {
                container.scrollTop(container.scrollTop() - (containerTop - rowTop)); // Rola para cima
            } else if (rowBottom > containerBottom) {
                container.scrollTop(container.scrollTop() + (rowBottom - containerBottom)); // Rola para baixo
            }
        }
        //-----------ir para nova venda ----------//
        function novaVenda() {
            let itens = getStorage(itensVendaAtual);

            if (!itens.length) {
                resetaCarBuscaProduto();
                montaTabelaVenda(itensVendaAtual);
                manipulaCardOperador('imagemPadraoCaixa');
                if (statuAtualCaixa != @json(config('config.status.livre'))) {
                    updateStatusCaixa(@json(config('config.status.livre')));
                }
                msgToastr('Caixa liberado para nova venda.', 'info');
                voltarDevolucao();

            } else {
                Swal.fire({
                    title: `Atenção: Todos os itens da venda atual serão perdidos se não forem salvos. Deseja continuar?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, continuar!",
                    cancelButtonText: "Não!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        voltarDevolucao();
                        if (statuAtualCaixa != @json(config('config.status.livre'))) {
                            updateStatusCaixa(@json(config('config.status.livre')));
                        }
                        montaTabelaVenda(itensVendaAtual);
                        n_venda_exibir();
                        msgToastr('Caixa liberado para nova venda.', 'info');
                    }
                });
            }
        }
        //-------------------devolução---------------//
        function calculaValorDevolucao() {
            let valorTotalDevolucao = 0; // Inicializa o valor total da devolução
            let focoSetado = false; // Variável para rastrear se o foco já foi definido

            // Itera sobre todos os checkboxes marcados
            $('.devolucao-checkbox:checked').each(function() {
                const $currentRow = $(this).closest('tr'); // Seleciona a linha (<tr>) correspondente
                const $qtdInput = $currentRow.find('.item-qtd-devolucao'); // Seleciona o input de quantidade

                const produtoPreco = converteParaFloat($qtdInput.attr('data-preco')); // Pega o preço do produto
                // Verifica se o input de quantidade está vazio
                if ($qtdInput.val() === '' || converteParaFloat($qtdInput.val()) <= 0) {
                    // Coloca o foco no input vazio se ainda não foi setado
                    if (!focoSetado) {
                        $qtdInput.focus();
                        focoSetado = true;
                    }

                    return false; // Sai do loop para evitar cálculo com valores inválidos
                }
                const qtdSelecionada = $qtdInput.val() != '' ? converteParaFloat($qtdInput.val()) :
                    0; // Pega a quantidade atual selecionada

                // Calcula o valor do produto devolvido e soma ao total
                valorTotalDevolucao += (qtdSelecionada * produtoPreco);
            });
            // Se algum campo estava inválido, não continua o cálculo
            if (focoSetado) {
                // msgToastr('Informe um valor válido a ser devolvido.', 'info');
                return;
            }
            // Obtém o desconto em porcentagem
            const descontoPercentual = converteParaFloat($('#desconto').val()) || 0;

            // Calcula o valor do desconto
            const valorDesconto = (valorTotalDevolucao * descontoPercentual) / 100;

            // Calcula o total da devolução com o desconto aplicado
            valorTotalDevolucao = valorTotalDevolucao - valorDesconto;

            // Garante que o valor total não seja negativo
            if (valorTotalDevolucao < 0) {
                valorTotalDevolucao = 0;
            }

            // Exibe o valor total da devolução em algum lugar na página
            $('#div-valor-devolver').removeClass('d-none');
            $('#input-devolver').val(centavosParaReais(valorTotalDevolucao));
            calculaProporcaoParaFormaDevolucao(valorTotalDevolucao);
        }

        function calculaProporcaoParaFormaDevolucao(valorTotalDevolucao) {
            const $formasDevolucao = $('.valor-devolucao');
            let sobra = valorTotalDevolucao;

            // 1. Itera sobre as formas de pagamento e distribui o valor da devolução
            $formasDevolucao.each(function() {
                const valorFormaPagamento = parseInt($(this).data('devolucao-valor')) || 0;
                const valorJaDevolvido = parseInt($(this).data('venda-pg-devolucao')) || 0;

                if (sobra <= 0 || valorJaDevolvido >= valorFormaPagamento) {
                    $(this).val(centavosParaReais(0));
                    return true; // Para a iteração se o valor total de devolução já foi distribuído
                }
                let valorProporcional = valorFormaPagamento - valorJaDevolvido;
                // 2. A devolução para essa forma de pagamento será o valor máximo entre o valor disponível ou o que sobrar
                valorProporcional = Math.min(valorProporcional, sobra);

                // 3. Atualiza o valor da devolução para a forma de pagamento
                $(this).val(centavosParaReais(Math.round(valorProporcional))); // Exibe como centavos

                // 4. Subtrai o valor devolvido da sobra
                sobra -= valorProporcional;
            });
        }

        function devolucao() {
            manipulaCardPrincipal('divDevolucao');
            $('#devolucaoSelect').empty();
            $('#desconto').val('');
            $('#desconto-digitado').val('');
            $('#valor-recebido').val('');
            $('#input-troco').val('');
            removeStorage(itensVisualizarVoltarVenda);
            removeStorage(itensVendaAtual);
            removeStorage(obj_venda);
            manipulaCardOperador('imagemPadraoCaixa');
            n_venda_exibir();
            $('#devolucaoSelect').focus();
            $('#formasDevolucao').empty();
        }

        function voltarDevolucao() {
            rezetaStorage();
        }

        function rezetaStorage() {
            removeStorage(obj_venda_devolucao);
            removeStorage(itensDevolucao);
            removeStorage(itensVisualizarVoltarVenda);
            removeStorage(itensVendaAtual);
            removeStorage(obj_venda);
            manipulaCardPrincipal('relogio');
            manipulaCardOperador('imagemPadraoCaixa');
            $('#buscarClienteFinalizarVenda').empty();
            n_venda_exibir();
            montaTabelaVenda(itensVendaAtual)
        }

        function confirmarItensDevolucao() {
            let vendId = $('#devolucaoSelect').val();
            let motivo = $('#motivoDevolucao').val();
            let formasDevolucao = [];

            $('.valor-devolucao').each(function() {
                // Pega o ID do atributo 'data-forma-devolucao-id' e o valor do input
                const id = $(this).data('forma-devolucao-id');
                const value = $(this).val() == '' ? 0 : floatParaCentavos(converteParaFloat($(this).val()));

                // Adiciona o objeto ao array
                if (value > 0) {
                    formasDevolucao.push({
                        id: id,
                        value: value
                    });
                }
            });

            //pega os itens selecionados para devolucao
            const linhasSelecionadas = $('input.devolucao-checkbox:checked').closest('tr');

            if (!vendId) {
                msgToastr('Informe a venda.', 'info');

                $('#devolucaoSelect').focus();
                return;
            }

            if (formasDevolucao.length == 0) {
                msgToastr('Nenhuma forma de devolução foi preenchido.', 'info');
                return
            }

            if (!motivo || motivo == '') {
                msgToastr('Informe o motivo.', 'info');
                $('#motivoDevolucao').focus();
                return;
            }

            if (!linhasSelecionadas.length) {
                msgToastr('Nenhum item selecionado para devolução.', 'info');
                return;
            }

            let itens = getStorage(itensDevolucao);
            itens.forEach(element => {
                linhasSelecionadas.each(function() {
                    const estoqueId = $(this).data('estoque-id');

                    if (element.estoqueId === estoqueId) {
                        const inputQuantidade = $(this).find(`input[name="qtdDevolucao"]`);
                        const quantidadeDevolucao = inputQuantidade.val();
                        element.devolucao =
                            true; // Adiciona ou atualiza o parâmetro 'devolucao' para true
                        element.quantidade_devolucao =
                            quantidadeDevolucao; // Adiciona ou atualiza o parâmetro 'devolucao' para true
                    }
                });
            });

            if (vendId) {
                $.ajax({
                    url: routeDevolucao,
                    type: 'POST',
                    data: {
                        venda_id: vendId,
                        motivo: motivo,
                        vendas_pagamento_devolucao: formasDevolucao,
                        itens: JSON.stringify(itens),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        valida_response(response);

                        if (response.success == true) {
                            imprimirDevolucao(response.devolucao)
                            rezetaStorage()
                            msgToastr(response.msg, 'success')
                            resetaCarBuscaProduto();
                            $('#div-tabela-produtos-selecionados').addClass('d-none');
                            $('#imagemPadraoCaixa').removeClass('d-none');
                            // Limpar o valor do select
                            $('#devolucaoSelect').val(null).trigger('change'); // Para atualizar o select2
                            $('#formasDevolucao').empty();

                            // Limpar o conteúdo do textarea
                            $('#motivoDevolucao').val('');
                            statuAtualCaixa = response.caixa.status_id;
                            updateStatusCaixa(@json(config('config.status.livre')));

                        } else {
                            msgToastr(response.msg, 'error')

                        }

                    },
                    error: function(xhr) {
                        // Manipule erros aqui
                        msgToastr('Erro na requisição:' + xhr, 'error');
                    }
                });
            } else {
                msgToastr('Não foi possível capturar a venda para realizar a devolucao. Tente mais tarde', 'warning');
            }
        }


        //--------------Sangria-------------------//
        function modalSangria() {
            $('#modal-sangria').modal('show');
            $.ajax({
                url: routeGetSangria,
                type: 'GET',
                data: {
                    caixa_id: @json($caixa->id)
                },
                success: function(response) {
                    // Verificar se há sangrias realizadas
                    imprimirSangria(response.sangria)

                    let ultimaSangria = '';
                    if (response.sangria.sangrias_realizadas.length > 0) {
                        let ultimaSangriaRealizada = response.sangria.sangrias_realizadas.at(-
                            1); // Última sangria
                        let dataAbertura = new Date(ultimaSangriaRealizada.data_abertura);
                        ultimaSangria =
                            `${dataAbertura.toLocaleDateString('pt-BR')} ${dataAbertura.toLocaleTimeString('pt-BR')}`;
                    } else {
                        ultimaSangria = 'Nenhuma sangria realizada até o momento.';
                    }

                    // Inicialização de variáveis
                    let totalDinheiro = 0;
                    let totalDevolucao = 0;
                    let devolucaoDinheiro = response.sangria?.total_por_forma_devolucao?.Dinheiro ?? 0;

                    // Calculando total de devoluções
                    if (response.sangria.total_por_forma_devolucao && typeof response.sangria
                        .total_por_forma_devolucao === 'object') {
                        for (let chave in response.sangria.total_por_forma_devolucao) {
                            totalDevolucao += response.sangria.total_por_forma_devolucao[chave];
                        }
                    }

                    // Construção de informações totais
                    let informacoesTotais = `
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group">
                           `;

                    if (response.sangria.total_por_forma_pagamento && typeof response.sangria
                        .total_por_forma_pagamento === 'object') {
                        for (let [forma, total] of Object.entries(response.sangria
                                .total_por_forma_pagamento)) {
                            if (forma === 'Dinheiro') {
                                totalDinheiro = total; // Atualizando o totalDinheiro
                            } else {
                                informacoesTotais += `
                            <li class="list-group-item font-weight-bold">
                                ${forma}: R$ ${centavosParaReais(total)}
                            </li>`;
                            }
                        }

                        // Atualiza o valor de dinheiro disponível após as devoluções
                        totalDinheiro -= devolucaoDinheiro;

                        informacoesTotais += `
                    <li class="list-group-item font-weight-bold">
                        Total: R$ ${centavosParaReais(
                            response.sangria.total_recebimento + response.sangria.ultimo_registro.valor_abertura - totalDevolucao
                        )}
                    </li>`;

                        if (devolucaoDinheiro > 0) {
                            informacoesTotais += `
                        <li class="list-group-item font-weight-bold">
                            Devoluções: R$ ${centavosParaReais(totalDevolucao)} <br>
                            Devoluções em dinheiro: R$ ${centavosParaReais(devolucaoDinheiro)}
                        </li>`;
                        }

                        informacoesTotais += `
                    <li class="list-group-item font-weight-bold" data-total-retirar-sangria="${
                        totalDinheiro + response.sangria.ultimo_registro.valor_abertura
                    }">Dinheiro: R$ ${centavosParaReais(totalDinheiro + response.sangria.ultimo_registro.valor_abertura)}
                    </li>`;
                    }

                    informacoesTotais += `
                        </ul>
                    </div>
                </div>`;

                    // Inserir o conteúdo no elemento HTML
                    $('#informacoes-totais').html(informacoesTotais);

                    // Atualizar variável global, se necessário
                    dataSangria = response.sangria;
                },
                error: function(xhr) {
                    msgToastr('Erro na requisição: ' + xhr.statusText, 'error');
                }
            });
        }


        function confirmarSangria() {
            let senha = $('#senhaUsuarioSangria').val();
            let observacao = $('#comentarioSangria').val();
            let quantidade_retirar = $('#quantidadeRetirarSangria').val();
            let totalPodeRetirar = $('[data-total-retirar-sangria]').data('total-retirar-sangria');

            let formas_pagamento = dataSangria.total_por_forma_pagamento;
            quantidade_retirar = quantidade_retirar == '' ? 0 : floatParaCentavos(converteParaFloat(
                quantidade_retirar));

            let valor = quantidade_retirar;


            if (senha == '') {
                msgToastr('Digite sua senha para realizar a operação.', 'info');
                $('#senhaUsuarioSangria').focus();
                return

            }

            if (valor == '' || valor <= 0) {
                msgToastr('A quantidade solicitada para retirar é maior do que contém em caixa.', 'info');
                $('#quantidadeRetirarSangria').focus();
                return

            }

            if (valor > totalPodeRetirar) {
                msgToastr('A quantidade solicitada para retirar é maior do que contém em caixa.', 'info');
                $('#quantidadeRetirarSangria').focus();

                return
            }

            $.ajax({
                url: routePostSangria,
                type: 'POST',
                data: {
                    caixa_id: @json($caixa->id),
                    senha: senha,
                    valor: valor,
                    observacao: observacao,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {

                    if (response.success == true) {
                        msgToastr(response.msg, 'success');
                        $('#modal-sangria').modal('hide');
                        $('#senhaUsuarioSangria').val('');
                        $('#comentarioSangria').val('');
                        $('#quantidadeRetirarSangria').val('');

                    } else {
                        msgToastr(response.msg, 'error');

                    }
                },
                error: function(xhr) {
                    // Manipule erros aqui
                    msgToastr('Erro na requisição:' + xhr, 'error');
                }
            });
        }

        function fecharModalSangria() {
            $('#modal-sangria').modal('hide');
            $('#senhaUsuarioSangria').val('');
        }

        //------------------Parte de recebimento--------------------//
        function recebimento() {
            rezetaStorage();
            manipulaCardPrincipal('divRecebimento');
            $('#recebimentoSelect').empty();
            $('#divValorRecebidoDinamico').empty();
            $('#desconto').val('');
            $('#desconto-digitado').val('');
            $('#valor-recebido').val('');
            $('#input-troco').val('');
            manipulaCardOperador('imagemPadraoCaixa');
            n_venda_exibir();
            $('#recebimentoSelect').focus();
            $('#formaPagamentoRecebimento').attr('disabled', true);

        }

        function calculaValoresRecebimento() {
            // Inicializa o total como 0
            let totalReceber = 0;
            // Seleciona todos os inputs habilitados com a classe 'valor-pagar-recebimento'
            $('.habilitar-input-recebimento:checked').each(function() {
                // Pega o valor do input, converte para float e soma ao total
                let valor = $(this).closest('td').find('.valor-pagar-recebimento').val() == '' ? 0 :
                    floatParaCentavos(converteParaFloat($(this).closest('td').find('.valor-pagar-recebimento')
                        .val()));
                totalReceber += valor;
            });

            $('#subtotal').val(centavosParaReais(totalReceber));
            $('#total').val(centavosParaReais(totalReceber));

            let valorRecebidoTotalDinheiro = $('#valor-recebido-recebimento-dinheiro').val();
            let recebidoEmDinheiro = 0;
            let troco = 0;
            let totalRecebido = 0;

            let selectedOptions = $('#formaPagamentoRecebimento').find(':selected');
            if (totalReceber == 0) {
                $('#formaPagamentoRecebimento').find('option').prop('selected', false).end().trigger('change');
                $('#divValorRecebidoDinamico').empty();
                $('#input-recebido').val(centavosParaReais(totalRecebido)); // Formata como moeda
                $('#input-troco').val(centavosParaReais(troco)); // Formata como moeda


                // msgToastr('Selecione a venda a ser recebida.', 'info');

            } else {

                if (selectedOptions.length == 1 && !selectedOptions.data('afeta-troco')) {
                    $('#input-recebido').val(centavosParaReais(totalReceber)); // Formata como moeda
                    $('#input-troco').val(centavosParaReais(troco)); // Formata como moeda

                } else {

                    // Iterar sobre todos os inputs ativos e somar os valores
                    $('.valor-recebido-recebimento').each(function() {
                        //valor que foi recebido total em dinheiro
                        const valor = $(this).val() == '' ? 0 : floatParaCentavos(converteParaFloat($(
                            this).val())); // Converte para número
                        totalRecebido += valor; // Soma o valor

                        if ($(this).data('afeta-troco')) {
                            recebidoEmDinheiro = valor;
                        }
                    });

                    if (valorRecebidoTotalDinheiro) {
                        valorRecebidoTotalDinheiro = valorRecebidoTotalDinheiro == '' ? 0 : floatParaCentavos(
                            converteParaFloat(valorRecebidoTotalDinheiro));
                        // console.log(valorRecebidoTotalDinheiro, recebidoEmDinheiro);

                        if (valorRecebidoTotalDinheiro < recebidoEmDinheiro) {
                            msgToastr('Valor recebido em dinheiro menor que o valor a ser pago em dinheiro.', 'info');

                        } else {
                            troco = valorRecebidoTotalDinheiro - recebidoEmDinheiro;
                        }
                    }
                    // Preencher o input de valor total recebido
                    $('#input-recebido').val(centavosParaReais(totalRecebido)); // Formata como moeda
                    $('#input-troco').val(centavosParaReais(troco)); // Formata como moeda
                    if (totalRecebido > totalReceber) {
                        msgToastr('Valor recebido maior que o valor a receber.', 'info');

                    }
                }
            }
        }

        function voltarRecebimento() {
            rezetaStorage();
            $('#formaPagamentoRecebimento').find('option').prop('selected', false).end().trigger('change');
        }

        function confirmarRecebimento() {
            let vendaPagamentoReceber = [];
            let pagamentos = [];
            let totalReceber = 0;
            let totalRecebido = 0;
            let validateValores = true;
            let msg = '';
            let contem_parcela = false;
            let selectedOptions = $('#formaPagamentoRecebimento').find(':selected');
            let valorRecebidoTotalDinheiro = $('#valor-recebido-recebimento-dinheiro').val();

            $('.habilitar-input-recebimento:checked').each(function() {
                // Pega o valor do input, converte para float e soma ao total
                let valor = $(this).closest('td').find('.valor-pagar-recebimento').val() == '' ? 0 :
                    floatParaCentavos(converteParaFloat($(this).closest('td').find('.valor-pagar-recebimento')
                        .val()));
                totalReceber += valor;
                if (valor == 0) {
                    validateValores = false;
                    msg = 'Valor para receber inválido. Verifique os valores preenchidos para receber.';
                }
                vendaPagamentoReceber.push({
                    venda_pagamento_id: $(this).data('id'),
                    valor: valor
                })
            });

            if (vendaPagamentoReceber.length == 0) {
                msgToastr('Nenhuma venda selecionada para realizar o recebimento.', 'info');
                return;
            }

            if (selectedOptions.length == 1 && $('#numero-parcelas-recebimento').val()) {
                contem_parcela = true;
                parcelas = $('#numero-parcelas-recebimento').val();

                pagamentos.push({
                    forma_pagamento_id: selectedOptions.val(),
                    valor: totalReceber,
                    parcelas: parcelas
                });
                totalRecebido = totalReceber;
            } else if (selectedOptions.length == 1 && !selectedOptions.data('afeta-troco')) {

                pagamentos.push({
                    forma_pagamento_id: selectedOptions.val(),
                    valor: totalReceber,
                    parcelas: 0
                });
                totalRecebido = totalReceber;

            } else {

                $('.valor-recebido-recebimento').each(function() {
                    //valor que foi recebido total em dinheiro
                    let formaPagamentoId = $(this).data('pagamento-id');
                    let parcelas = 0;
                    let valor = $(this).val() == '' ? 0 : floatParaCentavos(converteParaFloat($(
                        this).val())); // Converte para número
                    totalRecebido += valor; // Soma o valor
                    if (valor == 0) {
                        validateValores = false;
                        msg = 'Valor inválido na forma de pagamento ' + $(this).data('pagamento-nome');

                    }
                    if ($(this).data('contem-parcela')) {
                        contem_parcela = true;
                        parcelas = $('#numero-parcelas-recebimento').val();
                    }
                    pagamentos.push({
                        forma_pagamento_id: formaPagamentoId,
                        valor: valor,
                        parcelas: parcelas
                    });
                });
            }


            if (validateValores == false) {
                msgToastr(msg, 'info');
                return
            }
            if (pagamentos.length == 0) {
                msgToastr('Nenhuma forma de pagamento selecionada.', 'info');
                return;
            }
            if (contem_parcela && $('#numero-parcelas-recebimento').val() == '' || $('#numero-parcelas-recebimento')
                .val() == 0) {
                msgToastr('Quantidade de parcelas para forma de pagamento em crédito inválida.', 'info');
                return
            }

            if (totalRecebido < totalReceber) {
                msgToastr('O valor informado para receber é menor que o valor do recebimento.', 'info');
                return
            }
            if (totalRecebido > totalReceber) {
                msgToastr('O valor informado para receber é maior que o valor do recebimento.', 'info');
                return
            }

            $.ajax({
                url: routePostRecebimento,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    formas_pagamentos: pagamentos,
                    venda_pagamentos: vendaPagamentoReceber
                },
                success: function(response) {

                    if (response.success == true) {
                        imprimirRecebimento(response.recebimentos)
                        msgToastr(response.msg, 'success');
                        voltarRecebimento();

                    } else {
                        msgToastr(response.msg, 'error');

                    }
                },
                error: function(xhr) {
                    // Manipule erros aqui
                    msgToastr('Erro na requisição:' + xhr, 'error');
                }
            });
        }

        //-----------------Perguntar para voltar ao inicio----------------------//
        function voltarInicio() {
            Swal.fire({
                title: `Voltar ao início ?`,
                text: `Ao voltar a venda atual será cancelada.`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, voltar!",
                cancelButtonText: "Não!"
            }).then((result) => {

                if (result.isConfirmed) {
                    rezetaStorage();
                    window.location.href = routeInicio;
                }
            });
        }

        function aplicarAutocompleteOff() {
            $('input').each(function() {
                $(this).attr('autocomplete', 'off');
                $(this).attr('autocorrect', 'off');
                $(this).attr('autocapitalize', 'off');
                $(this).attr('spellcheck', 'false');
            });
        }

        $(document).ready(function() {
            aplicarAutocompleteOff();
            $(document).on('focus', 'input', function() {

                aplicarAutocompleteOff();
            });

            // Verifica se a página já está aberta
            if (localStorage.getItem(tokenGia)) {
                alert("A aplicação já está aberta em outra aba!");
                window.location.href = routeHome;
            } else {
                localStorage.setItem(tokenGia, "true");

                // Remove a chave quando a aba for fechada
                window.addEventListener("beforeunload", function() {
                    localStorage.removeItem(tokenGia);
                });
            }

            select2('buscarClienteFinalizarVenda', routeClientesGet);
            select2('clienteSalvarVenda', routeClientesGet + '/' + false, 'modal-salvar-venda');
            select2('voltarVenda', routeSelectVendaVoltar, 'modal-voltar-venda');
            select2('devolucaoSelect', routeVendaDevolucao);
            select2('recebimentoSelect', routeGetClienteRecebimento);
            select2('selectClienteDevolucao', routeClientesGet)
            select2('formaPagamento');
            select2('formaPagamentoRecebimento');

            // maskDinheiro('valorInicial');
            // maskDinheiro('subtotal');
            maskDinheiro('input-troco');
            // maskDinheiro('total');
            maskDinheiro('valor-recebido');
            maskDinheiro('quantidadeRetirarSangria');
            maskPorcentagem('desconto-digitado', 99);
            maskQtd('quantidadeItem');
            setTimeout(function() {

                // Opcional: Focar em um campo específico
                $('#buscaProdutos').focus();
            }, 500); // Aguarde 500ms antes de aplicar


            $('#modal-voltar-venda').on('hidden.bs.modal', function() {
                // Chama a função que você deseja executar ao fechar o modal
                // cancelarVoltarVenda();
                let itens = getStorage(itensVendaAtual);
                if (!itens.length) {
                    rezetaStorage();
                }
            });
            $('#fullscreen-btn').on('click', function() {
                if (!document.fullscreenElement) {
                    // Ativar o modo de tela cheia
                    document.documentElement.requestFullscreen().then(() => {
                        $(this).removeClass('bi-arrows-fullscreen').addClass('bi-fullscreen-exit');
                    }).catch((err) => {
                        console.error(`Erro ao entrar em tela cheia: ${err}`);
                    });
                } else {
                    // Sair do modo de tela cheia
                    document.exitFullscreen().then(() => {
                        $(this).removeClass('bi-fullscreen-exit').addClass('bi-arrows-fullscreen');
                    }).catch((err) => {
                        console.error(`Erro ao sair do modo de tela cheia: ${err}`);
                    });
                }
            });
            //--------------------------------------parte da busca-------------------------------//
            let debounceTimeout;
            $('#buscaProdutos').on('input', function() {
                manipulaCardPrincipal('carregando');
                let busca = $(this).val();
                n_venda_exibir();
                clearTimeout(debounceTimeout);

                debounceTimeout = setTimeout(function() {
                    if (busca.length >= 1) {
                        $.ajax({
                            url: routeBuscaProdutos,
                            type: 'GET',
                            data: {
                                q: busca
                            },
                            success: function(response) {
                                valida_response(response);

                                let tabelaBody = $('#produtos-busca-tabela tbody');
                                tabelaBody.empty();
                                var linhas = '';

                                if (response.data.length) {

                                    manipulaCardPrincipal(
                                        'div-produtos-busca-tabela'
                                    ); // Mostra a tabela de resultados
                                    response.data.forEach(produto => {

                                        linhas += `
                                <tr style="cursor:pointer;" data-estoque-id="${produto.estoque_id}"
                                    data-preco="${produto.preco}"
                                    data-cod-aux="${produto.cod_aux}"
                                    data-nome="${produto.nome + ' <b>' + produto.sigla + '</b>'}">
                                    <td>${produto.cod_aux}</td>
                                    <td>${produto.nome} <b>${produto.sigla}</b>
                                    ${!produto.ncm_id ? '<i class="bi bi-exclamation-triangle-fill text-warning" title="Produto sem NCM"></i>' : ''}</td>
                                    <td>${centavosParaReais(produto.preco)}</td>
                                </tr>`;
                                    });
                                    tabelaBody.append(linhas);
                                    currentIndex =
                                        0; // Seleciona a primeira linha por padrão
                                    selectcLinhaProdutoBusca(tabelaBody.find('tr'));
                                } else {

                                    // Mostra o alerta "Produto não encontrado"
                                    manipulaCardPrincipal(
                                        'produtos-busca-tabela-nao-encontrato');
                                }
                                if (response.data.length == 1 && leitorBr == true) {
                                    adicionarItem(tabelaBody.find('tr'));
                                }
                            },
                            error: function(xhr) {
                                // Manipule erros aqui
                                msgToastr('Erro na requisição:' + xhr, 'error');
                            }
                        });
                    } else {
                        manipulaCardPrincipal('relogio');

                    }
                }, 100); // 500ms de espera antes de executar a busca
            });

            //parte das interações com as teclas de seleção do produto quando esta no input de produto
            $('#buscaProdutos').on('keydown', function(e) {
                let tabelaBody = $('#produtos-busca-tabela tbody');
                let rows = tabelaBody.find('tr');

                interacaoComTeclasParaSelecionarProduto(rows, e)
                //valida se a consulta esta sendo por teclado
                // Verifica se a busca tem mais de 6 caracteres e contém apenas números
                if (/^\d{6,}$/.test($(this).val())) {
                    leitorBr = true;
                } else {
                    leitorBr = false;
                }


            });

            //--------------------------------interacoes com finalizar venda --------------------------------//
            //manipula os inputs recebidos caso seja mais de uma forma de pagamento
            $(document).on('blur', '.valor-recebido', function() {
                atualizaValores(itensVendaAtual);
            });

            //ao selecionar forma de pagamento coloca o focus no cliente
            $('#formaPagamento').on('select2:select select2:unselect', function() {
                let selectedOptions = $(this).find(':selected');
                let afetaTroco = false;
                let contem_parcela = false;
                // Verifica se alguma das opções selecionadas afeta o troco
                selectedOptions.each(function() {
                    if ($(this).data('afeta-troco')) {
                        afetaTroco = true;
                    }
                    if ($(this).data('contem-parcela')) {
                        contem_parcela = true;
                    }
                });
                //quando é selecionado mais de uma forma de pagamento
                let container = $('#divValorPagoDinamico');

                // Limpa os inputs antes de recriar, para garantir que não haja duplicações
                container.empty();
                if (selectedOptions.length > 1) {
                    selectedOptions.each(function() {
                        let pagamentoId = $(this).val(); // Pode ser o ID da forma de pagamento
                        let pagamentoNome = $(this).text(); // Nome da forma de pagamento

                        // Cria o input dinamicamente
                        let inputGroup = `
                                            <div class="input-group mb-2 col-md-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">${pagamentoNome}</span>
                                                </div>
                                                <input type="text" data-pagamento-nome="${pagamentoNome}"
                                                data-pagamento-id="${pagamentoId}" data-contem-parcela="${$(this).data('contem-parcela')}"
                                                       class="form-control valor-recebido"
                                                       placeholder="R$ 0,00"
                                                       id="valor-recebido-${pagamentoId}"
                                                       name="valorPago[${pagamentoId}]">
                                            </div>`;

                        // Adiciona o novo input ao container
                        container.append(inputGroup);
                        maskDinheiro(`valor-recebido-${pagamentoId}`);

                    });

                }

                // Adiciona input de parcelas se for necessário
                if (contem_parcela) {
                    let parcelasInput = `
                                <div class="input-group mb-2 col-md-6">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Parcelas</span>
                                    </div>
                                    <input type="number" class="form-control"
                                           id="numero-parcelas"
                                           name="numero_parcelas"
                                           placeholder="Quantidade de Parcelas"
                                           value="1"
                                           min="1"
                                           step="1"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                </div>`;
                    container.append(parcelasInput);
                }


                if (afetaTroco) {
                    $('#valor-recebido').attr('readonly', false);
                } else {
                    $('#valor-recebido').attr('readonly', true);
                    $('#valor-recebido').val('');
                    $('#input-troco').val('');
                    $('#input-recebido').val('')
                }

                let vendaJaTemCliente = getStorage(obj_venda) ? true : false;
                if (!vendaJaTemCliente) {
                    vendaJaTemCliente = $('#buscarClienteFinalizarVenda').val();
                }

                if (!vendaJaTemCliente) {
                    //manda para o input do cliente caso não exista
                    $('#buscarClienteFinalizarVenda').focus();
                } else {
                    //vai para o input de preencher o recebimento
                    if (afetaTroco) {
                        $('#valor-recebido').focus();
                    } else {
                        //manda para o botao de finalizar
                        $('#botaoFinalizarVenda').focus();
                    }
                }
            });

            $('#buscarClienteFinalizarVenda').on('select2:select', function() {
                var afetaTroco = $('#formaPagamento').find(':selected').data('afeta-troco');
                n_venda_exibir();
                if (afetaTroco) {
                    $('#valor-recebido').focus();
                } else {
                    $('#botaoFinalizarVenda').focus();
                }
            })
            //ao clianca enter no ultimo input caso seja valorPago chama a funcao para finalizar a venda
            $('#valor-recebido').on('keypress', function(event) {
                if (event.which === 13) { // Verifica se a tecla pressionada é Enter (código 13)
                    event.preventDefault(); // Previna o comportamento padrão de submissão do formulário
                    finalizarVendaPost();
                }
            });

            $('#valor-recebido').on('blur', function() {
                atualizaValores(itensVendaAtual);
            });
            $('#desconto-digitado').on('blur', function() {
                // Obter o valor inserido
                atualizaValores(itensVendaAtual);
            });

            //parte das interações com as teclas de seleção do produto quando esta no input de quantidade
            $('#quantidadeItem').on('keydown', function(e) {
                let tabelaBody = $('#produtos-busca-tabela tbody');
                let rows = tabelaBody.find('tr');

                interacaoComTeclasParaSelecionarProduto(rows, e)
            });

            //------------------interacao tabela produto busca------------------//
            //parte de interação com click na seleção de produtos
            $('#produtos-busca-tabela').on('click', 'tbody tr', function() {
                let selectedRow = $(this);

                if (selectedRow.hasClass('selected')) {
                    adicionarItem(selectedRow);
                }
                $('#produtos-busca-tabela tr').removeClass('selected');
                $(this).addClass('selected');

                selecionaItemVenda(selectedRow);


            });

            //seleciona item ao passar mouse por cima
            // $('#produtos-busca-tabela').on('mouseenter', 'tbody tr', function() {
            //     $('#produtos-busca-tabela tr').removeClass('selected');
            //     $(this).addClass('selected');
            //     // let selectedRow = $(this);
            //     // selecionaItemVenda(selectedRow);
            // });
            // //---------------------interacao tabela produtos selecionados--------------//
            //seleciona item da tabela de itens já selecionados para remover
            $('#tabela-produtos-selecionados').on('mouseenter', 'tbody tr', function() {
                $('#tabela-produtos-selecionados tr').removeClass('selected');
                $('#tabela-produtos-selecionados .delete-btn').addClass('d-none');

                // Adiciona a classe 'selected' à linha atual e exibe o botão de excluir na linha atual
                $(this).addClass('selected');
                $(this).find('.delete-btn').removeClass('d-none');

            });
            //ao sair do foco da tabela remove toda a selected
            $('#tabela-produtos-selecionados').on('mouseleave', function() {
                $('#tabela-produtos-selecionados tr').removeClass('selected');
                $(this).find('.delete-btn').addClass('d-none');
            });
            //remover item da tabela
            // Adiciona o manipulador de evento de clique ao botão de excluir
            $('#tabela-produtos-selecionados').on('click', '.delete-btn', function(e) {
                e.preventDefault(); // Previne a ação padrão do botão, se necessário

                // Obtém a linha da tabela onde o botão foi clicado
                let row = $(this).closest('tr');

                // Exibe um alerta com o texto da linha como exemplo (substitua com a lógica real)
                let produtoNome = row.find('td').eq(1)
                    .text(); // Assume que o nome do produto está na segunda coluna
                let quantidade = row.find('td').eq(2)
                    .text(); // Assume que o quantidade do produto está na segunda coluna

                let estoqueId = row.data(
                    'estoque-id'); // Use row.data('estoque-id') em vez de $(this).data('estoque-id')

                Swal.fire({
                    title: `Excluir ?`,
                    html: `<h3>${produtoNome}</h3><h3>Quantidade: ${quantidade}</h3>`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: "Não!"
                }).then((result) => {

                    if (result.isConfirmed) {
                        removeItem(estoqueId);
                        montaTabelaVenda(itensVendaAtual);
                        msgToastr('Item removido com sucesso.', 'success');
                    }
                });
            });

            //----------Voltar venda -------------//
            $('#voltarVenda').on('change', function() {
                var vendaId = $(this).val(); // Obtenha o valor selecionado
                voltarDevolucao();
                if (vendaId) {
                    $.ajax({
                        url: routeVoltarVenda, // URL da sua rota para lidar com a seleção
                        type: 'GET',
                        data: {
                            id: vendaId // Envie o ID da venda como parâmetro
                        },
                        success: function(response) {
                            valida_response(response);

                            removeStorage(itensVisualizarVoltarVenda);

                            let itensVenda = [];
                            response.venda_itens.forEach(element => {
                                let novoObjeto = {
                                    vendaItemId: element.id,
                                    estoqueId: element?.estoque_id,
                                    qtd: parseFloat(element?.quantidade),
                                    cod_aux: element?.estoque?.produto?.cod_aux,
                                    nome: element?.estoque?.produto?.nome +
                                        ' <b>' +
                                        element?.estoque?.produto
                                        ?.unidade_medida
                                        ?.sigla + '</b>',
                                    preco: parseFloat(element.preco),
                                    total: parseFloat(element.total),
                                };

                                itensVenda.push(novoObjeto);

                            });

                            delete response.venda_itens;
                            vendaVoltarSelecionada = response;

                            // Salvar o array atualizado de volta no cookie
                            setStorage(itensVisualizarVoltarVenda, JSON.stringify(
                                itensVenda));

                            montaTabelaVenda(itensVisualizarVoltarVenda, true);

                        },
                        error: function(xhr) {
                            // Manipule erros aqui
                            msgToastr('Erro na requisição:' + xhr, 'error');
                        }
                    });
                }
            });

            //----------Devolucao de venda -------------//
            $('#selectClienteDevolucao').on('change', function() {
                let cliente_id = $(this).val(); // Obtenha o valor selecionado
                // Pega o valor do atributo data-custom do item selecionado
                let documento = $(this).select2('data')[0].attr;
                if (documento == 0) {
                    msgToastr('O cliente não pode ser PADRÃO.', 'info');
                    return;
                }

            });
            $('#devolucaoSelect').on('change', function() {
                var vendaId = $(this).val(); // Obtenha o valor selecionado

                if (vendaId) {
                    $.ajax({
                        url: routeVoltarVenda, // URL da sua rota para lidar com a seleção
                        type: 'GET',
                        data: {
                            id: vendaId // Envie o ID da venda como parâmetro
                        },
                        success: function(response) {
                            valida_response(response);

                            removeStorage(itensVisualizarVoltarVenda);
                            removeStorage(obj_venda_devolucao);

                            let itensVenda = [];
                            response.venda_itens.forEach(element => {
                                let qtdJaDevolvida = 0;
                                if (element?.devolucao_item.length > 0) {
                                    qtdJaDevolvida = element.devolucao_item.reduce((
                                        sum,
                                        item) => {
                                        return sum + parseFloat(item
                                            .quantidade || 0);
                                    }, 0);
                                }

                                let novoObjeto = {
                                    vendaItemId: element?.id,
                                    produtoId: element.produto_id,
                                    estoqueId: element?.estoque_id,
                                    qtd: parseFloat((element?.quantidade -
                                        qtdJaDevolvida)),
                                    cod_aux: element?.estoque?.produto?.cod_aux,
                                    nome: element?.estoque?.produto?.nome +
                                        ' <b>' +
                                        element?.estoque?.produto
                                        ?.unidade_medida
                                        ?.sigla + '</b>',
                                    preco: parseFloat(element.preco),
                                    total: parseFloat(element.total),
                                    qtdJaDevolvida: qtdJaDevolvida
                                };

                                itensVenda.push(novoObjeto);

                            });

                            delete response.venda_itens;

                            // Salvar o array atualizado de volta no cookie
                            setStorage(itensDevolucao, JSON.stringify(itensVenda));
                            setStorage(obj_venda_devolucao, JSON.stringify(response));

                            montaTabelaVenda(itensDevolucao, true, 'check');

                            $('#nVenda').removeClass('d-none');
                            $('#n_venda_exibir').text(response.n_venda);
                            $('#cliente_nome_card').text(response.cliente.nome);
                            const $formasDevolucaoDiv = $('#formasDevolucao');

                            // Limpa os inputs anteriores
                            $formasDevolucaoDiv.empty();

                            response.venda_pagamentos.forEach(element => {
                                const pagamentoNome = element.especie.nome + ' ' + (
                                    element.parcela > 0 ? 'Parcela ' + element
                                    .parcela : '');
                                const pagamentoId = element.id;
                                const valor = element.valor;
                                const parcela = element.parcela;
                                let vendaPgDevolucao =
                                    0; // Use let para permitir reatribuição
                                if (element.venda_pagamento_devolucao && element
                                    .venda_pagamento_devolucao.length > 0) {
                                    vendaPgDevolucao = element
                                        .venda_pagamento_devolucao
                                        .reduce((sum, item) => sum + item.valor, 0);
                                }

                                // Cria o campo de entrada
                                const $campo = $(`
                 <div class="form-group col-md-6">
            ${vendaPgDevolucao ? `
                                                                                                                                                                                                                                                                                                                                    <span class="badge badge-danger mb-1">
                                                                                                                                                                                                                                                                                                                                        Já devolvido: R$ ${centavosParaReais(vendaPgDevolucao)}
                                                                                                                                                                                                                                                                                                                                    </span>` : ''}
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        ${pagamentoNome}: R$${centavosParaReais(valor)}
                    </span>
                </div>
                <input readonly type="text" data-devolucao-nome="${pagamentoNome}"
                       data-forma-devolucao-id="${pagamentoId}"
                       class="form-control valor-devolucao ${vendaPgDevolucao}"
                       placeholder="R$ 0,00"
                       id="valor-devolucao-${pagamentoId}"
                       name="valorDevolucao[${pagamentoId}]"
                       data-devolucao-valor="${valor}"
                       data-venda-pg-devolucao="${vendaPgDevolucao}"
                       data-devolucao-parcela="${parcela}">
            </div>
        </div>
            `);
                                $formasDevolucaoDiv.append($campo);
                            });

                            // $('.devolucao-checkbox').attr('checked', $(
                            //     '#selectAllDevolucaoItens').is(':checked'));

                            if ($('#selectAllDevolucaoItens').is(':checked')) {
                                $('#selectAllDevolucaoItens').trigger('click').trigger(
                                    'click');
                            }
                        },
                        error: function(xhr) {
                            // Manipule erros aqui
                            msgToastr('Erro na requisição:' + xhr, 'error');
                        }
                    });
                }
            });
            // Input de marcação de itens para devolução
            $('#selectAllDevolucaoItens').on('click', function() {
                // Verifica se o checkbox "Selecionar Todos" está marcado
                let isChecked = $('#selectAllDevolucaoItens').is(':checked');

                // Marca ou desmarca todos os checkboxes de devolução
                $('.devolucao-checkbox:not([disabled])').prop('checked', isChecked);
                // Habilita ou desabilita os campos de quantidade associados
                $('.devolucao-checkbox').each(function() {
                    const $currentRow = $(this).closest(
                        'tr'); // Seleciona a linha (<tr>) do checkbox atual
                    const $qtdInput = $currentRow.find(
                        '.item-qtd-devolucao'
                    ); // Seleciona o input de quantidade correspondente
                    const maxQtd = converteParaFloat($qtdInput.attr(
                        'data-max-qtd'
                    )); // Pega o valor máximo permitido do atributo 'data-max-qtd'

                    if ($(this).is(':checked') && !$(this).is('[disabled]')) {
                        $qtdInput.prop('readonly',
                            false
                        ); // Remove o atributo readonly se "Selecionar Todos" estiver marcado
                    } else {
                        $qtdInput.prop('readonly',
                            true
                        ); // Adiciona o atributo readonly se "Selecionar Todos" estiver desmarcado
                        $qtdInput.val(maxQtd); // Reseta o valor do campo para 1
                    }
                    calculaValorDevolucao();

                });
            });

            $(document).on('change', '.devolucao-checkbox', function() {
                maskQtdByClass('item-qtd-devolucao');
                const allChecked = $('.devolucao-checkbox').length === $('.devolucao-checkbox:checked')
                    .length;
                // Marca ou desmarca o checkbox "Selecionar Todos" com base no estado dos checkboxes individuais
                $('#selectAllDevolucaoItens').prop('checked', allChecked);

                const $currentRow = $(this).closest(
                    'tr'); // Seleciona a linha (<tr>) onde o checkbox foi alterado
                const $qtdInput = $currentRow.find(
                    '.item-qtd-devolucao'); // Seleciona o input de quantidade na mesma linha

                const maxQtd = converteParaFloat($qtdInput.attr(
                    'data-max-qtd')); // Pega o valor máximo permitido do atributo 'data-max-qtd'

                const produtoPreco = converteParaFloat($qtdInput.attr(
                    'data-preco')); // Pega o valor máximo permitido do atributo 'data-max-qtd'

                if ($(this).is(':checked')) {
                    $qtdInput.prop('readonly', false); // Habilita o campo de quantidade
                } else {
                    $qtdInput.prop('readonly', true); // Desabilita o campo de quantidade
                    $qtdInput.val(maxQtd); // Reseta o valor do input para 1, se necessário
                }

                $qtdInput.on('change', function() {
                    const qtdValue = $(this).val() == '' || $(this).val() == 0 ? 0 :
                        converteParaFloat($(this).val()); // Valor atual do input

                    if (qtdValue == 0 || qtdValue == '') {
                        $(this).val(
                            maxQtd
                        );
                        msgToastr('A quantidade não pode ser 0', 'warning');
                        // return;
                    }
                    if (qtdValue > maxQtd) {
                        $(this).val(
                            maxQtd
                        ); // Se o valor ultrapassar o máximo, define o valor para o máximo
                        msgToastr('A quantidade não pode ser maior que ' + maxQtd, 'warning');
                        // return;
                    }

                    // if (!Number.isInteger(qtdValue)) {
                    //     $(this).val(qtdValue); // Arredonda para baixo para evitar valores decimais
                    //     msgToastr('A quantidade deve ser um número inteiro', 'warning');
                    //     return;
                    // }
                });
                calculaValorDevolucao();

            });
            // Adiciona eventos aos inputs e checkboxes
            $(document).on('change input', '.devolucao-checkbox, .item-qtd-devolucao', function() {
                calculaValorDevolucao();
            });

            //-------------------------------Pagamentos-------------------------------------//
            $('#recebimentoSelect').on('change', function() {
                let cliente_id = $(this).val(); // Obtenha o valor selecionado

                if (cliente_id) {
                    $.ajax({
                        url: routeGetPagamentoCliente, // URL da sua rota para lidar com a seleção
                        type: 'GET',
                        data: {
                            id: cliente_id // Envie o ID da venda como parâmetro
                        },
                        success: function(response) {
                            valida_response(response);
                            $('#divValorRecebidoDinamico').empty();
                            $('#formaPagamentoRecebimento').attr('disabled', false);
                            manipulaCardOperador('div-tabela-recebimento');
                            let statusPago = @json(config('config.status.pago'));
                            let tabelaBody = $('#tabela-recebimento tbody')
                            tabelaBody.empty();
                            response.forEach((item, index) => {
                                let valorDevolucao = item.venda_pagamento_devolucao
                                    ?.reduce((sum, item) => sum + item.valor, 0);

                                let pago = centavosParaReais(item.valor_pago);
                                let total = centavosParaReais(item.valor);
                                let pendente = (item.valor - valorDevolucao) - item
                                    .valor_pago;
                                let maxReceber = pendente;
                                pendente = centavosParaReais(pendente);
                                // Verifica se houve devolução
                                let badgeDevolucao = valorDevolucao > 0 ?
                                    `<span class="badge badge-danger">Devolução: R$${centavosParaReais(valorDevolucao)}</span>` :
                                    '';

                                let linha = `
        <tr>
            <td>${item.venda.n_venda}</td>
            <td >${aplicarMascaraDataHora(item.venda.data_concluida)}</td>
<td class="d-flex align-items-center">
    <input type="checkbox" class="habilitar-input-recebimento mx-2" ${item.status_id == statusPago ? 'disabled' : '' } data-status-atual="${item.status_id}" data-id="${item.id}" style="transform: scale(1.5);">
    <input type="text" disabled class="form-control form-control-sm valor-pagar-recebimento"
           data-id="${item.id}" data-valor-pendente="${pendente}" data-max-receber="${maxReceber}" value="${item.status_id == statusPago ? 0 : pendente}" style="max-width: 100px;">
</td>
            <td>${item.forma.descricao}</td>
            <td>${item.parcela}</td>
            <td>R$ ${pendente}</td>
            <td>R$ ${pago} ${badgeDevolucao}</td>
            <td>R$ ${total}</td>
        </tr>
    `;
                                tabelaBody.append(linha);
                            });
                            $('#nVenda').removeClass('d-none');
                            $('#n_venda_exibir').text(response[0].venda.n_venda);
                            $('#cliente_nome_card').text(response[0].venda.cliente.nome);
                            maskDinheiroByClass('valor-pagar-recebimento');

                            if ($('#selectAllRecebimento').is(':checked')) {
                                $('#selectAllRecebimento').trigger('click').trigger(
                                    'click');
                            }
                            calculaValoresRecebimento();
                        },
                        error: function(xhr) {
                            // Manipule erros aqui
                            msgToastr('Erro na requisição:' + xhr, 'error');
                        }
                    });
                }
            });

            //evento do checkbox para habilitar inputs
            $(document).on('change', '.habilitar-input-recebimento', function() {
                // Pegando o id do item
                const id = $(this).data('id');

                // Pegue o status atual para ações adicionais, se necessário
                const statusAtual = $(this).data('status-atual');

                // Encontrar o input relacionado e habilitar/desabilitar
                const input = $(`[data-id="${id}"]`).closest('td').find('.valor-pagar-recebimento');

                if ($(this).prop('checked')) {
                    // Habilitar o input se o checkbox for marcado
                    input.prop('disabled', false);
                } else {
                    // Desabilitar o input se o checkbox for desmarcado
                    input.prop('disabled', true);
                    input.val(input.data('valor-pendente'));
                }

                // Verificar se todos os checkboxes estão marcados
                const allChecked = $('.habilitar-input-recebimento').length === $(
                    '.habilitar-input-recebimento:checked').length;

                // Atualizar o estado do "Selecionar Todos"
                $('#selectAllRecebimento').prop('checked', allChecked);

                calculaValoresRecebimento();
            });

            // Usando o evento 'change' para validar quando o usuário sair do campo
            $(document).on('change', '.valor-pagar-recebimento', function() {
                const valorDigitado = $(this).val() == '' ? 0 : floatParaCentavos(converteParaFloat($(
                        this)
                    .val())); // Pega o valor digitado no input
                const valorMaximo = $(this).data('max-receber'); // Pega o valor digitado no input
                const id = $(this).data('id'); // Pega o id associado ao input

                if (valorDigitado == 0) {
                    msgToastr('Informe um valor válido.', 'info');
                    $(this).focus();
                    calculaValoresRecebimento();
                    return

                }
                if (valorDigitado > valorMaximo) {
                    msgToastr('Valor superior ao valor pendente.', 'info');
                    $(this).val($(this).data('valor-pendente'));
                    $(this).focus();
                    calculaValoresRecebimento();
                    return
                }
                calculaValoresRecebimento();

            });
            //selecionar todos os recebimentos
            $('#selectAllRecebimento').on('click', function() {
                // Verifica se o checkbox "Selecionar Todos" está marcado
                let isChecked = $(this).is(':checked');

                // Percorre todos os checkboxes de recebimento e marca/desmarca conforme o estado do "Selecionar Todos"
                // Simula o clique em cada checkbox de recebimento para habilitar/desabilitar
                $('.habilitar-input-recebimento').each(function() {
                    $(this).prop('checked', isChecked).trigger(
                        'change'); // Marca/desmarca o checkbox e dispara o evento 'change'
                });
                calculaValoresRecebimento();

            });
            //parte de seleção de formas de pagamento com inputs de recebimento dinamico
            //ao selecionar forma de pagamento coloca o focus no cliente
            $('#formaPagamentoRecebimento').on('select2:select select2:unselect', function() {
                let selectedOptions = $(this).find(':selected');
                let contem_parcela = false;
                let afeta_troco = false;
                // Verifica se alguma das opções selecionadas afeta o troco
                selectedOptions.each(function() {
                    if ($(this).data('afeta-troco')) {
                        afeta_troco = true;
                    }
                    if ($(this).data('contem-parcela')) {
                        contem_parcela = true;
                    }
                });
                //quando é selecionado mais de uma forma de pagamento
                let container = $('#divValorRecebidoDinamico');

                // Limpa os inputs antes de recriar, para garantir que não haja duplicações
                container.empty();
                if (selectedOptions.length >= 1) {
                    selectedOptions.each(function() {
                        let pagamentoId = $(this).val(); // Pode ser o ID da forma de pagamento
                        let pagamentoNome = $(this).text(); // Nome da forma de pagamento
                        let inputGroup = '';
                        if ($(this).data('afeta-troco')) {
                            inputGroup += `
                                            <div class="input-group mb-2 col-md-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Recebido em dinheiro</span>
                                                </div>
                                                <input data-pagamento-id="0" type="text" data-pagamento-nome="Pago em dinheiro"
                                                       class="form-control"
                                                       placeholder="R$ 0,00"
                                                       id="valor-recebido-recebimento-dinheiro"
                                                       name="valor-pago-dinheiro">
                                            </div>`;

                        }

                        if (selectedOptions.length > 1) {
                            inputGroup += `
                                            <div class="input-group mb-2 col-md-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">${pagamentoNome}</span>
                                                </div>
                                                <input type="text" data-pagamento-nome="${pagamentoNome}"
                                                data-pagamento-id="${pagamentoId}"
                                                data-contem-parcela="${$(this).data('contem-parcela')}"
                                                data-afeta-troco="${$(this).data('afeta-troco')}"
                                                       class="form-control valor-recebido-recebimento"
                                                       placeholder="R$ 0,00"
                                                       id="valor-recebido-recebimento-${pagamentoId}"
                                                       name="valorPago[${pagamentoId}]">
                                            </div>`;
                        } else if ($(this).data('afeta-troco') && selectedOptions.length == 1) {
                            inputGroup += `
                                            <div class="input-group mb-2 col-md-6">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">${pagamentoNome}</span>
                                                </div>
                                                <input type="text" data-pagamento-nome="${pagamentoNome}"
                                                data-pagamento-id="${pagamentoId}"
                                                data-contem-parcela="${$(this).data('contem-parcela')}"
                                                data-afeta-troco="${$(this).data('afeta-troco')}"
                                                       class="form-control valor-recebido-recebimento"
                                                       placeholder="R$ 0,00"
                                                       id="valor-recebido-recebimento-${pagamentoId}"
                                                       name="valorPago[${pagamentoId}]">
                                            </div>`;
                        }




                        // Adiciona o novo input ao container
                        container.append(inputGroup);
                        maskDinheiro(`valor-recebido-recebimento-${pagamentoId}`);
                        calculaValoresRecebimento();
                    });
                }

                if (afeta_troco) {
                    maskDinheiro(`valor-recebido-recebimento-dinheiro`);
                }
                // Adiciona input de parcelas se for necessário
                if (contem_parcela) {
                    let parcelasInput = `
                                <div class="input-group mb-2 col-md-6">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Parcelas</span>
                                    </div>
                                    <input type="number" class="form-control"
                                           id="numero-parcelas-recebimento"
                                           name="numero_parcelas"
                                           placeholder="Quantidade de Parcelas"
                                           value="1"
                                           min="1"
                                           step="1"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                </div>`;
                    container.append(parcelasInput);
                }

            });
            //---------------------------Calcula valores ao preencher inputs de forma de pagamento--------//
            // Evento para calcular o total de valores recebidos
            $(document).on('input', '.valor-recebido-recebimento', function() {
                calculaValoresRecebimento()

            });
            $(document).on('input', '#valor-recebido-recebimento-dinheiro', function() {
                calculaValoresRecebimento()
            });

            //--------------------------------Scaner codigo de barras-----------------------//
            //celular
            const codeReader = new ZXing.BrowserMultiFormatReader();
            let canShowError = true; // Variável de controle para o intervalo de mensagens

            $('#start-scan').on('click', function() {
                // Alternar visibilidade da div
                if ($('#divScanerMobile').hasClass('d-none')) {
                    $('#divScanerMobile').removeClass('d-none');

                    // Iniciar leitura de código de barras
                    codeReader.decodeFromVideoDevice(null, 'barcode-scanner', (result, err) => {
                        if (result) {
                            var code = result.text;
                            $('#buscaProdutos').val(code);
                            leitorBr = true;
                            $('#beepSound')[0].play();
                            // Se quiser parar imediatamente após a leitura
                            codeReader.reset();
                            msgToastr('Codigo de barras capturado.', 'success')
                            $('#divScanerMobile').addClass('d-none');
                            $('#buscaProdutos').trigger('input');
                        }
                        // Detectar erros durante a leitura
                        if (err && canShowError) {
                            if (err instanceof ZXing.NotFoundException) {
                                msgToastr(
                                    'Nenhum código de barras encontrado. Verifique se o código está visível e corretamente alinhado.',
                                    'info');
                            } else if (err instanceof ZXing.ChecksumException) {
                                msgToastr(
                                    'Erro na leitura do código. O código de barras pode estar danificado ou ilegível.',
                                    'error');
                            } else if (err instanceof ZXing.FormatException) {
                                msgToastr('Formato do código de barras inválido.', 'error');
                            } else {
                                // Mensagem geral para outros erros, como ambiente escuro ou outros problemas desconhecidos
                                msgToastr(
                                    'Problema na leitura do código de barras. Verifique se o ambiente está bem iluminado e o código está alinhado.',
                                    'info');
                            }

                            canShowError = false;
                            setTimeout(() => {
                                canShowError =
                                    true; // Reativar a exibição de mensagens de erro após 10 segundos
                            }, 10000);
                        }
                    });


                } else {
                    // Parar leitura de código de barras
                    codeReader.reset();
                    $('#divScanerMobile').addClass('d-none');

                }
            });

            //-----------------------------------teclas de atalho-----------------------//
            $(document).on('keydown', function(event) {

                //verificação de tecla de atalho com shift pressionado
                if (event.shiftKey) {
                    switch (event.key) {
                        case 'F':
                            finalizarVenda()
                            event.preventDefault();
                            break;
                        case 'N':
                            novaVenda()
                            event.preventDefault();
                            break;
                        case 'R':
                            recebimento()
                            event.preventDefault();
                            break;
                        case 'B':
                            voltarVenda()
                            event.preventDefault();
                            break;
                        case 'C':
                            cancelarVenda()
                            event.preventDefault();
                            break;
                        case 'D':
                            darDesconto()
                            event.preventDefault();
                            break;
                        case 'S':
                            salvarVendaCaixa()
                            event.preventDefault();
                            break;
                        case 'X':
                            devolucao()
                            event.preventDefault();
                            break;
                        case 'I':
                            voltarInicio()
                            event.preventDefault();
                            break;
                    }
                }
            });
        });
    </script>
@endsection
