<div class="modal fade" id="modal-sangria" tabindex="-1" role="dialog" aria-labelledby="modal-sangria-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-sangria-label">Sangria</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <!-- Informações dos totais exibidas fora da tabela -->
                <div id="informacoes-totais" class="m-1">
                    <!-- Totais por forma de pagamento serão inseridos aqui -->
                </div>
                <div class="row mt-2 mb-2 d-flex justify-content-end">
                    <div class="col-12 col-md-6">
                        <p>Quanto retirar *:</p>
                        <input type="text" id="quantidadeRetirarSangria" class="form-control"
                            placeholder="Quantidade retirada">
                    </div>

                    <div class="col-12 col-md-6">
                        <p>Digite sua senha para confirmar a operação *:</p>
                        <input type="password" id="senhaUsuarioSangria" class="form-control" placeholder="Senha">
                    </div>
                    <div class="col-12 col-md-12 mt-2">
                        <p>Observação:</p>
                        <textarea class="form-control" name="comentarioSangria" id="comentarioSangria" cols="30" rows="2"></textarea>
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <button type="button" class="btn btn-outline-danger mx-1" onclick="fecharModalSangria()">   <i class="bi bi-arrow-left"></i>&nbsp;Fechar</button>

                    {{-- <button type="button" class="btn btn-primary mx-1" onclick="imprimirSegundaViaSangria()"
                        id="imprimir-sangrias"> <i class="bi bi-receipt-cutoff"></i>&nbsp;2º via sangrias</button> --}}
                    <button type="button" class="btn btn-success mx-1" onclick="confirmarSangria()" id="confirmar-sangria">
                        <i class="bi bi-check-lg"></i>&nbsp;Confirmar sangria</button>
                </div>
            </div>
        </div>
    </div>
</div>
