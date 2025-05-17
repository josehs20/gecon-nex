<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use Database\Seeders\baseFake\UsuariosFakeSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Mercado\Database\Seeders\CreateClassificacaoProdutoSeed;

class MercadoDatabaseFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CreateEnderecosFakeSeedTableSeeder::class);
        $this->call(CreateLojasFakeSeeder::class);
        $this->call(CreateFabricanteFakeSeed::class);
        $this->call(CreateFornecedoresFakeSeedTableSeeder::class);
        $this->call(CreateClientesFakeSeedTableSeeder::class);
        $this->call(CreateUnidadeMedidaFakeSeedTableSeeder::class);
        $this->call(CreateProdutoFakeSeed::class);
        // $this->call(CreateUsuariosDesenvolvimentoSeed::class);
        $this->call(CreateFormaPagamentoFakeSeed::class);
        $this->call(UsuariosFakeSeeder::class);
        $this->call(CreateCreditoClienteFakeSeed::class);
        $this->call(CreateClassificacaoProdutoSeed::class);
        $this->call(CreateCaixasFakeTableSeed::class);
    }
}
