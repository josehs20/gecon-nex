<?php

namespace App\System;

use Illuminate\Http\Request;
use Illuminate\Support\Fluent;

class Post
{
    public static function anti_injection($sql, $scape = true)
    {
        // remove palavras que são sintaxe SQL
        $pattern = "/( from |select|insert|delete|update|1=1|where|drop table|show tables|#|\*|--|\\\\)/i";
        $sql = preg_replace($pattern, '', $sql);
        $sql = trim($sql); // remove espaço em branco no início e no fim
        if ($scape == true) {
            $sql = addslashes($sql); // adiciona barras antes de caracteres que precisam ser escapados
        }

        return $sql != '' || $sql != null ? $sql : null;
    }

    //faz o anti injection de um array
    public static function anti_injection_array($requestAll)
    {
        $parans = [];
        foreach ($requestAll as $key => $value) {
            //verifica se a variavel é um array para fazer a checagem em mais um nível do array
            if (is_array($value)) {
                $parans[$key] = self::anti_injection_array($value);
            } else {
                $parans[$key] = Post::anti_injection($value);
            }
        }

        return $parans;
    }

    public static function anti_injection_yajra($requestAll, callable $callback = null): Fluent
    {
        $parans = self::anti_injection_array($requestAll);

        // Filtra o array de colunas, removendo a coluna de 'acao'
        $parans['columns'] = array_filter($parans['columns'], function ($column) {
            return $column['data'] !== 'acao'; // Remove a coluna onde 'data' é 'acao'
        });

        // Reindexa o array para garantir que as chaves sejam contínuas
        $parans['columns'] = array_values($parans['columns']);

        // Pega somente o valor e coluna do Yajra DataTables
        $data = array_map(function ($column) use ($callback) {
            $data = [
                'value' => $column['search']['value'],
                'coluna' => $column['data'],
            ];

            // Se um callback for fornecido, executa ele passando os dados
            return $callback ? $callback($data) : $data;
        }, $parans['columns']);

        // Verifica se existe algum valor de pesquisa global (search.value)
        if (isset($parans['search']) && $parans['search']['value'] != null && $parans['search']['value'] != '') {
            $data['search'] = $parans['search']['value'];
        }
        
        return new Fluent($data);
    }


    /**
     * Função Utilizada para remover apenas funções SQL da string
     */
    public static function anti_injection_html($sql)
    {
        $sql = preg_replace('/( from |select|insert|delete|update|1=1|where|drop table|show tables|#|\*|--|\\\\)/i', '', $sql);
        $sql = trim($sql);

        return $sql;
    }

    public static function anti_injection_vetor_for($vetor)
    {

        for (
            $i = 0;
            $i < count($vetor);
            $i++
        ) {
            $vetor[$i] = Post::anti_injection($vetor[$i]);
        }

        return $vetor;
    }

    public static function anti_injection_data($value)
    {
        if ($value) {
            $d = \DateTime::createFromFormat('Y-m-d', $value);
            if ($d && $d->format('Y-m-d') == $value) {
                return $value;
            } else {
                return date('Y-m-d');
            }
        }
    }

    public static function anti_injection_hora($value)
    {
        if ($value) {
            $d = \DateTime::createFromFormat('H:i', $value);
            if ($d && $d->format('H:i') == $value) {
                return $value;
            } else {
                return date('H:i');
            }
        }
    }

    public static function anti_injection_double($value)
    {
        if ($value) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            $value = floatval($value);

            if (is_float($value)) {
                return $value;
            } else {
                if (is_int($value)) {
                    return $value;
                } else {
                    return 0;
                }
            }
        }
    }

    public static function size($post, $origem)
    {

        //  debug( config( 'parameters.tamanho_maximo_post' ) );

        if ($post > config('parameters.tamanho_maximo_post')) {
            $mensagem = 'Erro de sistema: O Limite de tamanho de POST foi excedido.';
            $notf = new Alertas;
            salvaAlerta($notf->load($mensagem, 'vermelho'));

            return redirect()->route($origem);
        } elseif ($post == 0) {
            $mensagem = 'Erro de sistema: Requisição Inválida';
            $notf = new Alertas;
            salvaAlerta($notf->load($mensagem, 'vermelho'));

            return redirect()->route('atendimento.home');
        }
    }

    public static function get_ip()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public static function so_numero($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public static function valida_cpf($cpf)
    {

        $cpf = Post::so_numero($cpf);
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c]
                    * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if (
                $cpf[$c]
                != $d
            ) {
                return false;
            }
        }

        return true;
    }

    public static function valida_pis($pis)
    {

        if ($pis) {

            $total = 0;
            $pesos = '3298765432';
            $pesos = str_split($pesos);

            $numerosPis = str_split(Post::so_numero($pis));

            if (count($numerosPis) == 11) {
                for ($i = 0; $i < 10; $i++) {

                    $total = $total + ($pesos[$i] * $numerosPis[$i]);
                }

                $resto = $total % 11;

                $resultado = 11 - $resto;

                if ($resultado == 11 || $resultado == 10) {

                    if ($numerosPis[10] == 0) {
                        return true;
                    }
                } elseif ($resultado == $numerosPis[10]) {

                    return true;
                }
            }
        }
    }

    public static function valida_cnpj($cnpj)
    {
        $cnpj = Post::so_numero($cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function reporInputsValueSession(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            session()->flash($key, $value);
        }
    }
}
