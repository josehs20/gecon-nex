<div class="card" style="height: {{ $mobile ? '40' : '26' }}vh;">
    <div class="header-card d-flex justify-content-between align-items-center">
        <h4>CLIENTE: <span><u id="cliente_nome_card">Não informado</u></span></h4>
        <h4 id="nVenda" class="">Nº Venda: <span> <u id="n_venda_exibir">12345</u></span></h4>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Botão Finalizar -->
            <div class="col-md-3 mb-3">
                <button type="button" onclick="finalizarVenda()" class="btn btn-success w-100">
                    <i class="bi bi-bookmark-check"></i>&nbsp;FINALIZAR
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <button type="button" onclick="novaVenda()" class="btn btn-success w-100">
                    <i class="bi bi-receipt-cutoff"></i> &nbsp;NOVA VENDA
                </button>
            </div>
            <!-- Botão receber -->
            <div class="col-md-3 mb-3">
                <button type="button" onclick="recebimento()" class="btn btn-success w-100">
                    <i class="bi bi-cash-coin"></i>&nbsp;RECEBIMENTO
                </button>
            </div>

            <!-- Botão Voltar venda -->
            <div class="col-md-3 mb-3">
                <button type="button" onclick="voltarVenda()" class="btn btn-success w-100">
                    <i class="bi bi-save"></i>&nbsp;BUSCAR VENDA
                </button>
            </div>

            <!-- Botão Cancelar -->
            <div class="col-md-3 mb-3">
                <button type="button" onclick="cancelarVenda()" class="btn btn-danger w-100">
                    <i class="bi bi-x-lg"></i>&nbsp;CANCELAR
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <button type="button" onclick="devolucao()" class="btn btn-success w-100">
                    <i class="bi bi-box-arrow-left"></i>&nbsp;&nbsp;DEVOLUÇÃO
                </button>
            </div>

            <div class="col-md-3 mb-3">
                <button type="button" onclick="modalSangria()" class="btn btn-success w-100">
                    <i class="bi bi-cash-stack"></i>&nbsp;SANGRIA
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('caixa.fechar.index', ['caixa_id' => $caixa->id]) }}"
                    class="btn btn-success w-100">
                    <i class="bi bi-lock"></i>&nbsp;FECHAMENTO
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <button type="button" onclick="salvarVendaCaixa()" class="btn btn-success w-100">
                    <i class="bi bi-floppy"></i>&nbsp;SALVAR VENDA
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <button type="button" onclick="voltarInicio()" class="btn btn-success w-100">
                    <i class="bi bi-house"></i>&nbsp;&nbsp;INÍCIO
                </button>
            </div>
        </div>
    </div>

</div>
