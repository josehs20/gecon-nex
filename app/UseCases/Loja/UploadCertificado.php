<?php

namespace App\UseCases\Loja;

use App\Models\Loja;
use App\Repository\Loja\LojaRepository;
use App\UseCases\Loja\Requests\UploadCertificadoRequest;
use Exception;

class UploadCertificado
{
    private UploadCertificadoRequest $request;

    public function __construct(UploadCertificadoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
     return $this->salvaNoBanco($this->gravaArquivo());
    }

    // Método para gravar o arquivo no sistema de arquivos
    public function gravaArquivo()
    {
        // Verifica se o arquivo foi enviado
        if (!$this->request->getArquivo()->isValid()) {
            throw new Exception("Arquivo inválido.");
        }

        // Define o caminho onde o arquivo será armazenado
        $loja = Loja::find($this->request->getLojaId());

        $originalName = $this->request->getArquivo()->getClientOriginalName();
        $extensao = pathinfo($originalName, PATHINFO_EXTENSION);
        $nomeSemExtensao = pathinfo($originalName, PATHINFO_FILENAME);

        // Substitui os espaços por _ e caracteres especiais por _
        $nomeLimpo = preg_replace('/[^a-zA-Z0-9]/', '_', str_replace(' ', '_', $nomeSemExtensao));

        // Reconstrua o caminho com a extensão preservada
        $caminho = $loja->empresa_id . '/' . $loja->id . '/certificados/-' . $nomeLimpo . '.' . $extensao;

        // Define o diretório de destino para salvar o arquivo
        $diretorioDestino = storage_path('app/public/' . $caminho);

        // Cria o diretório se não existir
        if (!file_exists(dirname($diretorioDestino))) {
            mkdir(dirname($diretorioDestino), 0777, true);  // Cria o diretório com permissões adequadas
        }

        // Armazena o arquivo na pasta public do storage
        $this->request->getArquivo()->storeAs('public/' . dirname($caminho), basename($caminho));

        return $caminho;
    }

    // Método para salvar os dados do certificado no banco de dados
    public function salvaNoBanco($caminho)
    {
        $loja = Loja::find($this->request->getLojaId());
        if (!$loja->certificado) {
            return LojaRepository::createCertificado($loja->empresa_id, $loja->nfeio->id, true, $loja->id, $caminho, $this->request->getSenha(), $this->request->getExpiracao(), $this->request->getStatus());

        }else {
            return LojaRepository::updateCertificado($loja->certificado->id, $loja->empresa_id, $loja->nfeio->id, true, $loja->id, $caminho, $this->request->getSenha(), $this->request->getExpiracao(), $this->request->getStatus());
        }
    }
}
