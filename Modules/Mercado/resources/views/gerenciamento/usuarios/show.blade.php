<div class="modal fade" id="showUsuariosModal" tabindex="-1" aria-labelledby="showUsuariosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title d-flex justify-content-between w-100" id="showUsuariosModalLabel">
                    <h5 id="nomeUsuarioModal"></h5>
                    <h5 id="tipoUsuarioModal"></h5>
                </div>
            </div>
            <div class="modal-body" id="dadosUsuarioModal">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="fecharModal()">Fechar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function showUsuario(usuario){
        preencherTituloModal(usuario);
        preencherDadosModal(usuario);
        $('#showUsuariosModal').modal('show');
    }

    function preencherTituloModal(usuario){
        $('#nomeUsuarioModal').html(`<h5>${usuario.name}</h5>`);
        $('#tipoUsuarioModal').html(`<span class="badge badge-dark">${usuario.tipo_usuario.descricao}</span>`);
    }

    function preencherDadosModal(usuario){
        $('#dadosUsuarioModal').html(`
            ${renderizarDadosPessoais(usuario)}
            <hr>
            ${renderizarDadosEmpresa(usuario)}
            <hr>
            ${renderizarDadosProfissao(usuario)}
            <hr>
            ${renderizarPermissoes(usuario)}
        `);
    }

    function renderizarDadosPessoais(usuario){
        return `
            <h5> <strong>Dados pessoais</strong> </h5>
            <span class='ml-3'> <strong> E-mail             : </strong> ${usuario.email}                                                                    </span> <br>
            <span class='ml-3'> <strong> Documento          : </strong> ${aplicarMascaraDocumento(usuario.usuario_mercado.documento ?? 'Não informado')}                       </span> <br>
            <span class='ml-3'> <strong> Data de nascimento : </strong> ${aplicarMascaraData(usuario.usuario_mercado.data_nascimento) ?? 'Não informado'}   </span> <br>
            <span class='ml-3'> <strong> Telefone           : </strong> ${aplicarMascaraTelefoneFixo(usuario.usuario_mercado.telefone) ?? 'Não informado'}  </span> <br>
            <span class='ml-3'> <strong> Celular            : </strong> ${aplicarMascaraCelular(usuario.usuario_mercado.celular) ?? 'Não informado'}        </span> <br>
            <span class='ml-3'> <strong> Endereço           : </strong> ${renderizarEndereco(usuario.usuario_mercado)}                                      </span> <br>
        `;
    }

    function renderizarDadosEmpresa(usuario){
        return `
            <div class='d-flex justify-content-between'>
                <h5> <strong>Dados da empresa</strong></h5>
                <h5>${getSpanAtivo(usuario.usuario_mercado.ativo)}</h5>    
            </div>
            <span class='ml-3'> <strong> Login              : </strong> ${usuario.login}                                                                    </span> <br>
            <span class='ml-3'> <strong> Loja               : </strong> ${(usuario.usuario_mercado.loja.nome).toUpperCase()}                                </span> <br>
            <span class='ml-3'> <strong> Abrir caixa        : </strong> ${usuario.permite_abrir_caixa}                                                      </span> <br>
        `;
    }

    function renderizarDadosProfissao(usuario){
        return `
            <h5> <strong>Dados profissionais </strong> </h5>
            <span class='ml-3'> <strong> Salário             : </strong> R$ ${trocarPontoPorVirgula(usuario.usuario_mercado.salario)}                       </span> <br>
            <span class='ml-3'> <strong> Comissão            : </strong> ${trocarPontoPorVirgula(usuario.usuario_mercado.comissao)} %                       </span> <br>
            <span class='ml-3'> <strong> Tipo do contrato    : </strong> ${usuario.usuario_mercado.tipo_contrato ?? 'Não informado'}                        </span> <br>
            <span class='ml-3'> <strong> Data de admissão    : </strong> ${aplicarMascaraData(usuario.usuario_mercado.data_admissao) ?? 'Não informado'}    </span> <br>
            <span class='ml-3'> <strong> Data de demissão    : </strong> ${aplicarMascaraData(usuario.usuario_mercado.data_demissao) ?? 'Não informado'}    </span> <br>
        `;
    }

    function renderizarEndereco(usuario){
        let endereco = usuario.enderecos;

        if(endereco){
            let logradouro = endereco.logradouro;
            let numero = endereco.numero;
            let bairro = endereco.bairro;
            let cidade = endereco.cidade;
            let uf = endereco.uf;
            let complemento = endereco.complemento;
            let cep = endereco.cep;
            
            return `${logradouro}, ${numero ? numero + ', ' : ''} ${bairro}, ${cidade} - ${uf}, ${cep}${complemento ? ', ' + complemento : ''}.`;
        }
        
        return 'Não informado';
    }

    function renderizarPermissoes(usuario){
        let processos = usuario.tipo_usuario.processos;
        let html = `<h5> <strong>Permissões </strong> </h5>`;
        
        if (processos.length === 0) {
            html += `<span class="badge badge-info w-100"> Não existem permissões para este grupo de usuário! </span>`;
            return html;
        }
        
        processos.forEach(function(processo){
            html += `
                <span class='ml-4'> <strong> <i class="bi bi-arrow-right"></i> </strong>  ${processo.processo.descricao}                       </span> <br>
            `;
        });
        return html;
    }

    function fecharModal(){
        $('#showUsuariosModal').find(':focus').blur();
        $('#showUsuariosModal').modal('hide');
    }

    function getSpanAtivo($ativo){
        if($ativo){
            return "<a class=' badge badge-success'>Ativo</a>";
        }
        return "<a class=' badge badge-danger'>Inativo</a>";
    }
</script>