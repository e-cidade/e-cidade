<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use cl_rubricasesocial;
use db_utils;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\EventoBase;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\TipoPontoConstants;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\ValoresPontoEvento;

/**
 * Classe responsavel por montar as informacoes do evento S2399 Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Marcelo Hernane
 */
class EventoS2399 extends EventoBase
{
    use ValoresPontoEvento;

    /**
     *
     * @param \stdClass $dados
     */
    function __construct($dados)
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
        //exit(var_dump($this->dados));
        $aDadosAPI = array();
        $iSequencial = 1;
        foreach ($this->dados as $oDados) {

            $oDadosAPI                                    = new \stdClass;
            $oDadosAPI->evtTSVTermino                     = new \stdClass;
            $oDadosAPI->evtTSVTermino->sequencial          = $iSequencial;
            $oDadosAPI->evtTSVTermino->indRetif            = 1;
            $oDadosAPI->evtTSVTermino->nrRecibo            = null;
            $oDadosAPI->evtTSVTermino->cpfTrab             = $oDados->cpftrab;
            $oDadosAPI->evtTSVTermino->matricula           = $oDados->matricula;
            //$oDadosAPI->evtTSVTermino->codcateg          = ;
            $oDadosAPI->evtTSVTermino->dtTerm              = $oDados->rh05_recis;
            //$oDadosAPI->evtTSVTermino->mtvdesligtsv      = ;
            $oDadosAPI->evtTSVTermino->pensalim            = 0;
            //$oDadosAPI->evtTSVTermino->percaliment       = ;
            //$oDadosAPI->evtTSVTermino->vralim            = ;
            //$oDadosAPI->evtTSVTermino->nrproctrab        = ;
            //$oDadosAPI->novocpf                          = ;

            $std = $this->dmDevRH($oDados);

            if ($std->verbasresc->dmdev == null)
                continue;

            $oDadosAPI->evtTSVTermino->dmdev = $std->verbasresc->dmdev;

            $aDadosAPI[] = $oDadosAPI;
            $iSequencial++;
        }
        // echo '<pre>';
        // var_dump($aDadosAPI);exit;
        return $aDadosAPI;
    }

    private function dmDevRH($aDadosPorMatriculas)
    {
        $std = new \stdClass();
        $seqdmdev = 0;
        $aIdentificador = $this->buscarIdentificador($aDadosPorMatriculas->matricula, $aDadosPorMatriculas->rh30_regime);

        for ($iCont2 = 0; $iCont2 < count($aIdentificador); $iCont2++) {
            $std->verbasresc->dmdev[$seqdmdev] = new \stdClass();

            if ($aIdentificador[$iCont2]->idedmdev == 1) {
                $std->verbasresc->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas->matricula . 'gerfsal';
            }
            if ($aIdentificador[$iCont2]->idedmdev == 2) {
                $std->verbasresc->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas->matricula . 'gerfres';
            }
            if ($aIdentificador[$iCont2]->idedmdev == 3) {
                $std->verbasresc->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas->matricula . 'gerfcom';
            }
            if ($aIdentificador[$iCont2]->idedmdev == 4) {
                $std->verbasresc->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas->matricula . 'gerfs13';
            }

            $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0] = new \stdClass();
            $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->tpinsc = "1";
            $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->nrinsc = $aDadosPorMatriculas->nrinsc;
            $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->codlotacao = $aDadosPorMatriculas->codlotacao;

            $aDadosValoreRubrica = $this->buscarValorRubrica($aDadosPorMatriculas->matricula, $aIdentificador[$iCont2]->idedmdev);

            if (count($aDadosValoreRubrica) == 0) {
                continue;
            }

            for ($iCont4 = 0; $iCont4 < count($aDadosValoreRubrica); $iCont4++) {
                $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->detverbas[$iCont4] = new \stdClass();
                $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->detverbas[$iCont4]->codrubr = $aDadosValoreRubrica[$iCont4]->codrubr;
                $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->detverbas[$iCont4]->idetabrubr = 'TABRUB1';
                $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->detverbas[$iCont4]->vrrubr = $aDadosValoreRubrica[$iCont4]->vrrubr;
                $std->verbasresc->dmdev[$seqdmdev]->ideestablot[0]->detverbas[$iCont4]->indapurir = 0;
            }

            $seqdmdev++;
        }

        $std->verbasresc->infomv = new \stdClass();
        $std->verbasresc->infomv->indmv = $aDadosPorMatriculas->indmv;

        $std->verbasresc->infomv->remunoutrempr[1] = new \stdClass();
        $std->verbasresc->infomv->remunoutrempr[1]->tpinsc = 1;
        $std->verbasresc->infomv->remunoutrempr[1]->nrinsc = $aDadosPorMatriculas->nrInscremunOutrEmpr;
        $std->verbasresc->infomv->remunoutrempr[1]->codcateg = $aDadosPorMatriculas->rh51_categoria;
        $std->verbasresc->infomv->remunoutrempr[1]->vlrremunoe = $aDadosPorMatriculas->rh51_basefo;

        return $std;
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
        $aPontos = array(TipoPontoConstants::PONTO_13SALARIO);
        if ($this->indapuracao != 2) {
            $aPontos = array(TipoPontoConstants::PONTO_SALARIO, TipoPontoConstants::PONTO_COMPLEMENTAR);
            if ($rh30_regime == 1 || $rh30_regime == 3) {
                $aPontos = array(TipoPontoConstants::PONTO_SALARIO, TipoPontoConstants::PONTO_COMPLEMENTAR, TipoPontoConstants::PONTO_RESCISAO);
            }
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

    /**
     * Retorna os valores por rubrica no formato necessario para envio
     * pela API sped-esocial
     * @param int $matricula
     * @param int $ponto
     * @return array stdClass
     */
    private function buscarValorRubrica($matricula, $ponto)
    {
        require_once 'libs/db_libpessoal.php';
        $clrubricasesocial = new cl_rubricasesocial;

        $rsValores = $this->getValoresPorPonto($ponto, $matricula);
        for ($iCont = 0; $iCont < pg_num_rows($rsValores); $iCont++) {
            $oResult = \db_utils::fieldsMemory($rsValores, $iCont);
            $rubrica = $oResult->rubrica;
            if ($ponto == TipoPontoConstants::PONTO_RESCISAO) {
                $aRubEspeciais = $clrubricasesocial->buscarDadosRubricaEspecial($oResult->rubrica, $oResult->tipo);
                if (count($aRubEspeciais) > 0) {
                    $rubrica = $aRubEspeciais['rubrica'];
                }
            }
            $oFormatado = new \stdClass();
            $oFormatado->codrubr    = $rubrica;
            $oFormatado->idetabrubr = 'tabrub1';
            $oFormatado->vrrubr     = ($oResult->provdesc == 'Provento') ? $oResult->provento : $oResult->desconto;
            $oFormatado->indapurir  = 0;
            $oFormatado->idedmdev   = $oResult->idedmdev;

            $aItens[] = $oFormatado;
        }
        return $aItens;
    }

    /**
     * Retorna dados dos dependentes no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    private function buscarDependentes($matricula)
    {

        $oDaorhdepend = \db_utils::getDao("rhdepend");
        $sqlDependentes = $oDaorhdepend->sql_query_file(null, "*", "rh31_codigo", "rh31_regist = {$matricula}");
        $rsDependentes = db_query($sqlDependentes);
        if (pg_num_rows($rsDependentes) == 0) {
            return null;
        }
        $aDependentes = array();
        for ($iCont = 0; $iCont < pg_num_rows($rsDependentes); $iCont++) {
            $oDependentes = \db_utils::fieldsMemory($rsDependentes, $iCont);
            $oDependFormatado = new \stdClass;
            switch ($oDependentes->rh31_gparen) {
                case 'C':
                    $oDependFormatado->tpdep = '01';
                    break;
                case 'F':
                    $oDependFormatado->tpdep = '03';
                    break;
                case 'P':
                case 'M':
                case 'A':
                    $oDependFormatado->tpdep = '09';
                    break;

                default:
                    $oDependFormatado->tpdep = '99';
                    break;
            }
            $oDependFormatado->nmdep = $oDependentes->rh31_nome;
            $oDependFormatado->dtnascto = $oDependentes->rh31_dtnasc;
            $oDependFormatado->cpfdep = empty($oDependentes->rh31_cpf) ? null : $oDependentes->rh31_cpf;
            $oDependFormatado->depirrf = ($oDependentes->rh31_irf == "0" ? "N" : "S");
            $oDependFormatado->depsf = ($oDependentes->rh31_depend == "N" ? "N" : "S");
            $oDependFormatado->inctrab = ($oDependentes->rh31_especi == "C" || $oDependentes->rh31_especi == "S" ? "S" : "N");

            $aDependentes[] = $oDependFormatado;
        }
        return $aDependentes;
    }
}
