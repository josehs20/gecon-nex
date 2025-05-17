<style>
    .campo-senha {
        display: flex;
        position: relative;
        align-items: center;
    }

    .campo-senha>button {
        border: none;
        background-color: transparent;
        position: absolute;
        right: 10px;

    }
</style>
<div class="modal fade" id="modalAlterarSenha" tabindex="-1" aria-labelledby="modalAlterarSenhaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlterarSenhaLabel">Alterar senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_alterar_senha" action="{{ route('configuracoes.perfil.alterar_senha.store') }}"
                    method="post">
                    <div class="form-group">
                        <label for="senha_atual">Senha atual</label>
                        <div class="campo-senha">
                            <input class="form-control" type="password" id="senha_atual" name="senha_atual" onkeyup="verificar_input_senha()">
                            <button id="btn_ver_senha_atual" type="button"><i id="icone_ver_senha_atual"
                                    class="bi bi-eye"></i></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nova_senha">Nova senha</label>
                        <div class="campo-senha">
                            <input class="form-control" type="password" id="nova_senha" name="nova_senha"
                                onkeyup="verificar_input_senha()">
                            <button id="btn_ver_nova_senha" type="button">
                                <i id="icone_ver_nova_senha" class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="nova_senha_info"></div>
                    </div>

                    <div class="form-group">
                        <label for="repetir_nova_senha">Repita a nova senha</label>
                        <div class="campo-senha">
                            <input class="form-control" type="password" id="repetir_nova_senha"
                                name="repetir_nova_senha" onkeyup="verificar_input_senha()">
                            <button id="btn_ver_repetir_nova_senha" type="button">
                                <i id="icone_ver_repetir_nova_senha" class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="repetir_nova_senha_info" class="mt-1"></div>
                    </div>
                    <input id="usuario_id_modal" name="usuario_id" type="hidden" value="{{ $usuario->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="bi bi-x"></i>
                    Fechar</button>
                <button id="btn_alterar_senha_store" type="button" class="btn btn-success"> <i
                        class="bi bi-floppy2-fill"></i> Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        alterar_senha_store();
        ver_senha('btn_ver_senha_atual', 'icone_ver_senha_atual', 'senha_atual');
        ver_senha('btn_ver_nova_senha', 'icone_ver_nova_senha', 'nova_senha');
        ver_senha('btn_ver_repetir_nova_senha', 'icone_ver_repetir_nova_senha', 'repetir_nova_senha');
    });

    /*
    *   @param input_id O ID do input que irá ter a borda alterada
    */
    function info_campo_vazio(input_id){
        html = `
            <span style="color: red; font-weight: bold; font-size: 0.8rem">
                Preencha os campos!
            </span>
        `;
        $('#repetir_nova_senha_info').html(html);     
        $(`#${input_id}`).css('border', '2px solid red');
    }


    function verificar_input_senha() {
        var html = '';
        var senha_atual = $('#senha_atual').val();
        var nova_senha = $('#nova_senha').val();
        var rep_nova_senha = $('#repetir_nova_senha').val();

        // Verifica se a senha atual está vazia
        if (senha_atual === '') {
            info_campo_vazio('senha_atual');
            return false;
        } else {
            $('#senha_atual').css('border', '2px solid green');
        }

        if(senha_atual !== '' && nova_senha !== ''){
            if (nova_senha !== rep_nova_senha) {
                html = `
                    <span style="color: red; font-weight: bold; font-size: 0.8rem">
                        As senhas devem ser iguais!
                    </span>
                `;
                $('#repetir_nova_senha_info').html(html);
                $('#nova_senha, #repetir_nova_senha').css('border', '2px solid red');
                return false;
            } else {
                $('#repetir_nova_senha_info').empty();
                $('#nova_senha, #repetir_nova_senha').css('border', '2px solid green');
                return true;
            }
        } else {
            info_campo_vazio('nova_senha');
            return false;
        }
    }


    function alterar_senha_store() {

        $('#btn_alterar_senha_store').on('click', function() {

            var formData = {};
            var nova_senha = $('#nova_senha').val();
            var rep_nova_senha = $('#repetir_nova_senha').val();

            $('#form_alterar_senha').find('input').each(function() {
                var input = $(this);
                formData[input.attr('name')] = input.val();
            });

            formData['_token'] = '{{ csrf_token() }}';

            /* Verificar se os campos nova senha e repetir nova senha tem as senhas iguais, nao permitir enviar os inputs vazios */
            if (verificar_input_senha()) {
                $.ajax({
                    url: $('#form_alterar_senha').attr('action'),
                    type: "POST",
                    data: formData,
                    success: function(xhr, response) {
                        if (xhr.success) {
                            toastr.success(xhr.message);
                            
                            $('.modal-backdrop').remove(); // Remove o backdrop
                            $('#modalAlterarSenha').hide(); // Esconde o modal
                            
                            // Limpa os inputs do formulário
                            $('#form_alterar_senha').find('input[type="password"]').val('');
                            $('#form_alterar_senha').find('input[type="text"]').val('');

                            // Restaura as bordas para as cores iniciais
                            $('#nova_senha, #repetir_nova_senha, #senha_atual').css('border', '');
                        } else {
                            toastr.error(xhr.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.warning(xhr.message);
                    }
                });
            }
        });
    }
</script>
