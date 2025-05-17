@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.recebimento.index'), 'titulo' => 'Recebimentos'], ['titulo' => 'Receber']]])

@section('content')
    <style>
        .nav-link.active {
            background-color: #007bff !important;
            /* Cor primária do Bootstrap */
            color: #fff !important;
            /* Texto branco */
        }
    </style>
    <div class="cabecalho">
        <div class="page-header">
            <h3>Recebimento de NF</h3>
            <p class="lead">
                Nesta tela, você pode gerenciar o recebimento de notas fiscais (NF). É possível escanear ou inserir
                manualmente a chave da nota. Após isso, basta conferir os dados e prosseguir. </p>

        </div>
    </div>

    <div class="card card-body">
        <h5>Forma de Recebimento</h5>
        <span>* Ao realizar o recebimento por aqui, será gerado um pedido automaticamente, para fins de auditoria.</span>
        <br>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active custom-tab" id="nav-manual-tab" data-toggle="tab" data-target="#nav-manual"
                    type="button" role="tab" aria-controls="nav-manual" aria-selected="true">Manualmente</button>
                <button class="nav-link custom-tab" id="nav-nota-tab" data-toggle="tab" data-target="#nav-nota"
                    type="button" role="tab" aria-controls="nav-nota" aria-selected="false">Scanear</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- Aba Recebimento Manual -->
            <div class="tab-pane fade show active mt-3" id="nav-manual" role="tabpanel" aria-labelledby="nav-manual-tab">
                <!-- Conteúdo para o recebimento manual -->

                <!-- Formulário de consulta da Nota Fiscal -->
                <form>
                    <label for="campo-nf" class="mr-2">Número (chave) da Nota Fiscal:</label>
                    <div class="form-group d-flex align-items-center">
                        <input type="text" class="form-control" id="campo-nf" placeholder="Digite o número da NF">
                        <button onclick="chamaConsultaNota()" type="button"
                            class="btn btn-dark ml-2 d-flex align-items-center">
                            <i class="bi bi-search mr-1"></i> Buscar
                        </button>
                    </div>
                </form>

                <!-- Informações da Nota -->
                <div id="informacoes-nota" class="row mt-4 d-none">
                    <div class="col-12">
                        <h5 class="mt-3">Informações da Nota</h5>
                        <div class="d-flex justify-content-start">
                            <div class="px-3 text-center">
                                <strong>Loja:</strong> {{ auth()->user()->getUserModulo->loja->nome ?? '' }}
                            </div>
                            <div class="px-3 text-center">
                                <strong id="fornecedor-text">Fornecedor:</strong>
                                <span id="fornecedor-info"></span> <!-- Aqui será preenchido com o nome do fornecedor -->
                            </div>
                            <div class="px-3 text-center">
                                <strong id="cnpj-text">CNPJ: </strong>
                                <span id="cnpj-info"></span> <!-- Aqui será preenchido com o nome do fornecedor -->
                            </div>   <div class="px-3 text-center">
                                <strong id="frete-text">Frete: </strong>
                                <span id="frete-info"></span> <!-- Aqui será preenchido com o nome do fornecedor -->
                            </div>
                            <div class="px-3 text-center">
                                <strong>Total:</strong>
                                <span id="total-info"></span> <!-- Aqui será preenchido com o nome do fornecedor -->
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Listagem de Itens da Nota -->
                <div class="row mt-5 d-none" id="div-tabela-item-receber">
                    <div class="col-12">
                        <h5 class="mt-3">Listagem de Itens da Nota</h5>
                        <table id="tabela-item-receber-nota" class="table table-bordered mt-5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 30%;">Item</th>
                                    <th>Qtd Solicitada</th>
                                    <th>Qtd a Receber</th>
                                    <th>Preço</th>
                                    <th>Total</th>
                                    <th>Validade</th>
                                    <th>Lote</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- Aba Por Nota Fiscal -->
            <div class="tab-pane fade" id="nav-nota" role="tabpanel" aria-labelledby="nav-nota-tab">
                <!-- Conteúdo para o recebimento por NF -->
                <form>
                    <div class="form-group">

                        <div class="justify-content-start t3-2 mb-3 mt-3">
                            <div>
                                <!-- Explicação do botão Gerar QR -->
                                <label for="">Gera um QR Code para acessar a área de escaneamento da nota utilizando
                                    o smartphone.</label>
                                <button onclick="modalQR()" type="button"
                                    class="btn btn-success d-flex align-items-center">
                                    <i class="bi bi-qr-code mr-2"></i>QR Code
                                </button>
                            </div>
                            <div class="mt-2">
                                <!-- Explicação do botão Scanear -->
                                <label for="">Realiza a leitura da nota fiscal utilizando a câmera deste
                                    dispositivo.</label>
                                <button onclick="modalScanear()" type="button"
                                    class="btn btn-dark d-flex align-items-center">
                                    <i class="bi bi-camera mr-2"></i> Scanear
                                </button>
                            </div>
                        </div>


                        <label for="campo-nf-capturado">Número (chave) da Nota Fiscal Capturada:</label>
                        <input type="text" readonly class="form-control mb-3" id="campo-nf-capturado"
                            placeholder="Aguardando escaneamento...">
                    </div>
                </form>

            </div>


            <form id="receberPedido" action="{{ route('estoque.recebimento.receber.nf') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="chave_nota" name="chave_nota">

                <div class="row d-flex">

                    <div class="col-md-4 mt-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmacaoCaixa" required
                                style="transform: scale(1.5);">
                            <label class="form-check-label" for="confirmacaoCaixa">
                                Declaro, para os devidos fins, que estou ciente de que os itens recebidos estão em
                                conformidade
                                com o esperado.
                            </label>
                        </div>
                    </div>

                    <!-- Campo de Data -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dataRecebimento">Data de Recebimento <span class="text-danger">*</span></label>

                            <input type="date" id="dataRecebimento" name="dataRecebimento" class="form-control"
                                required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">


                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col text-end">
                        <a href="{{ route('cadastro.recebimento.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button id="submitButton" type="submit" disabled class="btn btn-success mx-2">
                            <i class="bi bi-floppy"></i> Salvar
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @include('mercado::estoque.recebimento.inc.modalQrCode')

    <script>
        var route = @json(route('consulta.nf'));
        montaDatatable('tabela-item-receber-nota');
        maskDinheiroByClass('qtd_dinheiro');
        $('#campo-nf').val('87878774456468954646464654654546545446579312');
        chamaConsultaNota()

        function chamaConsultaNota() {
            let chave = $('#campo-nf').val();
            consultanNF(chave, route).done(function(response) {
                if (response.success == false) {
                    msgToastr(response.msg, 'info');
                    $('#submitButton').attr('disabled', true);
                    $('#chave_nota-nf').val('');

                } else {
                    msgToastr(response.msg, 'success');

                    $('#chave_nota').val(chave);

                    let nota = JSON.parse(response.data.nota);
                    $('#div-tabela-item-receber').removeClass('d-none');
                    $('#informacoes-nota').removeClass('d-none');
                    $('#submitButton').attr('disabled', false);
                    const tabela = $('#tabela-item-receber-nota tbody');
                    const itens = nota.nfeProc.NFe.infNFe.det;
                    const fornecedor = nota.nfeProc?.NFe?.infNFe?.emit?.xFant;

                    const cnpj = aplicarMascaraDocumento(nota.nfeProc.NFe.infNFe.emit.CNPJ);
                    const frete = nota.nfeProc?.NFe?.infNFe?.total?.ICMSTot?.vFrete ?? 0;
                    const totalNF = nota.nfeProc?.NFe?.infNFe?.total?.ICMSTot?.vNF;

                    // Preenche os campos de Fornecedor e Total
                    $('#fornecedor-info').text(fornecedor);
                    $('#cnpj-info').text(cnpj);
                    $('#frete-info').text('R$ '+centavosParaReais(floatParaCentavos(frete)));

                    $('#total-info').text(" R$ " + centavosParaReais(floatParaCentavos(totalNF)));
                    // Acumula todas as linhas da tabela
                    let linhas = '';

                    itens.forEach((item) => {
                        const total = (item.prod.qCom * item.prod.vUnCom);
                        const rastro = item?.prod?.rastro?.[0] ?? {};
                        const lote = rastro?.nLote ?? 'N/A';
                        const validade = rastro?.dVal ? aplicarMascaraData(rastro?.dVal) : 'N/A';
                        const tr = `
        <tr>
            <td>${item.nItem}</td>
            <td>${item.prod.xProd}</td>
            <td>${item.prod.qCom}</td>
            <td><input type="number" class="form-control qtd_dinheiro" value="${item.prod.qCom}" min="0" readonly></td>
            <td>R$ ${centavosParaReais(floatParaCentavos(item.prod.vProd))}</td>
            <td>R$ ${centavosParaReais(floatParaCentavos(total))}</td>
            <td>${validade}</td>
            <td>${lote}</td>
        </tr>
    `;

                        // Acumula a linha no HTML
                        linhas += tr;
                    });

                    // Adiciona todas as linhas acumuladas ao tbody da tabela
                    tabela.html(linhas);

                }

            });
        }
    </script>
@endsection
