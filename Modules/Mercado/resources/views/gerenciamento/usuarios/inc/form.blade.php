<div class="card card-body table-responsive elevated">
    <h5>Dados pessoais</h5>

    <div class="row">
        <div class="form-group col-md-6 col-12">
            <label for="name">Nome <span style="color: red">*</span></label>
            <input required type="text" name="name" id="name" value="{{ isset($user) ? $user->name : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-6 col-12">
            <label for="email">E-mail <span style="color: red">*</span></label>
            <input required type="email" name="email" id="email" value="{{ isset($user) ? $user->email : '' }}"
                class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label for="documento">Documento <span style="color: red">*</span></label>
            <input required type="text" name="documento" id="documento"
                value="{{ isset($user) ? $user->usuarioMercado->documento : '' }}" class="form-control">
        </div>
        <div class="form-group col-md-3 col-12">
            <label for="data_nascimento">Data de Nascimento</label>
            <input type="text" placeholder="00/00/0000" name="data_nascimento" id="data_nascimento"
                value="{{ isset($user) ? dataBancoDeDadosParaDataString($user->usuarioMercado->data_nascimento) : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-3 col-12">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone"
                value="{{ isset($user) ? $user->usuarioMercado->telefone : '' }}" class="form-control">
        </div>
        <div class="form-group col-md-3 col-12">
            <label for="celular">Celular</label>
            <input type="text" name="celular" id="celular"
                value="{{ isset($user) ? $user->usuarioMercado->celular : '' }}" class="form-control">
        </div>
    </div>
    <br>

    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label for="cep">CEP</label>
            <div class="d-flex">
                <input type="text" name="cep" id="cep" value="{{ isset($endereco) ? $endereco->cep : '' }}"
                    class="form-control">
                <button type="button" class="btn btn-dark ml-1" id="botaoBuscarCep">Buscar</button>
            </div>
        </div>
        <div class="form-group col-md-9 col-12">
            <label for="logradouro">Logradouro</label>
            <input type="text" name="logradouro" id="logradouro"
                value="{{ isset($endereco) ? $endereco->logradouro : '' }}" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-12">
            <label for="numero">Número</label>
            <input type="text" name="numero" id="numero" value="{{ isset($endereco) ? $endereco->numero : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-4 col-12">
            <label for="bairro">Bairro</label>
            <input type="text" name="bairro" id="bairro" value="{{ isset($endereco) ? $endereco->bairro : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-4 col-12">
            <label for="cidade">Cidade</label>
            <input type="text" name="cidade" id="cidade" value="{{ isset($endereco) ? $endereco->cidade : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-2 col-12">
            <label for="uf">UF</label>
            <input type="text" name="uf" id="uf" value="{{ isset($endereco) ? $endereco->uf : '' }}"
                class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="complemento">Complemento</label>
        <input type="text" name="complemento" id="complemento"
            value="{{ isset($endereco) ? $endereco->complemento : '' }}" class="form-control">
    </div>
</div>

