<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

/**
 * Classe base para eventos Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Robson de Jesus
 */
abstract class EventoBase
{
    /**
     * Dados do Evento
     * @var \stdClass
     */
    protected $dados;

    /**
     * In�cio Validade das informa��es
     *
     * @var string
     */
    protected $iniValid;

    /**
     * modo
     *
     * @var string
     */
    protected $modo;

    /**
     * dt_alteracao
     *
     * @var string
     */
    protected $dt_alteracao;

    /**
     * indapuracao
     *
     * @var string
     */
    protected $indapuracao;

    /**
     * tppgto
     *
     * @var string
     */
    protected $tppgto;

    /**
     * tpevento
     *
     * @var string
     */
    protected $tpevento;

    /**
     * tpevento
     *
     * @var string
     */
    protected $transDCTFWeb;

    /**
     * tpevento
     *
     * @var string
     */
    protected $evtpgtos;

    /**
     * tpevento
     *
     * @var string
     */
    protected $aDadosExclusao;

    /**
     * dtpgto
     *
     * @var string
     */
    protected $dtpgto;

    /**
     *
     * @param \stdClass $dados
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     *
     * @param string $iniValid
     */
    public function setIniValid($iniValid)
    {
        $this->iniValid = $iniValid;
    }

    public function setModo($modo)
    {
        $this->modo = $modo;
    }

    public function setDtAlteracao($dt_alteracao)
    {
        $this->dt_alteracao = $dt_alteracao;
    }

    public function setIndApuracao($indapuracao)
    {
        $this->indapuracao = $indapuracao;
    }

    public function setTppgto($tppgto)
    {
        $this->tppgto = $tppgto;
    }

    public function setTpevento($tpevento)
    {
        $this->tpevento = $tpevento;
    }

    public function setTransDCTFWeb($transDCTFWeb)
    {
        $this->transDCTFWeb = $transDCTFWeb;
    }

    public function setEvtpgtos($evtpgtos)
    {
        $this->evtpgtos = $evtpgtos;
    }

    public function setDadosExclusao($aDadosExclusao)
    {
        $this->aDadosExclusao = $aDadosExclusao;
    }

    public function setDtpgto($dtpgto)
    {
        $this->dtpgto = $dtpgto;
    }

    public function anoPgto()
    {
        return explode('/', $this->dtpgto)[2];
    }

    public function mesPgto()
    {
        return explode('/', $this->dtpgto)[1];
    }

    public function diaPgto()
    {
        return explode('/', $this->dtpgto)[0];
    }

    public function ano()
    {
        return explode('-', $this->iniValid)[0];
    }

    public function mes()
    {
        return explode('-', $this->iniValid)[1];
    }

    /**
     * Retorna dados no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    abstract public function montarDados();
}
