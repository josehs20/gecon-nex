<div class="modal fade" id="modalAutenticacaoUsuario" tabindex="-1" role="dialog" aria-labelledby="modalExemploLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExemploLabel">Autenticação</h5>
                <button type="button" class="close" onclick="fechaModalAutenticacao()" data-dismiss="modal"
                    aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form id="formAutenticar" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="rowr">
                        @if (session('success'))
                            <div class="alert alert-success col-12" role="alert">
                                {!! session('success') !!}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger col-12" role="alert">
                                {!! session('error') !!}
                            </div>
                        @endif
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario">Usuário:</label>
                                <input type="text" disabled required class="form-control" id="usuario"
                                    name="usuario" value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" required class="form-control" id="senha" name="senha"
                                    required>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-secondary" onclick="fechaModalAutenticacao()"
                        data-dismiss="modal">Sair</a>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
