<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDbParagrafoHomologacaoAdjudicacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Primeiro UPDATE: Substitui 'PREGÃO PRESENCIAL' por '#$l44_descricao#'
        DB::unprepared("
            UPDATE db_paragrafo
            SET db02_texto = REPLACE(db02_texto, 'PREGÃO PRESENCIAL', '#\$l44_descricao#')
            WHERE db02_idparag IN (
                SELECT db04_idparag
                FROM db_docparag
                INNER JOIN db_documento ON db03_docum = db04_docum
                INNER JOIN db_paragrafo ON db04_idparag = db02_idparag
                WHERE db03_descr IN ('HOMOLOGACAO RELATORIO', 'ADJUDICACAO RELATORIO')
            )
        ");

        // Segundo UPDATE: Acrescenta 'CPF no #$z01_cpf#,' após '#$z01_nome#'
        DB::unprepared("
            UPDATE db_paragrafo AS p
            SET db02_texto = 
                CONCAT(
                    substring(p.db02_texto FROM 1 FOR position('#\$z01_nome#' IN p.db02_texto) + length('#\$z01_nome#') - 1),
                    ' CPF no #\$z01_cpf#,',
                    substring(p.db02_texto FROM position('#\$z01_nome#' IN p.db02_texto) + length('#\$z01_nome#'))
                )
            FROM db_documento AS d
            INNER JOIN db_docparag AS dp ON d.db03_docum = dp.db04_docum
            INNER JOIN db_paragrafo AS p2 ON dp.db04_idparag = p2.db02_idparag
            WHERE d.db03_descr = 'ADJUDICACAO RELATORIO'
            AND p2.db02_texto LIKE '%#\$z01_nome#%'
            AND p.db02_idparag = p2.db02_idparag
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse o primeiro UPDATE: Substitui '#$l44_descricao#' por 'PREGÃO PRESENCIAL'
        DB::unprepared("
            UPDATE db_paragrafo
            SET db02_texto = REPLACE(db02_texto, '#\$l44_descricao#', 'PREGÃO PRESENCIAL')
            WHERE db02_idparag IN (
                SELECT db04_idparag
                FROM db_docparag
                INNER JOIN db_documento ON db03_docum = db04_docum
                INNER JOIN db_paragrafo ON db04_idparag = db02_idparag
                WHERE db03_descr IN ('HOMOLOGACAO RELATORIO', 'ADJUDICACAO RELATORIO')
            )
        ");

        // Reverse o segundo UPDATE: Remove 'CPF no #$z01_cpf#,' após '#$z01_nome#'
        DB::unprepared("
            UPDATE db_paragrafo AS p
            SET db02_texto = 
                CONCAT(
                    substring(p.db02_texto FROM 1 FOR position(' CPF no #\$z01_cpf#,' IN p.db02_texto) - 1),
                    substring(p.db02_texto FROM position(' CPF no #\$z01_cpf#,' IN p.db02_texto) + length(' CPF no #\$z01_cpf#,' ))
                )
            FROM db_documento AS d
            INNER JOIN db_docparag AS dp ON d.db03_docum = dp.db04_docum
            INNER JOIN db_paragrafo AS p2 ON dp.db04_idparag = p2.db02_idparag
            WHERE d.db03_descr = 'ADJUDICACAO RELATORIO'
            AND p2.db02_texto LIKE '% CPF no #\$z01_cpf#,%'
            AND p.db02_idparag = p2.db02_idparag
        ");
    }
}
