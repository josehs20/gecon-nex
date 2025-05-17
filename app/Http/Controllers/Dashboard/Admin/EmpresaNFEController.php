<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Application\LojaApplication;
use App\Http\Controllers\ControllerBase;
use App\Models\InscricaoEstadual;
use App\Models\Loja;
use App\Services\NFEIOService;
use App\System\Post;
use App\UseCases\Loja\Requests\CriarInscricaoEstadualRequest;
use App\UseCases\Loja\Requests\CriarOrAtualizarLojaNFERequest;
use App\UseCases\Loja\Requests\UploadCertificadoRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpresaNFEController extends ControllerBase
{
    /**
     * Parte registrar empresa na api nfeio
     */
    public function store(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            // cada elemento vem com o id na loja sendo a chave
            $loja = Loja::find(key($parans->name));

            if (!$loja) {
                throw new \Exception("Loja não encontrada. loja ID: " . key($parans->name), 1);
            }

            $lojaData = [
                'company' => [
                    'name' => reset($parans->name),
                    'tradeName' => reset($parans->tradeName),
                    'federalTaxNumber' => limparCaracteres(reset($parans->federalTaxNumber)),
                    'taxRegime' => reset($parans->taxRegime),
                    'address' => [
                        'state' => reset($parans->state),
                        'city' => [
                            'code' => reset($parans->city)['code'], // Obtendo o código da cidade
                            'name' => reset($parans->city)['name'], // Nome da cidade
                        ],
                        'district' => reset($parans->district),
                        'additionalInformation' => reset($parans->additionalInformation),
                        'street' => reset($parans->street),
                        'number' => reset($parans->number),
                        'postalCode' => reset($parans->postalCode),
                        'country' => reset($parans->country),
                    ]
                ]
            ];

            $nfeService = new NFEIOService();
            if (!$loja->nfeio) {
                $response = $nfeService->criarEmpresa($lojaData);
            } else {
                $lojaData['company']['id'] = $loja->nfeio->nfeio_id;
                $lojaData['company']['accountId'] = $loja->nfeio->account_id;
                $response = $nfeService->atualizarEmpresa($lojaData);
            }

            $dataNFE = $response['company'];
            $lojaNFE = LojaApplication::criaOrAtualizarLojaNFE(new CriarOrAtualizarLojaNFERequest(
                $loja->empresa_id,
                $loja->id,
                $dataNFE['id'],
                $dataNFE['accountId'],
                $dataNFE['name'],
                $dataNFE['tradeName'],
                $dataNFE['federalTaxNumber'],
                $dataNFE['taxRegime'],
                'ativo',
                $dataNFE['address']
            ));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Loja salva na API de NFE com sucesso.']);
        } catch (\Exception $e) {

            //adicionar controle de logs
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'msg' => 'Erro ao salvar loja na API. ' . $e->getMessage()]);
        }
    }

    /**
     * Parte do certificado digital
     */
    public function storeCertificado(Request $request, $loja_id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('certificado'));
            $nfeService = new NFEIOService();
            $loja = Loja::find($loja_id);
            $arquivo = collect($request->certificado)->first();
            $senha = reset($parans->senha);
            $certificado = $nfeService->uploadCertificado($loja_id, $arquivo, $senha);
            // $certificado = $nfeService->getCertificado($loja->nfeio->nfeio_id);

            $certificado = $certificado['certificate'];
            LojaApplication::uploadCertificado(new UploadCertificadoRequest($loja->id, $arquivo, $senha, $certificado['validUntil'], $certificado['status']));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Certificado salvo na API de NFE com sucesso.']);
        } catch (\Exception $e) {

            //adicionar controle de logs
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'msg' => 'Erro ao salvar certificado na API. ' . $e->getMessage()]);
        }
    }

    public function downloadCertificado($loja_id)
    {
        // Busca a loja pelo ID
        $loja = Loja::find($loja_id);

        // Verifica se a loja existe
        if (!$loja) {
            throw new \Exception("Loja não encontrada.");
        }

        // Obtém o certificado associado à loja
        $certificado = $loja->certificado;

        // Verifica se o certificado existe
        if (!$certificado || !Storage::exists('public/' . $certificado->caminho)) {
            session()->flash('error', 'Arquivo não encontrado');
            return redirect()->back();
        }

        // Retorna o download do arquivo
        return response()->download(storage_path("app/public/" . $certificado->caminho));
    }

    /**
     * Parte da inscrição estadual
     */
    public function storeInscricaoEstadual(Request $request, $loja_id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $inscricao_estadual = reset($parans->inscricao_estadual);
            $loja = Loja::find($loja_id);
            $nfeService = new NFEIOService();

            $inscricao_estadual_nfce_io = $nfeService->criarInscricaoEstadual($loja_id, $inscricao_estadual);
            // $inscricao_estadual_nfce_io = $nfeService->listInscricaoEstadual($loja_id);

            $inscricao_estadual = LojaApplication::criarInscricaoEstadual(new CriarInscricaoEstadualRequest(
                $loja->id,
                $loja->nfeio->id, // nfeio_loja_id
                $inscricao_estadual_nfce_io["stateTax"]["id"], // state_tax_id (caso seja diferente, ajuste aqui)
                $inscricao_estadual_nfce_io["stateTax"]["accountId"],
                $inscricao_estadual_nfce_io["stateTax"]["companyId"],
                $inscricao_estadual_nfce_io["stateTax"]["code"],
                $inscricao_estadual_nfce_io["stateTax"]["specialTaxRegime"],
                $inscricao_estadual_nfce_io["stateTax"]["type"],
                $inscricao_estadual_nfce_io["stateTax"]["taxNumber"],
                $inscricao_estadual_nfce_io["stateTax"]["status"],
                $inscricao_estadual_nfce_io["stateTax"]["serie"],
                $inscricao_estadual_nfce_io["stateTax"]["number"],
                $inscricao_estadual_nfce_io["stateTax"]["processingDetails"],
                $inscricao_estadual_nfce_io['stateTax']['securityCredential']
            ));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Inscrição estadual salva na API de NFE com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'msg' => 'Erro ao salvar inscrição estadual na API. ' . $e->getMessage()]);
        }
    }

    public function updateInscricaoEstadual(Request $request, $inscricao_id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $inscricao_estadual = InscricaoEstadual::find($inscricao_id);
            $nfeService = new NFEIOService();
            if (!$inscricao_estadual) {
                throw new Exception("Inscrição estadual não encontrada.", 1);
            }

            $loja = $inscricao_estadual->loja;
            $inscricao_estadual_nfce_io = $nfeService->atualizaInscricaoEstadual($loja->id, $inscricao_id, $parans);

            $incricao_estadual = LojaApplication::atualizaInscricaoEstadual($inscricao_estadual->id, new CriarInscricaoEstadualRequest(
                $loja->id,
                $loja->nfeio->id, // nfeio_loja_id
                $inscricao_estadual_nfce_io["stateTax"]["id"], // state_tax_id (caso seja diferente, ajuste aqui)
                $inscricao_estadual_nfce_io["stateTax"]["accountId"],
                $inscricao_estadual_nfce_io["stateTax"]["companyId"],
                $inscricao_estadual_nfce_io["stateTax"]["code"],
                $inscricao_estadual_nfce_io["stateTax"]["specialTaxRegime"],
                $inscricao_estadual_nfce_io["stateTax"]["type"],
                $inscricao_estadual_nfce_io["stateTax"]["taxNumber"],
                $inscricao_estadual_nfce_io["stateTax"]["status"],
                $inscricao_estadual_nfce_io["stateTax"]['series'][0],//serie
                $inscricao_estadual_nfce_io["stateTax"]["series"][1],//number
                $inscricao_estadual_nfce_io["stateTax"]["processingDetails"],
                $inscricao_estadual_nfce_io["stateTax"]["securityCredential"]
            ));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Inscrição estadual atualizada na API de NFE com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'msg' => 'Erro ao atualizar inscrição estadual na API. ' . $e->getMessage()]);
        }
    }

    public function deleteInscricaoEstadual(Request $request, $inscricao_id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            dd($parans);
            $inscricao_estadual = reset($parans->inscricao_estadual);
            $loja = Loja::find($loja_id);
            $nfeService = new NFEIOService();
            if ($loja->inscricao_estadual) {
                throw new Exception("Essa loja já contém uma inscrição estadual", 1);
            }

            $inscricao_estadual_nfce_io = $nfeService->criarInscricaoEstadual($loja_id, $inscricao_estadual);
            // $inscricao_estadual_nfce_io = $nfeService->listInscricaoEstadual($loja_id);

            $incricao_estadual = LojaApplication::criarInscricaoEstadual(new CriarInscricaoEstadualRequest(
                $loja->id,
                $loja->nfeio->id, // nfeio_loja_id
                $inscricao_estadual_nfce_io["stateTax"]["id"], // state_tax_id (caso seja diferente, ajuste aqui)
                $inscricao_estadual_nfce_io["stateTax"]["accountId"],
                $inscricao_estadual_nfce_io["stateTax"]["companyId"],
                $inscricao_estadual_nfce_io["stateTax"]["code"],
                $inscricao_estadual_nfce_io["stateTax"]["specialTaxRegime"],
                $inscricao_estadual_nfce_io["stateTax"]["type"],
                $inscricao_estadual_nfce_io["stateTax"]["taxNumber"],
                $inscricao_estadual_nfce_io["stateTax"]["status"],
                $inscricao_estadual_nfce_io["stateTax"]["serie"],
                $inscricao_estadual_nfce_io["stateTax"]["number"],
                $inscricao_estadual_nfce_io["stateTax"]["processingDetails"],
                $inscricao_estadual_nfce_io["stateTax"]["securityCredential"]
            ));

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Inscrição estadual salva na API de NFE com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'msg' => 'Erro ao salvar inscrição estadual na API. ' . $e->getMessage()]);
        }
    }
}
