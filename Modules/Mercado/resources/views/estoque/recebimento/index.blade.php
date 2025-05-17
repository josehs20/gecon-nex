@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Recebimento']]])

@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Recebimento</h3>
            <p class="lead">Nesta tela você pode ver os recebimentos realizados, ou realizar um novo.</p>
        </div>
        <div>
            <a href="{{ route('estoque.recebimento.nf.create') }}" class="btn btn-dark">Receber por NF
                <i class="bi bi-card-checklist"></i>
            </a>
            {{-- @if ($aberto)
            <a href="{{ route('estoque.recebimento.create') }}" class="btn btn-dark">Continuar
                <i class="bi bi-arrow-right"></i>
            </a>
            @else
            <a href="{{ route('estoque.recebimento.create') }}" class="btn btn-dark">Receber
                <i class="bi bi-plus"></i>
            </a>
            @endif --}}

        </div>
    </div>

    <div class="card card-body">
        <h5>Listagem de recebimentos</h5>
        <br>
        <table id="tabela-recebimentos" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>status</th>
                    <th>Qtd Itens</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @if ($pedidos)
                    @foreach ($pedidos as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->usuario->master->name ?? 'N/A' }}</td>
                            <td>{{ formatarData($item->data_pedido) }}</td>
                            <td>
                                @if ($item->status_id == config('config.status.aberto'))
                                    <span class="badge badge-info">Em aberto</span>
                                @elseif($item->status_id == config('config.status.concluido'))
                                    <span class="badge badge-success">Concluído</span>
                                @else
                                    <span class="badge badge-secondary">Outro</span>
                                @endif
                            </td>
                            <td>{{ $item->pedido_itens->count() }}</td>
                            <td>
                                @if ($item->status_id == config('config.status.aberto'))
                                    <a href="{{ route('estoque.recebimento.iniciar', ['pedido_id' => $item->id]) }}"
                                        class="btn btn-dark btn-sm"><i class="bi bi-box-arrow-in-down"></i> Receber</a>
                                @elseif($item->status_id == config('config.status.concluido'))
                                    <a href="{{ route('estoque.recebimento.iniciar', ['pedido_id' => $item->id]) }}"
                                        class="btn btn-dark btn-sm"><i class="bi bi-info-circle"></i> Visualizar</a>
                                @else
                                    <span class="badge badge-secondary">Não atribuido</span>
                                @endif
                                {{-- <form action="{{ route('recebimento.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                            </form> --}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Status</th>
                    <th>Data de criação</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card">
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>


    <script>
        localStorage.removeItem('itensRecebimentoPedido');
        montaDatatable('tabela-recebimentos');
    </script>
@endsection
