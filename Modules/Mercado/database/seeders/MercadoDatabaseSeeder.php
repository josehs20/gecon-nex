<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class MercadoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->call(RecursosCaixaSeed::class);
        $this->call(CreateTipoImpostosSeed::class);
        $this->call(StatusMercadoSeed::class);
        $this->call(AcoesMercadoSeed::class);
        $this->call(CreateClassificacaoProdutoSeed::class);
        $this->call(CreateEspeciePagamentoTableSeed::class);
        $this->call(CreateTipoMovitacaoEstoqueSeed::class);
        $this->call(CreateTipoArquivoSeed::class);
    }
}
