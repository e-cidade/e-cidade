<?php
namespace App\Repositories\Contabilidade;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Contabilidade\PrevisaoOperacaoCredito;

/**
 * Previsao de Operacao de Credito Repository
 * @author widouglas
 */
class PrevisaoOperacaoCreditoRepository 
{
    private PrevisaoOperacaoCredito $model;
    
    public function __construct()
    {
        $this->model = new PrevisaoOperacaoCredito();
    }
    /**
     * Funcão responsavel por verificar se existe previsao de operacao de credito
     *
     * @param int $iReceita
     * @param int $iOperacaoCredito
     * @param int $iAnoUsu
     * @return bool
     */
    public function existePrevisao($iFonte, $iOperacaoCredito, $iAnoUsu): bool 
    {
        return DB::table('public.prevoperacaocredito')
            ->where('c242_fonte', $iFonte)
            ->where('c242_operacaocredito', $iOperacaoCredito)
            ->where('c242_anousu', $iAnoUsu)
            ->exists();
    }

    /**
     * Incluir previsao de operação de crédito
     *
     * @param string $iFonte
     * @param int $iOperacaoCredito
     * @param int $iAnoUsu
     * @param double $fValorPrevisto
     */
    public function insert($iFonte, $iOperacaoCredito, $iAnoUsu, $fValorPrevisto)
    {
        return DB::insert("insert into prevoperacaocredito (c242_fonte, c242_operacaocredito, c242_anousu, c242_vlprevisto) values (?, ?, ?, ?)", 
            [$iFonte, $iOperacaoCredito, $iAnoUsu, $fValorPrevisto]);
    }

    /**
     * Pega as operacões de crédito vinculadas a previsão
     *
     * @param int $iCodRec
     * @param int $iInstit
     * @param int $iAnoUsu
     * @return Array
     */
    public function pegarOperacoesDeCredito(int $iCodRec, int $iInstit, int $iAnoUsu): Array
    {
        $sql = " SELECT
                    o70_codrec,
                    op01_sequencial,
                    op01_numerocontratoopc,
                    op01_objetocontrato,
                    op01_dataassinaturacop,
                    sum(c242_vlprevisto) AS c242_vlprevisto,
                    sum(valor_arrecadado) as valor_arrecadado
            FROM 
                (SELECT 
                    o70_codrec,
                    op01_sequencial,
                    op01_numerocontratoopc,
                    op01_objetocontrato,
                    op01_dataassinaturacop,
                    sum(c242_vlprevisto) AS c242_vlprevisto,
                    0 AS valor_arrecadado
                FROM prevoperacaocredito
                    LEFT JOIN orcreceita ON c242_anousu = o70_anousu AND c242_fonte = o70_codrec 
                    LEFT JOIN db_operacaodecredito ON op01_sequencial = c242_operacaocredito
                    WHERE o70_codigo IN (" . implode(', ', OperacaoCreditoRepository::FONTES) . ") 
                    AND o70_anousu = {$iAnoUsu}
                    AND o70_instit = {$iInstit}
                    AND o70_valor > 0
                    AND o70_codrec = {$iCodRec}                    
                GROUP BY 1, 2, 3, 4, 5
            UNION
                SELECT *
                FROM
                    (SELECT 
                        o70_codrec,
                        op01_sequencial,
                        op01_numerocontratoopc,
                        op01_objetocontrato,
                        op01_dataassinaturacop,
                        0 AS c242_vlprevisto,
                        round(sum(CASE WHEN c71_coddoc = 100 THEN c70_valor ELSE c70_valor * -1 END),2) AS valor_arrecadado
                    FROM orcreceita 
                        LEFT JOIN conlancamrec ON c74_anousu = o70_anousu AND c74_codrec = o70_codrec
                        INNER JOIN conlancam ON c70_codlan = c74_codlan
                        LEFT JOIN conlancamdoc ON c74_codlan = c71_codlan
                        LEFT JOIN conlancamcorrente ON c86_conlancam = c74_codlan
                        LEFT JOIN corplacaixa ON (k82_id,k82_data,k82_autent) = (c86_id,c86_data,c86_autent)
                        LEFT JOIN placaixarec ON k81_seqpla = k82_seqpla
                        LEFT join conplanoreduz  on  conplanoreduz.c61_reduz = k81_conta and c61_anousu=o70_anousu
                        LEFT join conplano on  conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu=c60_anousu
                        LEFT JOIN conplanocontabancaria ON c56_codcon = c60_codcon AND c56_anousu = c60_anousu
                        LEFT JOIN contabancaria on contabancaria.db83_sequencial = c56_contabancaria
                        LEFT JOIN db_operacaodecredito ON op01_sequencial = db83_codigoopcredito::int 
                        LEFT JOIN prevoperacaocredito ON c242_anousu = o70_anousu
                            AND c242_fonte = o70_codrec
                            AND c242_operacaocredito = op01_sequencial
                        WHERE o70_codigo IN (" . implode(', ', OperacaoCreditoRepository::FONTES) . ") 
                        AND o70_anousu = {$iAnoUsu}
                        AND o70_instit = {$iInstit}
                        AND o70_valor > 0
                        AND o70_codrec = {$iCodRec}
                        AND op01_sequencial IS NOT NULL
                    GROUP BY 1, 2, 3, 4, 5, 6) AS x
                WHERE valor_arrecadado > 0) AS xx GROUP BY 1, 2, 3, 4, 5";
        return DB::select($sql);
    }

    /**
     * Deleta Operação de Crédito
     *
     * @param int $iOperacaoCredito
     * @param int $iFonte
     * @param int $iAnoUsu
     * @return void
     */
    public function delete($iOperacaoCredito, $iFonte, $iAnoUsu)
    {
        return DB::table('public.prevoperacaocredito')
            ->where('c242_operacaocredito', $iOperacaoCredito)
            ->where('c242_fonte', $iFonte)
            ->where('c242_anousu', $iAnoUsu)
            ->delete();
    }

    /**
     * Update
     *
     * @param int $iFonte
     * @param int $iOperacaoCredito
     * @param int $iAnoUsu
     * @param float $fValorPrevisto
     * @return void
     */
    public function update($iFonte, $iOperacaoCredito, $iAnoUsu, $fValorPrevisto)
    {
        return DB::table('public.prevoperacaocredito')
            ->where('c242_operacaocredito', $iOperacaoCredito)
            ->where('c242_fonte', $iFonte)
            ->where('c242_anousu', $iAnoUsu)
            ->update(['c242_vlprevisto' => $fValorPrevisto]);
    }

    /**
     * Pega o total previsto
     *
     * @param int $iFonte
     * @param int $iAnoUsu
     */
    public function totalPrevisto($iFonte, $iAnoUsu)
    {
        return DB::table('public.prevoperacaocredito')
            ->where('c242_fonte', $iFonte)
            ->where('c242_anousu', $iAnoUsu)
            ->sum('c242_vlprevisto');
    }
}