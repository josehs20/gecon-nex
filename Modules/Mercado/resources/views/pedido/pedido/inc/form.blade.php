  @vite('Modules/Mercado/resources/assets/js/views/pedido/pedido/inc/form.js', 'build/.vite')


  <div class="cards-container position-relative">
      <!-- Formulário para adicionar itens -->
      @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
          <div class="card card-body position-relative">
              <form id="adicionar-item-pedido" method="POST">
                  @csrf
                  <div class="row d-flex justify-content-between align-items-center">
                      <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert"
                          style="width: auto;">
                          <i class="bi bi-info-circle-fill me-1"></i>
                          <span style="color: black!important; font-size: 0.875rem;">
                              Ao repetir um item, ele será atualizado na lista.
                          </span>
                      </div>
                      <div class="col-md-10">
                          <div class="row align-items-center">
                              <div class="col-md-8">
                                  <div class="form-group">
                                      <label for="estoque_id">Selecione o produto: *</label>
                                      <select required id="estoque_id" name="estoque_id"
                                          class="form-control select2"></select>
                                  </div>
                              </div>
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="quantidade">Quantidade: *</label>
                                      <input required type="text" id="quantidade" name="quantidade"
                                          class="form-control" value="">
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <button type="submit" class="btn btn-dark" style="float: right">
                              <i class="bi bi-check"></i> Adicionar
                          </button>
                      </div>
                  </div>
              </form>
          </div>
      @endif
      <!-- Lista de itens e finalização -->
      <div class="card card-body position-relative mt-5">
          <div class="col-auto">
              @if ($pedido)
                  <ul class="list-inline m-0">
                      <li class="list-inline-item">
                          • <strong>Solicitado por:</strong> {{ $pedido->usuario->master->name ?? '-' }}
                      </li>
                      <li class="list-inline-item">
                          • <strong>Loja:</strong> {{ $pedido->loja->nome ?? '-' }}
                      </li>
                      <li class="list-inline-item">
                          • <strong>Data início:</strong> {{ formatarData($pedido->created_at) ?? '-' }}
                      </li>
                      <li class="list-inline-item">
                          • <strong>Data limite:</strong> {{ formatarData($pedido->data_limite) ?? '-' }}
                      </li>
                      <li class="list-inline-item">
                          • <strong> <span class="{{ $pedido && $pedido->status ? $pedido->status->badge() : '' }}">
                                  STATUS: {{ $pedido && $pedido->status ? $pedido->status->descricao() : '' }}
                              </span></strong>
                      </li>
                  </ul>
              @endif
          </div>

          <div class="row mt-4">
              <div class="col-md-10">
                  <h5 style="color: black !important;">Produtos selecionados</h5>
              </div>
          </div>
          <table id="tabela-item-pedido" class="table table-bordered table-hover">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Produto</th>
                      <th>Quantidade</th>
                      <th>Status</th>
                      @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
                          <th>Ação</th>
                      @endif
                  </tr>
              </thead>
              <tbody></tbody>
          </table>

          <form id="finalizar_pedido_post" action="{{ route('cadastro.pedido.post') }}" method="POST">
              @csrf
              <div class="row">
                  <input type="hidden" name="pedido_id" value="{{ $pedido->id ?? '' }}">
                  @php
                      $dataLimite =
                          $pedido && $pedido->data_limite
                              ? \Carbon\Carbon::parse($pedido->data_limite)->format('Y-m-d')
                              : '';
                      $minDate = \Carbon\Carbon::now()->format('Y-m-d');
                      $disabled = $pedido && $pedido->status_id != config('config.status.aberto') ? 'readonly' : '';
                  @endphp

                  <div class="col-md-12 mt-3">
                      <label for="data_limite" class="form-label">Data limite *</label>
                      <input required type="date" name="data_limite" id="data_limite" class="form-control"
                          value="{{ $dataLimite }}" min="{{ $minDate }}" {{ $disabled }}>
                  </div>



                  <div class="col-md-12 mt-3">
                      <label for="observacao">Observações *</label>

                      <textarea {{ $pedido && $pedido->status_id != config('config.status.aberto') ? 'readonly' : '' }} required
                          class="form-control" name="observacao" id="observacao" rows="3"
                          placeholder="Informações adicionais sobre o pedido">{{ $pedido->observacao ?? '' }}</textarea>
                  </div>
                  @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
                      <div class="col-md-12 mt-3 mx-4">
                          <input class="form-check-input" type="checkbox" id="confirmacaoPedido" required
                              style="transform: scale(1.5);">
                          <label class="form-check-label" for="confirmacaoPedido">
                              Declaro que estou ciente dos itens e quantidades neste pedido.
                          </label>
                      </div>
                  @endif

              </div>

              <input type="hidden" name="itens" id="itens">
              <input type="hidden" name="finalizar" id="finalizar" value="false">

              <div class="card-footer mt-4">
                  <a href="{{ route('cadastro.pedido.index') }}" class="btn btn-danger">
                      <i class="bi bi-arrow-left"></i> Voltar
                  </a>
                  @if ($pedido && $pedido->status_id == config('config.status.aguardando_cotacao'))
                      <button id="btn_alterar_pedido" type="button" class="btn btn-info">
                          <i class="bi bi-pencil"></i> Alterar pedido
                      </button>
                  @endif
                  @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
                      <button id="btn_salvar_pedido" type="button" class="btn btn-info">
                          <i class="bi bi-floppy"></i> Salvar pedido
                      </button>
                      <button id="btn_finalizar_pedido" type="button" class="btn btn-dark">
                          <i class="bi bi-check"></i> Finalizar pedido
                      </button>
                  @endif
              </div>
          </form>
      </div>
  </div>
  <div id="dataView" data-get-produtos="{{ route('estoques.select2') }}"
      data-itens-no-pedido="{{ session()->has('itens_no_pedido') ? session()->get('itens_no_pedido') : null }}"
      data-pedido="{{ $pedido }}"
      data-botao="{{ !$pedido || $pedido->status_id == config('config.status.aberto') }}"></div>
