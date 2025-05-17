<!-- Modal -->
<div class="modal fade" id="cadastroModal" tabindex="-1" role="dialog" aria-labelledby="cadastroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroModalLabel">Cadastrar de Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cadastroForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Nome: *</label>
                                <input required type="text" id="nome" name="nome" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="documento">Documento: *</label>
                                <input required type="text" id="documento" name="documento" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pessoa">Pessoa *</label>
                                <select required id="pessoa" name="pessoa" class="form-control">
                                    <option value="F">Física</option>
                                    {{-- <option value="J">Jurídica</option> --}}
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="celular">Celular:</label>
                                <input required type="text" id="celular" name="celular" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefone_fixo">Telefone:</label>
                                <input type="text" id="telefone_fixo" name="telefone_fixo" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="data_nascimento">Data de Nascimento: *</label>
                                <input type="text" required id="data_nascimento" name="data_nascimento" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="text" id="email" name="email" class="form-control">
                            </div>
                        </div>
                    </div>


                    <div id="divEndereco" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cep">CEP: *</label>
                                <input required type="text" id="cep" name="cep" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2 mt-2">
                            <button required type="button" id="botaoBuscarCep" class="btn btn-primary mt-4">Buscar</button>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="logradouro">Logradouro: *</label>
                                <input required type="text" id="logradouro" name="logradouro" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="numero">Número: </label>
                                <input type="text" id="numero" name="numero" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="cidade">Cidade: *</label>
                                <input required type="text" id="cidade" name="cidade" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="bairro">Bairro: *</label>
                                <input required type="text" id="bairro" name="bairro" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="uf">UF: *</label>
                                <select required id="uf" name="uf" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="SP">SP</option>
                                    <option value="RJ">RJ</option>
                                    <option value="MG">MG</option>
                                    <option value="RS">RS</option>
                                    <!-- Adicione outros estados conforme necessário -->
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="complemento">Complemento: *</label>
                                <input type="text" id="complemento" name="complemento" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacao">Observação:</label>
                                <textarea name="observacao" id="observacao" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" onclick="fecharModalCadastroCliente()" ><i class="bi bi-arrow-left" ></i> Fechar</button>
                <button onclick="cadastrarEnderecoDiv()" type="button" class="btn btn-warning"><i class="bi bi-geo-alt"></i> Cadastrar endereco</button>
                <button type="submit" form="cadastroForm" id="cadastroClienteCaixaFormButton" class="btn btn-success"><i class="bi bi-floppy"></i> Salvar</button>
            </div>
        </div>
    </div>
</div>
<script>
    var routeCadastrarClienteCaixa = @json(route('caixa.clientes.cadastrar'));
    cadastrarEnderecoDiv();

    function limparCampos(params) {
        $('#cadastroModal input').val(''); // Limpa os inputs

    }

    function fecharModalCadastroCliente() {        
        $('#cadastroModal').modal('hide')
    }

    function cadastrarEnderecoDiv() {

        $('#divEndereco').toggleClass('d-none'); // Alterna entre adicionar/remover a classe

        if ($('#divEndereco').hasClass('d-none')) {
            // Se a div está oculta, remove o atributo required dos inputs
            $('#divEndereco input').removeAttr('required').val(''); // Limpa os inputs
        } else {
            // Se a div está visível, adiciona o atributo required
            $('#divEndereco input').not('#numero').attr('required', true);
        }
    }

    $(document).ready(function() {
        formatarDocumento();
        formatarData();
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
            "url": "https://servicodados.ibge.gov.br/api/v1/localidades/estados"
            , "dataType": "json"
            , success: function(data) {
                var ufSelect = $('#uf');
                // Ordena os estados por nome
                data.sort(function(a, b) {
                    return a.sigla.localeCompare(b.sigla);
                });

                ufSelect.empty(); // Limpar opções existentes

                data.forEach(function(estado) {
                    ufSelect.append($('<option>', {
                        value: estado.sigla
                        , text: estado.sigla
                    }));
                });


            }
            , error: function(error) {
                console.log(error)
            }
        });

        /*
         *   faz verificações antes de enviar o formulario
         */
        $('#cadastroForm').on('submit', function(event) {
            event.preventDefault();
            let email = $('#email').val();

            if (email && email != '' && !verificarEmail(email)) {
                toastr.info('Email inválido, certifique-se que o email esta escrito corretamente');
                return;
            }

            let form = $('#cadastroForm')[0]; // Seleciona o formulário como um elemento DOM
            let formData = new FormData(form); // Cria um objeto FormData a partir do formulário

            // Adiciona o valor do campo 'addEndereco' dinamicamente ao FormData
            formData.append('addEndereco', !$('#divEndereco').hasClass('d-none'));
            disableButtons('cadastroClienteCaixaFormButton');

            $.ajax({
                url: routeCadastrarClienteCaixa
                , method: 'POST'
                , dataType: 'json'
                , data: formData
                , processData: false
                , contentType: false
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Inclua o token CSRF se estiver usando Laravel
                }
                , success: function(response) {

                    if (response.success == true) {
                        toastr.success(response.msg);
                        habilitaButtons('cadastroClienteCaixaFormButton', 'Salvar');
                        fecharModalCadastroCliente();
                        limparCampos();
                    } else {
                        toastr.info(response.msg);
                        habilitaButtons('cadastroClienteCaixaFormButton', 'Salvar');

                    }

                }
                , error: function(error) {
                    toastr.error('Erro ao cadastrar cliente, comunique-se ao suporte.');
                    habilitaButtons('cadastroClienteCaixaFormButton', 'Salvar');

                }
            });
        });
    });

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

        $('#data_nascimento').mask('00/00/0000');
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
