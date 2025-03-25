<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use cl_rubricasesocial;
use cl_rhdepend;
use cl_pesdiver;
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
        $anoPgto = $this->anoPgto();
        $mesPgto = $this->mesPgto();
        $diaPgto = $this->diaPgto();

        $ano = $this->ano();
        $mes = $this->mes();

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
                $oDadosAPI->evtPgtos->perapur             = $anoPgto . '-' . $mesPgto;
                if ($this->indapuracao == 2) {
                    $oDadosAPI->evtPgtos->perapur         = $anoPgto;
                }
                $oDadosAPI->evtPgtos->cpfbenef             = $aDadosPorMatriculas[0]->cpftrab;

                $std = new \stdClass();
                $seqinfopag = 0;
                for ($iCont = 0; $iCont < count($aDadosPorMatriculas); $iCont++) {
                    $aIdentificador = $this->buscarIdentificador($aDadosPorMatriculas[$iCont]->matricula, $aDadosPorMatriculas[$iCont]->rh30_regime);
                    for ($iCont2 = 0; $iCont2 < count($aIdentificador); $iCont2++) {
                        $std->infopgto[$seqinfopag]->codcateg = $oDados->codcateg;

                        $std->infopgto[$seqinfopag] = new \stdClass();

                        $std->infopgto[$seqinfopag]->dtpgto = "$anoPgto-$mesPgto-$diaPgto";
                        $std->infopgto[$seqinfopag]->tppgto = $this->tppgto;
                        $std->infopgto[$seqinfopag]->perref = "$ano-$mes";

                        if ($aIdentificador[$iCont2]->idedmdev == 1) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfsal' . $mes;
                        }
                        if ($aIdentificador[$iCont2]->idedmdev == 2) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfres' . $mes;
                        }
                        if ($aIdentificador[$iCont2]->idedmdev == 3) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfcom' . $mes;
                        }
                        if ($aIdentificador[$iCont2]->idedmdev == 4) {
                            $std->infopgto[$seqinfopag]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfs13' . $mes;
                        }

                        $std->infopgto[$seqinfopag]->vrliq = $this->buscarValorLiquido($aDadosPorMatriculas[$iCont]->matricula, $aDadosPorMatriculas[$iCont]->rh30_regime, $aIdentificador[$iCont2]->idedmdev);

                        $infoDepDeducao = $this->calcularDeducao($aDadosPorMatriculas[$iCont]->matricula);
                        $tpCR = $this->definirTpCR($oDados->codcateg, $oDados->rh30_vinculo);
                        $tpRend = $this->definirTpRend($aIdentificador[$iCont2]->idedmdev);
                        $rsPensaoAlim = $this->getPensaoAlim($aDadosPorMatriculas[$iCont]->matricula);
                        // if (count($infoDepDeducao) > 0 || $rsPensaoAlim) {
                        //     $std->infoircomplem[$iCont]->infoircr[0] = new \stdClass(); //opcional array com até 99 elementos Informações de Imposto de Renda, por Código de Receita
                        //     $std->infoircomplem[$iCont]->infoircr[0]->tpcr = $tpCR; //obrigatório codigo da receita
                        // }

                        // if (count($infoDepDeducao) > 0) {

                        //     foreach ($infoDepDeducao as $key => $value) {

                        //         $std->infoircomplem[$iCont]->infoircr[0]->deddepen[$key] = new \stdClass(); //opcional array com até 999 elementos Dedução do rendimento tributável relativa a dependentes
                        //         $std->infoircomplem[$iCont]->infoircr[0]->deddepen[$key]->tprend = $tpRend; //obrigatório Tipo de rendimento.
                        //         //11 - Remuneração mensal
                        //         //12 - 13º salário
                        //         //13 - Férias
                        //         $std->infoircomplem[$iCont]->infoircr[0]->deddepen[$key]->cpfdep = $value['cpfDependente']; //obrigatório Número de inscrição no CPF.
                        //         $std->infoircomplem[$iCont]->infoircr[0]->deddepen[$key]->vlrdeddep = $value['valorDeducao']; //obrigatório valor da dedução da base de cálculo
                        //     }
                        // }


                        if ($rsPensaoAlim) {
                            $std->infoircomplem[$iCont]->infoircr[0]->tpcr = $tpCR; //obrigatório codigo da receita
                            
                            $dependentes = $this->buscarInfoDep($aDadosPorMatriculas[$iCont]->matricula);
                            for ($i = 0; $i < pg_numrows($dependentes); $i++) {
                                $oDados = db_utils::fieldsMemory($dependentes, $i);

                                $std->infoircomplem[$i]->infodep[0] = new \stdClass();//opcional array com até 999 elementos Informações de dependentes não cadastrados
                                $std->infoircomplem[$i]->infodep[0]->cpfdep = $oDados->rh31_cpf; //obrigatório CPF do dependente
                                $std->infoircomplem[$i]->infodep[0]->dtnascto = $oDados->rh01_nasc; //opcional data de nascimento do dependente
                                $std->infoircomplem[$i]->infodep[0]->nome = $oDados->rh31_nome; //opcional nome do dependente 2-70 caracteres
                                $std->infoircomplem[$i]->infodep[0]->tpdep = '03'; //opcional tipo de dependente vide tabela 07
                                //$std->infoircomplem[$i]->infodep[0]->descrdep = $oDados->rh31_cpf; //opcional descrição da dependência. apenas se tpDep = 99
                                // var_dump($oDados);exit;
                            }
                            
                            foreach ($rsPensaoAlim as $key2 => $value2) {
                                $std->infoircomplem[$iCont]->infoircr[0]->penalim[$key2] = new \stdClass(); //opcional array com até 99 elementos Informação dos beneficiários da pensão alimentícia.
                                $std->infoircomplem[$iCont]->infoircr[0]->penalim[$key2]->tprend = $tpRend; //obrigatório tipo de rendimento
                                //11 - Remuneração mensal
                                //12 - 13º salário
                                //13 - Férias
                                //14 - PLR
                                //18 - RRA
                                //79 - Rendimento isento ou não tributável
                                $std->infoircomplem[$iCont]->infoircr[0]->penalim[$key2]->cpfdep = $value2['cpf']; //obrigatório Número do CPF do dependente/beneficiário da pensão alimentícia.
                                $std->infoircomplem[$iCont]->infoircr[0]->penalim[$key2]->vlrdedpenalim = $value2['valor']; //obrigatório dedução do rendimento tributável correspondente a pagamento de pensão alimentícia.
                            }
                        }

                        $seqinfopag++;
                    }
                }
                $oDadosAPI->evtPgtos->infopgto = $std->infopgto;
                if ($std->infoircomplem) {
                    $oDadosAPI->evtPgtos->infoircomplem = $std->infoircomplem;
                }
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

                $oDadosAPI->evtPgtos->cpfbenef             = $aDadosContabilidade[0]->cpf_benef;

                $std = $this->dmDevContabilidade($aDadosContabilidade);

                $oDadosAPI->evtPgtos->infopgto = $std->infopgto;
                $aDadosAPI[] = $oDadosAPI;
                $iSequencial++;
            }
        }
        // echo '<pre>';
        // print_r($aDadosAPI);
        // exit;
        return $aDadosAPI;
    }

    private function definirTpCR($codcateg, $vinculo)
    {
        $categoriasEspecificas = [
            701,
            711,
            712,
            721,
            722,
            723,
            731,
            734,
            738,
            741,
            751,
            761,
            771,
            781,
            901,
            902,
            903,
            904,
            906
        ];

        if (in_array($codcateg, $categoriasEspecificas)) {
            return '058806';
        } elseif (in_array($vinculo, ['I', 'P'])) {
            return '353301';
        } else {
            return '056107';
        }
    }

    private function definirTpRend($arquivo)
    {
        // Verificando a lógica para atribuir o valor correto de `tpRend`
        if ($arquivo == '4') {
            return '12';
        } else {
            return '11';
        }
    }



    private function calcularDeducao($matricula)
    {
        // Obter dados dos dependentes
        $dependentes = $this->buscarInfoDep($matricula);

        // Verifique se existem dependentes
        if (empty($dependentes)) {
            return null; // Sem deduções
        }

        $clpesdiver = new cl_pesdiver;
        $mes = $this->mes();
        $ano = $this->ano();
        $instit = db_getsession('DB_instit');
        $result01 = $clpesdiver->sql_record($clpesdiver->sql_query_file($ano, $mes, "D901", $instit, '*'));
        //db_criatabela($result01);exit;
        //db_fieldsmemory($result01,0);
        $oResult01 = db_utils::fieldsMemory($result01, 0);
        // Definir o valor de dedução por dependente (valor fictício, consulte a legislação para valores reais)
        $valorDeducaoPorDependente = $oResult01->r07_valor;

        // Contar o número de dependentes
        $numDependentes = count($dependentes);
        // Calcular a dedução total
        $deducaoTotal = $numDependentes * $valorDeducaoPorDependente;

        if ($numDependentes > 0) {
            for ($i = 0; $i < pg_numrows($dependentes); $i++) {
                $oDados = db_utils::fieldsMemory($dependentes, $i);
                $idade = date('Y') - date('Y', strtotime($oDados->rh31_dtnasc));

                if (
                    ($oDados->rh31_irf == 2 && $idade < 21) ||
                    ($oDados->rh31_irf == 3 && $idade < 24) ||
                    ($oDados->rh31_irf == 4 && $idade < 21) ||
                    ($oDados->rh31_irf == 5 && $idade < 24) ||
                    ($oDados->rh31_irf == 6) ||
                    ($oDados->rh31_irf == 8)
                ) {
                    $aDependentes[] = [
                        'cpfDependente' => $oDados->rh31_cpf,
                        'valorDeducao' => $valorDeducaoPorDependente,
                    ];
                }
            }
            return $aDependentes;
        }
    }

    private function buscarInfoDep($matricula)
    {
        $clrhdepend = new cl_rhdepend;
        $rsDep = $clrhdepend->sql_record($clrhdepend->sql_query(null, '*', null, "rh31_regist = {$matricula}"));
        if ($clrhdepend->numrows > 0) {
            return $rsDep;
        }
        return [];
    }

    /**
     * Retorna dados por matricula no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    private function buscarDadosPorMatricula($cpf, $tppgto)
    {
        $ano = $this->ano();
        $mes = $this->mes();

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
                (h13_categoria in ('101', '103', '106', '111', '301', '302', '303', '305', '306', '309', '312', '313','410', '902','701','712','771','711')
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
        $clrubricasesocial = new cl_rubricasesocial;

        $iAnoUsu = $this->ano();
        $iMesusu = $this->mes();

        $xtipo = "'x'";

        $proventos = 0;
        $descontos = 0;

        if ($ponto == 1)
            $opcao = 'salario';
        if ($ponto == 2)
            $opcao = 'rescisao';
        if ($ponto == 3)
            $opcao = 'complementar';
        if ($ponto == 4)
            $opcao = '13salario';

        switch ($opcao) {
            case 'salario':
                $sigla          = 'r14_';
                $arquivo        = 'gerfsal';
                $sTituloCalculo = 'Sal?rio';
                break;

            case 'complementar':
                $sigla          = 'r48_';
                $arquivo        = 'gerfcom';
                $sTituloCalculo = 'Complementar';
                break;

            case '13salario':
                $sigla          = 'r35_';
                $arquivo        = 'gerfs13';
                $sTituloCalculo = '13? Sal?rio';
                break;
            case 'rescisao':
                $sigla          = 'r20_';
                $arquivo        = 'gerfres';
                $xtipo          = ' r20_tpp ';
                $sTituloCalculo = 'Rescis?o';
                break;

            default:
                continue;
                break;
        }
        if ($opcao) {

            $sql = "  select '1' as ordem ,
                               {$sigla}rubric as rubrica,
                               case
                                 when rh27_pd = 3 then 0
                                 else case
                                        when {$sigla}pd = 1 then {$sigla}valor
                                        else 0
                                      end
                               end as Provento,
                               case
                                 when rh27_pd = 3 then 0
                                 else case
                                        when {$sigla}pd = 2 then {$sigla}valor
                                        else 0
                                      end
                               end as Desconto,
                               {$sigla}quant as quant,
                               rh27_descr,
                               {$xtipo} as tipo ,
                               case
                                 when rh27_pd = 3 then 'Base'
                                 else case
                                        when {$sigla}pd = 1 then 'Provento'
                                        else 'Desconto'
                                      end
                               end as provdesc,
                               case
                                when '{$arquivo}' = 'gerfsal' then 1
                                when '{$arquivo}' = 'gerfcom' then 3
                                when '{$arquivo}' = 'gerfs13' then 4
                                when '{$arquivo}' = 'gerfres' then 2
                                end as ideDmDev
                          from {$arquivo}
                               inner join rhrubricas on rh27_rubric = {$sigla}rubric
                                                    and rh27_instit = " . db_getsession("DB_instit") . "
                          " . bb_condicaosubpesproc($sigla, $iAnoUsu . "/" . $iMesusu) . "
                           and {$sigla}regist = $matricula
                           and {$sigla}pd != 3
                           and {$sigla}rubric not in ('R985','R993','R981')
                           order by {$sigla}pd,{$sigla}rubric";
        }
        $rsValores = db_query($sql);
        // echo $sql;
        // db_criatabela($rsValores);
        // exit;
        if ($opcao != 'rescisao') {
            for ($iCont = 0; $iCont < pg_num_rows($rsValores); $iCont++) {
                $oResult = \db_utils::fieldsMemory($rsValores, $iCont);
                $proventos  += ($oResult->provdesc == 'Provento') ? $oResult->provento : 0;
                $descontos  += ($oResult->provdesc == 'Desconto') ? $oResult->desconto : 0;
            }
            $vrliq = $proventos - $descontos;
        } else {
            for ($iCont2 = 0; $iCont2 < pg_num_rows($rsValores); $iCont2++) {
                $oResult = \db_utils::fieldsMemory($rsValores, $iCont2);
                $rsRubEspeciais = db_query($clrubricasesocial->sql_query(null, "e990_sequencial,e990_descricao", null, "baserubricasesocial.e991_rubricas = '{$oResult->rubrica}' AND e990_sequencial IN ('1000','5001','1020')"));
                $rubrica = $oResult->rubrica;
                if (pg_num_rows($rsRubEspeciais) > 0) {
                    $oRubEspeciais = db_utils::fieldsMemory($rsRubEspeciais);
                    switch ($oRubEspeciais->e990_sequencial) {
                        case '1000':
                            $rubrica = '9000';
                            $rh27_descr = 'Saldo de Salário na Rescisão';
                            break;
                        case '5001':
                            $rubrica = '9001';
                            $rh27_descr = '13º Salário na Rescisão';
                            break;
                        case '1020':
                            $rubrica = '9002';
                            $rh27_descr = 'Férias Proporcional na Rescisão';
                            break;
                        case '1020':
                            $rubrica = '9003';
                            $rh27_descr = 'Férias Vencidas na Rescisão';
                            break;

                        default:
                            break;
                    }
                }
                $proventos  += ($oResult->provdesc == 'Provento') ? $oResult->provento : 0;
                $descontos  += ($oResult->provdesc == 'Desconto') ? $oResult->desconto : 0;
            }
            $vrliq = $proventos - $descontos;
        }
        return round($vrliq, 2);
    }

    private function buscarIdentificador($matricula, $rh30_regime)
    {
        $iAnoUsu = $this->ano();
        $iMesusu = $this->mes();

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
        $ano = $this->ano();
        $mes = $this->mes();
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

    private function buscarBaseR984($matricula, $rh30_regime)
    {
        $iAnoUsu = $this->ano();
        $iMesusu = $this->mes();

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

    private function getPensaoAlim($matricula)
    {
        $ano = $this->ano();
        $mes = $this->mes();
        $iInstituicao = db_getsession('DB_instit');
        $sValor     = 'r52_valor + r52_valfer';
        $sOrder = 'z01_nome, codigo_banco, codigo_agencia';
        $sGroup = 'descricao_banco, codigo_banco, codigo_agencia, r52_dvagencia, conta, r52_dvconta, cgm_beneficiario, nome_beneficiario, rh01_regist, x.z01_nome, x.w01_work05,x.z01_cgccpf,x.o15_codigo,x.o15_descr';

        $sSql = "
            SELECT *
            FROM (
                SELECT CASE WHEN trim(r52_codbco) = '' OR r52_codbco IS NULL THEN '000'
                            ELSE r52_codbco
                    END                     AS codigo_banco,
                    CASE WHEN db90_descr IS NOT NULL THEN db90_descr
                            ELSE 'SEM BANCO'
                    END                     AS descricao_banco,
                    to_char(to_number(CASE WHEN trim(r52_codage) = '' THEN '0'
                            ELSE r52_codage
                    END, '99999'), '99999') AS codigo_agencia,
                    CASE WHEN r52_dvagencia IS NULL THEN ''
                            ELSE r52_dvagencia
                    END                     AS r52_dvagencia,
                    r52_conta               AS conta,
                    CASE WHEN r52_dvconta IS NULL THEN ''
                            ELSE r52_dvconta
                    END                     AS r52_dvconta,
                    r52_numcgm              AS cgm_beneficiario,
                    cgm.z01_nome            AS nome_beneficiario,
                        a.z01_nome,
                    rh01_regist,
                    {$sValor}               AS w01_work05,
                    cgm.z01_cgccpf,
                    o15_codigo,
                    o15_descr
                FROM pensao
                    INNER JOIN cgm          ON   r52_numcgm              =  z01_numcgm
                    INNER JOIN rhpessoal    ON  rh01_regist              =  r52_regist
                    INNER JOIN rhpessoalmov ON  rh01_regist              = rh02_regist
                                        AND  rh02_anousu              = {$ano}
                                        AND  rh02_mesusu              = {$mes}
                                        AND  rh02_instit              = {$iInstituicao}
                    INNER JOIN rhlota       ON   r70_codigo              = rh02_lota
                                        AND   r70_instit              = rh02_instit


                    LEFT JOIN rhlotaexe ON rhlotaexe.rh26_anousu = rhpessoalmov.rh02_anousu
                    AND rhlotaexe.rh26_codigo = rhlota.r70_codigo
                    LEFT JOIN rhlotavinc ON rhlotavinc.rh25_codigo = rhlotaexe.rh26_codigo
                    AND rhlotavinc.rh25_anousu = rhpessoalmov.rh02_anousu
                    LEFT JOIN orctiporec ON orctiporec.o15_codigo = rhlotavinc.rh25_recurso

                    INNER JOIN cgm AS a     ON a.z01_numcgm              = rh01_numcgm
                    LEFT  JOIN db_bancos    ON   r52_codbco::varchar(10) = db90_codban
                WHERE r52_anousu = {$ano}
                AND r52_mesusu = {$mes}
                AND {$sValor}  > 0
                AND rh01_regist = {$matricula}
            ) AS x
            GROUP BY {$sGroup}
            ORDER BY {$sOrder}
        ";

        $rsResult = db_query($sSql);
        //var_dump(pg_num_rows($rsResult));exit;
        if (pg_num_rows($rsResult) > 0) {
            for ($x = 0; $x < pg_numrows($rsResult); $x++) {
                $oDados = \db_utils::fieldsMemory($rsResult, $x);
                $retorno[] = [
                    'valor' => $oDados->w01_work05,
                    'cpf' => $oDados->z01_cgccpf
                ];
            }
            return $retorno;
        }
        return [];
    }
}