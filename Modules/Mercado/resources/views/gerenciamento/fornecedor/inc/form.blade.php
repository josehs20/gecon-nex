<div class="card card-body">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="nome">Nome: *</label>
                <input required type="text" id="nome" value="{{ $fornecedor ? $fornecedor->nome : '' }}" name="nome"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="nome_fantasia">Nome fantasia: *</label>
                <input required type="text" id="nome_fantasia"
                    value="{{ $fornecedor ? $fornecedor->nome_fantasia : '' }}" name="nome_fantasia" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="ativo">Ativo: *</label>
                <select required id="ativo" name="ativo" class="form-control">
                    <option value="true" {{ $fornecedor && $fornecedor->ativo == 1 ? 'selected' : '' }}>Ativo</option>
                    <option value="false" {{ $fornecedor && $fornecedor->ativo == 0 ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="pessoa">Pessoa: (Física ou Juridica) *</label>
                <select required id="pessoa" name="pessoa" class="form-control">
                    <option value="J" {{ $fornecedor && $fornecedor->pessoa == 'J' ? 'selected' : '' }}>Jurídica
                    </option>
                    <option value="F" {{ $fornecedor && $fornecedor->pessoa == 'F' ? 'selected' : '' }}>Física</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label id="labelDocumento" for="documento"></label>
                <input required type="text" id="documento" value="{{ $fornecedor ? $fornecedor->documento : '' }}"
                    name="documento" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="celular">Celular: *</label>
                <input required type="text" id="celular" value="{{ $fornecedor ? $fornecedor->celular : '' }}"
                    name="celular" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="telefone_fixo">Telefone: </label>
                <input type="text" id="telefone_fixo" value="{{ $fornecedor ? $fornecedor->telefone_fixo : '' }}"
                    name="telefone_fixo" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">E-mail: </label>
                <input type="text" id="email" value="{{ $fornecedor ? $fornecedor->email : '' }}" name="email"
                    class="form-control">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="site">Site: </label>
                <input type="text" id="site" value="{{ $fornecedor ? $fornecedor->site : '' }}" name="site"
                    class="form-control">
            </div>
        </div>
    </div>

    <div class="row" style="display: flex; align-items:center">
        <div class="col-md-2">
            <div class="form-group">
                <label for="cep">CEP: *</label>
                <input required type="text" id="cep" value="{{ $endereco ? $endereco->cep : '' }}" name="cep"
                    class="form-control">
            </div>
        </div>
        <button type="button" id="botaoBuscarCep" class="btn btn-success mt-3" style="height: fit-content">Buscar</button>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="logradouro">Logradouro: *</label>
                <input required type="text" id="logradouro" value="{{ $endereco ? $endereco->logradouro : '' }}"
                    name="logradouro" class="form-control">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="numero">Número: *</label>
                <input required type="text" id="numero" value="{{ $endereco ? $endereco->numero : '' }}"
                    name="numero" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cidade">Cidade: *</label>
                <input required type="text" id="cidade" value="{{ $endereco ? $endereco->cidade : '' }}"
                    name="cidade" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bairro">Bairro: *</label>
                <input required type="text" id="bairro" value="{{ $endereco ? $endereco->bairro : '' }}"
                    name="bairro" class="form-control">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="uf">UF: *</label>
                <select required id="uf" name="uf" class="form-control">
                    @if ($endereco)
                        <option value="{{ $endereco->uf }}" selected>{{ $endereco->uf }}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="complemento">Complemento: </label>
                <input type="text" id="complemento" value="{{ $endereco ? $endereco->complemento : '' }}"
                    name="complemento" class="form-control">
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('cadastro.fornecedor.index') }}" class="btn btn-danger">
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
        verificarSelectPessoa();

        /*
            quando mudar a opção do select de pessoa (fisica ou juridica),
            limpa o input de documento para prevenir problemas
        */
        $('#pessoa').on('change', function() {
            $('#documento').val('');
            verificarSelectPessoa();
        })

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

                data.forEach(function(estado) {
                    ufSelect.append($('<option>', {
                        value: estado.sigla,
                        text: estado.sigla
                    }));
                });

                @if ($endereco)
                    ufSelect.val("{{ $endereco->uf }}");
                @endif
            },
            "error": function(error) {
                console.log(error)
            }
        });

        /*
            faz verificações antes de enviar o formulario
        */
        $('#fornecedor').on('submit', function(event) {
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
        let form = $('#fornecedor');
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
                    toastr.success('Fornecedor cadastrado com sucesso.');
                    form.find('input').val('');
                } else if (form.data('identifier') === 'form-update') {
                    toastr.success('Fornecedor atualizado com sucesso.');
                }
                setTimeout(function() {
                    window.location.href = '{{ route('cadastro.fornecedor.index') }}'
                }, 1500);
            },
            "error": function(error) {
                if (error.responseJSON && error.responseJSON.message) {
                    var mensagemErro = JSON.parse(error.responseText).message;
                    toastr.error('Não foi possível cadastrar o fornecedor. Tente novamente!', mensagemErro);
                } else {
                    toastr.error('Não foi possível cadastrar o fornecedor. Tente novamente!');
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

    /*
        Aplica a mascara de acordo com a pessoa (fisica ou juridica) selecionada
    */
    function verificarSeCpfOuCnpjParaAplicarMascara() {
        $('#documento').on('input', function() {
            if (verificarSelectPessoa() == 'J') {
                $('#documento').mask('00.000.000/0000-00');
            } else {
                $('#documento').mask('000.000.000-00');
            }
        });
    }

    function aplicarMascaras() {
        /* cpf ou cnpj*/
        verificarSeCpfOuCnpjParaAplicarMascara();

        /* celular */
        $('#celular').mask('(00) 0 0000-0000');

        /* telefone fixo */
        $('#telefone_fixo').mask('(00) 0000-0000');

        /* cep */
        $('#cep').mask('00.000-000');
    }

    /*
        Verifico qual pessoa (Fisica ou Juridica) esta selecionada para alterar o label do input
        e tambem informar para o input documento qual formatação usar
     */
    function verificarSelectPessoa() {
        let pessoa = $('#pessoa').val();
        pessoa == 'J' ? $('#labelDocumento').html('CNPJ: *') : $('#labelDocumento').html('CPF: *');
        return pessoa;
    }
</script>
