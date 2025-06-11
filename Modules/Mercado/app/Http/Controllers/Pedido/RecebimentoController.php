<?php

namespace Modules\Mercado\Http\Controllers\Pedido;

use App\System\Post;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\CompraApplication;
use Modules\Mercado\Application\RecebimentoApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Recebimento\RecebimentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class RecebimentoController extends ControllerBaseMercado
{
    private function getLojaId(){
        return auth()->user()->usuarioMercado->loja_id;
    }
    public function index()
    {       
        $loja_id = $this->getLojaId();
        $recebimentos = RecebimentoApplication::obterRecebimentos($loja_id);
        $compras_para_receber = count(CompraApplication::obterComprasCompradas($loja_id));
        return view('mercado::pedido.recebimento.index', ['recebimentos' => $recebimentos, 'qtd_compras_para_receber' => $compras_para_receber]);
    }

    public function create()
    {
        $loja_id = $this->getLojaId();
        $compras = CompraApplication::obterComprasCompradas($loja_id);        
        return view('mercado::pedido.recebimento.create', ['compras' => $compras]);
    }

    public function receber(int $compra_id){
        $this->getDb()->begin();
        try {
            $compra = CompraApplication::obterCompraPorId($compra_id);
            $compra_recebida = RecebimentoRepository::existeRecebimentoComEssaCompra($compra_id, $this->getLojaId());
            $recebimento = RecebimentoRepository::obterRecebimentoPorCompraId($compra_id, $this->getLojaId());
            $minDate = \Carbon\Carbon::now()->subMonths(2)->format('Y-m-d');
            $dataLimite = $compra_recebida ? Carbon::parse($recebimento->data_recebimento)->toDateString() : '';
            $disabled = $compra_recebida ? 'disabled' : '';
            return view('mercado::pedido.recebimento.receber', [
                'compra' => $compra,
                'compra_recebida' => $compra_recebida,
                'recebimento' => $recebimento,
                'minDate' => $minDate,
                'dataLimite' => $dataLimite,
                'disabled' => $disabled
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            $this->getDb()->rollBack();
            session()->flash('error', 'Não foi possível realizar o recebimento: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request, int $compra_id){
        $this->getDb()->begin();
        try {
            $historico = $this->getCriarHistoricoRequest($request);
            $parans = (object)Post::anti_injection_array($request->all());
            RecebimentoApplication::receber(
                new ReceberRequest(
                    $compra_id,
                    config('config.status.entregue'),
                    $this->getLojaId(),
                    $historico,
                    $parans->data_recebimento,
                    $parans->observacao
                )
            );
            $this->getDb()->commit();
            session()->flash('success', 'Recebimento realizado com sucesso.');
            return redirect()->route('pedido.recebimento.index');
        } catch (\Exception $e) {
            Log::error($e);
            $this->getDb()->rollBack();
            session()->flash('error', 'Não foi possível realizar o recebimento: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}
