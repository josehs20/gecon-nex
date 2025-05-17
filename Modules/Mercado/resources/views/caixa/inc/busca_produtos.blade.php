      {{-- BUSCA PRODUTOS --}}
      <div class="card" style="height: {{ $mobile ? '30' : '15' }}vh;">

        <div class="card-body"> <!-- Remover padding -->
            <div class="row d-flex justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nome">Quantidade: *</label>
                        <input placeholder="Quantidade" type="text" id="quantidadeItem" name="quantidadeItem"
                            value="1" required class="form-control">
                    </div>
                </div>
                @if ($mobile)
                    <div id="divScanerMobile" class="row w-100 h-25 d-none" style="position: relative;">
                        <video id="barcode-scanner" autoplay playsinline style="width: 100%; height: 100%;"></video>
                        <!-- Elemento video para leitura -->
                        <div id="barcode-indicator">

                        </div>
                    </div>
                @endif
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="codigo">Código de barras / Código do produto / Nome *</label>
                        <div class="input-group">

                            <div class="input-group-append">
                                <button {{ $mobile ? 'id=start-scan' : '' }} class="btn" style="background-color: #404040"
                                    type="button">
                                    <i style="color: #fff;" class="bi bi-upc-scan"></i>
                                </button>
                            </div>


                            <input type="text" placeholder="Digite..." id="buscaProdutos" name="buscaProdutos"
                                value="" data-leitor-br="false" required class="form-control" autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn" style="background-color: #404040" type="button">
                                    <i style="color: #fff;" class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>