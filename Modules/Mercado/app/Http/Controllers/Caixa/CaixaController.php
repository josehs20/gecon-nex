<?php

namespace Modules\Mercado\Http\Controllers\Caixa;

use App\System\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\ClienteApplication;
use Modules\Mercado\Application\PagamentoApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Devolucao\DevolucaoRepository;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests\ClienteRequest;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\ReceberVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FinalizarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AbrirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CancelarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarSangriaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\DevolucaoVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FecharCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\TrocarDispositivoRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;

class CaixaController extends ControllerBaseMercado
{
    //autenticacao de abertura do caixa se não preencher o formulário não pode abrir o caixa de maneira nenhuma
    public function index(Request $request)
    {
        $caixaJaAberto = auth()->user()->usuarioMercado->caixa;

        if ($caixaJaAberto && $caixaJaAberto->evidencia->ip_address == $request->ip() && $caixaJaAberto->evidencia->token == session()->getId()) {
            return redirect()->route('caixa.venda');
        }

        removeCookie('estoques');
        removeCookie('n_venda');

        if ($caixaJaAberto) {
            $ultima_abertura = $caixaJaAberto->evidencias()->whereIn('acao_id', [config('config.acoes.abriu_caixa.id'), config('config.acoes.abriu_caixa.id')])->latest()->first();

            session()->flash('error', 'Atenção, o caixa <b>' . $caixaJaAberto->nome .
                '</b> está aberto em outro dispositivo ou guia por, <b><u>' . $caixaJaAberto->usuario->master->name . '</u></b> no dia ' .
                $ultima_abertura->created_at->format('d/m/Y') . ' às ' . $ultima_abertura->created_at->format('H:i'));
        }

        $caixas = CaixaRepository::getCaixaDisponiveis();
        return view('mercado::caixa.index', ['caixaDiponiveis' => $caixas, 'removeStrorages' => true, 'caixaAtual' => $caixaJaAberto]);
    }

    public function verifica_caixa(Request $request)
    {
        $caixaJaAberto = auth()->user()->usuarioMercado->caixa;

        return response()->json(['caixa' => $caixaJaAberto, 'status' => $caixaJaAberto->getStatus(), 'hora' =>  now()->format('d/m/Y') . ' às ' . now()->format('H:i:s')]);
    }

    public function venda(Request $request)
    {
        $user = auth()->user()->load('empresa', 'usuarioMercado');
        $caixaJaAberto = $user->usuarioMercado->caixa;

        if (!$caixaJaAberto) {
            session()->flash('error', 'Caixa Expirado!');

            return redirect()->route('caixa.autenticacao');
        }

        $formaPagamentos = PagamentoRepository::getAllFormaPagamentos();
        $formaDevolucoes = DevolucaoRepository::getAllTipoDevolucoes();

        $mobile = Agent::isMobile();
        return view('mercado::caixa.venda', [
            'caixa' => auth()->user()->usuarioMercado->caixa,
            'formaPagamentos' => $formaPagamentos,
            'mobile' => $mobile,
            'formaDevolucoes' => $formaDevolucoes,
            // 'imagemCupom' =>  gerarImagemBase64('app/public/'.$user->empresa->cnpj.'/gecom.png')
        ]);
    }

