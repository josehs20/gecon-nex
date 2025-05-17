<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Application\UsuarioApplication;
use App\Application\UsuarioGeconApplication;
use App\Models\Empresa;
use App\Models\User;
use App\UseCases\Usuario\Requests\UsuarioRequest;
use Illuminate\Database\Seeder;
use Modules\Mercado\Entities\Endereco;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CreateUsuariosDesenvolvimentoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarioAdmin = self::insertUsuarioAdmins();
        self::insertUsuarios($usuarioAdmin);
    }

    private static function insertUsuarioAdmins()
    {
        return User::where('tipo_usuario_id', config('config.tipo_usuarios.admin.id'))->first();
    }

    private static function insertUsuarios($usuarioAdmin)
    {
        $empresas = Empresa::where('ativo', true)->get();
        $endereco = Endereco::first();
        $processo_id = config('config.processos.empresas.empresa.id');
        $acao_id = config('config.acoes.cadastrou_usuario.id');
        $usuario_id = $usuarioAdmin->id;

        $comentario = 'Criado pelo sistema.';
        foreach ($empresas as $key => $empresa) {
            foreach ($empresa->mercadoLojas as $key => $l) {
                for ($i = 0; $i < count($empresa->mercadoLojas); $i++) {
                    $modulo_id = config('config.modulos.mercado');
                    $nome = 'usuario' . $l->id . '_' . $i;
                    $login = 'login.'.$nome;
                    $status_id = config('config.status.ativo');
                    $historicoRequest = new CriarHistoricoRequest($processo_id, $acao_id, $usuario_id, $comentario);
                    $user = UsuarioApplication::criar(
                        new UsuarioRequest(
                            $nome,
                            $login,
                            $nome . '@gecon.com.br',
                            'secret',
                            $modulo_id,
                            true,
                            config('config.tipo_usuarios.cliente_master.id'),
                            $empresa->id,
                            $l->id,
                            $endereco->id,
                            $status_id,
                            null,
                            self::gerarCpfAleatorio(),
                            null,
                            null,
                            true,
                            null,
                            0,
                            null,
                            null,
                            0,
                            $historicoRequest
                        )
                    );
                }
            }
        }
    }

    private static function gerarCpfAleatorio(): string
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        $d1 = $n9*2 + $n8*3 + $n7*4 + $n6*5 + $n5*6 + $n4*7 + $n3*8 + $n2*9 + $n1*10;
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        $d2 = $d1*2 + $n9*3 + $n8*4 + $n7*5 + $n6*6 + $n5*7 + $n4*8 + $n3*9 + $n2*10 + $n1*11;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        return "$n1$n2$n3$n4$n5$n6$n7$n8$n9$d1$d2";
    }

    private static function getNomesAleatorios()
    {
        return [
            "joao.silva",
            "maria.santos",
            "carlos.pereira",
            "ana.costa",
            "pedro.oliveira",
            "mariana.almeida",
            "lucas.rodrigues",
            "lara.souza",
            "gabriel.ferreira",
            "renata.gomes",
            "ricardo.martins",
            "claudia.melo",
            "paulo.barbosa",
            "isabel.machado",
            "jorge.ferraz",
            "tania.azevedo",
            "daniel.moraes",
            "aline.ramos",
            "anderson.nascimento",
            "carla.farias",
            "fernando.monteiro",
            "debora.borges",
            "rodrigo.silveira",
            "juliana.carvalho",
            "bruno.romano",
            "priscila.lima",
            "thiago.ribeiro",
            "leticia.campos",
            "filipe.amaral",
            "patricia.coelho",
            "rafael.lopes",
            "raquel.teixeira",
            "leonardo.freitas",
            "sabrina.barros",
            "gustavo.castro",
            "elisa.araujo",
            "felipe.guimaraes",
            "viviane.ramos",
            "samuel.alves",
            "luana.moreira",
            "nicolas.dias",
            "amanda.oliveira",
            "marcio.souza",
            "bianca.gomes",
            "ricardo.silva",
            "adriana.santos",
            "diego.mendes",
            "carolina.lima",
            "edson.souza",
            "marta.azevedo",
            "marcelo.moraes",
            "regina.barros",
            "alessandro.costa",
            "ana.simoes",
            "jorge.rodrigues",
            "renata.almeida",
            "rogerio.nogueira",
            "fabiana.santos",
            "danilo.ramos",
            "carolina.santos",
            "augusto.martins",
            "monica.souza",
            "cesar.farias",
            "lara.monteiro",
            "julio.azevedo",
            "diana.alves",
            "alex.machado",
            "luciana.castro",
            "ruben.teixeira",
            "valeria.pereira",
            "antonio.guimaraes",
            "clarissa.campos",
            "ricardo.barbosa",
            "elaine.martins",
            "sergio.araujo",
            "luiza.ribeiro",
            "mateus.lopes",
            "sonia.costa",
            "ariel.mendes",
            "vanessa.nascimento",
            "heitor.carvalho",
            "ester.rodrigues",
            "wilson.silveira",
            "tatiana.lima",
            "ronaldo.campos",
            "alice.freitas",
            "henrique.amaral",
            "bruna.borges",
            "vitor.coelho",
            "aline.teixeira",
            "fabio.guimaraes",
            "fernanda.ribeiro",
            "rachel.pereira",
            "caio.santos",
            "karla.silva",
            "lucas.barros",
            "paula.gomes",
            "renan.lopes",
            "leticia.silva",
            "jose.dias",
            "claudio.azevedo",
            "simone.monteiro",
            "jorge.machado",
            "helena.mendes",
            "samuel.romano",
            "joana.amaral"
        ];
    }
}
