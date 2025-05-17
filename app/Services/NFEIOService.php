<?php

namespace App\Services;

use App\Models\InscricaoEstadual;
use App\Models\Loja;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Fluent;
use Modules\Mercado\Entities\Estoque;
use Modules\Mercado\Entities\NCM;
use Modules\Mercado\Entities\Venda;

class NFEIOService
{
    protected $apiUrl; // URL base da API
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api.nfse.io/';
        $this->apiKey = config('services.nfe.api_key'); // Obtenha a chave da configuração
    }

    /**
     * Certificado
     */
    public function verificaCertificado($company_id)
    {
        $url = $this->apiUrl . 'v2/companies/' . $company_id . '/certificates';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($url);

        // Captura e retorna a resposta
        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->body(), 1);
        }
    }

    public function getCertificado($loja_id)
    {
        // Busca a loja pelo ID
        $loja = Loja::with('nfeio')->find($loja_id);

        // Verifica se a loja possui a configuração necessária
        if (!$loja || !$loja->nfeio) {
            throw new Exception("Empresa não cadastrada na API.", 1);
        }

        $url = $this->apiUrl . 'v2/companies/' . $loja->nfeio->nfeio_id . '/certificates';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($url);

        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->json()['errors'][0]['message'], $response->json()['errors'][0]['code']);
        }
    }

    public function uploadCertificado($loja_id, UploadedFile $arquivo, $senha)
    {
        // Busca a loja pelo ID
        $loja = Loja::with('nfeio')->find($loja_id);

        // Verifica se a loja possui a configuração necessária
        if (!$loja || !$loja->nfeio) {
            throw new Exception("Empresa não cadastrada na API.", 1);
        }

        // Define a URL da API
        $url = $this->apiUrl . 'v2/companies/' . $loja->nfeio->nfeio_id . '/certificates';

        // Verifica se o arquivo foi enviado e se é válido
        if (!$arquivo->isValid()) {
            throw new \Exception('Arquivo corrompido.');
        }

        $filePath = $arquivo->getRealPath();
        $fileName = $arquivo->getClientOriginalName();

        // Faz o upload para a API usando multipart/form-data
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->attach(
            'file', // Nome do campo de arquivo no formulário
            file_get_contents($filePath), // Conteúdo do arquivo
            $fileName // Nome original do arquivo
        )->post($url, [
            'password' => intval($senha), // Outros dados que precisam ser enviados
        ]);

        // Captura e retorna a resposta
        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->json()['errors'][0]['message'], $response->json()['errors'][0]['code']);
        }
    }

    /**
     * Empresa
     */
    public function atualizarEmpresa($empresaData)
    {
        //formato de dados para atualziar uma emrpesa para emissao de nota fiscal
        // $empresaData = [
        //     'company' => [
        //         'id' => 'c9a8685a05d440ef923e1384bdb5e1a8',
        //         'name' => 'Empresa Rozy',
        //         'accountId' => 'novo_id_da_conta',
        //         'tradeName' => 'Nome Fantasia Rozy',
        //         'federalTaxNumber' => 36395200000138,
        //         'taxRegime' => 'simplesNacional', // Certifique-se de usar um valor válido para o regime tributário
        //         'address' => [
        //             'state' => 'ES',
        //             'city' => [
        //                 'code' => '3202009',
        //                 'name' => 'Dores do rio preto' // Corrija o nome da cidade se necessário
        //             ],
        //             'district' => 'Pedra menina',
        //             'additionalInformation' => 'Casa',
        //             'street' => 'Rua nerilda ramos', // Certifique-se de que este campo está corretamente preenchido
        //             'number' => '71',
        //             'postalCode' => '29580000',
        //             'country' => 'BRA'
        //         ]
        //     ]
        // ];
        $url = $this->apiUrl . 'v2/companies/' . $empresaData['company']['id'];

        // Envia a requisição PUT para atualizar a empresa
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->put($url, $empresaData);

        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception("Erro ao atualizar empresa" . $response->body(), 1);
        }
    }

    public function criarEmpresa($empresaData)
    {
        //formato de dados para criação de empresas para emissao de nota fiscal
        // $empresaData = [
        //     'company' => [
        //         // 'id' => 'c9a8685a05d440ef923e1384bdb5e1a8',
        //         'name' => 'Empresa Rozy',
        //         'accountId' => '11111',
        //         'tradeName' => 'Nome Fantasia Rozy',
        //         'federalTaxNumber' => 52484205000193,
        //         'taxRegime' => 'simplesNacional', // Certifique-se de usar um valor válido para o regime tributário
        //         'address' => [
        //             'state' => 'ES',
        //             'city' => [
        //                 'code' => '3202009',
        //                 'name' => 'Dores do rio preto' // Corrija o nome da cidade se necessário
        //             ],
        //             'district' => 'Pedra menina',
        //             'additionalInformation' => 'Casa',
        //             'street' => 'Rua nerilda ramos', // Certifique-se de que este campo está corretamente preenchido
        //             'number' => '71',
        //             'postalCode' => '29580000',
        //             'country' => 'BRA'
        //         ]
        //     ]
        // ];

        $url = $this->apiUrl . 'v2/companies';

        // Envia a requisição POST para criar a empresa
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, $empresaData);

        // Captura e retorna a resposta
        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->json()['errors'][0]['message'], $response->json()['errors'][0]['code']);
        }
        // retorno caso sucesso
        // rray:1 [
        //     "company" => array:7 [
        //       "taxRegime" => "SimplesNacional"
        //       "address" => array:8 [
        //         "city" => array:2 [
        //           "name" => "Itaperuna"
        //           "code" => "3301900"
        //         ]
        //         "country" => "BRA"
        //         "postalCode" => "28300000"
        //         "number" => "123"
        //         "street" => "Rua Teste"
        //         "additionalInformation" => "Informações adicionais"
        //         "district" => "Centro"
        //         "state" => "RJ"
        //       ]
        //       "name" => "Empresa Teste"
        //       "accountId" => "66d8cf1ff2f80c0f50c810c6"
        //       "tradeName" => "Loja Teste"
        //       "federalTaxNumber" => 53694056000150
        //       "id" => "52a91388f0624b8b95542b8a7b3453f1"
        //     ]
        //   ]
    }

    public function getEmpreByCompanyId($companyId)
    {
        $url = $this->apiUrl . 'v2/companies/' . $companyId;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($url);

        // Captura e retorna a resposta
        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception("Não foi possível consultar a empresa", 1);
        }
    }

    public function getEmpresas($idStart = null, $fimId = null, $limit = 10)
    {
        // Construa a URL com parâmetros de consulta
        $url = $this->apiUrl . 'v2/companies';
        $queryParams = [
            'startingAfter' => $idStart,
            'endingBefore' => $fimId,
            'limit' => $limit,
        ];

        // Filtre os parâmetros nulos
        $queryParams = array_filter($queryParams);

        // Envie a requisição GET
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($url, $queryParams);

        // Verifique o status da resposta
        if ($response->ok()) {
            return $response->json(); // Retorna os dados das empresas
        } else {
            // Trate erros (opcional: você pode lançar exceções ou retornar mensagens de erro)
            throw new \Exception('Erro ao consultar empresas: ' . $response->body());
        }
    }

    public function deleteEmpresa($companyId)
    {

        $url = $this->apiUrl . 'v2/companies/' . $companyId;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete($url);
        // Verifique o status da resposta

        if ($response->status() == 204) {
            return true; // Retorna os dados das empresas
        } else {
            // Trate erros (opcional: você pode lançar exceções ou retornar mensagens de erro)
            throw new \Exception('Erro ao deletar empresa ' . $response->body());
        }
    }


    /**
     * Inscrição estadual
     */
    public function listInscricaoEstadual($loja_id)
    {
        // Busca a loja pelo ID
        $loja = Loja::with('nfeio')->find($loja_id);

        // Verifica se a loja possui a configuração necessária
        if (!$loja || !$loja->nfeio) {
            throw new Exception("Empresa não cadastrada na API.", 1);
        }

        $url = $this->apiUrl . 'v2/companies/' . $loja->nfeio->nfeio_id . '/statetaxes';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($url);

        // Captura e retorna a resposta
        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->body(), 1);
        }
    }

    public function criarInscricaoEstadual($loja_id, $inscricao_estadual)
    {
        // Busca a loja pelo ID
        $loja = Loja::with('nfeio')->find($loja_id);

        // Verifica se a loja possui a configuração necessária
        if (!$loja || !$loja->nfeio) {
            throw new Exception("Empresa não cadastrada na API.", 1);
        }

        // URL da API
        $url = $this->apiUrl . 'v2/companies/' . $loja->nfeio->nfeio_id . '/statetaxes';
        $address = json_decode($loja->nfeio->address, true);
        $uf = $address['state'];

        // Acessando o valor do "state"
        // Dados da Inscrição Estadual
        $dados = [
            'stateTax' => [
                'code' => $uf,  // Código do estado, por exemplo: 'rO' para Rondônia
                'environmentType' => 'none',  // Tipo de ambiente: 'none', 'production' ou 'test'
                'taxNumber' => $inscricao_estadual['taxNumber'],  // Inscrição Estadual (número) recebida como parâmetro
                'specialTaxRegime' => $inscricao_estadual['specialTaxRegime'],  // Regime de tributação, pode ser: 'automatico', 'nenhum', etc.
                'serie' => $inscricao_estadual['serie'],  // Série para emissão da NFe (número inteiro)
                'number' => $inscricao_estadual['number'],  // Número para emissão da NFe (número inteiro)
                'securityCredential' => [
                    'id' => $inscricao_estadual['security_id'],  // Id do código de segurança (número inteiro)
                    'code' => $inscricao_estadual['security_code'],  // Código de segurança do contribuinte
                ],
                'type' => $inscricao_estadual['type']  // Tipo de emissão, pode ser: 'default', 'nFe', 'nFCe'
            ]
        ];

        // Fazendo a requisição POST
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, $dados);

        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->body(), 1);
        }
    }

    public function atualizaInscricaoEstadual($loja_id, $inscricao_estadual_id, $data_update)
    {
        // Busca a loja pelo ID
        $loja = Loja::with('nfeio')->find($loja_id);
        $inscricao_estadual = InscricaoEstadual::find($inscricao_estadual_id);

        // Verifica se a loja possui a configuração necessária
        if (!$loja || !$loja->nfeio) {
            throw new Exception("Empresa não cadastrada na API.", 1);
        }

        // URL da API
        $url = $this->apiUrl . 'v2/companies/' . $loja->nfeio->nfeio_id . '/statetaxes/' . $inscricao_estadual->state_tax_id;
        $address = json_decode($loja->nfeio->address, true);
        $uf = $address['state'];

        // Acessando o valor do "state"
        // Dados da Inscrição Estadual
        $data_update = new Fluent($data_update);
        $dados = [
            'stateTax' => [
                'id' => (string) $inscricao_estadual->state_tax_id,  // Forçando para string
                'code' => (string) $uf,
                'environmentType' => 'none',
                'taxNumber' => (string) $data_update['taxNumber'],
                'specialTaxRegime' => (string) $data_update['specialTaxRegime'],
                'serie' => (int) $data_update['serie'],
                'number' => (int) $data_update['number'],
                'securityCredential' => [
                    'id' => (int) $data_update['security_id'],
                    'code' => (string) $data_update['security_code'],
                ],
                'type' => (string) $data_update['type']
            ]
        ];

        // Fazendo a requisição POST
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->put($url, $dados);

        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->body(), 1);
        }
    }

    public function deleteInscricaoEstadual($loja_id, $inscricao_estadual)
    {
        // Busca a loja pelo ID
        $loja = Loja::with('nfeio')->find($loja_id);

        // Verifica se a loja possui a configuração necessária
        if (!$loja || !$loja->inscricao_estadual) {
            throw new Exception("inscrição não cadastrada na API.", 1);
        }

        // URL da API
        $url = $this->apiUrl . 'v2/companies/' . $loja->nfeio->nfeio_id . '/statetaxes/' . $loja->inscricao_estadual->state_tax_id;

        // Fazendo a requisição delete
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->delete($url);

        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception($response->body(), 1);
        }
    }

    /**
     * Emissão de NFCE
     */

    public function calculaImpostoUmParaUm(Estoque $estoque, NCM $ncm)
    {

        // $url = $this->apiUrl . 'tax-rules/' . $estoque->loja->nfeio->account_id. '/engine/calculate';
        $url = $this->apiUrl . 'tax-codes/operation-code/';

        $dados = [];

        // Montando o corpo da requisição com base nos dados de Estoque e NCM
        $dados = [
            'collectionId' => 'estoque_' . $estoque->id, // Opcional, identificador interno
            'issuer' => [
                'taxRegime' => 'NationalSimple', // Substitua pelo regime tributário real da loja
                'state' => 'SP', // Substitua pelo estado da loja (ex.: $estoque->loja->estado)
            ],
            'recipient' => [
                'taxRegime' => 'NationalSimple', // Ajuste conforme o destinatário
                'state' => 'RJ', // Exemplo, ajuste conforme o caso
            ],
            'operationType' => 'Outgoing', // Venda (saída), ajuste se for entrada
            'items' => [
                [
                    'operationCode' => '5102', // CFOP exemplo para venda, ajuste conforme necessidade
                    'issuerTaxProfile' => 'retail', // Perfil do emitente (exemplo)
                    'recipientTaxProfile' => 'final_consumer_non_icms_contributor', // Perfil do destinatário
                    'sku' => 'SKU_' . $estoque->id, // Código do produto
                    'ncm' => $ncm->code, // Código NCM vindo do modelo NCM
                    'origin' => 'National', // Origem do produto, ajuste conforme necessário
                    'quantity' => 1, // Quantidade em estoque
                    'unitAmount' => 10, // Valor unitário
                ],
            ],
        ];

    //   // Enviando a requisição para a API
    //   dd("http://ibpt.nfe.io/ncm/RJ/{$ncm->codigo}.json");
    //     $response = Http::get("http://ibpt.nfe.io/ncm/RJ/{$ncm->codigo}.json");
    //     dd($this->getEmpresas());
    }

    public function emitirNFCE(Venda $venda)
    {
        $url = $this->apiUrl . 'v2/companies/' . $venda->loja->nfeio->nfeio_id . '/consumerinvoices/sync';

        $dados = $this->montaPostEmitirNFCE($venda);

        // Enviando a requisição para a API
        $response = Http::withHeaders([
            'Authorization' => $this->apiKey, // Sem o "Bearer"
            'Content-Type' => 'application/json',
        ])->post($url, $dados);

        // Debug da resposta
        dd($response->body(), $response->json(), $response->status(), $dados, $response);

        return $response->json();
    }

    public function montaPostEmitirNFCE(Venda $venda)
    {
        $inscricao_estadual = $venda->loja->inscricoes_estaduais->first(function ($item) {
            return $item->type == 'NFCe';
        });
        $dataTime = Carbon::parse($venda->data_concluida)->format('Y-m-d\TH:i:s.v\Z');
        $requestApi = [
            "id" => $venda->id,
            "payment" => [
                [
                    "paymentDetail" => [
                        [
                            // "method" => $venda->getMethodNFCE(),
                            // "methodDescription" => null,
                            // "paymentType" => $venda->getPaymentTypeNFCE(),
                            // "amount" => converteCentavosParaFloat($venda->total),
                            // "card" => [
                            //     "federalTaxNumber" => null,
                            //     "flag" => "None",
                            //     "authorization" => null,
                            //     "integrationPaymentType" => "NotIntegrated",
                            //     "federalTaxNumberRecipient" => null,
                            //     "idPaymentTerminal" => null
                            // ],
                            "paymentDate" => '2025-03-09T18:31:19.030Z',
                            // "federalTaxNumberPag" => '',
                            // "statePag" => null
                        ]
                    ],
                    "payBack" => 0
                ]
            ],
            // "serie" => $inscricao_estadual->serie,
            // "number" => $inscricao_estadual->number,
            // "operationOn" => $dataTime,
            // "operationNature" => "Venda de mercadoria para consumidor final",
            // "operationType" => "Incoming",
            // "destination" => "None",
            // "printType" => "DANFE_NFC_E",
            // "purposeType" => "Normal",
            // "consumerType" => "FinalConsumer",
            // "presenceType" => "Presence",
            // "contingencyOn" => null,
            // "contingencyJustification" => null,
            // "buyer" => [
            //     "accountId" => "string",
            //     "id" => "string",
            //     "name" => "string",
            //     "federalTaxNumber" => 0,
            //     "email" => "string",
            //     "address" => [
            //         "state" => "string",
            //         "city" => [
            //             "code" => "string",
            //             "name" => "string"
            //         ],
            //         "district" => "string",
            //         "additionalInformation" => "string",
            //         "street" => "string",
            //         "number" => "string",
            //         "postalCode" => "string",
            //         "country" => "string",
            //         "phone" => "string"
            //     ],
            //     "type" => "Undefined",
            //     "stateTaxNumberIndicator" => "None",
            //     "tradeName" => "string",
            //     "taxRegime" => "None",
            //     "stateTaxNumber" => "string"
            // ],
            // "transport" => [
            //     "freightModality" => "ByIssuer",
            //     "transportGroup" => [
            //         "accountId" => "string",
            //         "id" => "string",
            //         "name" => "string",
            //         "federalTaxNumber" => 0,
            //         "email" => "string",
            //         "address" => [
            //             "state" => "string",
            //             "city" => [
            //                 "code" => "string",
            //                 "name" => "string"
            //             ],
            //             "district" => "string",
            //             "additionalInformation" => "string",
            //             "street" => "string",
            //             "number" => "string",
            //             "postalCode" => "string",
            //             "country" => "string",
            //             "phone" => "string"
            //         ],
            //         "type" => "Undefined",
            //         "stateTaxNumber" => "string",
            //         "transportRetention" => "string"
            //     ],
            //     "reboque" => [
            //         "plate" => "string",
            //         "uf" => "string",
            //         "rntc" => "string",
            //         "wagon" => "string",
            //         "ferry" => "string"
            //     ],
            //     "volume" => [
            //         "volumeQuantity" => 0,
            //         "species" => "string",
            //         "brand" => "string",
            //         "volumeNumeration" => "string",
            //         "netWeight" => 0,
            //         "grossWeight" => 0
            //     ],
            //     "transportVehicle" => [
            //         "plate" => "string",
            //         "state" => "string",
            //         "rntc" => "string"
            //     ],
            //     "sealNumber" => "string",
            //     "transpRate" => [
            //         "serviceAmount" => 0,
            //         "bcRetentionAmount" => 0,
            //         "icmsRetentionRate" => 0,
            //         "icmsRetentionAmount" => 0,
            //         "cfop" => 0,
            //         "cityGeneratorFactCode" => 0
            //     ]
            // ],
            // "additionalInformation" => [
            //     "fisco" => "string",
            //     "taxpayer" => "string",
            //     "xmlAuthorized" => [0],
            //     "effort" => "string",
            //     "order" => "string",
            //     "contract" => "string",
            //     "taxDocumentsReference" => [
            //         [
            //             "taxCouponInformation" => [
            //                 "modelDocumentFiscal" => "string",
            //                 "orderECF" => "string",
            //                 "orderCountOperation" => 0
            //             ],
            //             "documentInvoiceReference" => [
            //                 "state" => 0,
            //                 "yearMonth" => "string",
            //                 "federalTaxNumber" => "string",
            //                 "model" => "string",
            //                 "series" => "string",
            //                 "number" => "string"
            //             ],
            //             "documentElectronicInvoice" => [
            //                 "accessKey" => "string"
            //             ]
            //         ]
            //     ],
            //     "taxpayerComments" => [
            //         [
            //             "field" => "string",
            //             "text" => "string"
            //         ]
            //     ],
            //     "referencedProcess" => [
            //         [
            //             "identifierConcessory" => "string",
            //             "identifierOrigin" => 0,
            //             "concessionActType" => 0
            //         ]
            //     ]
            // ],
            "items" => [
                [
                    "code" => "string",
                    "codeGTIN" => "string",
                    "description" => "string",
                    "ncm" => "string",
                    "nve" => ["string"],
                    "extipi" => "string",
                    "cfop" => 0,
                    "unit" => "string",
                    "quantity" => 0,
                    "unitAmount" => 0,
                    "totalAmount" => 0,
                    "codeTaxGTIN" => "string",
                    "unitTax" => "string",
                    "quantityTax" => 0,
                    "taxUnitAmount" => 0,
                    "freightAmount" => 0,
                    "insuranceAmount" => 0,
                    "discountAmount" => 0,
                    "othersAmount" => 0,
                    "totalIndicator" => true,
                    "cest" => "string",
                    "tax" => [
                        "totalTax" => 0,
                        "icms" => [
                            "origin" => "string",
                            "cst" => "string",
                            "csosn" => "string",
                            "baseTaxModality" => "string",
                            "baseTax" => 0,
                            "baseTaxSTModality" => "string",
                            "baseTaxSTReduction" => "string",
                            "baseTaxST" => 0,
                            "baseTaxReduction" => 0,
                            "stRate" => 0,
                            "stAmount" => 0,
                            "stMarginAmount" => 0,
                            "rate" => 0,
                            "amount" => 0,
                            "percentual" => 0,
                            "snCreditRate" => 0,
                            "snCreditAmount" => 0,
                            "stMarginAddedAmount" => "string",
                            "stRetentionAmount" => "string",
                            "baseSTRetentionAmount" => "string",
                            "baseTaxOperationPercentual" => "string",
                            "ufst" => "string",
                            "amountSTReason" => "string",
                            "baseSNRetentionAmount" => "string",
                            "snRetentionAmount" => "string",
                            "amountOperation" => "string",
                            "percentualDeferment" => "string",
                            "baseDeferred" => "string",
                            "exemptAmount" => 0,
                            "exemptReason" => "Agriculture",
                            "exemptAmountST" => 0,
                            "exemptReasonST" => "Agriculture",
                            "fcpRate" => 0,
                            "fcpAmount" => 0,
                            "fcpstRate" => 0,
                            "fcpstAmount" => 0,
                            "fcpstRetRate" => 0,
                            "fcpstRetAmount" => 0,
                            "baseTaxFCPSTAmount" => 0,
                            "substituteAmount" => 0,
                            "stFinalConsumerRate" => 0,
                            "effectiveBaseTaxReductionRate" => 0,
                            "effectiveBaseTaxAmount" => 0,
                            "effectiveRate" => 0,
                            "effectiveAmount" => 0,
                            "deductionIndicator" => "NotDeduct"
                        ],
                        "ipi" => [
                            "cst" => "string",
                            "classificationCode" => "string",
                            "classification" => "string",
                            "producerCNPJ" => "string",
                            "stampCode" => "string",
                            "stampQuantity" => 0,
                            "base" => 0,
                            "rate" => 0,
                            "unitQuantity" => 0,
                            "unitAmount" => 0,
                            "amount" => 0
                        ],
                        "ii" => [
                            "baseTax" => "string",
                            "customsExpenditureAmount" => "string",
                            "amount" => 0,
                            "iofAmount" => 0,
                            "vEnqCamb" => 0
                        ],
                        "pis" => [
                            "cst" => "string",
                            "baseTax" => 0,
                            "rate" => 0,
                            "amount" => 0,
                            "baseTaxProductQuantity" => 0,
                            "productRate" => 0
                        ],
                        "cofins" => [
                            "cst" => "string",
                            "baseTax" => 0,
                            "rate" => 0,
                            "amount" => 0,
                            "baseTaxProductQuantity" => 0,
                            "productRate" => 0
                        ],
                        "icmsDestination" => [
                            "vBCUFDest" => 0,
                            "pFCPUFDest" => 0,
                            "pICMSUFDest" => 0,
                            "pICMSInter" => 0,
                            "pICMSInterPart" => 0,
                            "vFCPUFDest" => 0,
                            "vICMSUFDest" => 0,
                            "vICMSUFRemet" => 0,
                            "vBCFCPUFDest" => 0
                        ]
                    ],
                    "additionalInformation" => "string",
                    "numberOrderBuy" => "string",
                    "itemNumberOrderBuy" => 0,
                    "importControlSheetNumber" => "string",
                    "fuelDetail" => [
                        "codeANP" => "string",
                        "percentageNG" => 0,
                        "descriptionANP" => "string",
                        "percentageGLP" => 0,
                        "percentageNGn" => 0,
                        "percentageGNi" => 0,
                        "startingAmount" => 0,
                        "codif" => "string",
                        "amountTemp" => 0,
                        "stateBuyer" => "string",
                        "cide" => [
                            "bc" => 0,
                            "rate" => 0,
                            "cideAmount" => 0
                        ],
                        "pump" => [
                            "spoutNumber" => 0,
                            "number" => 0,
                            "tankNumber" => 0,
                            "beginningAmount" => 0,
                            "endAmount" => 0,
                            "percentageBio" => 0
                        ],
                        "fuelOrigin" => [
                            "indImport" => 0,
                            "cUFOrig" => 0,
                            "pOrig" => 0
                        ]
                    ],
                    "benefit" => "string",
                    "importDeclarations" => [
                        [
                            "code" => "string",
                            "registeredOn" => "2025-03-09T03:57:51.872Z",
                            "customsClearanceName" => "string",
                            "customsClearanceState" => "NA",
                            "customsClearancedOn" => "2025-03-09T03:57:51.872Z",
                            "additions" => [
                                [
                                    "code" => 0,
                                    "manufacturer" => "string",
                                    "amount" => 0,
                                    "drawback" => 0
                                ]
                            ],
                            "exporter" => "string",
                            "internationalTransport" => "None",
                            "intermediation" => "None",
                            "acquirerFederalTaxNumber" => "string",
                            "stateThird" => "string"
                        ]
                    ],
                    "exportDetails" => [
                        [
                            "drawback" => "string",
                            "hintInformation" => [
                                "registryId" => "string",
                                "accessKey" => "string",
                                "quantity" => 0
                            ]
                        ]
                    ],
                    "taxDetermination" => [
                        "operationCode" => 0,
                        "issuerTaxProfile" => "string",
                        "buyerTaxProfile" => "string",
                        "origin" => "string",
                        "acquisitionPurpose" => "string"
                    ]
                ]
            ],
            // "totals" => [
            //     "icms" => [
            //         "baseTax" => 0,
            //         "icmsAmount" => 0,
            //         "icmsExemptAmount" => 0,
            //         "stCalculationBasisAmount" => 0,
            //         "stAmount" => 0,
            //         "productAmount" => 0,
            //         "freightAmount" => 0,
            //         "insuranceAmount" => 0,
            //         "discountAmount" => 0,
            //         "iiAmount" => 0,
            //         "ipiAmount" => 0,
            //         "pisAmount" => 0,
            //         "cofinsAmount" => 0,
            //         "othersAmount" => 0,
            //         "invoiceAmount" => 0,
            //         "fcpufDestinationAmount" => 0,
            //         "icmsufDestinationAmount" => 0,
            //         "icmsufSenderAmount" => 0,
            //         "federalTaxesAmount" => 0,
            //         "fcpAmount" => 0,
            //         "fcpstAmount" => 0,
            //         "fcpstRetAmount" => 0,
            //         "ipiDevolAmount" => 0,
            //         "qBCMono" => 0,
            //         "vICMSMono" => 0,
            //         "qBCMonoReten" => 0,
            //         "vICMSMonoReten" => 0,
            //         "qBCMonoRet" => 0,
            //         "vICMSMonoRet" => 0
            //     ],
            //     "issqn" => [
            //         "totalServiceNotTaxedICMS" => 0,
            //         "baseRateISS" => 0,
            //         "totalISS" => 0,
            //         "valueServicePIS" => 0,
            //         "valueServiceCOFINS" => 0,
            //         "provisionService" => "2025-03-09T03:57:51.872Z",
            //         "deductionReductionBC" => 0,
            //         "valueOtherRetention" => 0,
            //         "discountUnconditional" => 0,
            //         "discountConditioning" => 0,
            //         "totalRetentionISS" => 0,
            //         "codeTaxRegime" => 0
            //     ]
            // ],
            // "billing" => [
            //     "bill" => [
            //         "number" => "string",
            //         "originalAmount" => 0,
            //         "discountAmount" => 0,
            //         "netAmount" => 0
            //     ],
            //     "duplicates" => [
            //         [
            //             "number" => "string",
            //             "expirationOn" => "2025-03-09T03:57:51.872Z",
            //             "amount" => 0
            //         ]
            //     ]
            // ],
            // "issuer" => [
            //     "stStateTaxNumber" => "string"
            // ],
            // "transactionIntermediate" => [
            //     "federalTaxNumber" => 0,
            //     "identifier" => "string"
            // ]
        ];

        return $requestApi;
    }
}
