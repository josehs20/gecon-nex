<style>
    .configuracoes-menu ul li{
        list-style: none;
        padding: 5px 15px;
        width: 70%;
    }
    .configuracoes-menu .selecionado{
        background-color: #fff;
        padding: 5px 15px;
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
        border-left: 3px solid #ff7b3e;
    }
    .configuracoes-menu a:hover{
        font-weight: normal;
    }
    .configuracoes-menu ul li:hover{
        background-color: #fff;
        padding: 5px 15px;
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
        border-left: 3px solid #ff7b3e;
    }
</style>

<div class="configuracoes-menu">
    <ul>
        <li class="@if(Request::segment(2) === 'perfil') selecionado @endif">
            <a href="{{route('configuracoes.perfil')}}"><i class="bi bi-person"></i>&nbsp;Perfil</a>
        </li>
        {{-- <li class="@if(Request::segment(2) === 'teste') selecionado @endif">
            <a href="{{route('configuracoes.perfil')}}"><i class="bi bi-person"></i>&nbsp;teste</a>
        </li> --}}
    </ul>
</div>