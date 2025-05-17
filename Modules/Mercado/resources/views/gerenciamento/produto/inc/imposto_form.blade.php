<form id="form-fiscal-produto" action="{{ auth()->user()->getUserModulo->loja->nfeio && $produto ? route('cadastro.produto.post.ncms', ['estoque_id' => $produto->estoque->id]) : '#' }}"
    method="POST">
    @csrf
    <div class="row mt-2">
        @if (auth()->user()->getUserModulo->loja->nfeio && $produto)
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ncm" class="d-block">NCM: *</label>
                    <select style="width: 100%;" required id="ncm" name="ncm" class="form-control select2">
                    </select>
                </div>
            </div>
        @elseif (!$produto)
            <div class="alert alert-warning col-12" style="text-align: center !important" role="alert">
                Produto não cadastrado.
            </div>
        @else
            <div class="alert alert-warning col-12" style="text-align: center !important" role="alert">
                Loja não cadastrada para emissão de NFCE.
            </div>
        @endif
    </div>

    <div>
        <a href="{{ route('cadastro.produto.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (auth()->user()->getUserModulo->loja->nfeio && $produto)
            <button type="submit" class="btn btn-success mx-2">
                <i class="bi bi-floppy"></i> Salvar
            </button>
        @endif

    </div>
</form>
<script>
    var routeGetNcms = @json(route('cadastro.produto.get.ncms'));
    select2('ncm', routeGetNcms);
    $(document).ready(function() {
        $('#form-fiscal-produto').on('submit', function(e) {
            e.preventDefault(); // Impede o envio do formulário

            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize(); // Serializa os dados do form

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {
                    console.log(response);

                },
                error: function(xhr) {
                    alert("Erro ao cadastrar o produto.");
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
