<?php

namespace Modules\Mercado\Http\Controllers\Estoque;

use App\Services\NFEIOService;
use App\Services\NFCertificadosService;
use App\Services\NFConsultaService;
use App\Services\NFEmpresasService;
use App\System\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\RecebimentoApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Arquivo\ArquivoRepository;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberNFRequest;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;
use NFePHP\NFe\Tools;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RecebimentoController extends ControllerBaseMercado
{
    public function index()
    {
        // $nf = new NFCertificadosService();
        // $c = $nf->get
        // $nf = new NFEmpresasService();
        // $c = $nf->getEmpresas();
        $nf = new NFEIOService();
        dd($nf);
        // $c = $nf->uploadCertificado('89c6a37437f545e9a7577ab949f95eb2', 'public/arquivos/1/certificado.pfx', '123456');
        
        $c = $nf->criaInscricaoEstadual('89c6a37437f545e9a7577ab949f95eb2',[]);

    //          "accountId" => "66d8cf1ff2f80c0f50c810c6"
    //   "id" => "89c6a37437f545e9a7577ab949f95eb2"
        dd($c);
        $recebimentos = EstoqueRepository::getRecebimentosByUsuario(auth()->user()->getUserModulo->id, auth()->user()->getUserModulo->loja_id);
        $recebimento = EstoqueRepository::getRecebimentoAberto(auth()->user()->getUserModulo->id, auth()->user()->getUserModulo->loja_id);
        $pedidos = PedidoRepository::getPedidos(auth()->user()->getUserModulo->loja_id);
        return view('mercado::estoque.recebimento.index', ['pedidos' => $pedidos]);
    }

    public function create()
    {
        $recebimento = EstoqueRepository::getRecebimentoAberto(auth()->user()->getUserModulo->id, auth()->user()->getUserModulo->loja_id);

        return view('mercado::estoque.recebimento.create', ['recebimento' => $recebimento]);
    }

    public function get_produtos(Request $request)
    {
        $materiaisReceberSelect = ProdutoRepository::getProdutoByNomeAndCodAux(Post::anti_injection($request->q));
        $materiaisReceberSelect = $materiaisReceberSelect->map(function ($item) {
            return [
                'id' => $item->estoque_id,
                'text' => $item->getNomeCompleto()
            ];
        });
        return response()->json($materiaisReceberSelect);
    }

    public function receber_pedido($pedido)
    {
        $pedido = PedidoRepository::getPedidoById($pedido);
        // dd($pedido);
        if (!$pedido) {
            session('error', 'Pedido não encontrado');
            return redirect()->back();
        }
        return view('mercado::estoque.recebimento.receber', ['pedido' => $pedido]);
    }

    public function receber(Request $request)
    {
        $this->getDb()->begin();

        try {

            $parans = (object) Post::anti_injection_array($request->except('dados_storage'));
            $itens = Post::anti_injection_array(json_decode($request->dados_storage, true));
            $pedido = $parans->pedido;
            $data = $parans->dataRecebimento;
            $observacoes = $request->observacoes ? $parans->observacoes : null;
            $historico = $this->getCriarHistoricoRequest($request);
            $historico->setComentario($observacoes);
            $recebimento = RecebimentoApplication::receber(new ReceberRequest(
                $historico,
                $pedido,
                $data,
                $itens,
                $request->notaFiscal
            ));

            $this->getDb()->commit();
            session()->flash('success', 'Recebimento realizado com sucesso.');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            debugException($e);
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function download_nf($arquivo_id)
    {

        $usuario = auth()->user()->getUserModulo;
        $arquivo = ArquivoRepository::getArquivoByID($arquivo_id);

        if ($arquivo->loja_id != $usuario->loja_id) {
            session()->flush('error', 'Arquivo solicitado é diferente da loja em que você está logado.');
            return redirect()->back();
        }

        // Definindo o caminho do arquivo
        $filePath = storage_path($arquivo->path);

        // Verificando se o arquivo existe
        if (!file_exists($filePath)) {
            session()->flash('error', 'Arquivo não encontrado.');
            return redirect()->back();
        }

        // Se o arquivo existe, realiza o download
        return response()->download($filePath);
    }

    public function receber_nf_create()
    {
        //  '35241248531380000117550030000048611159456155'
        // $chave = '35170608530528000184550000000154301000771561';
        //     $chave = '35161147508411011603551000077959551093041003';
        //     $NF = new NFConsultaService();
        //    $nota = $NF->getNotaFiscal($chave, auth()->user()->empresa_id);
        //    dd($nota);
        return view('mercado::estoque.recebimento.receber_nf');
    }

    public function gerar_qr_code()
    {
        try {

            $usuario = auth()->user();
            if (!$usuario) {
                throw new Exception("Usuário não cadastrado para essa ação.", 1);
            }
            $token = DB::table('password_resets')
                ->where('created_at', '>=', now()->subMinutes(15))->where('email', $usuario->email)
                ->first();

            if (!$token) {
                $token = Hash::make('token_verificao');
                DB::table('password_resets')->insert([
                    'email' => $usuario->email,
                    'token' => $token,
                    'created_at' => now(),
                ]);
            }

            $qrCode = QrCode::format('svg')->size(200)->generate(route('verificar.token', ['token' => $token]));
            dd($qrCode);
            return response()->json([
                'success' => true,
                'msg' => 'QR Code gerado com sucesso.',
                'qrCode' => $qrCode,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            dd($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function scanear_nf($token)
    {
        try {

            $tokenData = DB::table('password_resets')
                ->where('token', $token) // Valida o token específico
                ->where('created_at', '>=', now()->subMinutes(15)) // Verifica a validade
                ->first();

            if (!$tokenData) {
                throw new Exception("Token expirado ou inválido.", 1);
            }

            return response()->json(['success' => false, 'msg' => 'De a permissão para a câmera e faça a scaneamento.']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function consulta_nf(Request $request)
    {

        $chave = $request->chave;
        $NF = new NFConsultaService();
        $nota = $NF->getNotaFiscal($chave, auth()->user()->empresa_id);


        return response()->json($nota);
    }

    public function receber_nf(Request $request)
    {
        $this->getDb()->begin();

        try {
            // $a = file_get_contents(storage_path('app/nfe.xml'));
            // $danfe = new Danfe($a);
    
            // // dd($danfe);
            // // Geração do DANFE em PDF
            // $pdf = $danfe->render();
            // return response($pdf)->header('Content-Type', 'application/pdf');
            
            (new NFConsultaService())->getNotaFiscal('a', 'asd');
            $parans = (object) Post::anti_injection_array($request->all());

            $observacoes = $request->observacoes ? $parans->observacoes : null;
            $historico = $this->getCriarHistoricoRequest($request);
            $historico->setComentario($observacoes);
            $loja_id = auth()->user()->getUserModulo->loja_id;
            $receberNFRequest = new ReceberNFRequest($historico,$parans->chave_nota, $request->dataRecebimento, $loja_id);
            /**
             * executa
             */
            RecebimentoApplication::creceberNF($receberNFRequest);
            // $this->getDb()->commit();
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            debugException($e);
        }
    }
}
