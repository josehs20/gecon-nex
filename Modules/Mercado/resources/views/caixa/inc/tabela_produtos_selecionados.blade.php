<div class="card" style="height: 75vh;">
    {{-- <div class="header-card">
        <h3>Operador : <u id="statusCaixaVenda">{{ auth()->user()->name }}</u></h3>
    </div> --}}
    {{-- Imagem --}}
    <div class="card-body" id="imagemPadraoCaixa"
        style="height: calc(100% - 50px); padding: 2%;">
        <img  src="{{ asset('siedBar/images/bannerTeste.jpg') }}" alt="Banner da Empresa"
            class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">

    </div>
    <div class="card-body p-0 d-none" id="div-tabela-produtos-selecionados" style="height: calc(100% - 60px); overflow: hidden; position: relative;">
        <div style="height: 100%; overflow-y: auto;">
            <!-- Ajuste a altura para o corpo -->
            <table id="tabela-produtos-selecionados" class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Produto</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Conteúdo da tabela -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body p-0 d-none" id="div-tabela-recebimento" style="height: calc(100% - 60px); overflow: hidden; position: relative;">
        <div style="height: 100%; overflow-y: auto;">
            <!-- Ajuste a altura para o corpo -->
            <table id="tabela-recebimento" class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th >Nº Venda</th>
                        <th >Data</th>
                        <th >Receber</th>
                        <th >Tipo</th>
                        <th >Parcela</th>
                        <th >Pendente</th>
                        <th >Pago</th>
                        <th >Total</th>
           
                    </tr>
                </thead>
                <tbody>
                    <!-- Conteúdo da tabela -->
                </tbody>
            </table>
        </div>
    </div>
    
</div>
