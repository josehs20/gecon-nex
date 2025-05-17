@if (session()->has('menu'))
    @if (auth()->user())
        <style>
            .nav-item {
                padding: 0;
            }

            .nav-link {
                padding: 10px 20px;
                color: #525252 !important;
            }

            .sub-menu {
                margin-left: -20px;
            }

            .sub-menu .nav-link {
                padding: 8px 40px;
            }
            .side-inner{
                background-color: #0A0A1A !important;
            }
            .side-inner a {
                color: #fff !important;
            }
        </style>

        <div class="nav-menu">
            <ul class="nav flex-column">
                {{-- <li class="nav-item ml-3" style="display: flex; align-items:center;">
                    <h6 style="margin: 0"><strong>DASHBOARD</strong></h6>
                </li> --}}
                {{-- <br> --}}

                @foreach (session('menu') as $key => $menu)
                    @php
                        $contem_submenus = collect($menu)->contains(function($sub){
                            return is_array($sub);
                        });
                    @endphp
                
                    @if ($contem_submenus)
                        <li class="nav-item">
                            <a class="nav-link"><strong>{{ $menu['nome'] }}</strong></a>
                            <div>
                                <ul class="sub-menu nav flex-column">

                                    @foreach (array_keys($menu['subMenus']) as $subMenuName)
                                        @if (array_key_exists($subMenuName, $menu['subMenus']) && count($menu['subMenus'][$subMenuName]) > 0)
                                            <li class="nav-item mx-3">

                                                <a class="nav-link collapsed toggle-menu"
                                                style="display: flex; justify-content:space-between"
                                                data-toggle="collapse"
                                                href="#{{ $subMenuName }}_{{ $key }}"
                                                aria-expanded="false">
                                                {{ ucfirst($subMenuName) }}
                                                <i class="bi bi-caret-down toggle-icon"></i>
                                            </a>

                                                <div id="{{ $subMenuName }}_{{ $key }}" class="collapse">
                                                    <ul class="nav flex-column">

                                                        @foreach ($menu['subMenus'][$subMenuName] as $processo)
                                                            <li class="nav-item mx-3">
                                                                <a class="nav-link"
                                                                    href="{{ route($processo->rota) }}">{{ $processo->nome }}</a>
                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>

                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route($menu['subMenus']->rota) }}"><strong>{{ $menu['nome'] }}</strong></a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
@endif
<script>
    $(document).ready(function () {
    $(".toggle-menu").on("click", function () {
        let icon = $(this).find(".toggle-icon");

        // Alterna entre as classes de seta para cima e para baixo
        if (icon.hasClass("bi-caret-down")) {
            icon.removeClass("bi-caret-down").addClass("bi-caret-up");
        } else {
            icon.removeClass("bi-caret-up").addClass("bi-caret-down");
        }
    });
});

</script>
