<?php

namespace Modules\Mercado\Http\Controllers\PDV;

use App\System\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\ClienteApplication;
use Modules\Mercado\Application\PagamentoApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Entities\CaixaEvidencia;
use Modules\Mercado\Entities\CaixaItemTemp;
use Modules\Mercado\Entities\EspeciePagamento;
use Modules\Mercado\Entities\Estoque;
use Modules\Mercado\Entities\Loja;
use Modules\Mercado\Entities\Usuario;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Devolucao\DevolucaoRepository;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests\ClienteRequest;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\ReceberVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FinalizarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AbrirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AdicionarItemTempRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CancelarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\DevolucaoVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FecharCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\OrcamentoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\ReceberContaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SangriaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SuprirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\TrocarDispositivoRequest;
use PhpParser\Node\Expr\Cast\Object_;

class CaixaPDVController extends ControllerBaseMercado
{
    //autenticacao de abertura do caixa se não preencher o formulário não pode abrir o caixa de maneira nenhuma
    public function index(Request $request)
    {
        $usuario = Auth::user()->getUserModulo;
        $permissaoCaixasLoja = $usuario->caixa_permissoes_loja;
        if (!$permissaoCaixasLoja) {
            session()->flash('error', 'Você não tem permissão para acessar nenhum caixa nesta loja.');
            return redirect()->back();
        }
        //melhorar logica caso sempre passe por essa rota
        $caixaJaAberto = auth()->user()->getUserModulo->caixa;

        if ($caixaJaAberto) {
            return redirect()->route('caixa.venda');
        }

        // removeCookie('estoques');
        // removeCookie('n_venda');
        if ($caixaJaAberto) {
            $ultima_abertura = $caixaJaAberto->evidencias()->whereIn('acao_id', [config('config.acoes.abriu_caixa.id'), config('config.acoes.abriu_caixa.id')])->latest()->first();

            session()->flash('error', 'Atenção, o caixa <b>' . $caixaJaAberto->nome .
                '</b> está aberto em outro dispositivo ou guia por, <b><u>' . $caixaJaAberto->usuario->master->name . '</u></b> no dia ' .
                $ultima_abertura->created_at->format('d/m/Y') . ' às ' . $ultima_abertura->created_at->format('H:i'));
        }

        $caixas = CaixaRepository::getCaixaDisponiveisByPermissao($usuario->loja_id, $usuario->id);

        return view('mercado::caixa.index', ['caixaDiponiveis' => $caixas, 'removeStrorages' => true, 'caixaAtual' => $caixaJaAberto]);
    }

    public function verifica_caixa(Request $request)
    {
        $caixaJaAberto = auth()->user()->usuarioMercado->caixa;

        return response()->json(['caixa' => $caixaJaAberto, 'status' => $caixaJaAberto->getStatus(), 'hora' =>  now()->format('d/m/Y') . ' às ' . now()->format('H:i:s')]);
    }

    public function venda(Request $request)
    {
        // $mobile = Agent::isMobile();
        $caixa = Auth::user()->getUserModulo->caixa;
        $isMasterCaixa = $caixa->permissoes()->where('usuario_id', $request->attributes->get('usuario_id'))->where('superior', true)->first();
        $especiesPagamento = EspeciePagamento::whereNotIn('id', [
            config('config.especie_pagamento.boleto.id'),
            config('config.especie_pagamento.transferencia.id'),
        ])->get();
        return view('mercado::pdv.caixa', [
            'caixa' => $caixa,
            'especiesPagamento' => $especiesPagamento,
            'isMasterCaixa' => $isMasterCaixa,
            'itensTemp' => CaixaPDVRepository::getItensCaixaTemp($request->attributes->get('caixa_id'))
        ]);
    }

    public function get_supervisores(Request $request)
    {
        $busca = Post::anti_injection($request->q) ?? '';

        $supervisores = CaixaPDVRepository::getSupervisoresCaixa($request->attributes->get('caixa_id'), $busca);
        $select2 = $supervisores->map(function ($usuario) {
            return [
                'id' => $usuario->id,
                'text' => $usuario->master->name
            ];
        });

        return response()->json($select2);
    }

