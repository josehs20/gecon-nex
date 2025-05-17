<?php

namespace Modules\Mercado\UseCases\Arquivo;

use Modules\Mercado\Entities\Arquivo;
use Modules\Mercado\Repository\Arquivo\ArquivoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriarArquivo
{

    private $arquivo;
    private $tipo_arquivo_id;
    private $loja_id;
    private CriarHistoricoRequest $historico;

    public function __construct($arquivo, $tipo_arquivo_id, $loja_id, CriarHistoricoRequest $historico)
    {
        $this->arquivo = $arquivo;
        $this->tipo_arquivo_id = $tipo_arquivo_id;
        $this->loja_id = $loja_id;
        $this->historico = $historico;
    }

    public function handle()
    {
        $this->validate();
        $caminho =  $this->salvaArquivoLocal();
        return $this->salvaNoBanco($caminho);
    }

    private function validate()
    {
        // Verifica se o arquivo foi enviado
        if (!$this->arquivo) {
            throw new \Exception("Nenhum arquivo foi enviado.");
        }

        // Verifica o tipo do arquivo
        $tiposPermitidos = ['pdf', 'jpg', 'png', 'jpeg', 'docx', 'xlsx'];
        $extensao = $this->arquivo->getClientOriginalExtension();

        if (!in_array(strtolower($extensao), $tiposPermitidos)) {
            throw new \Exception("Tipo de arquivo não permitido. Permitidos: " . implode(", ", $tiposPermitidos));
        }

        // Verifica o tamanho do arquivo (exemplo: máximo 5 MB)
        $tamanhoMaximo = 5 * 1024 * 1024; // 5 MB
        if ($this->arquivo->getSize() > $tamanhoMaximo) {
            throw new \Exception("O arquivo excede o tamanho máximo permitido de 5 MB.");
        }
    }

    private function salvaArquivoLocal()
    {
        // Define o caminho para salvar o arquivo localmente
        $diretorio = storage_path('app/public/arquivos/' . $this->loja_id);
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0755, true);
        }


        // Gera um nome único para o arquivo
        $nomeArquivo = now()->format('Y_m_d_H_i_s') . '_' . str_replace(' ', '_', $this->arquivo->getClientOriginalName());

        // Move o arquivo para o diretório de destino
        $caminhoCompleto = str_replace('/var/www/html/', '',  $diretorio . '/' . $nomeArquivo);

        $this->arquivo->move($diretorio, $nomeArquivo);

        $caminhoParaBuscaStorage = str_replace('/var/www/html/storage/', '',  $diretorio . '/' . $nomeArquivo);
        return $caminhoParaBuscaStorage;
    }

    private function salvaNoBanco($caminhoArquivo)
    {
        // Salva os dados no banco de dados
        return ArquivoRepository::create($this->tipo_arquivo_id, $caminhoArquivo, $this->loja_id, $this->historico); 
    }
}
