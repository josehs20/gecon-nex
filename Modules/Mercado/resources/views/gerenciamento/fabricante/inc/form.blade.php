<div class="card card-body">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="nome">Nome fantasia: *</label>
                <input required type="text" id="nome" value="{{ isset($fabricante) ? $fabricante->nome : '' }}" name="nome"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="razao_social">Razão social: *</label>
                <input required type="text" id="razao_social" value="{{ isset($fabricante) ? $fabricante->razao_social : '' }}" name="razao_social"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="cnpj">CNPJ: *</label>
                <input required type="text" id="cnpj" value="{{ isset($fabricante) ? $fabricante->cnpj : '' }}"
                    name="cnpj" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="descricao">Descrição: </label>
                <input type="text" id="descricao" value="{{ isset($fabricante) ? $fabricante->descricao : '' }}" name="descricao"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="inscricao_estadual">Inscrição estadual: </label>
                <input type="text" id="inscricao_estadual" value="{{ isset($fabricante) ? $fabricante->inscricao_estadual : '' }}" name="inscricao_estadual"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="ativo">Ativo: *</label>
                <select required id="ativo" name="ativo" class="form-control">
                    <option value="true" {{ isset($fabricante) && $fabricante->ativo == 1 ? 'selected' : '' }}>Ativo</option>
                    <option value="false" {{ isset($fabricante) && $fabricante->ativo == 0 ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="email">E-mail: </label>
                <input type="text" id="email" value="{{ isset($fabricante) ? $fabricante->email : '' }}" name="email"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="site">Site: </label>
                <input type="text" id="site" value="{{ isset($fabricante) ? $fabricante->site : '' }}" name="site"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="celular">Celular: </label>
                <input type="text" id="celular" value="{{ isset($fabricante) ? $fabricante->celular : '' }}" name="celular"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="telefone">Telefone: </label>
                <input type="text" id="telefone" value="{{ isset($fabricante) ? $fabricante->telefone : '' }}"
                    name="telefone" class="form-control">
            </div>
        </div>
    </div>
    
    <div class="row" style="display: flex; align-items:center">
        <div class="col-md-2">
            <div class="form-group">
                <label for="cep">CEP: *</label>
                <input type="text" id="cep" value="{{ isset($endereco) ? $endereco->cep : '' }}"
                    name="cep" class="form-control">
            </div>
        </div>
        <button type="button" id="botaoBuscarCep" class="btn btn-success mt-3"
            style="height: fit-content">Buscar</button>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="logradouro">Logradouro: </label>
                <input type="text" id="logradouro" value="{{ isset($endereco) ? $endereco->logradouro : '' }}"
                    name="logradouro" class="form-control">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="numero">Número: </label>
                <input type="text" id="numero" value="{{ isset($endereco) ? $endereco->numero : '' }}"
                    name="numero" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="cidade">Cidade: </label>
                <input type="text" id="cidade" value="{{ isset($endereco) ? $endereco->cidade : '' }}"
                    name="cidade" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bairro">Bairro: </label>
                <input type="text" id="bairro" value="{{ isset($endereco) ? $endereco->bairro : '' }}"
                    name="bairro" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="uf">UF: </label>
                <select id="uf" name="uf" class="form-control">
                    
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="complemento">Complemento: </label>
                <input type="text" id="complemento" value="{{ isset($endereco) ? $endereco->complemento : '' }}"
                    name="complemento" class="form-control">
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('cadastro.fabricante.index') }}" type="button" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <button id="bt-salvar-atualizar" type="submit"
            class="btn btn-dark mx-2">
            <i class="bi bi-floppy"></i> Salvar
        </button>
    </div>
</div>
<script>
    $(document).ready(function() {
        formatarDocumento();
        aplicarMascaras();
        buscarCep('cep', 'botaoBuscarCep', 'logradouro', 'bairro', 'cidade', 'uf', 'complemento');
        maskDinheiro('limite_credito')

        /* busca os estados para preencher o select */
        $.ajax({
            "url": "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
            "dataType": "json",
            "success": function(data) {
                var ufSelect = $('#uf');
                // Ordena os estados por nome
                data.sort(function(a, b) {
                    return a.sigla.localeCompare(b.sigla);
                });

                ufSelect.empty(); // Limpar opções existentes
                ufSelect.append($('<option>', { value: '', text: 'Selecione ...' }));
                data.forEach(function(estado) {
                    ufSelect.append($('<option>', {
                        value: estado.sigla,
                        text: estado.sigla
                    }));
                });

                @if (isset($endereco))
                    ufSelect.val("{{ $endereco->uf }}");
                @endif
            },
            "error": function(error) {
                toastr.error(error);
            }
        });

        /*
            faz verificações antes de enviar o formulario
        */
        $('#fabricante').on('submit', function(event) {
            event.preventDefault();
            let email = $('#email').val();

            /* email é opcional, só verifico o email se o usuario inseri-lo */
            if (email != '') {
                if (verificarEmail(email)) {
                    submitFormulario();
                } else {
                    toastr.warning('E-mail inválido!');
                }
            } else {
                submitFormulario();
            }
        });
    });

    function submitFormulario() {
        let form = $('#fabricante');
        let formData = form.serialize();
        let csrfToken = form.find('input[name="_token"]').val();

        $('#bt-salvar-atualizar').prop('disabled', true);
        $('#bt-salvar-atualizar').text('Processando ...');

        $.ajax({
            "url": form.attr('action'),
            "method": form.attr('method'),
            "dataType": "json",
            "data": formData,
            "headers": {
                'X-CSRF-TOKEN': csrfToken
            },
            "success": function(data) {
                if (form.data('identifier') === 'form-store') {
                    toastr.success('Fabricante cadastrado com sucesso.');
                    form.find('input').val('');
                } else if (form.data('identifier') === 'form-update') {
                    toastr.success('Fabricante atualizado com sucesso.');
                    form.find('input').val('');
                }
                
                window.location.href = '{{ route('cadastro.fabricante.index') }}'
            },
            "error": function(error) {
                $('#bt-salvar-atualizar').prop('disabled', false);
                $('#bt-salvar-atualizar').html('<i class="bi bi-floppy"></i> Salvar');
        
                if (form.data('identifier') === 'form-store') {
                    window.location.href = '{{ route('cadastro.fabricante.create') }}'
                    form.find('input').val('');
                } 
            }
        });
    }

    /*
        retorna true se o email for valido
        email valido é aquele que contém @ e .com ou .com.br
    */
    function verificarEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }


    function aplicarMascaras() {

        /* celular */
        $('#celular').mask('(00) 0 0000-0000');

        /* telefone fixo */
        $('#telefone').mask('(00) 0000-0000');

        /* cep */
        $('#cep').mask('00000-000');

        /* CNPJ */
        $('#cnpj').mask('00.000.000/0000-00');

    }
</script>
