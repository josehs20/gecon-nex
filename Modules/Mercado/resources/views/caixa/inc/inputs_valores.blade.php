<div class="card" style="height: {{ $mobile ? '40' : '15' }}vh;">
    <div class="card-body"> <!-- Remover padding -->
        <div class="row">
            <div class="mb-3 col-md">
                <label for="desconto" class="form-label">DESCONTO (%)</label>
                <input type="text" readonly class="form-control" id="desconto" placeholder="% 0,00"
                    aria-describedby="subtotalHelp">

            </div>
            {{-- <div class="mb-3 col-md">
                <label for="desconto-real" class="form-label">DESCONTO (R$)</label>
                <input type="text" readonly class="form-control" id="desconto-real" placeholder="R$ 0,00"
                aria-describedby="subtotalHelp">

            </div> --}}
            <div class="mb-3 col-md">
                <label for="subtotal" class="form-label">SUBTOTAL</label>
                <input type="text" readonly class="form-control" id="subtotal" placeholder="R$ 0,00"
                    aria-describedby="subtotalHelp">
            </div>
            <div class="mb-3 col-md">
                <label for="total" class="form-label">TOTAL</label>
                <input type="text" readonly class="form-control" id="total" placeholder="R$ 0,00"
                    aria-describedby="totalHelp">
            </div>
            <div class="mb-3 col-md">
                <label for="input-troco" class="form-label">TROCO</label>
                <input type="text" readonly class="form-control" id="input-troco" placeholder="R$ 0,00"
                    aria-describedby="subtotalHelp">

            </div>
            <div class="mb-3 col-md">
                <label for="input-recebido" class="form-label">RECEBIDO</label>
                <input type="text" readonly class="form-control" id="input-recebido" placeholder="R$ 0,00"
                    aria-describedby="subtotalHelp">

            </div>
            <div id="div-valor-devolver" class="mb-3 col-md d-none">
                <label for="input-devolver" class="form-label">DEVOLVER</label>
                <input type="text" readonly class="form-control" id="input-devolver" placeholder="R$ 0,00"
                    aria-describedby="subtotalHelp">

            </div>
        </div>
    </div>
</div>
