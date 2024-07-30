<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use cl_rubricasesocial;
use db_utils;
use DBPessoal;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\EventoBase;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\TipoPontoConstants;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\ValoresPontoEvento;

/**
 * Classe responsavel por montar as informacoes do evento S1210 Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Marcelo Hernane
 */
class EventoS1210 extends EventoBase
{
    use ValoresPontoEvento;

    /**
     *
     * @param \stdClass $dados
     */
    public function __construct($dados)
    {
        parent::__construct($dados);
    }

    /**
     * Retorna dados no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    public function montarDados()
    {
        $ano = date("Y", db_getsession("DB_datausu"));
        $mes = date("m", db_getsession("DB_datausu"));
        $dia = date("d", db_getsession("DB_datausu"));
        $data = "$ano-$mes-01";
        $data = new \DateTime($data);
        $data->modify('last day of this month');
        $ultimoDiaDoMes = $data->format('d');

        $aDadosAPI = array();
        $iSequencial = 1;

        foreach ($this->dados as $oDados) {

            if ($this->tpevento == 1) {

                $aDadosPorMatriculas = $this->buscarDadosPorMatricula($oDados->z01_cgccpf, $this->tppgto);
                if (empty($aDadosPorMatriculas)) {
                    continue;
                }
                $oDadosAPI                                = new \stdClass();
                $oDadosAPI->evtPgtos                      = new \stdClass();
                $oDadosAPI->evtPgtos->sequencial          = $iSequencial;
                $oDadosAPI->evtPgtos->modo                = $this->modo;
                $oDadosAPI->evtPgtos->indRetif            = 1;
                $oDadosAPI->evtPgtos->nrRecibo            = null;

                $oDadosAPI->evtPgtos->indapuracao         = $this->indapuracao;
                $oDadosAPI->evtPgtos->perapur             = $ano . '-' . $mes;
                if ($this->indapuracao == 2) {
                    $oDadosAPI->evtPgtos->perapur         = $ano;
                }
                $oDadosAPI->evtPgtos->cpfbenef             = $aDadosPorMatriculas[0]->cpftrab;

                $std = new \stdClass();
                $seqinfopag = 0;
                for ($iCont = 0; $iCont < count($aDadosPorMatriculas); $iCont++) {
                    $aIdentificador = $this->buscarIdentificador($aDadosPorMatriculas[$iCont]->matricula, $aDadosPorMatriculas[$iCont]->rh30_regime);
                    for ($iCont2 = 0; $iCont2 < count($aIdentificador); $iCont2++) {
                        $std->infopgto[$seqinfopag]->codcateg = $oDados->codcateg;

                        $std->infopgto[$seqinfopag] = new \stdClass();

                        $std->infopgto[$seqinfopag]->dtpgto = "$ano-$mes-$dia";
                        $std->infopgto[$seqinfopag]->tppgto = $this->tppgto;
                        $std->infopgto[$seqinfopag]->perref = "$ano-$mes";

                        if ($aIdentificador[$iCont2]->idedmdev == 1) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfsal';
                        }
                        if ($aIdentificador[$iCont2]->idedmdev == 2) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfres';
                        }
                        if ($aIdentificador[$iCont2]->idedmdev == 3) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfcom';
                        }
                        if ($aIdentificador[$iCont2]->idedmdev == 4) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfs13';
                        }

                        //$std->infopgto[$seqinfopag]->idedmdev = '1';

                        $std->infopgto[$seqinfopag]->vrliq = $this->buscarValorLiquido($aDadosPorMatriculas[$iCont]->matricula, $aDadosPorMatriculas[$iCont]->rh30_regime, $aIdentificador[$iCont2]->idedmdev);
                        // echo $std->infopgto[$seqinfopag]->vrliq;
                        // exit;
                        //$std->infopgto[$seqinfopag]->vrliq = (float) number_format($std->infopgto[$seqinfopag]->vrliq, 2, ',', '.');

                        $seqinfopag++;
                    }
                }
                $oDadosAPI->evtPgtos->infopgto = $std->infopgto;
                $aDadosAPI[] = $oDadosAPI;
                $iSequencial++;
            } else {
                $aDadosContabilidade = $this->buscarDadosContabilidade($oDados->z01_cgccpf, $ultimoDiaDoMes, $mes, $ano);

                $oDadosAPI                                = new \stdClass();
                $oDadosAPI->evtPgtos                      = new \stdClass();
                $oDadosAPI->evtPgtos->sequencial          = $iSequencial;
                $oDadosAPI->evtPgtos->modo                = $this->modo;
                $oDadosAPI->evtPgtos->indRetif            = 1;
                $oDadosAPI->evtPgtos->nrRecibo            = null;

                $oDadosAPI->evtPgtos->indapuracao         = $this->indapuracao;
                $oDadosAPI->evtPgtos->perapur             = $ano . '-' . $mes;
                // if ($this->indapuracao == 2) {
                //     $oDadosAPI->evtPgtos->perapur         = $ano;
                // }
                $oDadosAPI->evtPgtos->cpfbenef             = $aDadosContabilidade[0]->cpf_benef;

                $std = $this->dmDevContabilidade($aDadosContabilidade);

                $oDadosAPI->evtPgtos->infopgto = $std->infopgto;
                $aDadosAPI[] = $oDadosAPI;
                $iSequencial++;
            }
        }
        // echo '<pre>';
        // var_dump($aDadosAPI);
        // exit;
        return $aDadosAPI;
    }

    /**
     * Retorna dados por matricula no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    private function buscarDadosPorMatricula($cpf, $tppgto)
    {
        $ano = db_getsession("DB_anousu");
        $mes = date("m", db_getsession("DB_datausu"));

        $sql = "SELECT
        distinct
        1 as tpInsc,
        cgc as nrInsc,
        z01_cgccpf as cpfTrab,
        rh51_indicadesconto as indMV,
        case when length(rh51_cgcvinculo) = 14 then 1
        when length(rh51_cgcvinculo) = 11 then 2
        end as tpInsc2,
        rh51_cgcvinculo as nrInsc2,
        rh51_basefo as vlrRemunOE,
        'LOTA1' as codLotacao,
        case when rh02_ocorre = '2' then 2
        when rh02_ocorre = '3' then 3
        when rh02_ocorre = '4' then 4
        else '1'
        end as grauExp,
        rh30_regime,
        rh51_cgcvinculo,
        rh01_regist as matricula,
        rh01_esocial as matricula_esocial,
        h13_categoria as codCateg
        from
            rhpessoal
        left join rhpessoalmov on
            rh02_anousu = {$ano}
            and rh02_mesusu = {$mes}
            and rh02_regist = rh01_regist
            and rh02_instit = " . db_getsession("DB_instit") . "
       left join rhinssoutros on
	rh51_seqpes = rh02_seqpes
    inner join cgm on
        cgm.z01_numcgm = rhpessoal.rh01_numcgm
    inner join db_config on
	db_config.codigo = rhpessoal.rh01_instit
    left join rhpesrescisao on
	rh02_seqpes = rh05_seqpes
    left join rhregime on
        rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
    inner join tpcontra on
        tpcontra.h13_codigo = rhpessoalmov.rh02_tpcont
    left join rescisao on
            rescisao.r59_anousu = rhpessoalmov.rh02_anousu
            and rescisao.r59_mesusu = rhpessoalmov.rh02_mesusu
            and rescisao.r59_regime = rhregime.rh30_regime
            and rescisao.r59_causa = rhpesrescisao.rh05_causa
            and rescisao.r59_caub = rhpesrescisao.rh05_caub::char(2)
        left  outer join (
                SELECT distinct r33_codtab,r33_nome,r33_tiporegime
                                    from inssirf
                                    where     r33_anousu = {$ano}
                                            and r33_mesusu = {$mes}
                                        and r33_instit = " . db_getsession("DB_instit") . "
                                ) as x on r33_codtab = rhpessoalmov.rh02_tbprev+2
        where 1=1
        and ((rh05_recis is not null
            and date_part('month', rh05_recis) = {$mes}
            and date_part('year', rh05_recis) = {$ano}
            )
            or
            rh05_recis is null
        ) ";
        //1200
        if ($tppgto == 1) {
            $sql .= " and (
                (h13_categoria = '901' and rh30_vinculo = 'A')
                or
                (h13_categoria in ('101', '106', '111', '301', '302', '303', '305', '306', '309', '312', '313','410', '902','701','712','771','711')
                and rh30_vinculo = 'A'
                and r33_tiporegime = '1')
            )
            ";
        }
        //2299
        if ($tppgto == 2) {
            $sql .= " and rh30_regime = '2'
            and rescisao.r59_mesusu = {$mes}
            and rescisao.r59_anousu = {$ano} ";
        }
        //2399
        if ($tppgto == 3) {
            $sql .= "";
        }
        //1202
        if ($tppgto == 4) {
            $sql .= " and h13_categoria in ('301', '302', '303', '305', '306', '309', '410')
            and rh30_vinculo = 'A'
            and r33_tiporegime = '2' ";
        }
        //1207
        if ($tppgto == 5) {
            $sql .= " and rh30_vinculo in ('I','P') ";
        }

        $sql .= " and cgm.z01_cgccpf = '{$cpf}' ";

        $rsDados = db_query($sql);
        // echo $sql;
        // db_criatabela($rsDados);
        // exit;
        if (pg_num_rows($rsDados) > 0) {
            for ($iCont = 0; $iCont < pg_num_rows($rsDados); $iCont++) {
                $oResult = \db_utils::fieldsMemory($rsDados, $iCont);
                $aItens[] = $oResult;
            }
        }
        return $aItens;
    }

    /**
     * Retorna o valor liquido no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    private function buscarValorLiquido($matricula, $rh30_regime, $ponto)
    {
        require_once 'libs/db_libpessoal.php';
        $rsValores = $this->getValoresPorPonto($ponto, $matricula);
        $proventos  = 0;
        $descontos  = 0;
        for ($iCont = 0; $iCont < pg_num_rows($rsValores); $iCont++) {
            $oResult = \db_utils::fieldsMemory($rsValores, $iCont);
            $proventos  += ($oResult->provdesc == 'Provento') ? $oResult->provento : 0;
            $descontos  += ($oResult->provdesc == 'Desconto') ? $oResult->desconto : 0;
        }
        return round($proventos - $descontos, 2);
    }

    /**
     * Busca o identificador de acordo com os pontos existentes para o periodo
     * no formado requerido pela API sped-esocial
     * @param int $matricula
     * @param int $rh30_regime
     * @return array stdClass
     */
    private function buscarIdentificador($matricula, $rh30_regime)
    {
        $iAnoUsu = date("Y", db_getsession("DB_datausu"));
        $iMesusu = date("m", db_getsession("DB_datausu"));
        if ($rh30_regime == 1 || $rh30_regime == 3) {
            $aPontos = array(TipoPontoConstants::PONTO_13SALARIO);
            if ($this->indapuracao != 2)
                $aPontos = array(TipoPontoConstants::PONTO_SALARIO, TipoPontoConstants::PONTO_COMPLEMENTAR, TipoPontoConstants::PONTO_RESCISAO);
        } else {
            $aPontos = array(TipoPontoConstants::PONTO_13SALARIO);
            if ($this->indapuracao != 2)
                $aPontos = array(TipoPontoConstants::PONTO_SALARIO, TipoPontoConstants::PONTO_COMPLEMENTAR);
        }

        foreach ($aPontos as $opcao) {
            $tipoPonto = $this->getTipoPonto($opcao);
            $sql = "  select distinct
                        case
                        when '{$tipoPonto->arquivo}' = 'gerfsal' then 1
                        when '{$tipoPonto->arquivo}' = 'gerfcom' then 3
                        when '{$tipoPonto->arquivo}' = 'gerfs13' then 4
                        when '{$tipoPonto->arquivo}' = 'gerfres' then 2
                        end as ideDmDev
                        from {$tipoPonto->arquivo}
                        where " . $tipoPonto->sigla . "anousu = '" . $iAnoUsu . "'
                        and  " . $tipoPonto->sigla . "mesusu = '" . $iMesusu . "'
                        and  " . $tipoPonto->sigla . "instit = " . db_getsession("DB_instit") . "
                        and {$tipoPonto->sigla}regist = $matricula";

            $rsIdentificadores = db_query($sql);
            if (pg_num_rows($rsIdentificadores) > 0) {
                for ($iCont = 0; $iCont < pg_num_rows($rsIdentificadores); $iCont++) {
                    $oIdentificadores = \db_utils::fieldsMemory($rsIdentificadores, $iCont);

                    $aItens[] = $oIdentificadores;
                }
            }
        }
        return $aItens;
    }

