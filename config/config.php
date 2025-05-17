<?php
return [
   'caixa' => [
        'recursos' => [
            'venda' => [
                'id' => 1,
                'descricao' => 'Realiza venda no caixa.'
            ],
            'devolucao' => [
                'id' => 2,
                'descricao' => 'Realiza devolução de mercadorias.'
            ],
            'orcamento' => [
                'id' => 3,
                'descricao' => 'Gera orçamentos para o cliente.'
            ],
            'recebimento' => [
                'id' => 4,
                'descricao' => 'Recebe valores de contas ou crediário.'
            ],
            'sangria' => [
                'id' => 5,
                'descricao' => 'Retira valores do caixa (sangria).'
            ],
            'suprimentos' => [
                'id' => 6,
                'descricao' => 'Insere valores no caixa (suprimento).'
            ],
            'fechamento' => [
                'id' => 7,
                'descricao' => 'Realiza o fechamento do caixa.'
            ],
        ],
    ],

    'modulos' => [
        'gecon' => 1,
        'mercado' => 2,
        'farmacia' => 3,
        'vestimentos' => 4,
    ],

    'tipo_usuarios' => [
        'admin' => [
            'id' => 1,
            'descricao' => 'Administrador'
        ], // Admin Gecon Master

        'cliente_master' => [
            'id' => 2,
            'descricao' => 'Dono da empresa'
        ], // Admin da empresa

        'gerente' => [
            'id' => 3,
            'descricao' => 'Gerente geral'
        ],

        'caixa' => [
            'id' => 4,
            'descricao' => 'Caixa'
        ],

        'estoquista' => [
            'id' => 5,
            'descricao' => 'Estoquista'
        ],

        'gerente_estoque' => [
            'id' => 6,
            'descricao' => 'Gerente de estoque'
        ],

        'atendentes' => [
            'id' => 7,
            'descricao' => 'Atendente'
        ],
    ],

    'impostos' => [
        'ICMS' => 1,
        'IPI' => 2,
        'PIS' => 3,
        'COFINS' => 4,
    ],

    'tipo_arquivo' => [
        'nota_fiscal' => 1,
        'foto_perfil' => 2,
        'foto_caixa' => 3,
        'danfe' => 4
    ],

    'tipo_movimentacao_estoque' => [
        'entrada' => 1,
        'saida' => 2,
        'venda' => 3,
        'devolucao' => 4,
        'balanco' => 5,
        'movimentacao' => 6,
        'recebimento' => 7,
    ],

    'cliente_padrao' => [
        'documento' => 00000000000
    ],

    'status' => [
        'ativo' => 1,
        'inativo' => 2,
        'aberto' => 3,
        'ocupado' => 4,
        'fechado' => 5,
        'em_dia' => 6,
        'em_atraso' => 7,
        'quitado' => 8,
        'bloqueado' => 9,
        'livre' => 10,
        'salvo' => 11,
        'cancelado' => 12,
        'concluido' => 13,
        'parcelada' => 14,
        'devolucao' => 15,
        'pago' => 16,
        'pendente' => 17,
        'devolucao_parcial' => 18,
        'recebimento_iniciado' => 19,
        'aguardando_cotacao' => 20,
        'em_cotacao' => 21,
        'cotado' => 22,
        'comprado' => 23,
    ],

    'especie_pagamento' => [
        'dinheiro' => [
            'id' => 1,
            'nome' => 'Dinheiro',
            'afeta_troco' => true,
            'credito_loja' => false,
            'contem_parcela' => false
        ],
        'pix' => [
            'id' => 2,
            'nome' => 'Pix',
            'afeta_troco' => false,
            'credito_loja' => false,
            'contem_parcela' => false
        ],
        'cartao_debito' => [
            'id' => 3,
            'nome' => 'Cartão de débito',
            'afeta_troco' => false,
            'credito_loja' => false,
            'contem_parcela' => false
        ],
        'credito_loja' => [
            'id' => 4,
            'nome' => 'Crédito em loja',
            'afeta_troco' => false,
            'credito_loja' => true,
            'contem_parcela' => false
        ],
        'cartao_credito' => [
            'id' => 5,
            'nome' => 'Cartão de crédito',
            'afeta_troco' => false,
            'credito_loja' => false,
            'contem_parcela' => true
        ],
        'boleto' => [
            'id' => 6,
            'nome' => 'Boleto',
            'afeta_troco' => false,
            'credito_loja' => false,
            'contem_parcela' => false
        ],
        'transferencia' => [
            'id' => 7,
            'nome' => 'Tranferência',
            'afeta_troco' => false,
            'credito_loja' => false,
            'contem_parcela' => false
        ],
    ],

    'processos' => [

        'empresas' =>   [
            'nome' => 'Empresas',
            'empresa' => [
                'id' => 1,
                'nome' => 'Lista de empresas',
                'descricao' => null,
                'rota' => 'admin.empresa.index',
                'posicao_menu' => 1
            ],
            'gtin' => [
                'id' => 22,
                'nome' => 'Lista de gtins',
                'descricao' => null,
                'rota' => 'admin.gtin.index',
                'posicao_menu' => 1
            ]
        ],

        'dashboard' => [
            'id' => 26,
            'nome' => 'Dashboard',
            'descricao' => 'Dashboard',
            'rota' => 'dashboard.index',
            'posicao_menu' => 1
        ],

        'gerenciamento' => [
            'nome' => 'Gerenciamento',
            'produto' => [
                'id' => 2,
                'nome' => 'Produtos',
                'descricao' => 'Cadastro/Produtos',
                'rota' => 'cadastro.produto.index',
                'posicao_menu' => 1000,
            ],
            'estoque' => [
                'id' => 3,
                'nome' => 'Estoque',
                'descricao' => 'Cadastro/Estoque',
                'rota' => 'cadastro.estoque.index',
                'posicao_menu' => 2000,
            ],
            'balanco' => [
                'id' => 4,
                'nome' => 'Balanço',
                'descricao' => 'Estoque/Balanço',
                'rota' => 'estoque.balanco.index',
                'posicao_menu' => 2001,
            ],
            'movimentacao' => [
                'id' => 5,
                'nome' => 'Movimentações',
                'descricao' => 'Estoque/Movimentações',
                'rota' => 'estoque.movimentacao.index',
                'posicao_menu' => 2002,
            ],
            'unidade_medida' => [
                'id' => 6,
                'nome' => 'Unidade de medida',
                'descricao' => 'Cadastro/Unidade de medida',
                'rota' => 'cadastro.unidade_medida.index',
                'posicao_menu' => 1001,
            ],
            'classificacao_produto' => [
                'id' => 7,
                'nome' => 'Classificação de produto',
                'descricao' => 'Cadastro/Classificação de produto',
                'rota' => 'cadastro.classificacao_produto.index',
                'posicao_menu' => 1002,
            ],
            'fornecedor' => [
                'id' => 8,
                'nome' => 'Fornecedores',
                'descricao' => 'Cadastro/Fornecedores',
                'rota' => 'cadastro.fornecedor.index',
                'posicao_menu' => 1003,
            ],
            'caixas' => [
                'id' => 9,
                'nome' => 'Caixas',
                'descricao' => 'Cadastro/Caixas',
                'rota' => 'cadastro.caixa.index',
                'posicao_menu' => 1004,
            ],
            'cliente' => [
                'id' => 10,
                'nome' => 'Clientes',
                'descricao' => 'Cadastro/Clientes',
                'rota' => 'cadastro.cliente.index',
                'posicao_menu' => 1005,
            ],
            'forma_pagemento' => [
                'id' => 11,
                'nome' => 'Formas de pagamento',
                'descricao' => 'Cadastro/Formas de pagamento',
                'rota' => 'cadastro.forma_pagemento.index',
                'posicao_menu' => 1006,
            ],
            'usuarios' => [
                'id' => 12,
                'nome' => 'Usuários',
                'descricao' => 'Cadastro/Usuários',
                'rota' => 'cadastro.gecon.usuarios.index',
                'posicao_menu' => 3000,
            ],
            'permissao_usuario' => [
                'id' => 13,
                'nome' => 'Permissões de usuários',
                'descricao' => 'Cadastro/Permissões de usuários',
                'rota' => 'cadastro.gecon.usuarios.permissao.index',
                'posicao_menu' => 3001,
            ],
            'recebimento' => [
                'id' => 14,
                'nome' => 'Recebimento',
                'descricao' => 'Cadastro/Recebimento',
                'rota' => 'cadastro.recebimento.index',
                'posicao_menu' => 2003,
            ],
            'pedidos' => [
                'id' => 15,
                'nome' => 'Pedidos',
                'descricao' => 'Pedido/Pedidos',
                'rota' => 'cadastro.pedido.index',
                'posicao_menu' => 4000,
            ],
            'cotacao' => [
                'id' => 24,
                'nome' => 'Cotação',
                'descricao' => 'Pedido/Cotação',
                'rota' => 'cadastro.cotacao.index',
                'posicao_menu' => 4000,
            ],
            'recebimento_pedido' => [
                'id' => 16,
                'nome' => 'Recebimento de pedido',
                'descricao' => 'Cadastro/Recebimento de pedido',
                'rota' => 'cadastro.recebimento.index',
                'posicao_menu' => 4001,
            ],
            'fabricantes' => [
                'id' => 23,
                'nome' => 'Fabricantes',
                'descricao' => 'Cadastro/Fabricantes',
                'rota' => 'cadastro.fabricante.index',
                'posicao_menu' => 4002,
            ],
            'compras' => [
                'id' => 25,
                'nome' => 'Compras',
                'descricao' => 'Pedido/Compras',
                'rota' => 'cadastro.compra.index',
                'posicao_menu' => 4002,
            ]
        ],

        'pdv' => [
            'nome' => 'Ponto de venda',
            'caixa' => [
                'id' => 17,
                'nome' => 'Caixa',
                'descricao' => 'PDV/Caixa',
                'rota' => 'caixa.autenticacao',
                'posicao_menu' => 5000
            ],
            'fechamento_caixa' => [
                'id' => 18,
                'nome' => 'Fechamento de caixa',
                'descricao' => 'PDV/Fechamento de caixa',
                'rota' => 'caixa.fechamento.index',
                'posicao_menu' => 6000
            ],
        ],

        'nfe' => [
            'nome' => 'Nota Fiscal',
            'empresa' => [
                'id' => 19,
                'nome' => 'Empresa',
                'descricao' => 'NFE/Empresa',
                'rota' => 'nfe.empresa.index',
                'posicao_menu' => 7000
            ],
            'certificado' => [
                'id' => 20,
                'nome' => 'Certificado digital',
                'descricao' => 'NFE/Certificado digital',
                'rota' => 'nfe.certificado.index',
                'posicao_menu' => 8000
            ],
            'inscricao_estadual' => [
                'id' => 21,
                'nome' => 'Inscrição estadual',
                'descricao' => 'NFE/Inscrição estadual',
                'rota' => 'nfe.inscricao_estadual.index',
                'posicao_menu' => 9000
            ]
        ]
    ],

    'acoes' => [
        'cadastrou_produto' => [
            'id' => 1,
            'descricao' => 'Criou produto'
        ],
        'atualizou_produto' => [
            'id' => 2,
            'descricao' => 'Atualizou produto'
        ],
        'cadastrou_unidade_medida' => [
            'id' => 3,
            'descricao' => 'Cadastrou unidade de medida'
        ],
        'abriu_caixa' => [
            'id' => 4,
            'descricao' => 'Abriu o caixa'
        ],
        'criou_caixa' => [
            'id' => 5,
            'descricao' => 'Cadastrou o caixa'
        ],
        'atualizou_caixa' => [
            'id' => 6,
            'descricao' => 'Atualizou o caixa'
        ],
        'movimentou_estoque' => [
            'id' => 7,
            'descricao' => 'Movimentou estoque'
        ],
        'atualizou_status_caixa' => [
            'id' => 8,
            'descricao' => 'Atualizou status do caixa'
        ],
        'finalizou_venda' => [
            'id' => 9,
            'descricao' => 'Finalizou venda no caixa'
        ],
        'cancelou_venda_salva' => [
            'id' => 10,
            'descricao' => 'Cancelou uma venda salva',
        ],
        'excluir_movimentacao_estoque_item' => [
            'id' => 11,
            'descricao' => 'Excluiu item da movimentação de estoque'
        ],
        'adicionou_item_movimentacao_estoque' => [
            'id' => 12,
            'descricao' => 'Adicionou item para movimentação de estoque'
        ],
        'finalizou_movimentacao' => [
            'id' => 13,
            'descricao' => 'Finalizou movimentação de estoque'
        ],
        'criou_movimentacao_estoque' => [
            'id' => 14,
            'descricao' => 'Criou movimentação de estoque'
        ],
        'alterar_status_movimentacao_estoque' => [
            'id' => 15,
            'descricao' => 'Alteração de status movimentação de estoque'
        ],
        'transferiu_dispositivo' => [
            'id' => 16,
            'descricao' => 'Transferiu caixa de dispositivo'
        ],
        'realizou_logout_caixa_aberto' => [
            'id' => 17,
            'descricao' => 'Realizou o logout com caixa aberto'
        ],
        'devolucao' => [
            'id' => 18,
            'descricao' => 'Realizou a devolucao'
        ],
        'salvou_venda_caixa' => [
            'id' => 19,
            'descricao' => 'Salvou uma venda'
        ],
        'criou_forma_pagamento' => [
            'id' => 20,
            'descricao' => 'Criou uma forma de pagamento'
        ],
        'atualizou_forma_pagamento' => [
            'id' => 21,
            'descricao' => 'Atualizou uma forma de pagamento'
        ],
        'sangria' => [
            'id' => 22,
            'descricao' => 'Realizou uma sangria no caixa'
        ],
        'fechou_caixa' => [
            'id' => 23,
            'descricao' => 'Realizou o fechamento do caixa'
        ],
        'cadastrou_classificacao_produto' => [
            'id' => 24,
            'descricao' => 'Realizou o cadastro de uma classificação de produto'
        ],
        'alterou_classificacao_produto' => [
            'id' => 25,
            'descricao' => 'Realizou a alteração de uma classificação de produto'
        ],
        'cadastrou_usuario' => [
            'id' => 26,
            'descricao' => 'Realizou o cadastro de usuário'
        ],
        'cadastrou_cliente' => [
            'id' => 27,
            'descricao' => 'Realizou o cadastro de um cliente'
        ],
        'alterou_cliente' => [
            'id' => 28,
            'descricao' => 'Alterou o cadastro de um cliente'
        ],
        'cadastrou_fornecedor' => [
            'id' => 29,
            'descricao' => 'Realizou o cadastro de um fornecedor'
        ],
        'alterou_fornecedor' => [
            'id' => 30,
            'descricao' => 'Alterou o cadastro de um fornecedor'
        ],
        'alterou_unidade_medida' => [
            'id' => 31,
            'descricao' => 'Alterou unidade de medida'
        ],
        'alterou_informacao_estoque' => [
            'id' => 32,
            'descricao' => 'Alterou informações do estoque como quantidade mínima, máxima e localização'
        ],
        'criou_balanco' => [
            'id' => 33,
            'descricao' => 'Criou balanço'
        ],
        'criou_balanco_item' => [
            'id' => 34,
            'descricao' => 'Adicionou item ao balanço'
        ],
        'deletou_balanco_item' => [
            'id' => 35,
            'descricao' => 'Excluiu item do balanço'
        ],
        'finalizou_balanco' => [
            'id' => 36,
            'descricao' => 'Finalizou o balanço'
        ],
        'cadastrou_usuario' => [
            'id' => 37,
            'descricao' => 'Cadastrou um usuário'
        ],
        'atualizou_usuario' => [
            'id' => 38,
            'descricao' => 'Atualizou um usuário'
        ],
        'recebeu_venda_caixa' => [
            'id' => 39,
            'descricao' => 'Realizou o recebimento de venda pendente no caixa'
        ],
        'adicionou_permissao' => [
            'id' => 40,
            'descricao' => 'Adicionou permissão para um usuário'
        ],
        'recebeu_material' => [
            'id' => 41,
            'descricao' => 'Realizou o recebimento de materiais'
        ],
        'iniciou_recebimento' => [
            'id' => 42,
            'descricao' => 'Iníciou o recebimento de mercadorias.'
        ],
        'realizou_recebimento' => [
            'id' => 43,
            'descricao' => 'Realizou recebimento'
        ],
        'realizou_pedido' => [
            'id' => 44,
            'descricao' => 'Realizou um pedido de itens'
        ],
        'cadastrou_loja' => [
            'id' => 45,
            'descricao' => 'Realizou cadastro de uma loja'
        ],
        'atualizou_loja' => [
            'id' => 46,
            'descricao' => 'atualizou uma loja'
        ],
        'atualizou_ncm' => [
            'id' => 47,
            'descricao' => 'atualizou o ncm do produto.'
        ],
        'atualiza_balanco_item' => [
            'id' => 48,
            'descricao' => 'Atualizou item em balanço para sincronizar com a quantidade real do estoque.'
        ],
        'removeu_movimentacao_item' => [
            'id' => 49,
            'descricao' => 'Deletou um item da movimentação.'
        ],
        'finalizou_movimentacao' => [
            'id' => 50,
            'descricao' => 'Finalizou a operação de movimentação.'
        ],
        'realizou_contacao' => [
            'id' => 51,
            'descricao' => 'Finalizou uma cotação.',
        ],
        'atualizou_fabricante' => [
            'id' => 52,
            'descricao' => 'atualizou um fabricante.'
        ],
        'cadastrou_fabricante' => [
            'id' => 53,
            'descricao' => 'cadastrou um fabricante.'
        ],
        'cancelou_balanco' => [
            'id' => 54,
            'descricao' => 'Cancelou balanco.'
        ],
        'atualizou_movimentacao_estoque' => [
            'id' => 55,
            'descricao' => 'Salvou movimentação de estoque.'
        ],
        'cancelou_pedido' => [
            'id' => 56,
            'descricao' => 'Cancelou pedido.'
        ],
        'atualizou_pedido' => [
            'id' => 57,
            'descricao' => 'Atualizou pedido.'
        ],
        'iniciou_cotacao' => [
            'id' => 58,
            'descricao' => 'Iniciou a cotação.'
        ],
        'cancelou_cotacao' => [
            'id' => 59,
            'descricao' => 'Cancelou a cotação.'
        ],
        'atualizou_cotacao' => [
            'id' => 60,
            'descricao' => 'Atualizou a cotação.'
        ],
        'criou_compra' => [
            'id' => 61,
            'descricao' => 'Criou compra.'
        ],
        'cancelou_compra' => [
            'id' => 62,
            'descricao' => 'Cancelou compra.'
        ],
        'atualizou_recursos_caixa' => [
            'id' => 63,
            'descricao' => 'Atualizou recursos do caixa.'
        ],
        'atualizou_permissao_caixa' => [
            'id' => 64,
            'descricao' => 'Atualizou permissão do caixa.'
        ],
        'excluiu_permissao_caixa' => [
            'id' => 65,
            'descricao' => 'Excluiu permissão do caixa.'
        ]
    ],

    'tipo_contrato' => [
        'clt' => 'CLT',
        'pj' => 'PJ',
        'estagiario' => 'Estagiário',
        'jovem_aprendiz' => 'Jovem aprendiz',
        'temporario' => 'Temporário'
    ]
];
