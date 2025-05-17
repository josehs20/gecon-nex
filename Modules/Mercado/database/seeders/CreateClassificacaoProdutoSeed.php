<?php

namespace Modules\Mercado\Database\Seeders;

use App\Models\Empresa;
use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateClassificacaoProdutoSeed extends Seeder
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
        DB::connection($connection)->table('classificacao_produto')->truncate();
        $empresas = Empresa::get();

        foreach ($empresas as $key => $value) {
            DB::connection($connection)->table('classificacao_produto')->insert(self::getClassificaoProduto($value->id));
        }
        $this->enableForeignKeys($connection);
    }

    private function getClassificaoProduto($empresa_id)
    {
        return [
            [
                'descricao' => 'MATERIAL DE LIMPEZA',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'MATERIAL DE PINTURA',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ACOS PARA CONSTRUCAO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'HIDROSSANITARIO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ADITIVOS, DESMOLDANTES E ADESIVOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'AGREGADOS E AGLOMERANTES',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'IMPERMEABILIZACAO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ESQUADRIAS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PAISAGISMO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'LOUCAS E METAIS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ELEMENTOS DE FIXAÇÃO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'SISTEMAS ELETRICOS, AUTOMACAO, CABEAMENTO E TELEFONICOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PISOS E REVESTIMENTOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PEDRAS, MARMORES E GRANITOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ACO ESTRUTURAL, METALON , TUBOS E PERFIS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ARTEFATOS DE CONCRETO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'VIDROS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'COBERTURA',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'MADEIRAS E COMPENSADOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'CONCRETO USINADO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'DIVISORIAS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'FORRO E PAREDES EM DRYWALL',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PROTECAO TERMICA, ACUSTICA, VEDACAO E JUNTAS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'COMBUSTIVEIS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'INSTALACOES DE GAS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PLACAS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'SERVICOS CONTRATADOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'EPI, EPC E UNIFORME',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ALVENARIAS E FECHAMENTOS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'INCENDIO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'MAQUINAS, FERRAMENTAS E EQUIPAMENTOS PEQUENO PORTE',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'FERTILIZANTES',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'ALIMENTACAO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'TRANSPORTE',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'EXAMES',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'SEGURO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'AR CONDICIONADO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PAVIMENTACAO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'FERRAMENTA',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'EPI, MEDIDO EM GRUPO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'PAPELARIA',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'MOBILIARIO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'GASES MEDICINAIS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'AR CONDIONADO',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'CONSUMÍVEIS',
                'empresa_master_cod' => $empresa_id,
            ],
            [
                'descricao' => 'LIMPEZA',
                'empresa_master_cod' => $empresa_id,
            ],
        ];
    }
}
