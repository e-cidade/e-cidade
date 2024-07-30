<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use cl_rubricasesocial;
use db_utils;
use DBPessoal;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\EventoBase;

/**
 * Classe responsável por montar as informações do evento S1210 Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Marcelo Hernane
 */
class EventoS1299 extends EventoBase
{
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

        $aDadosAPI = array();
        $iSequencial = 1;

        $oDadosAPI                                     = new \stdClass();
        $oDadosAPI->evtFechaEvPer                      = new \stdClass();
        $oDadosAPI->evtFechaEvPer->sequencial          = $iSequencial;
        $oDadosAPI->evtFechaEvPer->modo                = $this->modo;
        $oDadosAPI->evtFechaEvPer->indRetif            = 1;
        $oDadosAPI->evtFechaEvPer->nrRecibo            = null;

        $oDadosAPI->evtFechaEvPer->indapuracao         = $this->indapuracao;
        $oDadosAPI->evtFechaEvPer->perapur             = $ano . '-' . $mes;
        if ($this->indapuracao == 2) {
            $oDadosAPI->evtFechaEvPer->perapur         = $ano;
        }

        $std = new \stdClass();

        $std->infofech->evtremun = 'S';
        $std->infofech->evtpgtos = ($this->evtpgtos == 'S') ? 'S' : 'N';

        $std->infofech->evtcomprod = 'N';

        $std->infofech->evtcontratavnp = 'N';

        $std->infofech->evtinfocomplper = 'N';
        if ($this->transDCTFWeb == 'S') {
            $std->infofech->transdctfweb = $this->transDCTFWeb;
        }

        $oDadosAPI->evtFechaEvPer->infofech = $std->infofech;
        $aDadosAPI[] = $oDadosAPI;

        return $aDadosAPI;
    }
}
