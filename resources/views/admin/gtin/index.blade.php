@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('admin.empresa.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Lista de Gtins']]])

@section('content')
    @vite('resources/js/views/admin/gtins.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Lista de Gtins</h3>

            <p class="lead">Nesta tela você pode ver todos os Gtins cadastrado em base</p>
        </div>
        <a id="CadastrarGtin" class="btn btn-success">
            <i class="bi bi-plus"></i> GTIN
        </a>
    </div>

    <div class="card card-body table-responsive">
        <h5 class="card-title" style="color: black !important;">Links sugeridos</h5>

        <ul>
            <li><u><a target="_blank" style="color: black !important;" href="https://cosmos.bluesoft.com.br/">Cosmos</a></u>
            </li>
            <li><u><a target="_blank" style="color: black !important;"
                        href="https://dfe-portal.svrs.rs.gov.br/Nfe/gtin">Receita</a></u> </li>
        </ul>
        <br>
        <table class="table table-bordered" id="tabela-gtin-admin" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gtin</th>
                    <th>Descricao</th>
                    <th>NCM</th>
                    <th>Última validação</th>
                    <th>Prioridade</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> --}}
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Gtin</th>
                    <th>Descricao</th>
                    <th>NCM</th>
                    <th>Última validação</th>
                    <th>Prioridade</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('admin.empresa.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="modal fade" id="modalCadastrar" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastrarLabel">Cadastrar GTIN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCadastrar" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="gtin">GTIN</label>
                            <input type="text" class="form-control" name="gtin" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" class="form-control" name="tipo" required>
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" required>
                        </div>
                        <div class="form-group">
                            <label for="ncm">NCM</label>
                            <input type="text" class="form-control" name="ncm">
                        </div>
                        <div class="form-group">
                            <label for="comprimento">Comprimento</label>
                            <input type="text" class="form-control" name="comprimento">
                        </div>
                        <div class="form-group">
                            <label for="altura">Altura</label>
                            <input type="text" class="form-control" name="altura">
                        </div>
                        <div class="form-group">
                            <label for="largura">Largura</label>
                            <input type="text" class="form-control" name="largura">
                        </div>
                        <div class="form-group">
                            <label for="peso_bruto">Peso Bruto</label>
                            <input type="text" class="form-control" name="peso_bruto">
                        </div>
                        <div class="form-group">
                            <label for="peso_liquido">Peso Líquido</label>
                            <input type="text" class="form-control" name="peso_liquido">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                onclick="fecharModal('modalCadastrar')">Fechar</button>
                            <button type="submit" class="btn btn-dark">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Edição -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar GTIN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        @csrf
                        <input type="hidden" id="id_gtin">
                        <div class="form-group">
                            <label for="gtin">GTIN</label>
                            <input type="text" class="form-control" id="gtin" name="gtin">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao">
                        </div>

                        <div class="form-group">
                            <label for="ncm">NCM</label>
                            <input type="text" class="form-control" id="ncm" name="ncm">
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" class="form-control" id="tipo" name="tipo">
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade">
                        </div>
                        <div class="form-group">
                            <label for="comprimento">Comprimento</label>
                            <input type="text" class="form-control" id="comprimento" name="comprimento">
                        </div>
                        <div class="form-group">
                            <label for="altura">Altura</label>
                            <input type="text" class="form-control" id="altura" name="altura">
                        </div>
                        <div class="form-group">
                            <label for="largura">Largura</label>
                            <input type="text" class="form-control" id="largura" name="largura">
                        </div>
                        <div class="form-group">
                            <label for="peso_bruto">Peso Bruto</label>
                            <input type="text" class="form-control" id="peso_bruto" name="peso_bruto">
                        </div>
                        <div class="form-group">
                            <label for="peso_liquido">Peso Líquido</label>
                            <input type="text" class="form-control" id="peso_liquido" name="peso_liquido">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                onclick="fecharModal('modalEditar')">Fechar</button>
                            <button type="submit" class="btn btn-dark">Salvar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
