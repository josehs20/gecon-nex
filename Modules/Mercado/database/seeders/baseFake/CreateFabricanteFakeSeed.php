<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateFabricanteFakeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('fabricantes')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('fabricantes')->insert(self::getFabricantes());

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getFabricantes()
    {
        return [
            [
                'nome' => 'Nestlé',
                'descricao' => 'Empresa suíça de alimentos e bebidas',
                'cnpj' => '12345678000195',  // Exemplo de CNPJ
                'razao_social' => 'Nestlé Brasil Ltda',
                'inscricao_estadual' => '1234567890',
                'endereco_id' => null,
                'celular' => '11987654321',
                'telefone' => '1132334455',
                'email' => 'contato@nestle.com.br',
                'site' => 'https://www.nestle.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Bauducco',
                'descricao' => 'Empresa brasileira de alimentos, conhecida principalmente por seus panetones',
                'cnpj' => '98765432000107',
                'razao_social' => 'Bauducco Alimentos S.A.',
                'inscricao_estadual' => '9876543210',
                'endereco_id' => 1,
                'celular' => '11987654322',
                'telefone' => '1132334456',
                'email' => 'contato@bauducco.com.br',
                'site' => 'https://www.bauducco.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Bunge',
                'descricao' => 'Empresa brasileira de alimentos e agronegócio',
                'cnpj' => '11223344000152',
                'razao_social' => 'Bunge Brasil S.A.',
                'inscricao_estadual' => '1122334455',
                'endereco_id' => 1,
                'celular' => '11987654323',
                'telefone' => '1132334457',
                'email' => 'contato@bunge.com.br',
                'site' => 'https://www.bunge.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'M. Dias Branco',
                'descricao' => 'Empresa brasileira de alimentos, especialmente de massas e biscoitos',
                'cnpj' => '33445566000121',
                'razao_social' => 'M. Dias Branco S.A.',
                'inscricao_estadual' => '3344556677',
                'endereco_id' => 1,
                'celular' => '11987654324',
                'telefone' => '1132334458',
                'email' => 'contato@mdiasbranco.com.br',
                'site' => 'https://www.mdiasbranco.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Marfrig',
                'descricao' => 'Empresa brasileira de alimentos, especialmente de carnes',
                'cnpj' => '55667788000199',
                'razao_social' => 'Marfrig Global Foods S.A.',
                'inscricao_estadual' => '5566778899',
                'endereco_id' => 1,
                'celular' => '11987654325',
                'telefone' => '1132334459',
                'email' => 'contato@marfrig.com.br',
                'site' => 'https://www.marfrig.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Sadia',
                'descricao' => 'Empresa brasileira de alimentos, especialmente de produtos de carne',
                'cnpj' => '66778899000188',
                'razao_social' => 'Sadia S.A.',
                'inscricao_estadual' => '6677889910',
                'endereco_id' => 1,
                'celular' => '11987654326',
                'telefone' => '1132334460',
                'email' => 'contato@sadia.com.br',
                'site' => 'https://www.sadia.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Perdigão',
                'descricao' => 'Empresa brasileira de alimentos, especialmente de produtos de carne',
                'cnpj' => '77889900123456',
                'razao_social' => 'Perdigão S.A.',
                'inscricao_estadual' => '7788990022',
                'endereco_id' => 1,
                'celular' => '11987654327',
                'telefone' => '1132334461',
                'email' => 'contato@perdigao.com.br',
                'site' => 'https://www.perdigao.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Grupo Aurora',
                'descricao' => 'Cooperativa brasileira de alimentos, especialmente de produtos de carne',
                'cnpj' => '88990011223344',
                'razao_social' => 'Cooperativa Aurora Alimentos',
                'inscricao_estadual' => '8899002233',
                'endereco_id' => 1,
                'celular' => '11987654328',
                'telefone' => '1132334462',
                'email' => 'contato@auroraalimentos.com.br',
                'site' => 'https://www.auroraalimentos.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'Cargill',
                'descricao' => 'Empresa norte-americana de alimentos e agronegócio, com forte presença no Brasil',
                'cnpj' => '99887766554433',
                'razao_social' => 'Cargill Agrícola S.A.',
                'inscricao_estadual' => '9988776677',
                'endereco_id' => 1,
                'celular' => '11987654329',
                'telefone' => '1132334463',
                'email' => 'contato@cargill.com.br',
                'site' => 'https://www.cargill.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
            [
                'nome' => 'JBS',
                'descricao' => 'Empresa brasileira de alimentos, especialmente de carnes',
                'cnpj' => '66778899000101',
                'razao_social' => 'JBS S.A.',
                'inscricao_estadual' => '6677889911',
                'endereco_id' => 1,
                'celular' => '11987654330',
                'telefone' => '1132334464',
                'email' => 'contato@jbs.com.br',
                'site' => 'https://www.jbs.com.br',
                'ativo' => true,
                'empresa_master_cod' => 1
            ],
        ];
    }
    
}
