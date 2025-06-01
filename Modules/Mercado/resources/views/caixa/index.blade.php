@extends('mercado::layouts.app')

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pdv/index.js', 'build/.vite')
    <div class="trilha-paginas-acessadas">
        <a href="{{ route('home.index') }}">Página inicial</a>
        <span>&nbsp;-&nbsp;</span>
        <p>Caixa</p>
    </div>

    <style>
        /* Estilos para o ícone de carregamento */
        .loading {
            display: inline-block;
            /* Muda para inline-block para não ocupar toda a largura */
            font-size: 16px;
            /* Tamanho do ícone */
            margin-left: 5px;
            /* Espaçamento */
            animation: spin 1s linear infinite;
            /* Adiciona a animação de rotação */
            vertical-align: middle;
            /* Alinha verticalmente com o texto */
        }

        /* Animação de rotação */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Modal -->
    <div class="modal fade" id="modalAbrirCaixa" tabindex="-1" role="dialog" aria-labelledby="modalExemploLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExemploLabel">
                        {{ !$caixaAtual ? 'Abrir caixa' : 'Trocar caixa de dispositivo' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <div class="alert alert-info d-flex align-items-center m-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2" style="font-size: 1rem;"></i>
                    <div class="mx-2" style="font-size: 0.9rem;">
                        Se esta guia for fechada ou aberta em outro local/dispositivo, depois que o caixa estiver aberto, será necessário confirmar a troca de
                        dispositivo.
                    </div>
                </div>

                <form action="{{ route('caixa.abrir') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

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
                            @if (!$caixaAtual)
                                <input type="hidden" name="transferir_dispositivo" value="{{ false }}">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="caixa_id">Caixas disponíveis: *</label>
                                        <select required id="caixa_id" name="caixa_id" class="form-control select2 w-100">
                                            @foreach ($caixaDiponiveis as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->nome . ' - ' . $c->loja->nome }}</option>
                                            @endforeach
                                        </select>
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
                        <div class="form-group">
                            <label for="valorInicial">Valor Inicial (Troco):</label>
                            <input type="text" required class="form-control" id="valorInicial" name="valorInicial"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="valorInicial">Observação:</label>
                            <textarea id="comentario" name="comentario" class="form-control"></textarea>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('home.index') }}" type="button" class="btn btn-outline-danger">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-unlock"></i> Abrir caixa
                        </button>
                    </div>
                @else
                    <input type="hidden" name="transferir_dispositivo" value="{{ true }}">
                    <div class="alert alert-warning col-12" role="alert">
                        <h6>Você pode trocar o caixa de dispositivo assim que ele estiver <b>LIVRE</b> .</h6>
                        <h6 id="textoForcarMudancaDeStatus"
                            class="{{ $caixaAtual->status_id == config('config.status.livre') ? 'd-none' : '' }}">Você pode
                            forçar a mudança de status, caso faça isso o caixa será desativado no outro dispositivo.
                            <button type="button" id="botaoForcarMudancaDeStatus" onclick="forcarMudancaDeStatus()"
                                class="btn btn-warning" style="font-size: 12px;">Forçar mudança de status</button>
                        </h6>

                        <h6><b>Status atual: </b> <u id="statuAtual">{{ $caixaAtual->getStatus() }}</u></h6>
                        <h6>
                            <b>Última atualização:</b>
                            <u
                                id="ultimaAtualizacao">{{ $caixaAtual->updated_at->format('d/m/Y') . ' às ' . $caixaAtual->updated_at->format('H:i:s') }}</u>
                            <i class="bi bi-arrow-clockwise loading"></i> <!-- Ícone de carregamento visível -->
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" required class="form-control" id="senha" name="senha" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('home.index') }}" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Fechar</a>
                        <button type="submit" id="botaoSubmitAbriCaixa"
                            {{ $caixaAtual->status_id != config('config.status.livre') ? 'disabled' : '' }}
                            class="btn btn-primary">Tranferir para este dispositivo</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div id="dataView" data-route-home="{{ route('home.index') }}" data-route-validate="{{ route('caixa.abrir') }}"
        data-route-get-caixa="{{ route('caixa.verificar.status') }}"
        data-route-update-status-caixa="{{ route('caixa.status.update') }}"
        data-rodar-feath="{{ $caixaAtual ? true : false }}" data-status-valido="{{ config('config.status.livre') }}"
        data-remove-storages="{{ isset($removeStrorages) && $removeStrorages === true }}"></div>

@endsection
