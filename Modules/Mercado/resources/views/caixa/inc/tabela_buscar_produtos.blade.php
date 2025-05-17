<div class="card" id="card-principal" style="height: {{ $mobile ? '60' : '47' }}vh;">
    {{-- <div class="header-card">
    </div> --}}
    <div class="card-body p-0" id="div-principal-produtos-busca-tabela" style="overflow-y: auto; position: relative;">
        <!-- Tabela de Produtos -->
        <div id="div-produtos-busca-tabela" class="d-none" style="height: 100%;">
            <table id="produtos-busca-tabela" class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Produto</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Os produtos serão inseridos aqui -->
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="text-center d-none" id="relogio" style="height: 100%;">
                <div class="clock-container">
                    <div id="clock" class="clock"></div>
                    <div id="date" class="date"></div>
                    <div id="weather" class="mt-4"></div> <!-- Previsão do tempo -->
                </div>
            </div>
        </div>
        <!-- Mensagem de Produto Não Encontrado -->

        <div id="produtos-busca-tabela-nao-encontrato" class="alert alert-warning text-center d-none mt-5"
            role="alert">
            <h4>Nenhum produto encontrado!</h4>
        </div>


        <!-- Imagem de Carregamento -->
        <div class="d-flex justify-content-center align-items-center">
            <img id="carregando" src="{{ asset('img/loading4-unscreen.gif') }}" alt="Loading..." width="35%">

        </div>

        {{-- Devolução --}}
        {{-- <div class="d-flex justify-content-center align-items-center p-3"> --}}
        <div id="divDevolucao" class="d-none col-md-12 mt-3" style="height: 100%;">
            <!-- Instruções -->
            <div class="text-center mb-1">
                <h5 class="text-dark" style="font-weight: 900;"><u>DEVOLUÇÃO</u></h5>
            </div>
            <div class="mb-1">
                <small class="d-flex align-items-center mb-3" style="font-size: 1.1rem;">
                    <span class="badge bg-warning text-dark d-flex align-items-start text-wrap"
                        style="white-space: normal;">
                        <i class="bi bi-exclamation-triangle-fill text-white me-2" style="font-size: 1.2rem;"></i>
                        Devoluções são permitidas apenas dentro do prazo de 8 dias após a compra. Para compras
                        com descontos aplicados, o cálculo será realizado com base na quantidade dos itens. <br>
                        A quantidade devolvida será automaticamente inserida no estoque da loja vinculada ao caixa
                        atual.
                    </span>
                </small>
            </div>

            <!-- Venda -->
            <div class="mb-4">
                <label for="devolucaoSelect" class="form-label"><strong>Venda: *</strong></label>
                <select required id="devolucaoSelect" name="devolucaoSelect" class="form-control select2"></select>
            </div>

            <!-- Cliente -->
            <div class="mb-4 d-none" id="divSelectClienteDevolucao">
                <label for="selectClienteDevolucao" class="form-label"><strong>Cliente: *</strong></label>
                <select required id="selectClienteDevolucao" name="selectClienteDevolucao"
                    class="form-control select2"></select>
            </div>

            <div id="avisoTiposPagamentoVenda" class="mb-2 d-none">
                <small class="d-flex align-items-center mb-3" style="font-size: 1.2rem;">
                    <span class="badge bg-info text-dark d-flex align-items-start text-wrap"
                        style="white-space: normal;">
                        <i class="bi bi-info-circle-fill text-white me-2" style="font-size: 1.2rem;"></i>
                    </span>
                </small>
            </div>
            <!-- Forma de devolução -->
            <div class="mb-3">
                <label for="selectFormaDevolucao" class="form-label d-flex align-items-center">
                    <strong>Forma de devolução: *</strong>
                </label>
                <span class="badge bg-info text-white d-flex align-items-center mb-2" style="font-size: 0.8rem;">
                    <i class="bi bi-info-circle-fill"
                        title="A devolução será realizada gradativamente entre as formas de pagamento utilizadas na venda."></i>
                    &nbsp; A devolução será realizada de forma gradativa entre as formas de pagamento utilizadas na
                    venda.
                </span>


                <div id="formasDevolucao" class="row d-flex flex-wrap">

                </div>

                {{-- <select required id="selectFormaDevolucao" name="selectFormaDevolucao" class="form-control select2">
                        @foreach ($formaDevolucoes as $fd)
                            @if ($fd->id == config('config.especie_pagamento.credito_loja.id'))
                                <option disabled value="{{ $fd->id }}">
                                    {{ $fd->nome }} / Em breve
                                </option>
                            @else
                                <option value="{{ $fd->id }}">
                                    {{ $fd->nome }}
                                </option>
                            @endif
                        @endforeach
                    </select> --}}
            </div>
            <div id="avisoTipoDevolucao" class="mb-2 d-none">
                <small class="d-flex align-items-center mb-3" style="font-size: 1.2rem;">
                    <span class="badge bg-primary text-light d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-white me-2" style="font-size: 1.2rem;"></i>
                    </span>
                </small>
            </div>

            <!-- Selecionar Todos -->
            <div class="form-check d-flex justify-content-between mb-3">
                <div>
                    <input class="form-check-input" type="checkbox" id="selectAllDevolucaoItens" autocomplete="off"
                        style="width: 15px; height: 15px;">
                    <label class="form-check-label" for="selectAllDevolucaoItens">
                        <strong> Selecionar Todos</strong>

                    </label>
                </div>
            </div>
            <!-- Motivo -->
            <div class="">
                <label for="motivoDevolucao" class="form-label"><strong>Motivo:</strong></label>
                <textarea name="motivoDevolucao" id="motivoDevolucao" cols="30" rows="3" class="form-control"></textarea>
            </div>

            <!-- Botões de Devolução fixados no fundo -->
            <div class="sticky-footer">
                <div class="row mt-3 p-3">
                    <div class="col d-flex justify-content-start">
                        <button onclick="voltarDevolucao()" type="button" class="btn btn-outline-danger mx-1">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </button>
                        <button onclick="confirmarItensDevolucao()" id="confirmarItensDevolucaoButton" type="button"
                            class="btn btn-primary mx-1">
                            <i class="bi bi-check-lg"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}

        {{-- Recebimento --}}
        {{-- <div class="d-flex justify-content-center align-items-center p-3"> --}}
        <div id="divRecebimento" class="d-none col-md-12 mt-3" style="height: 100%;">
            <!-- Instruções -->
            <div class="text-center mb-1">
                <h5 class="text-dark" style="font-weight: 900;"><u>RECEBIMENTO</u></h5>
            </div>
            {{-- <div class="mb-1">
                    <small class="d-flex align-items-center mb-3" style="font-size: 1.1rem;">
                        <span class="badge bg-warning text-dark d-flex align-items-start text-wrap"
                            style="white-space: normal;">
                            <i class="bi bi-exclamation-triangle-fill text-white me-2" style="font-size: 1.2rem;"></i>
                            Atenção ao realizar o recebimento certifiquese
                        </span>
                    </small>
                </div> --}}

            <!-- Cliente -->
            <div class="mb-3">
                <label for="recebimentoSelect" class="form-label"><strong>Cliente: *</strong></label>
                <select required id="recebimentoSelect" name="recebimentoSelect"
                    class="form-control select2"></select>
            </div>
       <!-- Selecionar Todos -->
       <div class="form-check d-flex justify-content-between mb-3">
        <div>
            <input class="form-check-input" type="checkbox" id="selectAllRecebimento" autocomplete="off"
                style="width: 15px; height: 15px;">
            <label class="form-check-label" for="selectAllRecebimento">
                <strong> Selecionar Todas</strong>

            </label>
        </div>
    </div>
            <!-- Forma de Pagamento -->
            <div class="mb-3">
                <label for="formaPagamentoRecebimento" class="form-label"><strong>Forma de Pagamento: *</strong></label>
                <select multiple required id="formaPagamentoRecebimento" name="formaPagamentoRecebimento"
                    class="form-control select2">
                    @foreach ($formaPagamentos as $f)
                        @if (!$f->especie->credito_loja)
                            <option data-afeta-troco="{{ $f->especie->afeta_troco }}"
                                data-contem-parcela="{{ $f->especie->contem_parcela }}"
                                data-credito-loja="{{ $f->especie->credito_loja }}" value="{{ $f->id }}">
                                {{ $f->descricao }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div id="divValorRecebidoDinamico" class="d-flex flex-wrap">
                <!-- Os inputs dinâmicos serão adicionados aqui -->
            </div>
            


            <!-- Botões de Devolução fixados no fundo -->
            <div class="sticky-footer">
                <div class="row mt-3 p-3">
                    <div class="col d-flex justify-content-start">
                        <button onclick="voltarRecebimento()" type="button" class="btn btn-outline-danger mx-1">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </button>
                        <button onclick="confirmarRecebimento()" type="button" class="btn btn-primary mx-1">
                            <i class="bi bi-check-lg"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}

        <!-- Div de Finalizar Venda -->
        <div class="d-flex justify-content-center align-items-center p-3">
            <div id="div-finalizar-venda" class="d-none col-md-12">
                <div class="text-center mb-1">
                    <h5 class="text-dark" style="font-weight: 900;"><u>FINALIZAR</u></h5>
                </div>
                <div class="row"> <!-- Adicionei a classe row para alinhar os inputs -->

                    <!-- Forma de Pagamento -->
                    <div class="form-group col-md-4">
                        <label for="formaPagamento">Forma de Pagamento: *</label><br>
                        <select multiple required id="formaPagamento" name="formaPagamento"
                            class="form-control select2">
                            @foreach ($formaPagamentos as $f)
                                <option data-afeta-troco="{{ $f->especie->afeta_troco }}"
                                    data-contem-parcela="{{ $f->especie->contem_parcela }}"
                                    data-credito-loja="{{ $f->especie->credito_loja }}" value="{{ $f->id }}">
                                    {{ $f->descricao }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cliente -->
                    <div class="form-group col-md-8">
                        <label for="buscarClienteFinalizarVenda">Cliente: <small>(Opcional)</small></label>
                        <select id="buscarClienteFinalizarVenda" name="buscarClienteFinalizarVenda"
                            class="form-control select2"></select>
                    </div>

                    <!-- Desconto -->
                    <div class="form-group col-md-6">
                        <label for="desconto-digitado">Desconto: <small>(Valor em %)</small></label>
                        <input type="text" id="desconto-digitado" name="desconto-digitado" class="form-control"
                            autocomplete="off" placeholder="Digite o desconto">
                    </div>

                    <!-- Valor Recebido -->
                    <div id="divValorPago" class="form-group col-md-6">
                        <label for="valor-recebido">Valor recebido: <small>Em dinheiro</small></label>
                        <div class="input-group" autocomplete="off">
                            <input required type="text" id="valor-recebido" name="valorPago" class="form-control"
                                placeholder="R$ 0,00" autocomplete="off">
                        </div>
                    </div>
                    <div id="divValorPagoDinamico" class="d-flex flex-wrap col-md-12">
                        <!-- Os inputs dinâmicos serão adicionados aqui -->
                    </div>

                </div>

                <!-- Botões e Quantidade Restante -->
                <div class="sticky-footer">
                    <div class="row mt-3 p-3">
                        <!-- Botões -->
                        <div class="col d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" onclick="manipulaCardPrincipal()"
                                    class="btn btn-outline-danger mx-1">
                                    <i class="bi bi-arrow-left"></i> Sair
                                </button>
                                <button id="botaoFinalizarVenda" onclick="finalizarVendaPost()" type="button"
                                    class="btn btn-primary mx-1">
                                    <i class="bi bi-check"></i> Finalizar venda
                                </button>
                            </div>

                            <!-- Quantidade Restante -->
                            {{-- <div>
                                <h3 id="quantidadePagamentoRestante" class="text-primary mb-0">

                                </h3>
                            </div> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    .sticky-footer {
        position: sticky;
        bottom: 0;
        margin-top: 10%;
        left: 0;
        right: 0;
        z-index: 10;
        background-color: white;
        /* padding: 1rem; */
        /* border-top: 1px solid #ddd; */
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Ajuste para o card, garantindo que ele tenha altura suficiente */
    #divDevolucao,
    #divRecebimento,
    #div-finalizar-venda {
        position: relative;
        height: 100%;
        /* Garante que o card ocupe a altura total */
        display: flex;
        flex-direction: column;
    }

    /* Ajusta para que o conteúdo do card ocupe a altura restante, se necessário */
    /* #divDevolucao .d-flex, #divRecebimento .d-flex,
    #div-finalizar-venda .d-flex {
        flex-grow: 1;
    } */
</style>
