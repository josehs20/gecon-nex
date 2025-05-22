<style>
    .navbar-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 90px;
        padding-left: 50px;
        padding-right: 25px;
        background-color: #0A0A1A;
    }

    .navbar-custom span {
        color: #fff;
    }

    .navbar-custom a:hover {
        font-weight: bold;
    }

    .navbar-custom .info {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .ul-lista {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
    }

    .ul-lista li {
        margin-right: 10px;
    }

    .lista-de-links {
        display: flex;
        align-items: center;
    }

    .notification-icon {
        position: relative;
        cursor: pointer;
        color: green;
        /* Cor do sino */
        font-size: 30px;
        /* Ajusta o tamanho do ícone */
    }

    .notification-icon .badge {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: blue;
        /* Cor do contador */
        color: white;
        border-radius: 50%;
        padding: 2px 5px;
        font-size: 12px;
    }

    .l {
        font-weight: 700;
        color: #fff !important;
    }

    /* Estilos para a segunda navbar */
    .navbar-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        /* Centraliza o texto */
        height: 50px;
        /* Altura da navbar */
        background-color: #f8f9fa;
        /* Cor de fundo */
        font-size: 18px;
        /* Tamanho da fonte */
        font-weight: bold;
        /* Fonte em negrito */
        color: #333;
        /* Cor do texto */
    }
</style>

<nav class="navbar-custom">
    <div class="lista-de-links">
        {{-- <ul class="ul-lista">
            <a href="{{ route('home.index') }}"> <!-- Substitua "/sua-url-aqui" pela URL desejada -->
                <img src="{{ asset('img/logo_gecon.jpeg') }}" alt="Imagem" class="" style="height: 90px">
            </a>

        </ul> --}}
    </div>
    <div class="info">
        @if (!Agent::isMobile())
            <div>
                <span>Você está na <u><span
                            class="l">{{ auth()->user()->usuarioMercado->loja->nome ?? '' }}</span></u>
                </span>
            </div>
        @endif

        <div class="btn-group dropleft mx-5">
            <div class="notification-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="bi bi-bell"></span>
                <span class="badge">3</span> <!-- Número de notificações -->
            </div>
            <div class="dropdown-menu">
                <a class="dropdown-item" style="color: black !important;" href="#">Notificação 1</a>
                <hr>

            </div>
        </div>
        <div class="btn-group dropleft">
            <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="{{ asset('img/logo_gecon.jpg') }}" width="40" height="40" alt="Usuário"
                    class="user-avatar rounded-circle"> <!-- Caminho da imagem do usuário -->
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" style="color: black !important;" href="{{ route('configuracao.index') }}">
                    <span class="bi bi-gear"></span>&nbsp;Configurações
                </a>
                <hr>
                <a class="dropdown-item" style="color: black !important;"href="{{ route('configuracao.index') }}">
                    <span class="bi bi-shop"></span>&nbsp;Trocar de loja
                </a>
                <hr>
                <a class="dropdown-item"style="color: black !important;"     id="logout-button" href="#">
                    <span class="bi bi-box-arrow-right"></span>&nbsp;Sair
                </a>
            </div>
        </div>
    </div>
</nav>

@if (Agent::isMobile())
    <!-- Segunda Navbar -->
    <nav class="navbar-secondary" style="background-color: #fff;">
        <div>
            <span>Você está na <u><span class="l">{{ auth()->user()->usuarioMercado->loja->nome ?? '' }}</span></u>
            </span>
        </div>
    </nav>
@endif



<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirmar Logout</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="alert alert-info col-12 text-center" role="alert">
                    <h3><b><u>ATENÇÃO</u></b></h3>
                    <br>
                    <p class="lead mb-0 p-2">Se você sair do caixa sem fechá-lo, ele permanecerá em aberto. Será
                        necessário confirmar a troca de dispositivo ao acessá-lo novamente.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="$('#logoutModal').modal('hide');">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="redirectFecharCaixa()">Realizar
                    fechamento</button>
                <button type="button" onclick="confirmarLogout()" class="btn btn-success"
                    id="confirm-logout">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<form id="formLogout" action="{{ route('logout') }}" method="post">
    @csrf
</form>
@php
    $routeFecharCaixa = auth()->user()->getUserModulo->caixa
        ? route('caixa.fechar.index', ['caixa_id' => auth()->user()->getUserModulo->caixa->id])
        : null;
@endphp
<script>
    var confirmLogountComCaixa = @json(auth()->user()->getUserModulo->caixa);
    var routeValidaSessionCaixaLogout = @json(route('caixa.verificar.status'));
    var statusValido = @json(config('config.status.livre'));
    var routeUpdateStatusCaixa = @json(route('caixa.status.update'));
    if (confirmLogountComCaixa && confirmLogountComCaixa.id) {
        var routeFecharCaixa = @json($routeFecharCaixa);
    }

    function logout() {
        $('#formLogout').submit();
    }
    @if (session('limpaStorage'))
        {
            limpaStorage();
        }
    @endif
    function confirmarLogout() {
        limpaStorage()

        $.ajax({
            url: routeUpdateStatusCaixa, // Rota de logout
            type: 'POST', // Método POST
            data: {
                _token: '{{ csrf_token() }}',
                status_id: statusValido,
                confirm_logout: true
            },
            success: function(response) {
                console.log(response);

                if (response.success == true) {
                    // Redireciona ou atualiza a página após logout
                    logout();
                } else {
                    msgToastr(
                        'Não foi possível realizar o logout, tente novamente em alguns instantes.')
                }


            },
            error: function(xhr) {
                msgToastr('Não foi possível realizar o logout, tente novamente em alguns instantes.')

            }
        });
    }

    function limpaStorage() {
        localStorage.clear();

    }

    function redirectFecharCaixa() {
        window.location.href = routeFecharCaixa;

    }
  
</script>
