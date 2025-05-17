<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Loja;

class CreateFormaPagamentoFakeSeed extends Seeder
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
        DB::connection($connection)->table('forma_pagamentos')->truncate();

        DB::connection($connection)->table('forma_pagamentos')->insert(self::getFormaPagamento());
        $this->enableForeignKeys($connection);
    }

    public static function getFormaPagamento()
    {
        $formas = [];
        $especies = config('config.especie_pagamento');
        $ativo = true;

        $lojas = Loja::get();
        foreach ($lojas as $key => $l) {
            foreach ($especies as $keyEspecie => $e) {
                if ($keyEspecie == 'dinheiro' || $keyEspecie == 'pix') {
                    $formas[] = [
                        'descricao' => $e['nome'] ,
                        'ativo' => $ativo,
                        'loja_id' => $l->id,
                        'especie_pagamento_id' => $e['id'],
                    ];
                }elseif ($keyEspecie == 'cartao_debito') {
                    $formas[] = [
                        'descricao' => $e['nome'] ,
                        'ativo' => $ativo,
                        'loja_id' => $l->id,
                        'especie_pagamento_id' => $e['id'],
                    ];
                }elseif ($keyEspecie == 'credito_loja') {
                    $formas[] = [
                        'descricao' => $e['nome'] ,
                        'ativo' => $ativo,
                        'loja_id' => $l->id,
                        'especie_pagamento_id' => $e['id'],
                    ];
                }else{
                    $formas[] = [
                        'descricao' => $e['nome'],
                        'ativo' => $ativo,
                        'loja_id' => $l->id,
                        'especie_pagamento_id' => $e['id'],
                    ];
                }

            }

        }

        return $formas;
    }
}
