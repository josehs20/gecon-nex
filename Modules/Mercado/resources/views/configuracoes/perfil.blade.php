@extends('mercado::configuracoes.index')

@section('configuracoes-conteudo')
    <style>
        .linha1 {
            display: flex;
            justify-content: space-between;
            align-items: center
        }

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
    <div class="">
        <h4>Perfil</h4>
        <hr>
        <form id="form_salvar_perfil" action="{{ route('configuracoes.perfil.store') }}" method="POST">
            @csrf
            <div>
                <div class="form-group">
                    <div class="linha1">
                        <label for="nome">Nome</label>
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modalAlterarSenha">
                            <i class="bi bi-house-lock-fill"></i> Alterar senha
                        </button>
                    </div>
                    <input id="nome" name="nome" class="form-control w-75" type="text"
                        value="{{ $usuario->name }}">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" class="form-control" type="text" value="{{ $usuario->email }}">
                </div>
                <div class="form-group">
                    <label for="loja">Loja</label>
                    <div class="">
                        @foreach ($usuario->usuarioMercado->lojas as $loja)
                            <input id="loja_{{ $loja->id }}" name="loja_{{ $loja->id }}" class="form-control mb-1"
                                type="text" value="{{ $loja->nome }}" disabled>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="empresa">Empresa</label>
                    <div>
                        <input id="empresa" name="empresa" class="form-control" type="text"
                            value="{{ $usuario->usuarioMercado->loja->empresa->razao_social }}" disabled>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="campo-senha">
                        <input id="usuario_id" name="usuario_id" type="hidden" value="{{ $usuario->id }}">
                        <input id="senha" name="senha" class="form-control" type="password"
                            placeholder="Digite sua senha para salvar os dados">
                        <button id="btn_ver_senha" type="button"><i id="icone_ver_senha" class="bi bi-eye"></i></button>
                    </div>
                </div>
            </div>

            <button id="btn_salvar_perfil" style="float: right" class="btn btn-success" type="button"><i
                    class="bi bi-floppy2-fill"></i>&nbsp;Salvar</button>
        </form>
    </div>

    @include('mercado::configuracoes.modalAlterarSenha')

    <script>
        $(document).ready(function() {
            perfil_store();
            ver_senha('btn_ver_senha', 'icone_ver_senha', 'senha');
        });

        /* Realiza a requisicao para salvar os dados */
        function perfil_store() {
            $('#btn_salvar_perfil').on('click', function() {
                var senha = $('#senha').val();

                var formData = {};

                $('#form_salvar_perfil').find('input').each(function() {
                    var input = $(this);
                    formData[input.attr('name')] = input.val();
                });

                formData['_token'] = '{{ csrf_token() }}';

                /* atualiza os dados só se for informada a senha atual do usuario */
                if (senha === '') {
                    toastr.warning('Informe a senha para atualizar os dados!');
                } else {
                    $.ajax({
                        url: $('#form_salvar_perfil').attr('action'),
                        type: "POST",
                        data: formData,
                        success: function(xhr, response) {
                            if (xhr.success) {
                                $('#senha').val('');
                                icone = $('#icone_ver_senha');
                                icone.removeClass();
                                $('#senha').attr('type', 'password');
                                icone.addClass('bi-eye');
                                toastr.success(xhr.message);                             
                            } else {
                                toastr.error(xhr.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr, status, error);
                        }
                    });
                }
            });
        }

        /* Funciona ao clicar no icone de visualizar senha, alterna o icone e permite ver qual a senha digitada 
         *   @param botao_id O ID botao que ao clicar muda os atributos do input de senha
         *   @param icone_id O ID do icone o qual é o texto, conteudo do botao
         *   @param input_id O input que digita a senha
         */
        function ver_senha(botao_id, icone_id, input_id) {
            var botao = $(`#${botao_id}`);
            var icone = $(`#${icone_id}`);
            var campoSenha = $(`#${input_id}`);

            botao.on('click', function() {
                if (campoSenha.attr('type') === 'password') {
                    campoSenha.attr('type', 'text');
                    icone.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    campoSenha.attr('type', 'password');
                    icone.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });
        }
    </script>
@endsection