    public function validar_supervisor(Request $request)
    {
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;
            $usuario = Usuario::find($parans->usuario_id);
            $usuario->verificaSenha($parans->senha);
            return response()->json(['success' => true, 'msg' => 'Ação autorizada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function colocar_orcamento_em_venda(Request $request)
    {
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;
            $temps = PDVApplication::colocar_orcamento_em_venda(
                $parans->orcamentoId,
                $request->attributes->get(
                    'caixa_id'
                ),
                $this->getCriarHistoricoRequest($request)
            );
            $orcamento = CaixaPDVRepository::getOrcamentoById($parans->orcamentoId);
            return response()->json(['success' => true, 'msg' => 'Orçamento selecionado com sucesso', 'itens' => $temps, 'total' => $temps->sum('total'), 'orcamento' => $orcamento]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function excluir_orcamento(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;

            PDVApplication::excluir_orcamento(
                $parans->orcamentoId,
                $this->getCriarHistoricoRequest($request)
            );
            $this->getDb()->commit();

            return response()->json(['success' => true, 'msg' => 'Orçamento excluído com sucesso']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function abrir(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $transferir_dispositivo = filter_var($request->transferir_dispositivo, FILTER_VALIDATE_BOOLEAN);
            $usuario_id = Auth::user()->getUserModulo->id;

            if ($transferir_dispositivo == false) {
                $comentario = $request->comentario ? $parans->comentario : null;

                $valor_inicial = converteExibicaoParaCentavos($parans->valorInicial);
                $historicoRequest = $this->getCriarHistoricoRequest($request);
                $historicoRequest->setComentario($comentario);

                // $caixa = CaixaApplication::abrir_caixa(new AbrirCaixaRequest(
                $caixa = PDVApplication::abrir_caixa(new AbrirCaixaRequest(
                    $historicoRequest,
                    $valor_inicial,
                    $parans->senha,
                    $parans->caixa_id,
                    $usuario_id,
                    $request
                ));

                session()->flash('success', 'Caixa aberto!');
            } else {
                $usuario = auth()->user()->getUserModulo;
                $usuario_id = $usuario->id;;
                $historicoRequest = $this->getCriarHistoricoRequest($request);
                $historicoRequest->setAcaoId(config('config.acoes.transferiu_dispositivo.id'));
                $caixa_id = auth()->user()->usuarioMercado->caixa->id;
                $caixa = CaixaApplication::trocar_dispositivo(new TrocarDispositivoRequest(
                    $historicoRequest,
                    $parans->senha,
                    $caixa_id,
                    $usuario_id,
                    $request
                ));

                session()->flash('success_toastr', 'Troca de dispositivo realizado com sucesso!');
            }

            $this->getDb()->commit();
            return redirect()->route('caixa.venda');
        } catch (\Exception $e) {

            $this->getDb()->rollBack();
            Log::error($e);
            session()->flash('error', 'error: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    public function adicionar_item(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = Post::anti_injection_array($request->all());
            $parans = (object) $parans['data'];

            $temps = PDVApplication::adicionar_item_temp(new AdicionarItemTempRequest(
                $parans->estoqueId,
                $parans->quantidade,
                $request->attributes->get('caixa_id'),
                $this->getCriarHistoricoRequest($request)
            ));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Item adicionado com sucesso.', 'itens' => $temps, 'total' => $temps->sum('total')]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function remover_item(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = Post::anti_injection_array($request->all());
            $parans = (object) $parans['data'];
            $temps = [];
            if (isset($parans->remover_todos)) {
                $itens = CaixaPDVRepository::getItensCaixaTemp($request->attributes->get('caixa_id'));
                if ($itens->count() == 0) {
                    throw new Exception("Não existe itens a serem cancelados.", 1);
                }

                foreach ($itens as $key => $i) {
                    $temps = PDVApplication::remove_item_temp($i->id);
                }
            } else {
                $temps = PDVApplication::remove_item_temp($parans->id);
            }

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Item removido com sucesso.', 'itens' => $temps, 'total' => $temps->sum('total')]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_produtos(Request $request)
    {
        $busca = Post::anti_injection($request->busca) ?? '';
        // $produtos = CaixaApplication::get_produtos($busca);
        $produtos = PDVApplication::get_produtos($request->attributes->get('loja_id'), $busca);

        return response()->json($produtos, 200);
    }

    public function get_orcamentos(Request $request)
    {
        $busca = Post::anti_injection($request->q) ?? '';
        // $produtos = CaixaApplication::get_produtos($busca);
        $orcamentos = CaixaPDVRepository::getOrcamentos(Auth::user()->empresa_id, $busca);
        $orcamentos = $orcamentos->map(function ($o) {
            return [
                'id' => $o->id,
                'text' => $o->cliente->nome . 'asdasdasdas asdas  asda | Criado em: ' . $o->created_at->format('d-m-Y')
            ];
        });
        return response()->json($orcamentos, 200);
    }

    public function get_orcamento(Request $request)
    {
        $orcamento = CaixaPDVRepository::getOrcamentoById($request->data['orcamentoId']);
        // dd($orcamento);
        return response()->json(['success' => true, 'orcamento' => $orcamento], 200);
    }

    public function get_especies(Request $request)
    {
        $especies = EspeciePagamento::whereIn('id', [
            config('config.especie_pagamento.dinheiro.id'),
            config('config.especie_pagamento.transferencia.id'),
            config('config.especie_pagamento.pix.id'),
        ])->where('nome', 'like', formataLikeSql(Post::anti_injection($request->q)))
            ->get()->map(function ($item) {
                return ['id' => $item->id, 'text' => $item->nome];
            });

        return response()->json($especies, 200);
    }

    public function get_caixa(Request $request)
    {
        $caixa = CaixaRepository::getCaixaById($request->attributes->get('caixa_id'));

        return response()->json(['success' => true, 'caixa' => $caixa], 200);
    }

    public function get_caixa_fechamento(Request $request)
    {
        $caixa = CaixaRepository::getCaixaById($request->attributes->get('caixa_id'));
        //pega todas as operações do caixa de acordo coma  evidência e acao
        $detalhesFechamentoCaixa = CaixaPDVRepository::get_detalhes_evidencias_caixa_atual($request->attributes->get('caixa_id'));
        //valor minimo esperado para fechamento
        $caixa->ultima_evidencia->valor_minimo_dinheiro = $caixa->ultima_evidencia->valor_dinheiro * 0.95;
        return response()->json(['success' => true, 'caixa' => $caixa, 'detalhes' => $detalhesFechamentoCaixa], 200);
    }

    public function get_clientes(Request $request)
    {
        $busca = $request->q ? Post::anti_injection($request->q) : '';
        $clientes = CaixaPDVRepository::getClientesVendaCaixa(Auth::user()->empresa_id, $busca);

        return response()->json($clientes, 200);
    }

    public function get_formas_pagamento(Request $request)
    {
        $busca = $request->q ? Post::anti_injection($request->q) : '';
        $formasPagamneto = CaixaPDVRepository::getFormasPagamento($request->attributes->get('loja_id'), $busca);
        //por enquanto rejeito boleto e trasnferência bancária
        $formasPagamneto = $formasPagamneto->reject(function ($f) {
            return $f->especie_pagamento_id == config('config.especie_pagamento.boleto.id') ||
                $f->especie_pagamento_id == config('config.especie_pagamento.transferencia.id');
            // $f->especie_pagamento_id == config('config.especie_pagamento.credito_loja.id');
        });

        return response()->json($formasPagamneto, 200);
    }

    public function update_status(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $usuario = auth()->user()->getUserModulo;
            $caixaId = $usuario->caixa->id;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            if ($request->confirm_logout) {
                $historicoRequest->setAcaoId(config('config.acoes.realizou_logout_caixa_aberto.id'));
            }
            $caixa = CaixaApplication::editar_status(new EditarStatusCaixaRequest(
                $historicoRequest,
                $caixaId,
                $parans->status_id,
                $usuario->id
            ));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Status do caixa alterado com sucesso !', 'status' => $caixa->getStatus(), 'caixa' => $caixa]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function finalizar_venda(Request $request)
    { //insere itens na temp
        // $estoques = $this->insereItensTemp();
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $caixa = Auth::user()->getUserModulo->caixa;

            $finalizarVendaRequest = new FinalizarVendaRequest(
                $historicoRequest,
                $caixa->id,
                $parans->cliente_id,
                $parans->formas_pagamento,
                $parans->desconto_percentual
            );

            $venda = PDVApplication::finalizar_venda($finalizarVendaRequest);
            $temps = CaixaPDVRepository::getItensCaixaTemp($venda->caixa_id);
            // $this->getDb()->commit();
            //falta processo de impressao elgin
            return response()->json(['success' => true, 'msg' => 'Venda finalizada com sucesso !', 'venda' => $venda, 'itens' => $temps, 'total' => $temps->sum('total')]);
        } catch (Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function orcamento(Request $request)
    {
        // $this->insereItensTemp();
        //os itens estarão gravados na caixaitenstemp
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;

            if (!isset($parans->cliente_id)) {
                throw new Exception("Cliente é obrigatório para ser indentificado nos orçamentos.", 1);
            }

            $desconto = isset($parans->desconto_percentual) ? $parans->desconto_percentual : 0;
            $descricao = isset($parans->descricao) ? $parans->descricao : null;
            $usuario = Auth::user();
            $status_id = config('config.status.aberto');
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($descricao);
            $orcamentoRequest = new OrcamentoRequest(
                $this->getCriarHistoricoRequest($request),
                $request->attributes->get('loja_id'),
                $usuario->id,
                $usuario->empresa->id,
                $request->attributes->get('caixa_id'),
                $status_id,
                $parans->cliente_id,
                $parans->formas_pagamento,
                $desconto,
                $descricao
            );

            // $vendaSalva = CaixaApplication::salvar_venda($criarVendaRequest);
            $orcamento = PDVApplication::criar_orcamento($orcamentoRequest);

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Orçamento criado com sucesso.']);
        } catch (\Exception $e) {

            $this->getDb()->rollBack();

            Log::error($e);

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    private function insereItensTemp()
    {
        return Estoque::whereIn('id', [1, 2, 3])->get()->map(function ($item) {
            $quantidade = number_format(mt_rand(100, 1000) / 10, 3, '.', '');
            // Exemplo: de 10.0 até 100.0 kg, com 3 casas decimais
            $preco = $item->preco; // ou calcule conforme necessário
            $total = (int) round($preco * $quantidade);

            return CaixaItemTemp::create([
                'estoque_id' => $item->id,
                'produto_id' => $item->produto_id,
                'caixa_id' => Auth::user()->getUserModulo->caixa->id,
                'quantidade' => $quantidade,
                'preco' => $preco,
                'total' => $total,
            ]);
        });
    }
    public function get_vendas(Request $request)
    {
        $busca = $request->q ? Post::anti_injection($request->q) : '';

        $vendas = CaixaApplication::get_vendas_voltar($busca);

        return response()->json($vendas, 200);
    }

    public function get_venda_voltar(Request $request)
    {
        $id =  Post::anti_injection($request->id);

        $venda = CaixaApplication::get_venda_by_id($id);

        return response()->json($venda, 200);
    }


    public function cancelar_venda(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $usuario = auth()->user()->getUserModulo;

            $historicoRequest = $this->getCriarHistoricoRequest($request);

            $caixa_id = $usuario->caixa->id;
            $status_caixa_id = config('config.status.livre');

            $cancelarVendaRequest = new CancelarVendaRequest($parans->n_venda, new EditarStatusCaixaRequest($historicoRequest, $caixa_id, $status_caixa_id, $usuario->id));

            $caixa = CaixaApplication::cancelar_venda($cancelarVendaRequest);

            $caixa->status->descricao = $caixa->getStatus();

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Venda cancelada com sucesso, caixa livre para nova venda', 'caixa' => $caixa]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => true, 'msg' => $e->getMessage()]);
        }
    }

    public function cadastrar_cliente(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());

            $empresa_master_cod = auth()->user()->empresa_id;
            $nome = $parans->nome;
            $documento = $parans->documento;
            $pessoa = $parans->pessoa;
            $ativo = true;
            $status = config('config.status.em_dia');
            $celular = $parans->celular;
            $telefone_fixo = $parans->telefone_fixo;
            $email = $parans->email;
            $data_nascimento = $parans->data_nascimento;
            $limite_credito = null;
            $observacao = $parans->observacao;

            $addEndereco = filter_var($request->addEndereco, FILTER_VALIDATE_BOOLEAN);

            $enderecoRequest = null;
            if ($addEndereco) {
                $logradouro = $parans->logradouro;
                $numero = $parans->numero;
                $cidade = $parans->cidade;
                $bairro = $parans->bairro;
                $uf = $parans->uf;
                $cep = $parans->cep;
                $complemento = $parans->complemento;
                $enderecoRequest = new EnderecoRequest(
                    $this->getCriarHistoricoRequest($request),
                    $logradouro,
                    $cidade,
                    $bairro,
                    $uf,
                    $cep,
                    $numero,
                    $complemento
                );
            }

            $clienteRequest = new ClienteRequest(
                $this->getCriarHistoricoRequest($request),
                $empresa_master_cod,
                $nome,
                $documento,
                $pessoa,
                $ativo,
                $status,
                $celular,
                $telefone_fixo,
                $email,
                $data_nascimento,
                $limite_credito,
                $observacao,
                $enderecoRequest
            );

            $cliente = ClienteApplication::criarCliente($clienteRequest);
            $this->getDb()->commit();

            return response()->json(['success' => true, 'msg' => 'Cliente cadastrado com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            debugException($e);
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_vendas_devolucao(Request $request)
    {
        $busca = Post::anti_injection($request->q) ?? '';
        //regra para quantidades de dias que uma venda pode ser devolvida
        $qtdDiasPodeSerDevolvida = 8; //até o momneto sem regra por loja é uma semana
        // $vendas = CaixaApplication::getVendaDevolucao($busca);
        $vendas = CaixaPDVRepository::get_vendas_devolucao($request->attributes->get('loja_id'), $qtdDiasPodeSerDevolvida, $busca);
        $select2 = $vendas->map(function ($v) {
            return [
                'id' => $v->id,
                'text' =>  $v->n_venda . ' - ' . $v->cliente->nome . ' - ' . aplicarMascaraDocumentoDiscreta($v->cliente->documento),
                'n_venda' => $v->n_venda
            ];
        });

        return response()->json($select2, 200);
    }

    public function get_venda_devolver(Request $request)
    {
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;

            $venda = CaixaPDVRepository::getVendaById($parans->vendaId);

            return response()->json(['success' => true, 'msg' => 'Devolução capturada com sucesso.', 'venda' => $venda]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_clientes_parcela(Request $request)
    {
        $parans = (object) Post::anti_injection_array($request->all());
        $parans = (object) $parans->data;

        $vendaPagamento = CaixaPDVRepository::get_venda_pagamento_by_id($parans->venda_pagamento_id);
        $vendaPagamento->cliente->credito; //carrega relacao
        $cliente = $vendaPagamento->cliente;
        return response()->json(['success' => true, 'venda_pagamento' => $vendaPagamento, 'cliente' => $cliente]);
    }

    public function get_clientes_parcela_receber(Request $request)
    {
        $busca = Post::anti_injection($request->q) ?? '';
        // $produtos = CaixaApplication::get_produtos($busca);
        $lojas = Loja::where('empresa_master_cod', Auth::user()->empresa_id)->get();
        $vendaPagamentos = CaixaPDVRepository::get_venda_pagamentos_cliente($lojas->pluck('id')->toArray(), $busca);
        $vendaPagamentos = $vendaPagamentos->map(function ($vp) {
            return [
                'id' => $vp->id,
                'text' => $vp->cliente->nome . ' - ' . $vp->created_at->format('d-m-Y')
            ];
        });
        return response()->json($vendaPagamentos, 200);
    }
    public function devolucao(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('data.itens_quantidades'));
            $parans = $parans->data;

            //validações antes da transaction efetiva de itens no banco
            if (!isset($parans['motivo'])) {
                throw new Exception("Motivo da devolução é obrigatório.", 1);
            }

            if (!isset($request->data['itens_quantidades'])) {
                throw new Exception("Nenhum item foi adicionado há devolução.", 1);
            }

            if (!isset($parans['venda_id'])) {
                throw new Exception("Venda ID não específicada, comunique ao suporte.", 1);
            }

            if (!isset($parans['especie_pagamento_id'])) {
                throw new Exception("Forma de pagamento da devolução não indentificada.", 1);
            }

            //itens e historico padrão
            $itens = json_decode($request->data['itens_quantidades'], true);
            $itens = Post::anti_injection_array($itens);
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($parans['motivo']);

            /**
             * executa
             */
            // $venda = CaixaApplication::devolucao_venda(new DevolucaoVendaRequest());
            $devolucao = PDVApplication::devolucao_venda(new DevolucaoVendaRequest(
                $historicoRequest,
                $parans['venda_id'],
                $request->attributes->get('caixa_id'),
                $request->attributes->get('loja_id'),
                $historicoRequest->getUsuarioId(),
                $parans['especie_pagamento_id'],
                $itens
            ));

            $this->getDb()->commit();

            return response()->json(['success' => true, 'msg' => 'Devolução realizada com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_sangria(Request $request)
    {
        try {
            $sangria = CaixaRepository::getSangria(Post::anti_injection($request->caixa_id));

            return response()->json(['sangria' => $sangria, 'success' => true], 200);
        } catch (\Exception $e) {

            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_sangria_segunda_via(Request $request)
    {
        try {

            if (!Hash::check(Post::anti_injection($request->senha), auth()->user()->password)) {
                throw new Exception("Senha incorreta.", 1);
            }


            $dataSangrias[] = CaixaRepository::getSegundaViaSangria(Post::anti_injection($request->caixa_id));


            dd($dataSangrias);
            return response()->json(['sangria' => $sangria, 'success' => true], 200);
        } catch (\Exception $e) {
            dd($e);
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function sangria(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;

            if (!isset($parans->motivo)) {
                throw new Exception("Motivo é obrigatório", 1);
            }
            $motivo = $parans->motivo;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($motivo);
            $valor = converteExibicaoParaCentavos($parans->valor);
            // $sangria = CaixaApplication::criarSangria(new CriarSangriaRequest($historicoRequest, $parans->caixa_id, $parans->senha, $parans->valor, $observacao, $request));

            $sangria = PDVApplication::sangria(new SangriaRequest(
                $historicoRequest,
                $request->attributes->get('caixa_id'),
                $valor,
                $parans->especie_pagamento_id,
                $motivo
            ));

            $this->getDb()->commit();

            return response()->json(['msg' => 'Sangria realizada com sucesso.', 'success' => true], 200);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function receber_conta(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;

            if (!isset($parans->forma_pagamento)) {
                throw new Exception("Forma de pagamento informada.", 1);
            }
            if (!isset($parans->venda_parcelas)) {
                throw new Exception("Nenhuma parcela selecionada.", 1);
            }

            $observacao = $request->observacao ? $parans->observacao : null;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($observacao);
            // $sangria = CaixaApplication::criarSangria(new CriarSangriaRequest($historicoRequest, $parans->caixa_id, $parans->senha, $parans->valor, $observacao, $request));
            $usuario = Auth::user()->getUserModulo;
            $recebimentos = PDVApplication::receber_conta(new ReceberContaRequest(
                $historicoRequest,
                $request->attributes->get('loja_id'),
                $request->attributes->get('caixa_id'),
                $request->attributes->get('usuario_id'),
                $parans->venda_parcelas,
                $parans->forma_pagamento,
                $observacao
            ));

            $this->getDb()->commit();
            return response()->json(['msg' => 'Recebimento realizado com sucesso!.', 'success' => true], 200);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function fechar_caixa_index($caixa_id)
    {
        $evidencia = auth()->user()->getUserModulo->caixa->ultima_abertura;
        $fechamento = CaixaRepository::getFechamentoCaixaByEvidencia($evidencia->id);

        // $sangria = CaixaRepository::getSangria($caixa_id);
        $totalRecebidoDinheiro = array_key_exists('Dinheiro', $fechamento->total_por_forma_pagamento) ? $fechamento->total_por_forma_pagamento['Dinheiro'] : 0;
        $totalDevolucaoDinheiro = array_key_exists('Dinheiro', $fechamento->total_por_forma_devolucao) ? $fechamento->total_por_forma_devolucao['Dinheiro'] : 0;

        $devolucoes = $fechamento->devolucoes;
        $sangrias = $fechamento->sangrias_realizadas;
        $totalDinheiroAtual =  ($totalRecebidoDinheiro + $evidencia->valor_abertura) - ($totalDevolucaoDinheiro + $sangrias->sum('valor_sangria'));
        $totalDevolucao = array_sum($fechamento->total_por_forma_devolucao);

        $total_fechamento = ($fechamento->total_recebimento + $sangrias->sum('valor_sangria') + $fechamento->valor_abertura) - $totalDevolucao;

        return view('mercado::caixa.fechamento', [
            'caixa' => $fechamento,
            'vendas' => $fechamento->vendas,
            'totalDinheiroAtual' => $totalDinheiroAtual,
            'totalDevolucao' => $totalDevolucao,
            'totalDevolucaoDinheiro' => $totalDevolucaoDinheiro,
            'sangrias' => $sangrias,
            'total_por_forma_pagamento' => $fechamento->total_por_forma_pagamento,
            'total_por_forma_devolucao' => $fechamento->total_por_forma_devolucao,
            'devolucoes' => $devolucoes,
            'recebimentos' => $fechamento->recebimentos,
            'total_fechamento' => $total_fechamento
        ]);
    }

    private function get_data_fechamento($fechamento)
    {
        // $sangria = CaixaRepository::getSangria($caixa_id);
        $totalRecebidoDinheiro = array_key_exists('Dinheiro', $fechamento->total_por_forma_pagamento) ? $fechamento->total_por_forma_pagamento['Dinheiro'] : 0;
        $totalDevolucaoDinheiro = array_key_exists('Dinheiro', $fechamento->total_por_forma_devolucao) ? $fechamento->total_por_forma_devolucao['Dinheiro'] : 0;

        $devolucoes = $fechamento->devolucoes;
        $sangrias = $fechamento->sangrias_realizadas;
        $totalDinheiroAtual =  ($totalRecebidoDinheiro + $fechamento->valor_abertura) - ($totalDevolucaoDinheiro + $sangrias->sum('valor_sangria'));
        $totalDevolucao = array_sum($fechamento->total_por_forma_devolucao);

        $total_fechamento = ($fechamento->total_recebimento + $sangrias->sum('valor_sangria') + $fechamento->valor_abertura) - $totalDevolucao;

        return [
            'caixa' => $fechamento,
            'vendas' => $fechamento->vendas,
            'totalDinheiroAtual' => $totalDinheiroAtual,
            'totalDevolucao' => $totalDevolucao,
            'totalDevolucaoDinheiro' => $totalDevolucaoDinheiro,
            'sangrias' => $sangrias,
            'total_por_forma_pagamento' => $fechamento->total_por_forma_pagamento,
            'total_por_forma_devolucao' => $fechamento->total_por_forma_devolucao,
            'devolucoes' => $devolucoes,
            'recebimentos' => $fechamento->recebimentos,
            'total_fechamento' => $total_fechamento
        ];
    }

    public function fechamento_get_itens_venda(Request $request)
    {
        $venda = VendaRepository::getVendaById(Post::anti_injection($request->venda_id));
        $vendaItens = $venda->venda_itens->map(function ($item) {
            return [
                $item->produto->cod_aux,
                $item->produto->getNomeCompleto(),
                $item->quantidade,
                converterParaReais($item->preco),
                converterParaReais($item->total)
            ];
        });
        return response()->json(['data' => $vendaItens], 200);
    }

    public function fechamento_get_itens_venda_devolucao(Request $request)
    {
        $devolucao = DevolucaoRepository::getDevolucaoById(Post::anti_injection($request->devolucao_id));

        $devolucaoItens = $devolucao->devolucao_itens->map(function ($item) use ($devolucao) {
            return [
                $item->produto->cod_aux,
                $item->produto->getNomeCompleto(),
                $item->venda_item->quantidade,
                $item->quantidade,
                converterParaReais($item->preco),
                converterVirgulaParaPonto($devolucao->venda->desconto_porcentagem) .   '% ',
                converterParaReais($item->total)
            ];
        });


        return response()->json(['data' => $devolucaoItens], 200);
    }

    public function fechar_caixa(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;
            if (!isset($parans->total_dinheiro)) {
                throw new Exception("Valor em dinheiro obrigatório para fechamento do caixa", 1);
            }

            $observacao = $request->observacao ? $parans->observacao : null;

            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($observacao);
            $totalDinheiro = converteExibicaoParaCentavos($parans->total_dinheiro);
            $autorizado = $request->autorizado ? true : false;
            $fechaCaixaRequest = new FecharCaixaRequest(
                $historicoRequest,
                $request->attributes->get('caixa_id'),
                $totalDinheiro,
                $autorizado
            );

            // $caixa = CaixaApplication::fechar_caixa($fechaCaixaRequest);
            $caixa = PDVApplication::fechar_caixa($fechaCaixaRequest);

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Caixa fechado com sucesso.', 'rota_redirect' => route('home.index')]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_recebimentos(Request $request)
    {
        $busca = Post::anti_injection($request->q) ?? '';
        $lojas = auth()->user()->getUserModulo->lojas->pluck('id')->toArray();

        $recebimentos = CaixaRepository::getRecebimentos($busca, $lojas);

        $recebimentos = $recebimentos->map(function ($item) {
            $nome = $item->nome; // Nome do cliente
            $documento = $item->documento; // Documento do cliente

            // Mascarar os últimos 5 dígitos do documento
            $documento_mascarado = substr($documento, 0, -5) . str_repeat('*', 5);

            return [
                'id' => $item->id,
                'text' => $nome . ' - ' . $documento_mascarado,
            ];
        });
        return response()->json($recebimentos, 200);
    }

    public function get_venda_recebimentos(Request $request)
    {
        $cliente_id = Post::anti_injection($request->id);
        $lojas = auth()->user()->getUserModulo->lojas->pluck('id')->toArray();

        $venda_recebimentos = CaixaRepository::getVendaRecebimentos($cliente_id, $lojas);

        return response()->json($venda_recebimentos, 200);
    }

    public function receber_venda(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $usuario = auth()->user()->getUserModulo;
            $caixa = $usuario->caixa;
            $loja_id = $usuario->loja_id;
            $caixa_id = $caixa->id;
            $caixa_evidencia_id = $caixa->ultimo_registro->id;
            $receberVendaRequest = new ReceberVendaRequest(
                $historicoRequest,
                $caixa_id,
                $caixa_evidencia_id,
                $loja_id,
                $parans->venda_pagamentos,
                $parans->formas_pagamentos
            );

            $pagamentos = PagamentoApplication::receberVenda($receberVendaRequest);
            $dataRecibo = [];

            $dataRecibo = $pagamentos->map(function ($p) {
                return (object) [
                    'n_conta' => $p->venda->n_venda,
                    'v_conta' => converterParaReais($p->venda_pagamento->getValor()),
                    'v_pago' => converterParaReais($p->valor),
                    'v_ja_pago' => converterParaReais($p->venda_pagamento->total_valor_pago),
                    'v_restante' => converterParaReais(($p->venda_pagamento->getValor() - $p->venda_pagamento->total_valor_pago)),
                    'cliente' => $p->venda->cliente->nome,
                    'especie' => $p->especie->nome,
                ];
            });

            $this->getDb()->commit();

            return response()->json(['success' => true, 'msg' => 'Recebimento realizado com sucesso', 'recebimentos' => $dataRecibo]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            debugException($e);
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function teste_recursos(Request $request)
    {
        try {
            $venda = VendaRepository::getVendaById(4);

            return response()->json(['venda' => $venda]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function suprir_caixa(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $parans = (object) $parans->data;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($parans->motivo);
            $usuario = Auth::user()->getUserModulo;
            $recurso = config('config.caixa.recursos.suprimentos.id');
            $evidencia = PDVApplication::criar_evidencias(new CriarEvidenciaRequest($historicoRequest, $request->attributes->get('caixa_id'), $historicoRequest->getAcaoId(), $usuario->id, $recurso, null, null, $historicoRequest->getComentario()));
            $diario = $evidencia->caixa->diario_atual;
            $valor = converteExibicaoParaCentavos($parans->valor);

            $suprimento = PDVApplication::suprir_caixa(new SuprirCaixaRequest($historicoRequest, $request->attributes->get('caixa_id'), $evidencia->id, $diario->id, $usuario->id, $valor, $parans->especie_pagamento_id, $historicoRequest->getComentario()));
            $suprirEmDinheiro = $suprimento->especie_pagamento_id == config('config.especie_pagamento.dinheiro.id');

            $valorDinhieiro = $suprirEmDinheiro ? $suprimento->valor : 0;
            $totalSupriu = $suprimento->valor;
            $evidenciaAnterior = $evidencia->evidenciaAnterior();
            $total = $evidenciaAnterior->valor_total + $totalSupriu;
            $valorDinheiro = $evidenciaAnterior->valor_dinheiro + $valorDinhieiro;

            $evidencia = CaixaPDVRepository::editaCaixaEvidenciaAttrs($historicoRequest, $evidencia->id, [
                'valor_movimentado' => $totalSupriu,
                'valor_total' => $evidenciaAnterior->valor_total + $totalSupriu,
                'valor_dinheiro' => $evidenciaAnterior->valor_dinheiro + $valorDinhieiro,
                'total_credito_loja' => $evidenciaAnterior->total_credito_loja,
            ]);
            $this->getDb()->commit();
            //comprovante de supriemntos
            return response()->json(['success' => true, 'msg' => 'Caixa foi suprido com sucesso']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            debugException($e);
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    //sempre retorna os dados que a impressao precisa
    public function impressao_teste(Request $request)
    {
        $parans = (object) Post::anti_injection_array($request->all());
        $parans = (object) $parans->data;
        $tipo = $parans->tipo;

        switch ($tipo) {
            case 'cupom':
                $venda = Venda::first();
                return response()->json(['success' => true, 'venda' => CaixaPDVRepository::getVendaById($venda->id)]);
                break;
            case 'teste':
                return response()->json(['success' => true, 'msg' => 'mensagem do servidor ok']);
                break;
            default:
                # code...
                break;
        }
    }
}
