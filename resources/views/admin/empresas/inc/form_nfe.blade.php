@php
    $collapseId = 'formCard-' . $loja->id; // Garante um ID único para cada loja
    $nfeio = $loja->nfeio; // Acessando o objeto NFEIO
    $address = json_decode($nfeio->address ?? null); // Decodificando o JSON de endereço
@endphp
<button disabled class="btn btn-primary mb-2 w-100 d-flex justify-content-between align-items-center" type="button"
    data-toggle="collapse" data-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}"
    onclick="toggleIcon(this)">
    <span>Dados NFE atrelado à loja: <strong class="text-warning">{{ $loja->nome }}</strong></span>
    <i class="bi bi-chevron-down"></i>
</button>
<div class="collapse" id="{{ $collapseId }}">
    <div class="card card-body">
        <form id="form-{{ $loja->id }}" action="{{ route('nfe.cadastro.empresas.store') }}" method="POST"
            novalidate>
            @csrf
            <div class="row">
                <!-- Coluna Cadastro -->
                <div class="col-md-6">
                    <h5>Cadastro</h5>

                    <div class="form-group">
                        <label for="name-{{ $loja->id }}">Razão Social</label>
                        <input type="text" class="form-control" name="name[{{ $loja->id }}]"
                            id="name-{{ $loja->id }}" value="{{ $nfeio->name ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="tradeName-{{ $loja->id }}">Nome Fantasia</label>
                        <input type="text" class="form-control" name="tradeName[{{ $loja->id }}]"
                            id="tradeName-{{ $loja->id }}" value="{{ $nfeio->trade_name ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="federalTaxNumber-{{ $loja->id }}">CNPJ</label>
                        <input type="text" class="form-control mask-cnpj"
                            name="federalTaxNumber[{{ $loja->id }}]" id="federalTaxNumber-{{ $loja->id }}"
                            value="{{ $nfeio->federal_tax_number ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="taxRegime-{{ $loja->id }}">Regime Tributário</label>
                        <select class="form-control" name="taxRegime[{{ $loja->id }}]"
                            id="taxRegime-{{ $loja->id }}" required>
                            <option value="isento"
                                {{ $nfeio && strtolower($nfeio->tax_regime) == 'isento' ? 'selected' : '' }}>Isento
                            </option>
                            <option value="microempreendedorIndividual"
                                {{ $nfeio && strtolower($nfeio->tax_regime) == 'microempreendedorindividual' ? 'selected' : '' }}>
                                Microempreendedor Individual</option>
                            <option value="simplesNacional"
                                {{ $nfeio && strtolower($nfeio->tax_regime) == 'simplesnacional' ? 'selected' : '' }}>
                                Simples Nacional</option>
                            <option value="lucroPresumido"
                                {{ $nfeio && strtolower($nfeio->tax_regime) == 'lucropresumido' ? 'selected' : '' }}>
                                Lucro Presumido</option>
                            <option value="lucroReal"
                                {{ $nfeio && strtolower($nfeio->tax_regime) == 'lucroreal' ? 'selected' : '' }}>Lucro
                                Real</option>
                            <option value="none"
                                {{ $nfeio && strtolower($nfeio->tax_regime) == 'none' ? 'selected' : '' }}>Nenhum
                            </option>
                        </select>
                    </div>

                    @if (true)
                        <button type="button" class="btn btn-primary d-block mb-2" data-toggle="modal"
                            data-target="#certificadoModal-{{ $loja->id }}"
                            id="openModalButton-{{ $loja->id }}">
                            Adicionar Certificado
                        </button>
                    @elseif($loja->certificado)
                        <a href="{{ route('nfe.cadastro.empresas.download.certificado', ['loja_id' => $loja->id]) }}"
                            class="d-block mb-2 d-none" id="downloadCertificado-{{ $loja->id }}">
                            Loja já possui certificado ativo: Download
                        </a>
                    @endif

                    @if ($loja->nfeio)
                        <button type="button" class="btn btn-primary d-block" data-toggle="modal"
                            id="buttonModalAddInscricao-{{ $loja->id }}"
                            data-target="#inscricaoEstadualModal-{{ $loja->id }}">
                            Adicionar Inscrição Estadual
                        </button>
                    @endif

                </div>

                <!-- Coluna Endereço -->
                <div class="col-md-6">
                    <h5>Endereço</h5>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="stateSelect-{{ $loja->id }}">Estado (UF)</label>
                                <select class="form-control select2" name="state[{{ $loja->id }}]"
                                    id="stateSelect-{{ $loja->id }}" required>
                                    <option value="">Selecione um estado</option>
                                    @foreach (getEstadosBrasileiros() as $sigla => $nome)
                                        <option value="{{ $sigla }}"
                                            {{ $address && $address->state === $sigla ? 'selected' : '' }}>
                                            {{ $nome }} ({{ $sigla }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cityCode-{{ $loja->id }}">Código da Cidade</label>
                                <input type="text" class="form-control" name="city[{{ $loja->id }}][code]"
                                    id="cityCode-{{ $loja->id }}" value="{{ $address->city->code ?? '' }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="cityName-{{ $loja->id }}">Nome da Cidade</label>
                                <input type="text" class="form-control" name="city[{{ $loja->id }}][name]"
                                    id="cityName-{{ $loja->id }}" value="{{ $address->city->name ?? '' }}"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="district-{{ $loja->id }}">Bairro</label>
                        <input type="text" class="form-control" name="district[{{ $loja->id }}]"
                            id="district-{{ $loja->id }}" value="{{ $address->district ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="street-{{ $loja->id }}">Rua</label>
                        <input type="text" class="form-control" name="street[{{ $loja->id }}]"
                            id="street-{{ $loja->id }}" value="{{ $address->street ?? '' }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="number-{{ $loja->id }}">Número</label>
                                <input type="text" class="form-control" name="number[{{ $loja->id }}]"
                                    id="number-{{ $loja->id }}" value="{{ $address->number ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="postalCode-{{ $loja->id }}">CEP</label>
                                <input type="text" class="form-control cep-mask"
                                    name="postalCode[{{ $loja->id }}]" id="postalCode-{{ $loja->id }}"
                                    value="{{ $address->postalCode ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country-{{ $loja->id }}">País</label>
                                <input type="text" class="form-control" name="country[{{ $loja->id }}]"
                                    value="{{ $address->country ?? 'BRA' }}" id="country-{{ $loja->id }}"
                                    required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="additionalInformation-{{ $loja->id }}">Informações Adicionais</label>
                        <input type="text" class="form-control" name="additionalInformation[{{ $loja->id }}]"
                            id="additionalInformation-{{ $loja->id }}"
                            value="{{ $address->additionalInformation ?? '' }}">
                    </div>
                </div>


                <h5 class="card-title mt-3">Inscrições estaduais</h5>
                <table class="table table-bordered" id="tabela-inscricoes-estaduais" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Número da Inscrição</th>
                            <th>Regime Especial</th>
                            <th>Série NFe</th>
                            <th>Número Inicial</th>
                            <th>Tipo de Emissão</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loja->inscricoes_estaduais as $inscricao)
                            <tr>
                                <td>{{ $inscricao->id }}</td>
                                <td>{{ $inscricao->tax_number }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $inscricao->special_tax_regime)) }}</td>
                                <td>{{ $inscricao->serie }}</td>
                                <td>{{ $inscricao->number }}</td>
                                <td>{{ ucfirst($inscricao->type) }}</td>
                                <td>
                                    <!-- Botão para abrir o modal específico -->
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#editarInscricaoModal-{{ $inscricao->id }}">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="excluirInscricao({{ $inscricao->id }})">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal exclusivo para essa inscrição -->
                            <div class="modal fade" id="editarInscricaoModal-{{ $inscricao->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editarInscricaoModalLabel-{{ $inscricao->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="editarInscricaoModalLabel-{{ $inscricao->id }}">Editar Inscrição
                                                Estadual</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div method="POST" id="formEditarInscricao-{{ $inscricao->id }}"
                                            action="{{ route('nfe.cadastro.empresas.update.inscricao', ['inscricao_id' => $inscricao->id]) }}">
                                            @csrf

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Número da Inscrição Estadual:</label>
                                                    <input type="text" class="form-control somente-numero"
                                                        name="taxNumber" value="{{ $inscricao->tax_number }}"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Regime Especial de Tributação:</label>
                                                    <select class="form-control" name="specialTaxRegime" required>
                                                        <option value="automatico"
                                                            {{ $inscricao->special_tax_regime == 'automatico' ? 'selected' : '' }}>
                                                            Automático</option>
                                                        <option value="nenhum"
                                                            {{ $inscricao->special_tax_regime == 'nenhum' ? 'selected' : '' }}>
                                                            Nenhum</option>
                                                        <option value="microempresaMunicipal"
                                                            {{ $inscricao->special_tax_regime == 'microempresaMunicipal' ? 'selected' : '' }}>
                                                            Microempresa Municipal</option>
                                                        <option value="estimativa"
                                                            {{ $inscricao->special_tax_regime == 'estimativa' ? 'selected' : '' }}>
                                                            Estimativa</option>
                                                        <option value="sociedadeDeProfissionais"
                                                            {{ $inscricao->special_tax_regime == 'sociedadeDeProfissionais' ? 'selected' : '' }}>
                                                            Sociedade de Profissionais</option>
                                                        <option value="cooperativa"
                                                            {{ $inscricao->special_tax_regime == 'cooperativa' ? 'selected' : '' }}>
                                                            Cooperativa</option>
                                                        <option value="microempreendedorIndividual"
                                                            {{ $inscricao->special_tax_regime == 'microempreendedorIndividual' ? 'selected' : '' }}>
                                                            Microempreendedor Individual</option>
                                                        <option value="microempresarioEmpresaPequenoPorte"
                                                            {{ $inscricao->special_tax_regime == 'microempresarioEmpresaPequenoPorte' ? 'selected' : '' }}>
                                                            Microempresário / Empresa Pequeno Porte</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Série para emissão da NFe:</label>
                                                    <input type="text"
                                                        class="form-control somente-numero-emissao-nfe" name="serie"
                                                        value="{{ $inscricao->serie }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Número inicial da NFe:</label>
                                                    <input type="text"
                                                        class="form-control somente-numero-inicial-nfe" name="number"
                                                        value="{{ $inscricao->number }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tipo de emissão:</label>
                                                    <select class="form-control" name="type" required>
                                                        <option value="default"
                                                            {{ strtolower($inscricao->type) == 'default' ? 'selected' : '' }}>
                                                            Padrão
                                                        </option>
                                                        <option value="nFe"
                                                            {{ strtolower($inscricao->type) == 'nfe' ? 'selected' : '' }}>
                                                            Nota
                                                            Fiscal Eletrônica (NFe)
                                                        </option>
                                                        <option value="nFCe"
                                                            {{ strtolower($inscricao->type) == 'nfce' ? 'selected' : '' }}>
                                                            Nota
                                                            Fiscal do Consumidor Eletrônica (NFCe)
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>ID do Código de Segurança:</label>
                                                    <input type="number" class="form-control" name="security_id"
                                                        value="{{ json_decode($inscricao->security_credential)->id }}"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Código de Segurança do Contribuinte:</label>
                                                    <input type="text" class="form-control" name="security_code"
                                                        value="{{ json_decode($inscricao->security_credential)->code }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary fecharModalAll"
                                                    data-dismiss="modal">Fechar</button>
                                                <button type="button"
                                                    onclick="editarInscricaoEstadual({{ $inscricao->id }})"
                                                    class="btn btn-dark">
                                                    <i class="bi bi-floppy"></i> Salvar Alterações
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-right">
                {{-- <button type="button" class="btn btn-secondary" onclick="fillForm({{ $loja->id }})">
                    <i class="bi bi-pencil-square"></i> Preencher Automaticamente
                </button> --}}
                <a href="{{ route('admin.empresa.index') }}" class="btn btn-outline-danger">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <button type="button" class="btn btn-dark" onclick="submitFormNFE({{ $loja->id }})">
                    <i class="bi bi-floppy"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="certificadoModal-{{ $loja->id }}" tabindex="-1"
    aria-labelledby="certificadoModalLabel-{{ $loja->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificadoModalLabel-{{ $loja->id }}">
                    Adicionar Certificado Digital Para Loja: {{ $loja->nome }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Formulário dentro do modal -->
            <form id="certificadoForm-{{ $loja->id }}"
                action="{{ route('nfe.cadastro.empresas.store.certificado', ['loja_id' => $loja->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="certificado-{{ $loja->id }}">Certificado Digital (.pfx ou
                            .p12)</label>
                        <input type="file" class="form-control" id="certificado-{{ $loja->id }}"
                            name="certificado[{{ $loja->id }}]" accept=".pfx,.p12" required>
                    </div>
                    <div class="form-group">
                        <label for="senha-{{ $loja->id }}">Senha do Certificado</label>
                        <input type="password" class="form-control" id="senha-{{ $loja->id }}"
                            name="senha[{{ $loja->id }}]" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary fecharModalAll"
                        data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-dark" onclick="salvarCertificado({{ $loja->id }})">
                        <i class="bi bi-floppy"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para a Inscrição Estadual -->
