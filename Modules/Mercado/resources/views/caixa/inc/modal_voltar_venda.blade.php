<div class="modal fade" id="modal-voltar-venda" tabindex="-1" role="dialog" aria-labelledby="modal-voltar-venda-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-voltar-venda-label">Voltar venda</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div id="divVoltarVenda" class="">
                    <div class="mb-1">
                        <h5 for="clienteSalvarVenda">Selecione o cliente ou venda:</h5>
                    </div>

                    <select required id="voltarVenda" name="voltarVenda" class="form-control select2 mb-4">

                    </select>

                    <div class="d-flex mt-3">
                        <button onclick="cancelarVoltarVenda()" type="button" class="btn btn-outline-danger mx-1">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </button>
                        <button onclick="confirmarVoltaVenda()" id="confirmarVoltaVenda" type="button"
                            class="btn btn-success mx-1">
                            <i class="bi bi-check-lg"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
   
        </div>
    </div>
</div>
