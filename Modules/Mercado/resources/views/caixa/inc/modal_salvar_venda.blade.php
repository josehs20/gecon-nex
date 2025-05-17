<div class="modal fade" id="modal-salvar-venda" tabindex="-1" role="dialog" aria-labelledby="modal-salvar-venda-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-salvar-venda-label">Salvar venda</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div id="divSalvarVenda" class="">
                    <div class="mb-1">
                        <h5 for="clienteSalvarVenda">Selecione o cliente:</h5>
                        <small class="d-none" id="clienteJaPertenceAVenda">Essa venda já está salva para o cliente abaixo.</small>
                    </div>
                    <select required id="clienteSalvarVenda" name="clienteSalvarVenda" class="form-control select2 mb-4">
                        <!-- As opções do Select2 aparecerão aqui -->
                    </select>
                    <div class="d-flex mt-3">
                        <!-- Adicionada a classe mt-3 para mais espaço acima dos botões -->
                        <button onclick="fecharSalvarVenda()" type="button" class="btn btn-outline-danger mx-1">
                            <i class="bi bi-arrow-left"></i> Fechar
                        </button>
                        
                        <button type="button" class="btn btn-warning mx-1" onclick="modalCadastrarCliente()">
                            <i class="bi bi-person-plus""></i> Cadastrar
                        </button>
                        <button onclick="salvarVendaPost()" id="confirmarVoltaVenda" type="button"
                            class="btn btn-success mx-1">
                            <i class="bi bi-check-lg"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
