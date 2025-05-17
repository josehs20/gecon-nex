<div class="card card-body">
    <div class="form-group">
        <label for="descricao">Descrição: <small>EX: Cartão de crédito</small></label>
        <input value="{{ isset($pagamento) ? $pagamento->descricao : '' }}" type="text" name="descricao" required
            class="form-control" id="descricao" placeholder="Digite a descrição">
    </div>

    <!-- Select de Espécie de Pagamento -->
    <div class="form-group">
        <label for="especie_pagamento">Espécie de Pagamento</label>
        <select required class="form-control" name="especie" id="especie_pagamento"
            {{ isset($pagamento) ? 'disabled' : '' }}>
            <option value="" readonly selected>Selecione a forma de pagamento</option>

            @foreach ($especies as $es)
                <option value="{{ $es->id }}"
                    {{ isset($pagamento) && $pagamento->especie_pagamento_id == $es->id ? 'selected' : '' }}>
                    {{ $es->nome }}
                </option>
            @endforeach

        </select>
    </div>
    @if (!isset($pagamento))
        <!-- Toggle Switch para Habilitar Parcelas -->
        <div class="form-group">
            <label for="habilitarParcelas">Essa forma de pagamento vai ter conter parcela ?</label>
            <div class="custom-control custom-switch">
                <input value="{{ isset($pagamento) && $pagamento->ativo == true ? 'checked' : '' }}" type="checkbox"
                    class="custom-control-input" id="habilitarParcelas" {{ isset($pagamento) ? 'disabled' : '' }}>
                <label class="custom-control-label" for="habilitarParcelas"></label>
            </div>
        </div>
    @endif

    {{-- <!-- Container de Parcelas -->
    <div class="form-group" id="parcelasContainer">
        <label for="parcelas">Parcelas</label>
        <input value="{{ isset($pagamento) ? $pagamento->parcelas : '' }}" type="number" class="form-control"
            id="parcelas" name="parcelas" min="1" max="12" value="1" step="1"
            placeholder="Quantidade de parcelas" required readonly>
    </div> --}}

    <div class="form-check mb-3 mx-2">
        <input class="form-check-input" type="checkbox" name="ativo" id="flexCheckDefault"
            {{ isset($pagamento) && $pagamento->ativo == true ? 'checked' : (!isset($pagamento) ? 'checked' : '') }}>
        <label class="form-check-label" for="flexCheckDefault">
            Ativo*
        </label>
    </div>
</div>
<div class="card">
    <!-- Botão de Enviar -->
    <div class="card-body">
        <a href="{{ route('cadastro.forma_pagemento.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>

        {{-- <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Enviar</button> --}}

    </div>

</div>

<script>
    $(document).ready(function() {
        somenteInteiro('parcelas');
        // Função para habilitar ou desabilitar o campo de parcelas
        $('#habilitarParcelas').change(function() {
            if ($(this).is(':checked')) {
                // $('#parcelasContainer').show(); // Exibe o container de parcelas
                $('#parcelas').prop('readonly', false); // Permite editar o campo de parcelas
            } else {
                // $('#parcelasContainer').hide(); // Oculta o container de parcelas
                $('#parcelas').prop('readonly', true).val(
                    ''); // Mantém o campo de parcelas como readonly
            }
        });
    });
</script>