<div class="card card-body table-responsive elevated">
    <h5>Dados da empresa</h5>

    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label for="login">Login <span style="color: red">*</span></label>
            <input required type="text" name="login" id="login"
                value="{{ isset($user) ? $user->login : '' }}" class="form-control">
        </div>
        
        <div class="form-group col-md-3 col-12">
            <label for="tipo_usuario_id">Tipo de usuário <span style="color: red">*</span></label>
            <select required class="form-control" name="tipo_usuario_id" id="tipo_usuario_id">
                <option value="0" {{ !isset($user) ? 'selected' : '' }}>Selecione ... </option>
                @foreach (config('config.tipo_usuarios') as $tipo_usuario_chave => $tipo_usuario_valor)
                    @php
                        $idAdmin = config('config.tipo_usuarios.admin.id');
                        $idClienteMaster = config('config.tipo_usuarios.cliente_master.id');
                        $usuarioLogadoTipoId = $usuario_logado->tipo_usuario_id ?? null;
                    @endphp

                    @if (
                        ($usuarioLogadoTipoId == $idClienteMaster && $tipo_usuario_valor['id'] != $idAdmin) ||
                            ($usuarioLogadoTipoId != $idClienteMaster &&
                                $usuarioLogadoTipoId != $idAdmin &&
                                $tipo_usuario_valor['id'] != $idAdmin &&
                                $tipo_usuario_valor['id'] != $idClienteMaster))
                        <option value="{{ $tipo_usuario_valor['id'] }}"
                            {{ isset($user) && $user->tipo_usuario_id == $tipo_usuario_valor['id'] ? 'selected' : '' }}>
                            {{ strtoupper($tipo_usuario_valor['descricao']) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        <input type="hidden" name="status_id" value={{ config('config.status.ativo') }}>
        {{-- <div class="form-group col-md-3 col-12">
            <label for="status_id">Status <span style="color: red">*</span></label>

            <select required class="form-control" name="status_id" id="status_id">
                <option value="0" {{ !isset($user) ? 'selected' : '' }}>Selecione ... </option>
                @foreach (obterStatusApresentavel() as $status => $status_id)
                    <option value="{{ $status_id }}" 
                        {{ (isset($user) && $user->usuarioMercado->status_id == $status_id) ? 'selected' : '' }}>
                        {{ strtoupper($status) }}
                    </option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group col-md-3 col-12 form-check">
            <label>Ativo <span style="color: red">*</span></label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" value="true"
                    {{ !isset($user) || (isset($user) && $user->usuarioMercado->ativo) ? 'checked' : '' }}>
                <label class="custom-control-label" for="ativo"
                    id="label_ativo">{{ !isset($user) || (isset($user) && $user->usuarioMercado->ativo) ? 'Sim' : 'Não' }}</label>
            </div>
        </div>

        <div class="form-group col-md-3 col-12 form-check">
            <label>Permite abrir caixa <span style="color: red">*</span></label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="permite_abrir_caixa"
                    name="permite_abrir_caixa" value="true"
                    {{ !isset($user) || (isset($user) && $user->permite_abrir_caixa) ? 'checked' : '' }}>
                <label class="custom-control-label" for="permite_abrir_caixa"
                    id="label_permite_abrir_caixa">{{ !isset($user) || (isset($user) && $user->permite_abrir_caixa) ? 'Sim' : 'Não' }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label for="modulo_id">Módulo <span style="color: red">*</span></label>
            <input type="hidden" name="modulo_id" value={{ $modulo->id }}>
            <select required disabled class="form-control" name="modulo_id" id="modulo_id">
                <option value="{{ $modulo->id }}">
                    {{ strtoupper($modulo->nome) }}
                </option>
            </select>
        </div>
        <div class="form-group col-md-3 col-12">
            <label for="empresa_id">Empresa <span style="color: red">*</span></label>
            <input type="hidden" name="empresa_id" value={{ $empresa->id }}>
            <select required disabled class="form-control" name="empresa_id" id="empresa_id">
                <option value="{{ $empresa->id }}">
                    {{ strtoupper($empresa->nome_fantasia) }}
                </option>
            </select>
        </div>
        <div class="form-group col-md-3 col-12">
            <label for="loja_id">Loja <span style="color: red">*</span></label>
            <input type="hidden" name="loja_id" value={{ $loja->id }}>
            <select required disabled name="loja_id" id="loja_id" class="form-control">
                <option value="{{ $loja->id }}">
                    {{ strtoupper($loja->nome) }}
                </option>
            </select>
        </div>

    </div>
</div>

<div class="card card-body table-responsive elevated">
    <h5>Dados profissionais</h5>

    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label for="tipo_contrato">Tipo de contrato <span style="color: red">*</span></label>
            <select required class="form-control" name="tipo_contrato" id="tipo_contrato">
                <option value="0" {{ !isset($user) ? 'selected' : '' }}>Selecione ... </option>
                @foreach (config('config.tipo_contrato') as $tipo => $tipo_descricao)
                    <option value="{{ $tipo_descricao }}"
                        {{ isset($user) && $user->usuarioMercado->tipo_contrato == $tipo_descricao ? 'selected' : '' }}>
                        {{ $tipo_descricao }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3 col-12">
            <label for="salario">Salário</label>
            <input onkeyup="formatarCampo(this)" type="text" name="salario" id="salario"
                value="{{ isset($user) ? number_format($user->usuarioMercado->salario, 2, ',', '.') : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-6 col-12">
            <label for="comissao">Comissão (%)</label>
            <input maxlength="4" type="text" name="comissao" id="comissao"
                value="{{ isset($user) ? number_format($user->usuarioMercado->comissao, 1, ',', '') : '' }}"
                class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-12">
            <label for="data_admissao">Data de Admissão</label>
            <input type="text" placeholder="00/00/0000" name="data_admissao" id="data_admissao"
                value="{{ isset($user) ? dataBancoDeDadosParaDataString($user->usuarioMercado->data_admissao) : '' }}"
                class="form-control">
        </div>
        <div class="form-group col-md-6 col-12">
            <label for="data_demissao">Data de Demissão</label>
            <input type="text" placeholder="00/00/0000" name="data_demissao" id="data_demissao"
                value="{{ isset($user) ? dataBancoDeDadosParaDataString($user->usuarioMercado->data_demissao) : '' }}"
                class="form-control">
        </div>
    </div>
</div>

@if ($canCriarSenha)
    <div class="card card-body table-responsive elevated">
        <h5>Senha</h5>

        <div class="form-group">
            <label for="password">Senha <span style="color: red">*</span></label>
            <input required type="password" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar Senha <span style="color: red">*</span></label>
            <input required type="password" name="password_confirmation" id="password_confirmation"
                class="form-control">
        </div>
    </div>
@endif


<div class="card-body card-footer elevated">
    <a href="{{ route('cadastro.gecon.usuarios.index') }}" class="btn btn-outline-danger">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

    <button type="submit" class="btn btn-success">
        <i class="bi bi-arrow-right"></i> Salvar
    </button>
</div>

<script>
    $(document).ready(function() {
        toggleSwithSelecionadoAtivo();
        toggleSwithSelecionadoAbrirCaixa();
        buscarCep('cep', 'botaoBuscarCep', 'logradouro', 'bairro', 'cidade', 'uf', 'complemento');
        aplicarMascaras();
    });

    function aplicarMascaras() {
        /* cpf */
        $('#documento').mask('000.000.000-00');

        /* celular */
        $('#celular').mask('(00) 0 0000-0000');

        /* telefone */
        $('#telefone').mask('(00) 0000-0000');

        /* cep */
        $('#cep').mask('00.000-000');

        aplicarMascaraData('#data_nascimento', 'Data de nascimento');
        aplicarMascaraData('#data_admissao', 'Data de admissão');
        aplicarMascaraData('#data_demissao', 'Data de demissão');
    }

    function aplicarMascaraData(selector, campo) {
        $(selector).mask('00/00/0000', {
            onComplete: function(val) {
                const [dia, mes, ano] = val.split('/');
                const dataInvalida = ((dia > 31) || (mes > 12));

                if (dataInvalida) {
                    Swal.fire({
                        title: 'Atenção!',
                        text: campo + ' inválida!',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d',
                        confirmButtonText: 'Fechar',
                    });
                    $(selector).val('');
                }
            }
        });
    }

    function toggleSwithSelecionadoAbrirCaixa() {
        $('#permite_abrir_caixa').on('change', function() {
            const isChecked = $(this).is(':checked');
            var opcao = isChecked ? 'Sim' : 'Não';
            $('#label_permite_abrir_caixa').html(opcao);
        });
    }

    function toggleSwithSelecionadoAtivo() {
        $('#ativo').on('change', function() {
            const isChecked = $(this).is(':checked');
            var opcao = isChecked ? 'Sim' : 'Não';
            $('#label_ativo').html(opcao);
        });
    }

    const ROTA = @json(route('gecon.usuarios.obter_lojas_por_empresa', 'EMPRESA_ID'));

    function formatarCampo(input) {
        let cursorPos = input.selectionStart;

        let valorSemFormatacao = input.value.replace(/[^\d,]/g, '');
        let valorFormatado = formatarValor(valorSemFormatacao);

        input.value = valorFormatado;

        // Ajusta o cursor para o final (opcional, para manter a usabilidade)
        input.setSelectionRange(valorFormatado.length, valorFormatado.length);
    }

    function formatarValor(valor) {
        valor = valor.replace(/[^\d,]/g, '');

        let partes = valor.split(',');
        let parteInteira = partes[0];
        let parteDecimal = partes.length > 1 ? ',' + partes[1].slice(0, 2) : '';

        parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return parteInteira + parteDecimal;
    }
</script>