    public function abrir(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $transferir_dispositivo = filter_var($request->transferir_dispositivo, FILTER_VALIDATE_BOOLEAN);
            $usuario_id = auth()->user()->getUserModulo->id;

            if ($transferir_dispositivo == false) {
                $comentario = $request->comentario ? $parans->comentario : null;

                $valor_inicial = converteExibicaoParaCentavos($parans->valorInicial);
                $historicoRequest = $this->getCriarHistoricoRequest($request);
                $historicoRequest->setComentario($comentario);

                $caixa = CaixaApplication::abrir_caixa(new AbrirCaixaRequest(
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

    public function get_produtos(Request $request)
    {
        $busca = Post::anti_injection($request->q) ?? '';
        $produtos = CaixaApplication::get_produtos($busca);

        return response()->json(['data' => $produtos], 200);
    }

    public function get_clientes(Request $request, $clienteVenda = true)
    {
        $busca = $request->q ? Post::anti_injection($request->q) : '';
        $clientes = CaixaApplication::get_cliente($busca);

        return response()->json($clientes, 200);
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
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $usuario = auth()->user()->getUserModulo;
            $usuario_id = $usuario->id;
            $itens = json_decode($request->itens, true);
            $itens = Post::anti_injection_array($itens);
            $caixa = $usuario->caixa;
            $caixa_id = $caixa->id;
            $caixa_evidencia_id = $caixa->ultimo_registro->id;
            $loja_id = $usuario->loja_id;
            $status_id = config('config.status.concluido');
            $formaPagamento_id = $parans->formaPagamento;
            $desconto_porcentagem = $request->desconto ? converteDinheiroParaFloat($parans->desconto) : null;
            $cliente_id = $request->cliente ? $parans->cliente : null;
            $venda_id = $request->venda_id ? $parans->venda_id : null;
            $valorRecebido = $request->valorRecebido ? converteDinheiroParaFloat($parans->valorRecebido) : null;

            $criarVendaRequest = new CriarVendaRequest(
                $historicoRequest,
                $itens,
                $caixa_id,
                $caixa_evidencia_id,
                $loja_id,
                $usuario_id,
                $status_id,
                $cliente_id,
                $formaPagamento_id,
                $desconto_porcentagem,
                $venda_id,
                $valorRecebido,
                now()
            );

            $finalizarVendaRequest = new FinalizarVendaRequest(
                $criarVendaRequest
            );

            $venda = CaixaApplication::finalizar_venda($finalizarVendaRequest);
            $venda->usuario->master;
            $this->getDb()->commit();
            $venda = VendaRepository::getVendaById($venda->id);
            return response()->json(['success' => true, 'msg' => 'Venda finalizada com sucesso !', 'status' => $venda->caixa->getStatus(), 'caixa' => $venda->caixa, 'venda' => $venda]);
        } catch (Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function salvar_venda(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $usuario = auth()->user()->getUserModulo;
            $usuario_id = $usuario->id;
            $historicoRequest = $this->getCriarHistoricoRequest($request);

            $itens = json_decode($request->itens, true);
            $itens = Post::anti_injection_array($itens);
            $caixa_id = $usuario->caixa->id;
            $caixa_evidencia_id = $usuario->caixa->ultimo_registro->id;
            $loja_id = $usuario->loja_id;
            $status_id = config('config.status.salvo');
            $formaPagamento_id = null;
            $desconto_porcentagem = null;
            $cliente_id = $parans->cliente;

            $criarVendaRequest = new CriarVendaRequest(
                $historicoRequest,
                $itens,
                $caixa_id,
                $caixa_evidencia_id,
                $loja_id,
                $usuario_id,
                $status_id,
                $cliente_id,
                $formaPagamento_id,
                $desconto_porcentagem,
                $request->venda_id
            );

            $vendaSalva = CaixaApplication::salvar_venda($criarVendaRequest);

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Venda salva comsucesso, caixa livre para nova venda!', 'status' => $vendaSalva->caixa->getStatus(), 'caixa' => $vendaSalva->caixa]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            Log::error($e);

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
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

        $vendas = CaixaApplication::getVendaDevolucao($busca);

        return response()->json($vendas, 200);
    }

    public function devolucao(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $itens = json_decode($request->itens, true);
            $itens = Post::anti_injection_array($itens);
            $usuario = auth()->user()->getUserModulo;
            $loja_id = $usuario->loja_id;
            $caixa = $usuario->caixa;
            $caixa_id = $caixa->id;
            $caixa_evidencia_id = $caixa->ultimo_registro->id;
            $usuario_id = $usuario->id;
            $formaDevolucoes = $parans->vendas_pagamento_devolucao;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($parans->motivo);
            $devolucaoRequest = new DevolucaoVendaRequest($parans->venda_id, $loja_id, $caixa_id, $caixa_evidencia_id, $usuario_id, $formaDevolucoes, $itens, $historicoRequest);

            /**
             * executa
             */
            $venda = CaixaApplication::devolucao_venda($devolucaoRequest);
            $devolucao = $venda->devolucoes->last();
            $devolucao->itens = $devolucao->devolucao_itens->map(function ($item) {
                return (object)[
                    'nome' => $item->produto->getNomeCompleto(),
                    'cod_aux' => $item->produto->cod_aux,
                    'quantidade' => $item->quantidade,
                    'preco' => $item->preco,
                    'total' => $item->total,
                ];
            });
            $devolucao->n_venda = $devolucao->venda->n_venda;
            $devolucao->cliente = $devolucao->venda->cliente->nome;
            $this->getDb()->commit();

            return response()->json(['success' => true, 'msg' => 'Devolução realizada com sucesso', 'caixa' => $usuario->caixa, 'devolucao' => $devolucao]);
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
            $observacao = $request->observacao ? $parans->observacao : null;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($observacao);

            $sangriaRealizada = CaixaRepository::getSangria($parans->caixa_id);

            $sangria = CaixaApplication::criarSangria(new CriarSangriaRequest($historicoRequest, $parans->caixa_id, $parans->senha, $parans->valor, $observacao, $request));
            $this->getDb()->commit();

            return response()->json(['msg' => 'Sangria realizada com sucesso.', 'success' => true, 'sangria' => $sangriaRealizada], 200);
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

    function fechamento_caixa_index()
    {
        try {
            $usuario_id = auth()->user()->getUserModulo->id;

            $fechamentos = CaixaRepository::getFechamentosCaixaByUsuario($usuario_id);

            return view('mercado::caixa.fechamento_index', [
                'fechamentos' => $fechamentos,
            ]);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function fechamento_show($evidencia_id)
    {
        try {
            $fechamento = CaixaRepository::getFechamentoCaixaByEvidencia($evidencia_id);


            return view('mercado::caixa.fechamento_show', $this->get_data_fechamento($fechamento));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
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

            $caixa_id = $parans->caixa_id;

            $senha  = $parans->senha;
            $observacao = $parans->observacao;
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $historicoRequest->setComentario($observacao);
            $fechaCaixaRequest = new FecharCaixaRequest(
                $caixa_id,
                $senha,
                $observacao,
                $historicoRequest,
                $request
            );

            $caixa = CaixaApplication::fechar_caixa($fechaCaixaRequest);
            $fechamento = CaixaRepository::getFechamentoCaixaByEvidencia($caixa->ultima_abertura->id);
            $dataFechamento = $this->get_data_fechamento($fechamento);

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Caixa fechado com sucesso.', 'fechamento' => $dataFechamento]);
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
    public function venda_teste()
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
}