<div class="modal fade" id="inscricaoEstadualModal-{{ $loja->id }}" tabindex="-1" role="dialog"
    aria-labelledby="inscricaoEstadualModalLabel-{{ $loja->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inscricaoEstadualModalLabel-{{ $loja->id }}">
                    Adicionar Inscrição Estadual
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="inscEstadual-{{ $loja->id }}"
                action="{{ route('nfe.cadastro.empresas.store.inscricao', ['loja_id' => $loja->id]) }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Número da Inscrição Estadual:</label>
                        <input type="text" class="form-control somente-numero"
                            name="inscricao_estadual[{{ $loja->id }}][taxNumber]" required>
                    </div>

                    <div class="form-group">
                        <label>Regime Especial de Tributação:</label>
                        <select class="form-control"
                            name="inscricao_estadual[{{ $loja->id }}][specialTaxRegime]" required>
                            <option value="automatico">Automático</option>
                            <option value="nenhum">Nenhum</option>
                            <option value="microempresaMunicipal">Microempresa Municipal</option>
                            <option value="estimativa">Estimativa</option>
                            <option value="sociedadeDeProfissionais">Sociedade de Profissionais</option>
                            <option value="cooperativa">Cooperativa</option>
                            <option value="microempreendedorIndividual">Microempreendedor Individual</option>
                            <option value="microempresarioEmpresaPequenoPorte">Microempresário / Empresa Pequeno Porte
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Série para emissão da NFe:</label>
                        <input type="text" class="form-control somente-numero-emissao-nfe"
                            name="inscricao_estadual[{{ $loja->id }}][serie]" required>
                    </div>

                    <div class="form-group">
                        <label>Número inicial da NFe:</label>
                        <input type="text" class="form-control somente-numero-inicial-nfe"
                            name="inscricao_estadual[{{ $loja->id }}][number]" required>
                    </div>

                    <div class="form-group">
                        <label>Tipo de emissão:</label>
                        <select class="form-control" name="inscricao_estadual[{{ $loja->id }}][type]" required>
                            <option value="default">Padrão</option>
                            <option value="nFe">Nota Fiscal Eletrônica (NFe)</option>
                            <option value="nFCe">Nota Fiscal do Consumidor Eletrônica (NFCe)</option>
                        </select>
                    </div>
                    <!-- Novos Campos -->
                    <div class="form-group">
                        <label>ID do Código de Segurança:</label>
                        <input type="number" class="form-control"
                            name="inscricao_estadual[{{ $loja->id }}][security_id]" required>
                    </div>

                    <div class="form-group">
                        <label>Código de Segurança do Contribuinte:</label>
                        <input type="text" class="form-control"
                            name="inscricao_estadual[{{ $loja->id }}][security_code]" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary fecharModalAll" data-dismiss="modal">
                        Fechar
                    </button>
                    <button type="button" class="btn btn-dark"
                        onclick="salvarInscricaoEstadual({{ $loja->id }})">
                        <i class="bi bi-floppy"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleIcon(button) {
        let icon = button.querySelector("i");
        if (icon.classList.contains("bi-chevron-down")) {
            icon.classList.remove("bi-chevron-down");
            icon.classList.add("bi-chevron-up");
        } else {
            icon.classList.remove("bi-chevron-up");
            icon.classList.add("bi-chevron-down");
        }
    }

    function submitFormNFE(lojaId) {
        var form = $('#form-' + lojaId);

        // Validação nativa do HTML
        if (!form[0].checkValidity()) {
            form[0].reportValidity(); // Mostra os erros de validação se houver
            return; // Não envia o formulário se a validação falhar
        }

        var formData = form.serialize(); // Serializa os dados do formulário
        // // Realiza o envio via AJAX
        bloquear();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {

                desbloquear();
                if (response.success == true) {
                    msgToastr(response.msg, 'success');

                } else {
                    msgToastr(response.msg, 'warning');
                }
            },
            error: function(xhr, status, error) {
                desbloquear();
                msgToastr(error, 'error');
            }
        });
    }

    function salvarInscricaoEstadual(lojaId) {
        let form = $('#inscEstadual-' + lojaId); // Pegando o formulário correto

        // Validação nativa do HTML
        if (!form[0].checkValidity()) {
            form[0].reportValidity(); // Mostra os erros de validação se houver
            return; // Não envia o formulário se a validação falhar
        }

        var formData = new FormData(form[0]);

        bloquear();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false, // Impede que o jQuery tente processar o FormData
            contentType: false, // Evita erro de cabeçalho Content-Type
            success: function(response) {
                desbloquear();
                if (response.success == true) {
                    msgToastr(response.msg, 'success');

                    $('.fecharModalAll').click();

                    // Esconder o botão de abrir o modal
                    $('#buttonModalAddInscricao-' + lojaId).addClass(
                        'd-none') // Esconde o botão de abrir

                } else {
                    msgToastr(response.msg, 'warning');
                }
            },
            error: function(xhr) {
                desbloquear();
                msgToastr(xhr, 'error');
            }
        });
    }

    function editarInscricaoEstadual(inscricao_id) {
        // Pegando a div com o ID correto
        let div = $('#formEditarInscricao-' + inscricao_id);

        // Criando o formulário e movendo o conteúdo da div para o formulário
        let form = $('<form>', {
            action: div.attr('action'), // Supondo que você tenha o atributo 'data-action' na div
            method: 'POST',
            id: 'formEditarInscricao-' + inscricao_id
        });

        // Move todo o conteúdo da div para o formulário
        form.append(div.children());

        // Substitui a div pela nova tag form
        div.replaceWith(form);

        // Validação nativa do HTML
        if (!form[0].checkValidity()) {
            form[0].reportValidity(); // Mostra os erros de validação se houver
            return; // Não envia o formulário se a validação falhar
        }

        var formData = new FormData(form[0]);
        console.log(form.attr('action'));

        bloquear();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false, // Impede que o jQuery tente processar o FormData
            contentType: false, // Evita erro de cabeçalho Content-Type
            success: function(response) {
                desbloquear();
                if (response.success == true) {
                    msgToastr(response.msg, 'success');

                    $('.fecharModalAll').click();

                    // Esconder o botão de abrir o modal
                    $('#buttonModalAddInscricao-' + lojaId).addClass(
                        'd-none') // Esconde o botão de abrir

                } else {
                    msgToastr(response.msg, 'warning');
                }
            },
            error: function(xhr) {
                desbloquear();
                msgToastr(xhr, 'error');
            }
        });
    }

    function salvarCertificado(lojaId) {
        let form = $('#certificadoForm-' + lojaId); // Pegando o formulário correto

        // Validação nativa do HTML
        if (!form[0].checkValidity()) {
            form[0].reportValidity(); // Mostra os erros de validação se houver
            return; // Não envia o formulário se a validação falhar
        }

        var formData = new FormData(form[0]);

        bloquear();
        $.ajax({
            url: form.attr('action'), // Pegando a URL do formulário
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                desbloquear();
                if (response.success == true) {
                    msgToastr(response.msg, 'success');
                    $('.fecharModalAll').click();

                    // Esconder o botão de abrir o modal
                    $('#openModalButton-' + lojaId).addClass(
                        'd-none') // Esconde o botão de abrir

                    // Mostrar o link de download
                    $('#downloadCertificado-' + lojaId).removeClass('d-none'); // Mostra o link de download

                } else {
                    msgToastr(response.msg, 'warning');
                }
            },
            error: function(xhr) {
                desbloquear();
                msgToastr(xhr, 'error');
            }
        });
    }

    function excluirInscricao(inscricaoId) {
        var routeDeleteInscricao = "{{ route('nfe.cadastro.empresas.delete.inscricao', ':id') }}".replace(':id',
            inscricaoId);
        Swal.fire({
            title: "Excluir ?",
            text: "Você não poderá reverter essa ação!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sim, excluir!",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routeDeleteInscricao,
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        if (response.success == true) {
                            msgToastr(response.msg, 'success');
                            location.reload(); // Recarrega a página após exclusão
                        } else {
                            msgToastr(response.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        msgToastr(response.msg, 'error');
                        console.log(xhr);
                    }
                });
            }
        });
    }

    function fillForm(lojaId) {
        const data = {
            'name': 'Empresa Teste',
            'tradeName': 'Loja Teste',
            'federalTaxNumber': '53.694.056/0001-50',
            'taxRegime': 'simplesNacional',
            'state': 'RJ', // Rio de Janeiro
            'city': {
                'code': '3301900', // Código correto de Itaperuna (RJ)
                'name': 'Itaperuna'
            },
            'district': 'Centro',
            'street': 'Rua Teste',
            'number': '123',
            'postalCode': '28300000', // Exemplo de CEP de Itaperuna
            'country': 'BRA',
            'additionalInformation': 'Informações adicionais'
        };

        // Preenchendo os campos automaticamente com os dados de exemplo
        $('#name-' + lojaId).val(data.name);
        $('#tradeName-' + lojaId).val(data.tradeName);
        $('#federalTaxNumber-' + lojaId).val(data.federalTaxNumber);
        $('#taxRegime-' + lojaId).val(data.taxRegime).change();
        $('#stateSelect-' + lojaId).val(data.state).change();
        $('#cityCode-' + lojaId).val(data.city.code);
        $('#cityName-' + lojaId).val(data.city.name);
        $('#district-' + lojaId).val(data.district);
        $('#street-' + lojaId).val(data.street);
        $('#number-' + lojaId).val(data.number);
        $('#postalCode-' + lojaId).val(data.postalCode);
        $('#country-' + lojaId).val(data.country);
        $('#additionalInformation-' + lojaId).val(data.additionalInformation);
    }

    $(document).ready(function() {
        $(".mask-cnpj").mask('00.000.000/0000-00', {
            reverse: false // A máscara será aplicada da esquerda para a direita
        });
        somenteInteiroByClass('somente-numero');

        $('.somente-numero-emissao-nfe').mask('999');
        $('.somente-numero-inicial-nfe').mask('9');

    });
    $(document).on('keypress', 'form', function(e) {
        if (e.which === 13) {
            e.preventDefault(); // Impede o envio do formulário
            return false;
        }
    });
</script>
