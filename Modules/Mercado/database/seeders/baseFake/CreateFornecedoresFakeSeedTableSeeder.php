<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateFornecedoresFakeSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('fornecedor')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('fornecedor')->insert(self::getFornecedores());

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getFornecedores()
    {
        return [
            [
                'empresa_master_cod' => 1,
                'nome' => 'CA TRANSPORTES UNITED',
                'nome_fantasia' => 'CAT UNITED',
                'documento' => '54.789.987/0001-01',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(21) 9 9905-0044',
                'email' => 'catransportesunited0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Global Supplies Ltda',
                'nome_fantasia' => 'Global Supplies',
                'documento' => '27.123.456/0001-02',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(21) 9 9999-9999',
                'email' => 'globalsupplies0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Transporte Rápido SA',
                'nome_fantasia' => 'TransRápido',
                'documento' => '33.987.654/0001-03',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(11) 9 8888-8888',
                'email' => 'transrapido0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Distribuidora Nacional',
                'nome_fantasia' => 'DistNacional',
                'documento' => '21.654.321/0001-04',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(31) 9 7777-7777',
                'email' => 'distnacional0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Serviços Logísticos Ltda',
                'nome_fantasia' => 'ServLog',
                'documento' => '12.345.678/0001-05',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(41) 9 6666-6666',
                'email' => 'servlog0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Entrega Rápida ME',
                'nome_fantasia' => 'Entrega Rápida',
                'documento' => '43.210.987/0001-06',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(51) 9 5555-5555',
                'email' => 'entregarapida0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Cargas & Transportes',
                'nome_fantasia' => 'CargTrans',
                'documento' => '65.432.109/0001-07',
                'pessoa' => 'J',
                'ativo' => false,
                'celular' => '(61) 9 4444-4444',
                'email' => 'cargtrans0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Frota Completa Ltda',
                'nome_fantasia' => 'FrotaCom',
                'documento' => '76.543.210/0001-08',
                'pessoa' => 'J',
                'ativo' => false,
                'celular' => '(71) 9 3333-3333',
                'email' => 'frotacom0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Distribuição Total SA',
                'nome_fantasia' => 'DistTotal',
                'documento' => '87.654.321/0001-09',
                'pessoa' => 'J',
                'ativo' => false,
                'celular' => '(81) 9 2222-2222',
                'email' => 'disttotal0@gmail.com',
                'endereco_id' => 1
            ],
            [
                'empresa_master_cod' => 1,
                'nome' => 'Transporte Expresso ME',
                'nome_fantasia' => 'TranspExpresso',
                'documento' => '98.765.432/0001-10',
                'pessoa' => 'J',
                'ativo' => true,
                'celular' => '(91) 9 1111-1111',
                'email' => 'transpexpresso0@gmail.com',
                'endereco_id' => 1
            ],
        ];
    }
}
