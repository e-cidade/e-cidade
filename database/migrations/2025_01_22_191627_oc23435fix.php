<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class Oc23435fix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
  
            SELECT setval('db_layouttxt_db50_codigo_seq', (SELECT MAX(db50_codigo) + 1 FROM db_layouttxt));
            SELECT setval('db_layoutlinha_db51_codigo_seq', (SELECT MAX(db51_codigo) + 1 FROM configuracoes.db_layoutlinha));
            SELECT setval('db_layoutcampos_db52_codigo_seq', (SELECT MAX(db52_codigo) + 1 FROM configuracoes.db_layoutcampos));

            INSERT INTO configuracoes.db_layouttxt
            VALUES (NEXTVAL('db_layouttxt_db50_codigo_seq'),
                    'SICOM IP MTFIS 2025',
                    0,
                    'Detalhamento das Metas Fiscais - 2025',
                    3);

            INSERT INTO configuracoes.db_layoutlinha
            VALUES (NEXTVAL('db_layoutlinha_db51_codigo_seq'),
                    (SELECT db50_codigo FROM configuracoes.db_layouttxt WHERE db50_descr = 'SICOM IP MTFIS 2025'),
                    'DETALHAMENTO DAS METAS FISCAIS - IP 2025',
                    3,
                    354,
                    0,
                    0,
                    '',
                    ';',
                    FALSE);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (SELECT db51_codigo FROM configuracoes.db_layoutlinha WHERE db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),
                    'exercicio',
                    'EXERCICIO DA META FISCAL',
                    14,
                    1,
                    '',
                    4,
                    FALSE,
                    TRUE,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (SELECT db51_codigo FROM configuracoes.db_layoutlinha WHERE db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),
                    'vlMetasFiscaisRPPS',
                    'Metas Fiscais RPPS',
                    14,
                    5,
                    '',
                    2,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (SELECT db51_codigo FROM configuracoes.db_layoutlinha WHERE db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),
                    'vlCorrenteReceitaTotal',
                    'VALOR CORRENTE DA RECEITA TOTAL',
                    14,
                    7,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteRecImpTaxContrMelh',
                    'Valor Corrente das Receitas I',
                    14,
                    21,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteRecTransfCorr',
                    'Valor Corrente das Receitas de T',
                    14,
                    49,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteDemaisRecPrimCorr',
                    'Valor Corrente das Demais R',
                    14,
                    63,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteRecPrimCap',
                    'Valor Corrente das Receitas P C',
                    14,
                    77,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteDespesaTotal',
                    'Valor Corrente da D T',
                    14,
                    91,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteDespPessEncSoc',
                    'Valor Corrente das Despesas P E S',
                    14,
                    105,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteOutrasDespCorr',
                    'Valor Corrente das Outras D C',
                    14,
                    119,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteDespPrimCap',
                    'Valor Corrente das  Despesas P C',
                    14,
                    133,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrentePagRPDespPrim',
                    'Valor Corrente dos Pagamento',
                    14,
                    147,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteDividaPublicaConsolidada',
                    'Valor Corrente da D P C',
                    14,
                    179,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlCorrenteDividaConsolidadaLiquida',
                    'Valor Corrente da D C L',
                    14,
                    193,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteReceitaTotal',
                    'Valor Constante da Receita Total',
                    14,
                    207,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteRecImpTaxContrMelh',
                    'Valor Constante das R I',
                    14,
                    221,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteRecTransfCorr',
                    'Valor Constante das R T',
                    14,
                    249,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteDemaisRecPrimCorr',
                    'Valor Constante D R',
                    14,
                    263,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteRecPrimCap',
                    'Valor Constante das R P',
                    14,
                    277,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteDespesaTotal',
                    'Valor Corrente da Despesa Total',
                    14,
                    291,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteDespPessEncSoc',
                    'Valor Constante das Despesas',
                    14,
                    305,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteOutrasDespCorr',
                    'Valor Constante das O D C',
                    14,
                    319,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteDespPrimCap',
                    'Valor Constante das  Despesas P C',
                    14,
                    333,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstantePagRPDespPrim',
                    'Valor Constante dos P Restos',
                    14,
                    347,
                    '',
                    14,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteDividaPublicaConsolidada',
                    'Valor Constante D P',
                    14,
                    389,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);

            INSERT INTO configuracoes.db_layoutcampos
            VALUES (NEXTVAL('db_layoutcampos_db52_codigo_seq'),
                    (
                    SELECT
                        db51_codigo
                    FROM
                        configuracoes.db_layoutlinha
                    WHERE
                        db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025'),

                                    'vlConstanteDividaConsolidadaLiquida',
                    'Valor Constante da D L',
                    14,
                    403,
                    '',
                    7,
                    false,
                    true,
                    'd',
                    '',
                    0);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("
            DELETE FROM configuracoes.db_layoutcampos
            WHERE db51_codigo = (SELECT db51_codigo FROM configuracoes.db_layoutlinha WHERE db51_descr = 'DETALHAMENTO DAS METAS FISCAIS - IP 2025');

            DELETE FROM configuracoes.db_layoutlinha
            WHERE db50_codigo = (SELECT db50_codigo FROM configuracoes.db_layouttxt WHERE db50_descr = 'SICOM IP MTFIS 2025');

            DELETE FROM configuracoes.db_layouttxt
            WHERE db50_descr = 'SICOM IP MTFIS 2025';

        ");
    }
}
