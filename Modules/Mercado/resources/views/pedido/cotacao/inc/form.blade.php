    @vite('Modules/Mercado/resources/assets/js/views/pedido/cotacao/inc/form.js', 'build/.vite')

    <style>
        .fornecedor-card {
            position: relative;
            /* Necessário para posicionar a barra */
            overflow: hidden;
            /* Evita que a barra ultrapasse os limites do card */
        }

        .selection-bar {
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            /* Largura da barra */
            height: 100%;
            /* Ocupa toda a altura do card */
            background-color: transparent;
            /* Invisível por padrão */
            transition: background-color 0.3s ease;
            /* Transição suave */
        }

        .fornecedor-selecionado .selection-bar {
            background-color: #007bff;
            /* Cor da barra (azul) quando selecionado */
        }

        .fornecedor-selecionado {
            background-color: #f8f9fa;
            /* Fundo claro para reforçar o destaque */
        }
    </style>
    <div class="">
        <div class="row justify-content-start">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        @if ($cotacao)
                            <ul class="list-inline m-0">
                                <li class="list-inline-item">• <strong>Criada por:</strong>
                                    {{ $cotacao->usuario->master->name ?? '-' }}</li>
                                <li class="list-inline-item">• <strong>Loja:</strong> {{ $cotacao->loja->nome ?? '-' }}
                                </li>
                                <li class="list-inline-item">• <strong>Data abertura:</strong>
                                    {{ aplicarMascaraDataNascimento($cotacao->data_abertura ?? now()) }}</li>
                                <li class="list-inline-item">• <strong>Data encerramento:</strong>
                                    {{ aplicarMascaraDataNascimento($cotacao->data_encerramento) ?? '-' }}</li>
                                <li class="list-inline-item">
                                    • <strong>
                                        <span id="statusCotacao"
                                            class="{{ $cotacao && $cotacao->status ? $cotacao->status->badge() : '' }}">
                                            STATUS:
                                            {{ $cotacao && $cotacao->status ? $cotacao->status->descricao() : '' }}
                                        </span>
                                    </strong>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1">
            @if ($cotacao && $cotacao->cot_fornecedores->count() > 0)
                <div class="row">
                    <!-- Metade esquerda: lista de fornecedores -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white" style="padding: 1rem 0.8rem !important;">
                                Fornecedores na cotação
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach ($cotacao->cot_fornecedores as $cf)
                                    <div class="card mb-3 shadow-sm border-0 rounded-lg fornecedor-card"
                                        data-fornecedor-id="{{ $cf->fornecedor_id }}">
                                        <div class="selection-bar"></div>
                                        <div class="card-body pb-2">
                                            <h5 class="card-title mb-1 font-weight-bold text-primary">
                                                {{ $cf->fornecedor->nome ?? 'Fornecedor' }}
                                            </h5>
                                            <p class="mb-0 text-muted small">
                                                {{ $cf->fornecedor->email ?? 'Sem e-mail cadastrado' }}
                                            </p>
                                        </div>
                                        <div class="card-footer d-flex align-items-center">
                                            <button type="button"
                                                class="btn btn-info d-flex align-items-center btn-sm mostrar-cotacao"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}">
                                                Pendentes
                                                <span class="badge badge-warning ml-2"
                                                    id="pendentes-{{ $cf->fornecedor_id }}">{{ $cf->cot_for_itens->count() }}</span>
                                            </button>

                                            <div class="ml-sm-1">
                                                <smal style="font-size: 0.9rem" for="subtotal-{{ $cf->fornecedor_id }}"
                                                    class="form-label custom-label">Subtotal:</smal>
                                                <input type="text" readonly
                                                    class="form-control form-control-sm sub-total-fornecedor"
                                                    id="subtotal-{{ $cf->fornecedor_id }}"
                                                    data-fornecedor-id="{{ $cf->fornecedor_id }}" placeholder="0,00">
                                            </div>
                                            <div class="ml-sm-1">
                                                <smal style="font-size: 0.9rem" for="total-{{ $cf->fornecedor_id }}"
                                                    class="form-label custom-label">Total:</smal>
                                                <input type="text" readonly
                                                    class="form-control form-control-sm total-fornecedor"
                                                    id="total-{{ $cf->fornecedor_id }}"
                                                    data-fornecedor-id="{{ $cf->fornecedor_id }}" placeholder="0,00">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Metade direita: conteúdo dinâmico do fornecedor -->
                    <div class="col-md-8" id="conteudoFornecedorEmCotacao">
                        @if ($cotacao->cot_fornecedores->count() > 0)
                            @foreach ($cotacao->cot_fornecedores as $cf)
                                <div class="card shadow-sm cotacao-fornecedor d-none"
                                    id="cotacao-fornecedor-{{ $cf->fornecedor_id }}">
                                    <div class="card-header bg-dark text-white"
                                        style="padding: 1rem 0.8rem !important;">
                                        Cotação do fornecedor: {{ $cf->fornecedor->nome ?? 'Fornecedor' }}
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-bordered" id="tabela-{{ $cf->fornecedor->id }}"
                                            width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Nº Pedido(s)</th>
                                                    <th>Produto</th>
                                                    <th>Qtd solicitada</th>
                                                    <th>Preço unitário</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $permitirAlterarInputs = !(
                                                        $cotacao->status_id == config('config.status.cotado')
                                                    );
                                                @endphp
                                                @foreach ($cf->cot_for_itens as $item)
                                                    <tr>
                                                        <td>{{ $item->pedidos_agrupados }}</td>
                                                        <td>{{ $item->produto->getNomeCompleto() }}</td>
                                                        <td>{{ number_format($item->quantidade, 3, ',', '.') }}</td>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm preco-unitario"
                                                                data-item-id="{{ $item->pedido_item_id }}"
                                                                data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                                data-quantidade="{{ $item->quantidade }}"
                                                                step="0.01" min="0" placeholder="0,00"
                                                                value="{{ $item->preco_unitario() }}"
                                                                {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm total-item"
                                                                data-item-id="{{ $item->pedido_item_id }}" readonly
                                                                placeholder="0,00">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label for="frete-{{ $cf->fornecedor_id }}">Frete</label>
                                                <input type="text" class="form-control frete-fornecedor"
                                                    id="frete-{{ $cf->fornecedor_id }}"
                                                    data-fornecedor-id="{{ $cf->fornecedor_id }}" step="0.01"
                                                    min="0" placeholder="0,00" value="{{ $cf->frete() }}"
                                                    {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="previsao-entrega-{{ $cf->fornecedor_id }}">Previsão de
                                                    Entrega</label>
                                                <input type="date" class="form-control"
                                                    id="previsao-entrega-{{ $cf->fornecedor_id }}"
                                                    data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                    value="{{ $cf->previsao_entrega }}" min="{{ date('Y-m-d') }}"
                                                    {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="desconto-{{ $cf->fornecedor_id }}">Desconto</label>
                                                <input type="text" class="form-control desconto-fornecedor"
                                                    id="desconto-{{ $cf->fornecedor_id }}"
                                                    data-fornecedor-id="{{ $cf->fornecedor_id }}" placeholder="0,00"
                                                    value="{{ $cf->desconto() }}"
                                                    {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label for="observacao-{{ $cf->fornecedor_id }}">Observação</label>
                                                <textarea class="form-control" id="observacao-{{ $cf->fornecedor_id }}"
                                                    data-fornecedor-id="{{ $cf->fornecedor_id }}" rows="3" {{ $permitirAlterarInputs ? '' : 'disabled' }}>{{ $cf->observacao }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer mt-4">
            <a href="{{ route('cadastro.cotacao.selecionar_pedidos') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            @if ($cotacao && $cotacao->status_id == config('config.status.cotado'))
                <button id="btn_alterar_cotacao" type="button" class="btn btn-info">
                    <i class="bi bi-pencil"></i> Alterar cotação
                </button>
            @elseif($cotacao && $cotacao->status_id != config('config.status.cancelado'))
                <button id="btn_salvar_cotacao" type="button" class="btn btn-info">
                    <i class="bi bi-floppy"></i> Salvar cotação
                </button>
                <button id="btn_finalizar_cotacao" type="button" class="btn btn-dark">
                    <i class="bi bi-check"></i> Finalizar cotação
                </button>
            @endif

        </div>
    </div>

    <div id="dataView" data-route-salvar-cotacao="{{ route('cadastro.cotacao.update') }}"
        data-cotacao="{{ $cotacao }}"></div>
