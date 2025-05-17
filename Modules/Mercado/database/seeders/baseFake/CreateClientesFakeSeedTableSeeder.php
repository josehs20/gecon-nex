<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Modules\Mercado\Entities\Endereco;

class CreateClientesFakeSeedTableSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $connection = 'mercado';
        $this->disableForeignKeys($connection);
        DB::connection($connection)->table('clientes')->truncate();

        foreach (self::getClientes() as $key => $clientes) {
            DB::connection($connection)->table('clientes')->insert($clientes);
        }

        $this->enableForeignKeys($connection);
    }

    private static function getClientes()
    {
        $faker = Factory::create('pt_BR');
        $qtdBlocos = 10; // Quantidade de blocos
        $registrosPorBloco = 500; // Quantidade de registros por bloco
        $clientes = [];
        $endereco = Endereco::first();
        // Cria o cliente padrão
        $clientePadrao = [
            'empresa_master_cod' => 1,
            'nome' => 'PADRÃO',
            'documento' => '00000000000', // CPF fictício ou válido para o cliente padrão
            'pessoa' => 'Física',
            'ativo' => true,
            'status_id' => config('config.status.em_dia'),
            'celular' => '00000000000', // Telefone fictício para o cliente padrão
            'telefone_fixo' => '0000000000', // Telefone fixo fictício para o cliente padrão
            'email' => 'padrao@example.com', // E-mail fictício para o cliente padrão
            'data_nascimento' => '2000-01-01', // Data de nascimento fictícia
            'observacao' => 'Cliente padrão para uso geral',
            'endereco_id' => null,
        ];

        // Adiciona o cliente padrão ao início do array de clientes
        array_unshift($clientes, $clientePadrao);
        for ($b = 1; $b < $qtdBlocos; $b++) {
            // Resetar o array de clientes para cada bloco

            for ($i = 0; $i < $registrosPorBloco; $i++) {
                $clientes[$b][] = [
                    'empresa_master_cod' => 1,
                    'nome' => $faker->name,
                    'documento' => $faker->cpf(false),
                    'pessoa' => 'Física',
                    'ativo' => true,
                    'status_id' => config('config.status.em_dia'),
                    'celular' => $faker->cellphoneNumber,
                    'telefone_fixo' => $faker->phoneNumber,
                    'email' => $faker->unique()->safeEmail,
                    'data_nascimento' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
                    'observacao' => $faker->optional($weight = 0.3)->sentence,
                    'endereco_id' => $endereco->id,
                ];
            }
        }
        return $clientes;
    }
}
