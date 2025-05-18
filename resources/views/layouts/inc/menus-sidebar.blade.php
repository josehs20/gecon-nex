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
                    <li class="nav-item">
                        <a class="nav-link"><strong>{{ $menu['nome'] }}</strong></a>
                        <div>
                            <ul class="sub-menu nav flex-column">
                                @if (is_array($menu['subMenus']))
                                @foreach (array_keys($menu['subMenus']) as $subMenuName)
                                    @if (array_key_exists($subMenuName, $menu['subMenus']) && count($menu['subMenus'][$subMenuName]) > 0)
                                        <li class="nav-item mx-3">

                                            <a class="nav-link collapsed"
                                                style="display: flex; justify-content:space-between"
                                                data-toggle="collapse" href="#{{ $subMenuName }}_{{ $key }}"
                                                aria-expanded="false">
                                                {{ ucfirst($subMenuName) }}
                                                <i class="bi bi-caret-down"></i>
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
                                @else
                                <li class="nav-item mx-3">
                                    <a class="nav-link"

                                        href="{{ route($menu['subMenus']->rota) }}">{{ $menu['subMenus']->nome }}</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