    public function buscarDadosContabilidade($cpf, $ultimoDiaDoMes, $mes, $ano)
    {
        $ano = date("Y", db_getsession("DB_datausu"));
        $mes = date("m", db_getsession("DB_datausu"));
        $sql = "SELECT *
            FROM (
                    select e60_numcgm as num_cgm,
                        z01_cgccpf as cpf_benef,
                        e50_codord as ide_dm_dev,
                        substr(e50_data::varchar, 1, 7) as per_ref,
                        (e53_valor - e53_vlranu) as valor_op,
                        corrente.k12_data as dt_pgto,
                        sum(
                            case
                                when corgrupotipo.k106_sequencial = 4 then corrente.k12_valor * -1
                                else corrente.k12_valor
                            end
                        ) as vr_liq
                    from pagordem
                        inner join empempenho ON empempenho.e60_numemp = pagordem.e50_numemp
                        inner join cgm ON cgm.z01_numcgm = empempenho.e60_numcgm
                        inner join empord on empord.e82_codord = pagordem.e50_codord
                        inner join empagemov on empagemov.e81_codmov = empord.e82_codmov
                        inner join corempagemov on corempagemov.k12_codmov = empagemov.e81_codmov
                        inner join corrente on (
                            corrente.k12_id,
                            corrente.k12_data,
                            corrente.k12_autent
                        ) = (
                            corempagemov.k12_id,
                            corempagemov.k12_data,
                            corempagemov.k12_autent
                        )
                        inner join corgrupocorrente on (
                            corrente.k12_id,
                            corrente.k12_data,
                            corrente.k12_autent
                        ) = (
                            corgrupocorrente.k105_id,
                            corgrupocorrente.k105_data,
                            corgrupocorrente.k105_autent
                        )
                        inner join corgrupo ON corgrupo.k104_sequencial = corgrupocorrente.k105_corgrupo
                        inner join corgrupotipo on corgrupotipo.k106_sequencial = corgrupocorrente.k105_corgrupotipo
                        inner join pagordemele on e50_codord = e53_codord
                    where e50_cattrabalhador is not null
                        and date_part('month',corrente.k12_data) = $mes
                        and date_part('year',corrente.k12_data) = $ano
                        and corgrupotipo.k106_sequencial in (1, 4)
                        and length(z01_cgccpf) = 11
                    group by 1,
                        2,
                        3,
                        4,
                        5,
                        6
                    order by e50_codord, corrente.k12_data
                ) AS pagamentos
            WHERE vr_liq > 0
            ";
        if ($cpf != null) {
            $sql .= " and cpf_benef in ('$cpf') ";
        }
        $rs = \db_query($sql);
        // echo $sql;
        // db_criatabela($rs);
        // exit;
        if (!$rs) {
            throw new \Exception("Erro ao buscar os preenchimentos do S1210");
        }
        /**
         * @todo busca os empregadores da institui??o e adicona para cada rubriuca
         */
        return \db_utils::getCollectionByRecord($rs);
    }

    private function dmDevContabilidade($aDadosPorCpf)
    {
        $std = new \stdClass();

        for ($iCont = 0; $iCont < count($aDadosPorCpf); $iCont++) {

            $std->infopgto[$iCont] = new \stdClass();

            $std->infopgto[$iCont]->dtpgto = $aDadosPorCpf[$iCont]->dt_pgto;
            $std->infopgto[$iCont]->tppgto = $this->tppgto;
            $std->infopgto[$iCont]->perref = $aDadosPorCpf[$iCont]->per_ref;

            $std->infopgto[$iCont]->idedmdev = $aDadosPorCpf[$iCont]->ide_dm_dev;

            $std->infopgto[$iCont]->vrliq = $aDadosPorCpf[$iCont]->vr_liq;
        }
        return $std;
    }
}
