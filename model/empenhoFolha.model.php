<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


class empenhoFolha {

  /**
   * C�digo do empenho da folha
   *
   * @var integer
   */
  protected $empenhofolha = null;
  /**
   * Se possui reserva de saldo
   *
   * @var bool
   */
  protected $temreservasaldo = false;
  /**
   * C�digo da reserva de saldo;
   *
   * @var unknown_type
   */
  protected $reservasaldo    = 0;
  /**
   * J� possui empenho
   *
   * @var bool
   */
  protected $temempenho  = false;
  /**
   * C�digo do empenho gerado
   *
   * @var integer
   */
  protected $numeroempenho = null;
  /**
   * Valor do Empenho
   *
   * @var float
   */
  protected $valorempenho = 0;
  /**
   * C�digo da Dotacao
   *
   * @var integer
   */
  protected $dotacao  = null;
  /**
   * Elemento do empenho
   *
   * @var integer
   */
  protected $elemento = null;
  /**
   * Recurso
   *
   * @var integer
   */
  protected $recurso  = null;
  /**
   * Orgao
   *
   * @var integer
   */
  protected $orgao    = null;
  /**
   * Unidade
   *
   * @var integer
   */
  protected $unidade  = null;
  /**
   * Projeto/Atividade
   *
   * @var integer
   */
  protected $projativ  = null;

  protected $programa  = null;

  protected $funcao    = null;

  protected $subfuncao = null;
  /**
   * Ano da Sess�o
   *
   * @var integer
   */
  protected $ano  = null;
  /**
   * Ano da Folha
   *
   * @var integer
   */
  protected $anoFolha  = null;

  /**
   * Mes da folha
   *
   * @var string
   */
  protected $mes = null;
  /**
   * Tipo da folha
   *
   * @var strings
   */
  protected $tipofolha = '';
  /**
   * Sigla do tipo da folha
   *
   * @var string
   */
  protected $siglafolha = '';
  /**
   * C�digo da tabela de previdencia
   *
   * @var integer
   */
  protected $tabelaprevidencia = null;
  /**
   * Tipo de Empenho
   *
   * @var integer
   */
  protected $tipoempenho = 0;
  /**
   * C�digo da Ordem auxiliar
   *
   * @var unknown_type
   */
  protected $ordemauxiliar;
  /**
   * Caracter�stica Peculiar
   *
   * @var integer
   */
  protected $caracteristica;

  /**
   * C�digo do empenho financeiro gerado empempenho.e60_numemp
   * @var integer
   */
  protected $iNumeroEmpenhoFinanceiro = null;

  /**
   * Tipo empenho resumo da folha
   *
   * @var integer
   */
  protected $tipoEmpenhoResumo = 0;

  /**
   * Lota��o do empenho
   *
   * @var integer
   */
  protected $lotacao = null;


  /**
   * Controla reten��o da rubrica
   *
   * @var integer
   */
  protected $lRubrica = null;

  /**
   * Define reten��es da OP
   *
   * @var integer
   */
  protected $sRetencoes = null;

    /**
   * Define credor do empenho em caso de recis�o
   *
   * @var integer
   */
  protected $sCredor = null;


  /**
   * @param integer $iNumeroEmpenhoFinanceiro
   */
  function setNumeroEmpenhoFinanceiro($iNumeroEmpenhoFinanceiro) {
    $this->iNumeroEmpenhoFinanceiro = $iNumeroEmpenhoFinanceiro;
  }

  /**
   * @return integer
   */
  public function getNumeroEmpenhoFinanceiro() {
    return $this->iNumeroEmpenhoFinanceiro;
  }


  function __construct($iEmpenhoFolha, $iLotacao = null, $lRubrica = false, $sRetencoes = null, $sCredor = null) {

    if (empty($iEmpenhoFolha)) {
      throw new ParameterException("Empenho da Folha n�o informado");
    }
    $this->empenhofolha = $iEmpenhoFolha;
    $sSqlDadosEmpenho   = "SELECT rh72_sequencial, ";
    $sSqlDadosEmpenho  .= "       rh72_coddot, ";
    $sSqlDadosEmpenho  .= "       rh72_codele, ";
    $sSqlDadosEmpenho  .= "       rh72_unidade, ";
    $sSqlDadosEmpenho  .= "       rh72_orgao, ";
    $sSqlDadosEmpenho  .= "       rh72_projativ, ";
    $sSqlDadosEmpenho  .= "       rh72_programa, ";
    $sSqlDadosEmpenho  .= "       rh72_funcao, ";
    $sSqlDadosEmpenho  .= "       rh72_subfuncao, ";
    $sSqlDadosEmpenho  .= "       rh72_anousu, ";
    $sSqlDadosEmpenho  .= "       rh72_mesusu, ";
    $sSqlDadosEmpenho  .= "       rh72_recurso, ";
    $sSqlDadosEmpenho  .= "       rh72_tabprev, ";
    $sSqlDadosEmpenho  .= "       rh72_siglaarq,";
    $sSqlDadosEmpenho  .= "       rh72_concarpeculiar,";
    $sSqlDadosEmpenho  .= "       rh72_tipoempenho,";
    $sSqlDadosEmpenho  .= "       round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end), 2) as valor ";
    $sSqlDadosEmpenho  .= "  from rhempenhofolha ";
    $sSqlDadosEmpenho  .= "       inner join rhempenhofolharhemprubrica        on rh81_rhempenhofolha = rh72_sequencial ";
    $sSqlDadosEmpenho  .= "       inner join rhempenhofolharubrica on rh73_sequencial     = rh81_rhempenhofolharubrica";
    $sSqlDadosEmpenho  .= "       inner join rhpessoalmov          on rh73_seqpes     = rh02_seqpes  ";
    $sSqlDadosEmpenho  .= "                                        and rh73_instit     = rh02_instit ";
    $sSqlDadosEmpenho  .= "   where rh72_sequencial  = {$iEmpenhoFolha}  ";
    if ($iLotacao != null) {
      $sSqlDadosEmpenho  .= "   and rh02_lota  = {$iLotacao}  ";
    }
    //$sSqlDadosEmpenho  .= "     and rh72_tipoempenho = {$iTipo}";
    $sSqlDadosEmpenho  .= "     and rh73_tiporubrica = 1";
    $sSqlDadosEmpenho  .= "   group by rh72_sequencial,  ";
    $sSqlDadosEmpenho  .= "            rh72_coddot,  ";
    $sSqlDadosEmpenho  .= "            rh72_codele, ";
    $sSqlDadosEmpenho  .= "            rh72_unidade, ";
    $sSqlDadosEmpenho  .= "            rh72_orgao, ";
    $sSqlDadosEmpenho  .= "            rh72_projativ, ";
    $sSqlDadosEmpenho  .= "            rh72_programa, ";
    $sSqlDadosEmpenho  .= "            rh72_funcao, ";
    $sSqlDadosEmpenho  .= "            rh72_subfuncao, ";
    $sSqlDadosEmpenho  .= "            rh72_mesusu, ";
    $sSqlDadosEmpenho  .= "            rh72_anousu, ";
    $sSqlDadosEmpenho  .= "            rh72_recurso, ";
    $sSqlDadosEmpenho  .= "            rh72_tabprev, ";
    $sSqlDadosEmpenho  .= "            rh72_tipoempenho,";
    $sSqlDadosEmpenho  .= "            rh72_concarpeculiar,";
    $sSqlDadosEmpenho  .= "            rh72_siglaarq ";
    $rsDadosEmpenho     = db_query($sSqlDadosEmpenho);
    if (!$rsDadosEmpenho) {
      throw new DBException('N�o foi poss�vel consultar dados do empenho');
    }
    $oDadosEmpenho           = db_utils::fieldsMemory($rsDadosEmpenho, 0);
    $this->elemento          = $oDadosEmpenho->rh72_codele;
    $this->recurso           = $oDadosEmpenho->rh72_recurso;
    $this->projativ          = $oDadosEmpenho->rh72_projativ;
    $this->programa          = $oDadosEmpenho->rh72_programa;
    $this->funcao            = $oDadosEmpenho->rh72_funcao;
    $this->subfuncao         = $oDadosEmpenho->rh72_subfuncao;
    $this->dotacao           = $oDadosEmpenho->rh72_coddot;
    $this->orgao             = $oDadosEmpenho->rh72_orgao;
    $this->unidade           = $oDadosEmpenho->rh72_unidade;
    $this->valorempenho      = $oDadosEmpenho->valor;
    $this->ano               = db_getsession('DB_anousu');
    $this->anoFolha          = $oDadosEmpenho->rh72_anousu;
    $this->mes               = $oDadosEmpenho->rh72_mesusu;
    $this->siglafolha        = $oDadosEmpenho->rh72_siglaarq;
    $this->tabelaprevidencia = $oDadosEmpenho->rh72_tabprev;
    $this->tipoempenho       = $oDadosEmpenho->rh72_tipoempenho;
    $this->caracteristica    = $oDadosEmpenho->rh72_concarpeculiar;
    $this->lotacao           = $iLotacao;
    $this->lRubrica          = $lRubrica;
    $this->sRetencoes        = $sRetencoes;
    $this->sCredor           = $sCredor;


    switch ($oDadosEmpenho->rh72_siglaarq) {

      case  'r14':

        $this->tipofolha  = 'Sal�rio';
        break;

      case 'r20' :

        $this->tipofolha  = 'Rescis�o';
        break;

      case 'r22' :

        $this->tipofolha  = 'Adiantamento';
        break;

      case 'r31' :

        $this->tipofolha  = 'F�rias';
        break;

      case 'r35' :

        $this->tipofolha  = '13� Sal�rio';
        break;

      case 'r48' :

        $this->tipofolha  = 'Complementar';
        break;

      default :

        $this->tipofolha = '';
        break;

    }
    /**
     * Verificamos se existe Reserva de Saldo para esse empenho
     */
    if ($iLotacao != null) {
      $sWhere = "o120_rhempenhofolha={$this->empenhofolha} and o120_lota = {$this->lotacao} ";
    } else {
      $sWhere = "o120_rhempenhofolha={$this->empenhofolha}";
    }
    $oDaoReservaSaldo = db_utils::getDao("orcreservarhempenhofolha");
    $sSqlReservaSaldo = $oDaoReservaSaldo->sql_query_file(null,
                                                     "o120_orcreserva",
                                                     null,
                                                     $sWhere,
                                                     );

    $rsReservaSaldo  = $oDaoReservaSaldo->sql_record($sSqlReservaSaldo);
    if ($oDaoReservaSaldo->numrows > 0) {

      $this->temreservasaldo = true;
      $this->reservasaldo    = db_utils::fieldsMemory($rsReservaSaldo, 0)->o120_orcreserva;

    }

    /**
     * Verificamos se o empenho da folha j� foi empenhado
     */
    if ($iLotacao != null) {
      $sWhere = "rh76_rhempenhofolha = {$this->empenhofolha} and rh76_lota = {$this->lotacao} ";
    } else {
      $sWhere = "rh76_rhempenhofolha = {$this->empenhofolha} ";
    }
    $oDaoEmpenhoFolhaEmpenho = db_utils::getDao("rhempenhofolhaempenho");
    $sSqlEmpenho             = $oDaoEmpenhoFolhaEmpenho->sql_query_file(null,
                                                                        "*",
                                                                        null,
                                                                        $sWhere
                                                                        );
    $rsEmpenho                = $oDaoEmpenhoFolhaEmpenho->sql_record($sSqlEmpenho);
    if ($oDaoEmpenhoFolhaEmpenho->numrows > 0) {

      $this->temempenho    = true;
      $this->numeroempenho = db_utils::fieldsMemory($rsEmpenho, 0)->rh76_numemp;

    }
  }
  /**
   * @return integer
   */
  public function getDotacao() {

    return $this->dotacao;
  }

  /**
   * @return integer
   */
  public function getElemento() {

    return $this->elemento;
  }

  /**
   * @return integer
   */
  public function getEmpenhofolha() {

    return $this->empenhofolha;
  }

  /**
   * @return integer
   */
  public function getOrgao() {

    return $this->orgao;
  }

  /**
   * @return integer
   */
  public function getProjativ() {

    return $this->projativ;
  }

  /**
   * @return integer
   */
  public function getRecurso() {

    return $this->recurso;
  }

  /**
   * @return bool
   */
  public function getReservasaldo() {

    return $this->reservasaldo;
  }

  /**
   * @return integer
   */
  public function getUnidade() {

    return $this->unidade;
  }

  /**
   * @return float
   */
  public function getValorempenho() {

    return $this->valorempenho;
  }

  /**
   * empenho possui reserva de saldo
   *
   * @return bool
   */
  function temReservaSaldo() {
    return $this->temreservasaldo;
  }

  /**
   * Faz a reserva de saldo para o empenho da folha
   *
   */
  function reservarSaldo() {

    /**
     * Validamos o valor do empenho,.
     * caso o valor das retencoes seje maior que o valor do emepnho, cancelamos o procedimento
     */
    if ($this->getValorRetencao() > $this->valorempenho) {

      $sMessage  = "N�o foi poss�vel fazer a reserva de saldo do empenho da folha ({$this->empenhofolha}).\n";
      $sMessage .= "Valor da reten��o maior que o valor do Empenho.\nContate Suporte.";
      throw new BusinessException($sMessage);

    }

    if (!$this->temreservasaldo) {

      /**
       * Incluimos na tabela orcreserva caso ainda exista saldo na dota��o
       */
      require_once("libs/db_liborcamento.php");
      $rsDotacaoSaldo           = db_dotacaosaldo(8, 2, 2, true,
                                                  "o58_coddot={$this->dotacao}", db_getsession("DB_anousu"));
      $oDotacaoSaldo            = db_utils::fieldsMemory($rsDotacaoSaldo, 0);
      if ($oDotacaoSaldo->atual_menos_reservado - $this->valorempenho >= 0) {

        $oDaoReservaSaldo         = db_utils::getDao("orcreserva");
        $oDaoReservaSaldo->o80_anousu = db_getsession("DB_anousu");
    	  $oDaoReservaSaldo->o80_coddot = $this->dotacao;
        $oDaoReservaSaldo->o80_dtfim  = date('Y', db_getsession('DB_datausu'))."-12-31";
  	    $oDaoReservaSaldo->o80_dtini  = date('Y-m-d', db_getsession('DB_datausu'));
  	    $oDaoReservaSaldo->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
  	    $oDaoReservaSaldo->o80_valor  = $this->valorempenho;
  	    $oDaoReservaSaldo->o80_descr  = "Reserva saldo empenho folha {$this->empenhofolha} ";
        $oDaoReservaSaldo->o80_justificativa  = "Reserva saldo empenho folha {$this->empenhofolha} ";
  	    $oDaoReservaSaldo->incluir(null);
  	    if ($oDaoReservaSaldo->erro_status == 0) {

  	      $sMessage = "N�o foi poss�vel fazer a reserva de saldo \n$oDaoReservaSaldo->erro_msg";
  	      throw new DBException($sMessage);

  	    }
  	    /**
  	     * Vinculamos a reserva ao empenho da folha
  	     */
  	    $oDaoReservaEmpenhoFolha = new cl_orcreservarhempenhofolha;
  	    $oDaoReservaEmpenhoFolha->o120_orcreserva     = $oDaoReservaSaldo->o80_codres;
  	    $oDaoReservaEmpenhoFolha->o120_rhempenhofolha = $this->empenhofolha;
        $oDaoReservaEmpenhoFolha->o120_lota = $this->lotacao;
  	    $oDaoReservaEmpenhoFolha->incluir(null);
  	    if ($oDaoReservaEmpenhoFolha->erro_status == 0) {

  	      $sMessage = "N�o foi poss�vel fazer a reserva de saldo\n$oDaoReservaEmpenhoFolha->erro_msg";
  	      throw new DBException($sMessage);

  	    }
  	    $this->temreservasaldo = true;
  	    $this->reservasaldo    = $oDaoReservaSaldo->o80_codres;
      } else {
        throw new BusinessException("Dota��o {$this->dotacao} n�o possui saldo suficiente para empenhar.");
      }

    }
  }

  /**
   * Cancela reserva de saldo para o empenho da folha
   *
   */
  function cancelarReservaSaldo() {

    if (empty($this->empenhofolha)) {
      throw new ParameterException("C�digo do Empenho n�o declarado");
    }

    //buscamos as reservas geradas para o empenho
    $oDaoOrcReservaRhEmpenhoFolha = db_utils::getDao("orcreservarhempenhofolha");
    $oDaoOrcReserva               = db_utils::getDao("orcreserva");

    if ($this->lotacao != null) {
      $sWhere = "o120_rhempenhofolha = {$this->empenhofolha} and o120_lota = {$this->lotacao}";
    } else {
      $sWhere = "o120_rhempenhofolha = {$this->empenhofolha}";
    }

    $sSqlReservasGeradas = $oDaoOrcReservaRhEmpenhoFolha->sql_query_file(null, "*", null, $sWhere);
    $rsReservasGeradas   = $oDaoOrcReservaRhEmpenhoFolha->sql_record($sSqlReservasGeradas);
    $oReservasGeradas    = db_utils::getCollectionByRecord($rsReservasGeradas);

    $oDaoOrcReservaRhEmpenhoFolha->excluir(null, $sWhere);
    if ($oDaoOrcReservaRhEmpenhoFolha->erro_status == "0") {
      throw new DBException("2. Erro ao excluir reservas de saldo  do Empenho {$this->empenhofolha}\nMensagem:\n{$oDaoOrcReservaRhEmpenhoFolha->erro_msg}");
    }

    if (count($oReservasGeradas) > 0) {

      foreach ($oReservasGeradas as $oReserva) {

        $oDaoOrcReserva->excluir($oReserva->o120_orcreserva);
        if ($oDaoOrcReserva->erro_status == "0") {
          throw new DBException("1. Erro ao excluir vinculo da reserva de saldo {$oReserva->o120_orcreserva}\nMensagem:\n{$oDaoOrcReserva->erro_msg}");
        }

      }

    }

    return true;

  }

  /**
   * Retorna as retencoes lancadas para o empenho da folha
   *
   */
  function getRetencoes($iTipoEmpenho = 1, $iPd = 2) {

    $sSqlDadosRetencao   = "SELECT rh72_sequencial, ";
    $sSqlDadosRetencao  .= "       rh72_coddot, ";
    $sSqlDadosRetencao  .= "       rh72_codele, ";
    $sSqlDadosRetencao  .= "       rh72_unidade, ";
    $sSqlDadosRetencao  .= "       rh72_orgao,     ";
    $sSqlDadosRetencao  .= "       rh72_projativ,  ";
    $sSqlDadosRetencao  .= "       rh72_programa,  ";
    $sSqlDadosRetencao  .= "       rh72_funcao,    ";
    $sSqlDadosRetencao  .= "       rh72_subfuncao, ";
    $sSqlDadosRetencao  .= "       rh72_anousu, ";
    $sSqlDadosRetencao  .= "       rh72_mesusu, ";
    $sSqlDadosRetencao  .= "       rh72_recurso, ";
    $sSqlDadosRetencao  .= "       rh72_siglaarq,";
    $sSqlDadosRetencao  .= "       rh78_retencaotiporec,";
    $sSqlDadosRetencao  .= "       round(sum(rh73_valor), 2) as valorretencao ";
    $sSqlDadosRetencao  .= "  from rhempenhofolha ";
    $sSqlDadosRetencao  .= "       inner join rhempenhofolharhemprubrica        on rh81_rhempenhofolha = rh72_sequencial ";
    $sSqlDadosRetencao  .= "       inner join rhempenhofolharubrica on rh73_sequencial     = rh81_rhempenhofolharubrica";
    $sSqlDadosRetencao  .= "       inner join rhpessoalmov          on rh73_seqpes                         = rh02_seqpes  ";
    $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh02_instit ";
    $sSqlDadosRetencao  .= "       inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial ";
    $sSqlDadosRetencao  .= "   where rh72_sequencial  = {$this->empenhofolha}  ";
    $sSqlDadosRetencao  .= "     and rh72_tipoempenho = {$iTipoEmpenho}";
    $sSqlDadosRetencao  .= "     and rh72_siglaarq    = '{$this->siglafolha}'";
    $sSqlDadosRetencao  .= "     and rh73_tiporubrica = 2";
    $sSqlDadosRetencao  .= "     and rh73_pd          = {$iPd}";
    $sSqlDadosRetencao  .= "   group by rh72_sequencial,  ";
    $sSqlDadosRetencao  .= "            rh72_coddot,  ";
    $sSqlDadosRetencao  .= "            rh72_codele, ";
    $sSqlDadosRetencao  .= "            rh72_unidade, ";
    $sSqlDadosRetencao  .= "            rh72_orgao, ";
    $sSqlDadosRetencao  .= "            rh72_projativ, ";
    $sSqlDadosRetencao  .= "            rh72_programa, ";
    $sSqlDadosRetencao  .= "            rh72_funcao, ";
    $sSqlDadosRetencao  .= "            rh72_subfuncao, ";
    $sSqlDadosRetencao  .= "            rh72_mesusu, ";
    $sSqlDadosRetencao  .= "            rh72_anousu, ";
    $sSqlDadosRetencao  .= "            rh72_recurso, ";
    $sSqlDadosRetencao  .= "            rh72_siglaarq, ";
    $sSqlDadosRetencao  .= "            rh78_retencaotiporec";
    $rsDadosEmpenho     = db_query($sSqlDadosRetencao);

    $aRetencoes         = db_utils::getCollectionByRecord($rsDadosEmpenho);
    return $aRetencoes;

  }

    /**
   * Retorna as retencoes lancadas para o empenho da folha na rubrica principal
   *
   */
  function getRetencoesRubricaPrincipal($sRetencoes,$iTipoEmpenho = 1, $iPd = 2) {
    if ($sRetencoes == null) {
      $sFiltroRetencao = "";
    } else {
      $sFiltroRetencao = " and rh78_retencaotiporec in ({$sRetencoes}) ";
    }

    if ($this->sCredor == null) {
      $filtroCredor = " rh02_lota = {$this->lotacao} ";
    } else {
      $filtroCredor = " rh73_seqpes = {$this->sCredor} ";
    }
    $sSqlDadosRetencao   = "SELECT  ";
    $sSqlDadosRetencao  .= "       rh72_anousu, ";
    $sSqlDadosRetencao  .= "       rh72_mesusu, ";
    $sSqlDadosRetencao  .= "       rh78_retencaotiporec,";
    $sSqlDadosRetencao  .= "       round(sum(rh73_valor), 2) as valorretencao ";
    $sSqlDadosRetencao  .= "  from rhempenhofolha ";
    $sSqlDadosRetencao  .= "       inner join rhempenhofolharhemprubrica        on rh81_rhempenhofolha = rh72_sequencial ";
    $sSqlDadosRetencao  .= "       inner join rhempenhofolharubrica on rh73_sequencial     = rh81_rhempenhofolharubrica";
    $sSqlDadosRetencao  .= "       inner join rhpessoalmov          on rh73_seqpes                         = rh02_seqpes  ";
    $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh02_instit ";
    $sSqlDadosRetencao  .= "       inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial ";
    $sSqlDadosRetencao  .= "   where {$filtroCredor}  ";
    $sSqlDadosRetencao  .= "     {$sFiltroRetencao}";
    $sSqlDadosRetencao  .= "     and rh72_tipoempenho = {$iTipoEmpenho}";
    $sSqlDadosRetencao  .= "     and rh73_tiporubrica = 2";
    $sSqlDadosRetencao  .= "     and rh73_pd          = {$iPd}";
    $sSqlDadosRetencao  .= "     and rh72_siglaarq    = '{$this->siglafolha}'";
    $sSqlDadosRetencao  .= "     and rh72_anousu = {$this->ano} ";
    $sSqlDadosRetencao  .= "     and rh72_mesusu = {$this->mes} ";
    $sSqlDadosRetencao  .= "   group by   ";
    $sSqlDadosRetencao  .= "            rh72_mesusu, ";
    $sSqlDadosRetencao  .= "            rh72_anousu, ";
    $sSqlDadosRetencao  .= "            rh78_retencaotiporec";
    $rsDadosEmpenho     = db_query($sSqlDadosRetencao);
    $aRetencoes         = db_utils::getCollectionByRecord($rsDadosEmpenho);

    return $aRetencoes;

  }
  /**
   * Gera o empenho para esse empenho da folha
   */
  function gerarEmpenho($iNumCgm, $lPrevidencia = false) {


    /**
     * Validamos o valor do empenho,.
     * caso o valor das retencoes seje maior que o valor do emepnho, cancelamos o procedimento
     */
    if ($this->getValorRetencao() > $this->valorempenho) {

      $sMessage  = "N�o foi poss�vel lan�ar empenho da folha ({$this->empenhofolha}).\n";
      $sMessage .= "Valor da reten��o maior que o valor do Empenho.\nContate Suporte.";

      throw new BusinessException($sMessage);
    }

    /**
     * Validamos o valor do empenho,.
     * caso o valor n�o possua valor, devemos cancelar a gera��o desse empenho. (definido em conversa com o paulo
     * , Robson e iuri em 29/09/2011
     */
    $nValorEmpenho = round($this->valorempenho, 2);
    if ($nValorEmpenho <= 0) {

    	/**
    	 * Cancela o saldo da Reserva para o empenho da folha
    	 */
    	$oDaoReserva             = db_utils::getDao("orcreserva");
    	$oDaoReservaSaldoEmpenho = db_utils::getDao("orcreservarhempenhofolha");
      if ($this->lotacao != null) {
        $sWhere = "o120_rhempenhofolha = {$this->empenhofolha} and o120_lota = {$this->lotacao}";
      } else {
        $sWhere = "o120_rhempenhofolha = {$this->empenhofolha}";
      }
    	$sSqlDadosReserva        = $oDaoReservaSaldoEmpenho->sql_query_file(null, "o120_orcreserva", null, $sWhere);
    	$rsDadosEmpenho          = $oDaoReservaSaldoEmpenho->sql_record($sSqlDadosReserva);
    	if ($oDaoReservaSaldoEmpenho->numrows > 0) {

    		$oReserva = db_utils::fieldsMemory($rsDadosEmpenho, 0);
      	$oDaoReservaSaldoEmpenho->excluir(null, "o120_orcreserva = {$oReserva->o120_orcreserva}");
      	if ($oDaoReservaSaldoEmpenho->erro_status == 0) {
    			throw new DBException("Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReservaSaldoEmpenho->erro_msg}");
    		}

    		$oDaoReserva->excluir($oReserva->o120_orcreserva);
    		if ($oDaoReserva->erro_status == 0) {
    			throw new DBException("Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReserva->erro_msg}");
    		}

    	}
      return true;
    }

    /*
     * A rotina � composta de 3 etapas, (gera a autorizacao de empenho, empenha e liquida sem oc o empenho)
     * so podemos gerar empenho se j� foi reservado o saldo para esse empenho da folha
     */
    if ($this->temReservaSaldo() && !$this->temempenho) {

      /**
       * Geramos a autorizacao de empenho
       */
      $oDaoEmpAutoriza                     = db_utils::getDao("empautoriza");
      $oDaoEmpAutoriza->e54_anousu         = db_getsession("DB_anousu");
      $oDaoEmpAutoriza->e54_valor          = $this->valorempenho;
      $oDaoEmpAutoriza->e54_concarpeculiar = $this->caracteristica;
      $oDaoEmpAutoriza->e54_codtipo        = 1;
      $oDaoEmpAutoriza->e54_codcom         = 7;
      $oDaoEmpAutoriza->e54_destin         = "";
      $oDaoEmpAutoriza->e54_tipol          = "" ;
      $oDaoEmpAutoriza->e54_numerl         = "";
      $oDaoEmpAutoriza->e54_emiss          = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoEmpAutoriza->e54_instit         = db_getsession("DB_instit");
      $oDaoEmpAutoriza->e54_depto          = db_getsession("DB_coddepto");
      $oDaoEmpAutoriza->e54_praent         = '';
      $oDaoEmpAutoriza->e54_entpar         = '';
      $oDaoEmpAutoriza->e54_conpag         = '';
      $oDaoEmpAutoriza->e54_codout         = '';
      $oDaoEmpAutoriza->e54_contat         = '';
      $oDaoEmpAutoriza->e54_telef          = '';
      $oDaoEmpAutoriza->e54_numsol         = '';
      $oDaoEmpAutoriza->e54_resumo         = $this->getResumo();
      $oDaoEmpAutoriza->e54_numcgm         = $iNumCgm;
      $oDaoEmpAutoriza->e54_login          = db_getsession("DB_id_usuario");
      $oDaoEmpAutoriza->e54_anulad         = null;
      $oDaoEmpAutoriza->e54_datainclusao = date('Y-m-d H:i:s.v', db_getsession("DB_datausu"));
      $oDaoEmpAutoriza->incluir(null);
      if ($oDaoEmpAutoriza->erro_status == 0) {
        throw new DBException("N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n{$oDaoEmpAutoriza->erro_msg}");
      }
      /**
       * Incluimos o item da autoriza��o
       */
      $oDaorhItemElemento = db_utils::getDao("rhelementoemppcmater");
      $sSqlItem           = $oDaorhItemElemento->sql_query(null,
                                                           "pc01_codmater",
                                                           null,
                                                           "rh38_codele     = {$this->elemento}
                                                            and rh38_anousu = {$this->ano}"
                                                           );
      $rsItem             = $oDaorhItemElemento->sql_record($sSqlItem);
      if ($oDaorhItemElemento->numrows == 0) {

        $sErroMsg  = "N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Elemento {$this->elemento} sem item vinculado";
        throw new DBException($sErroMsg);

      }
      $iItemEmpenho = db_utils::fieldsMemory($rsItem, 0)->pc01_codmater;
      /**
       * Incluimos o item na empautitem
       */
      $oDaoAutorizacaoItem             = db_utils::getDao("empautitem");
      $oDaoAutorizacaoItem->e55_autori = $oDaoEmpAutoriza->e54_autori;
      $oDaoAutorizacaoItem->e55_item   = $iItemEmpenho;
      $oDaoAutorizacaoItem->e55_sequen = 1;
      $oDaoAutorizacaoItem->e55_quant  = 1;
	    $oDaoAutorizacaoItem->e55_vltot  = $this->valorempenho;
  	  $oDaoAutorizacaoItem->e55_vlrun  = $this->valorempenho;
  	  $oDaoAutorizacaoItem->e55_descr  = $oDaoEmpAutoriza->e54_resumo;
	    $oDaoAutorizacaoItem->e55_codele = $this->elemento;
      $oDaoAutorizacaoItem->e55_unid   = '0';
	    $oDaoAutorizacaoItem->incluir($oDaoEmpAutoriza->e54_autori, 1);
	    if ($oDaoAutorizacaoItem->erro_status == 0) {

	      $sErroMsg  = "N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Imposs�vel incluir item da autoriza��o \n {$oDaoAutorizacaoItem->erro_msg}";
        throw new DBException($sErroMsg);

	    }

	  /**
	   * Incluimos a dotacao para a autorizacao
	   * cancelamos a reserva de saldo para o empenho da folha e incluimos a reserva para a autorizacao
	   */
	  $oDaoAutorizaDotacao      = db_utils::getDao("empautidot");
	  $oDaoAutorizaDotacao->e56_anousu     = $this->ano;
	  $oDaoAutorizaDotacao->e56_autori     = $oDaoEmpAutoriza->e54_autori;
	  $oDaoAutorizaDotacao->e56_coddot     = $this->dotacao;
	  $oDaoAutorizaDotacao->e56_orctiporec = null;
	  $oDaoAutorizaDotacao->incluir($oDaoEmpAutoriza->e54_autori);
	  if ($oDaoAutorizaDotacao->erro_status == 0) {

	    $sErroMsg  = "N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Imposs�vel definir dotacao para a autorizacao.\n{$oDaoAutorizaDotacao->erro_msg}" ;
        throw new DBException($sErroMsg);

	  }
	  $oDaoReservaSaldoEmpenho = db_utils::getDao("orcreservarhempenhofolha");
	  $oDaoReservaSaldoEmpenho->excluir(null, "o120_orcreserva = {$this->reservasaldo}");
	  if ($oDaoReservaSaldoEmpenho->erro_status == 0) {

	    $sErroMsg  = "N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Imposs�vel cancelar saldo do empenho da folha" ;
        throw new DBException($sErroMsg);

	  }
    $oDaoReservaAut = db_utils::getDao("orcreservaaut");
    $oDaoReservaAut->o83_autori  = $oDaoEmpAutoriza->e54_autori;
	  $oDaoReservaAut->incluir($this->reservasaldo);
	  if ($oDaoReservaAut->erro_status == 0) {

	    $sErroMsg  = "N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Imposs�vel reservar saldo do empenho da folha.\n{$oDaoReservaAut->erro_msg}" ;
        throw new DBException($sErroMsg);

	  }

	  $iCodigoDocumento = 1;


	  /**
	   * 2 passo:
	   * Incluimos o empenho , e fazemos o lancamentos de empenho
	   */
	  /**
	   * Verificamos se existe saldo para fazer esse empenho
	   */
      $sSqlSaldo   = "select fc_verifica_lancamento({$oDaoEmpAutoriza->e54_autori},
                                                    '".date("Y-m-d",db_getsession("DB_datausu"))."',
                                                    $iCodigoDocumento, 00.00) as retorno";
      $rsSaldo  = db_query($sSqlSaldo);
      $sRetorno = db_utils::fieldsMemory($rsSaldo, 0)->retorno;
      if(substr($sRetorno,0,2) > 0 ) {

         $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
         $sErroMsg .= substr($sRetorno,3);
         throw new DBException($sErroMsg);
      }
      /**
       * Cancelammos a reserva de saldo da autorizacao
       */
      $oDaoReservaAut->excluir(null, "o83_codres = {$this->reservasaldo}");
      $oDaoReserva = db_utils::getDao("orcreserva");
      $oDaoReserva->excluir($this->reservasaldo);
      /**
       * Pesquisamos o proximo numero de empenho
       */

      $oDaoEmpParamNum  = db_utils::getDao("empparamnum");
      $SqlNumeroEmpenho = $oDaoEmpParamNum->sql_query_file($this->ano,
                                                           db_getsession("DB_instit"),
                                                           " (e29_codemp + 1) as e60_codemp"
                                                          );
      $rsNumeroEmpenho = $oDaoEmpParamNum->sql_record($SqlNumeroEmpenho);
      /**
       * Pesquisamos na emparametro pelo n�mero do empenho
       */
      if ($oDaoEmpParamNum->numrows == 0) {

        $oDaoEmpParam  = db_utils::getDao("empparametro");
        $sSqlEmpParam = $oDaoEmpParam->sql_query_file($this->ano,"(e30_codemp+1) as e60_codemp,e30_notaliquidacao");
        $rsEmpParam   = $oDaoEmpParam->sql_record($sSqlEmpParam);
        if ($oDaoEmpParam->numrows > 0) {

          $oParam  = db_utils::fieldsmemory($rsEmpParam, 0);
          $iCodEmp = $oParam->e60_codemp;
          /**
           * Atualizamos o numero do empenho
           */
   	      $oDaoEmpParam->e39_anousu         = $this->ano;
	        $oDaoEmpParam->e30_codemp         = $oParam->e60_codemp;
	        $oDaoEmpParam->e30_notaliquidacao = $oParam->e30_notaliquidacao;
  	      $oDaoEmpParam->alterar($this->ano);
        }

      } else {

        $oParam = db_utils::fieldsmemory($rsNumeroEmpenho, 0);
        $iCodEmp = $oParam->e60_codemp;

        $oDaoEmpParamNum->e29_anousu = $this->ano;
        $oDaoEmpParamNum->e29_instit = db_getsession('DB_instit');
        $oDaoEmpParamNum->e29_codemp = $iCodEmp;
        $oDaoEmpParamNum->alterar($this->ano,db_getsession('DB_instit'));
        if ( $oDaoEmpParamNum->erro_status == 0) {

          $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
          $sErroMsg .= "Tabela de parametros por instituicao para o exercicio {$this->ano} nao criada!";
          throw new DBException($sErroMsg);

        }
      }

     $rsDotacao     = db_dotacaosaldo(8,2,2,"true","o58_coddot={$this->dotacao}" ,db_getsession("DB_anousu")) ;
	   $oSaldoDotacao = db_utils::fieldsmemory($rsDotacao, 0);
	   $oDaoEmpenho   = db_utils::getDao("empempenho");
	   if (empty($oSaldoDotacao->dot_ini)) {
	    $oSaldoDotacao->dot_ini = 1;
	   }

    $oCodigoAcompanhamento  = new ControleOrcamentario();
    $oCodigoAcompanhamento->setTipoDespesa(0);
    $oCodigoAcompanhamento->setFonte($oSaldoDotacao->o58_codigo);
    $oCodigoAcompanhamento->setDeParaFonteCompleta();
    $e60_codco = $oCodigoAcompanhamento->getCodigoParaEmpenho();

    $buscarOrdenadores = buscarParamentosOrdenadores();

    $buscarOrdenadoresliquidacao = buscarParamentosOrdenadoresLiquidacao();

    if ($buscarOrdenadores == 1 || $buscarOrdenadores == 3 ) {

      $aOrdenadoresdespesas   = getOrdenadoresDespesa($this->orgao,$this->unidade);
      $oDaoEmpenho->e60_numcgmordenador = $aOrdenadoresdespesas['cgmordenadordespesa'];

      if (!$oDaoEmpenho->e60_numcgmordenador) {
        $sErroMsg  = "Ordenador da despesa n�o encontrado para o org�o $this->orgao unidade $this->unidade";
        throw new DBException($sErroMsg);
      }

    } elseif ($buscarOrdenadores == 2) {

      $aAssinantesDespesas  = getAssinantes($this->orgao,$this->unidade,1,0);
      $aOrdenadoresdespesas = getAllOrdenadores($aAssinantesDespesas);
      $oDaoEmpenho->e60_numcgmordenador = $aOrdenadoresdespesas->z01_numcgm;
      if (!$aOrdenadoresdespesas->z01_numcgm) {
        $sErroMsg  = "Ordenador da despesa n�o encontrado para o org�o $this->orgao unidade $this->unidade";
        throw new DBException($sErroMsg);
      }
    }

    if ($buscarOrdenadoresliquidacao == 1 || $buscarOrdenadoresliquidacao == 3 ) {

      $aOrdenadoresliquidacao = getOrdenadoresLiquidacao($this->orgao,$this->unidade);
      $cgmordenadorliquidacao = $aOrdenadoresliquidacao['cgmordenadorliquidacao'];

      if (!$cgmordenadorliquidacao) {
        $sErroMsg  = "Ordenador da liquida��o n�o encontrado para o org�o $this->orgao unidade $this->unidade";
        throw new DBException($sErroMsg);
      }

    } elseif ($buscarOrdenadoresliquidacao == 2) {

      $aAssinantesLiquidacao  = getAssinantes($this->orgao,$this->unidade,2,1);
      $aOrdenadoresliquidacao = getAllOrdenadores($aAssinantesLiquidacao);
      $cgmordenadorliquidacao = $aOrdenadoresliquidacao->z01_numcgm;

      if (!$aOrdenadoresliquidacao->z01_numcgm) {
        $sErroMsg  = "Ordenador da liquida��o n�o encontrado para o org�o $this->orgao unidade $this->unidade";
        throw new DBException($sErroMsg);
      }
    }

	  $oDaoEmpenho->e60_coddot         = $this->dotacao;
	  $oDaoEmpenho->e60_anousu         = $this->ano;
	  $oDaoEmpenho->e60_codcom         = 7;
	  $oDaoEmpenho->e60_codemp         = "$iCodEmp";
	  $oDaoEmpenho->e60_codtipo        = $oDaoEmpAutoriza->e54_codtipo;
	  $oDaoEmpenho->e60_concarpeculiar = "{$this->caracteristica}";
	  $oDaoEmpenho->e60_destin         = "";
	  $oDaoEmpenho->e60_emiss          = date("Y-m-d", db_getsession("DB_datausu"));
	  $oDaoEmpenho->e60_vencim         = date("Y-m-d",db_getsession("DB_datausu"));
	  $oDaoEmpenho->e60_instit         = db_getsession("DB_instit");
	  $oDaoEmpenho->e60_numcgm         = $oDaoEmpAutoriza->e54_numcgm;
	  $oDaoEmpenho->e60_numerol        = "";
	  $oDaoEmpenho->e60_resumo         = $oDaoEmpAutoriza->e54_resumo;
	  $oDaoEmpenho->e60_vlrorc         = "$oSaldoDotacao->dot_ini";
	  $oDaoEmpenho->e60_salant         = "$oSaldoDotacao->atual";
	  $oDaoEmpenho->e60_tipol          = "";
	  $oDaoEmpenho->e60_vlremp         = $this->valorempenho;
	  $oDaoEmpenho->e60_vlrliq         = "0";
	  $oDaoEmpenho->e60_vlrpag         = "0";
	  $oDaoEmpenho->e60_vlranu         = "0";
	  $oDaoEmpenho->e60_id_usuario     = db_getsession("DB_id_usuario");
	  $oDaoEmpenho->e60_tipodespesa    = "0";
    $oDaoEmpenho->e60_codco          = $e60_codco;
	  $oDaoEmpenho->incluir(null);
	  if ($oDaoEmpenho->erro_status == 0) {

	    $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
      $sErroMsg .= "Erro ao incluir empenho \n{$oDaoEmpenho->erro_msg}";
      throw new DBException($sErroMsg);
	  }

      $this->setNumeroEmpenhoFinanceiro($oDaoEmpenho->e60_numemp);

	  /**
	   * Incluimos o elemento do empenho
	   */
	  $oDaoEmpElemento = db_utils::getDao("empelemento");
	  $oDaoEmpElemento->e64_codele = $this->elemento;
	  $oDaoEmpElemento->e64_numemp = $oDaoEmpenho->e60_numemp;
	  $oDaoEmpElemento->e64_vlremp = $oDaoEmpenho->e60_vlremp;
	  $oDaoEmpElemento->e64_vlrliq = "0";
	  $oDaoEmpElemento->e64_vlrpag = "0";
	  $oDaoEmpElemento->e64_vlranu = "0";
	  $oDaoEmpElemento->incluir($oDaoEmpenho->e60_numemp, $this->elemento);
	  if ($oDaoEmpElemento->erro_status == 0) {

	    $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Erro ao incluir elemento empenho \n{$oDaoEmpElemento->erro_msg}";
        throw new DBException($sErroMsg);

	  }

	  /**
	   * incluimos o item do empenho
	   */
	  $oDaoEmpempItem             = db_utils::getDao("empempitem");
    $oDaoEmpempItem->e62_numemp = $oDaoEmpenho->e60_numemp ;
	  $oDaoEmpempItem->e62_item   = $oDaoAutorizacaoItem->e55_item   ;
	  $oDaoEmpempItem->e62_sequen = $oDaoAutorizacaoItem->e55_sequen ;
	  $oDaoEmpempItem->e62_quant  = $oDaoAutorizacaoItem->e55_quant ;
	  $oDaoEmpempItem->e62_vltot  = $oDaoAutorizacaoItem->e55_vltot ;
	  $oDaoEmpempItem->e62_vlrun  = $oDaoAutorizacaoItem->e55_vlrun ;
	  $e55_descr                  = AddSlashes($oDaoAutorizacaoItem->e55_descr);
	  $oDaoEmpempItem->e62_descr  = $oDaoAutorizacaoItem->e55_descr;
	  $oDaoEmpempItem->e62_codele = $oDaoAutorizacaoItem->e55_codele;
	  $oDaoEmpempItem->incluir($oDaoEmpenho->e60_numemp, $oDaoAutorizacaoItem->e55_sequen);
	  if ($oDaoEmpempItem->erro_status == 0 ){

	    $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Erro ao incluir item do empenho \n{$oDaoEmpempItem->erro_msg}";
        throw new DBException($sErroMsg);

	  }
	  /**
	   * Incluimos a ligacao do empenho com a autorizacao
	   */
	  $oDaoEmpAut             = db_utils::getDao("empempaut");
    $oDaoEmpAut->e61_numemp = $oDaoEmpenho->e60_numemp;
    $oDaoEmpAut->e61_autori = $oDaoEmpAutoriza->e54_autori;
    $oDaoEmpAut->incluir($oDaoEmpenho->e60_numemp);
    if ($oDaoEmpAut->erro_status == 0) {

      $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
      $sErroMsg .= "Erro ao incluir vincular empenho com a autorizacao \n{$oDaoEmpAut->erro_msg}";
      throw new DBException($sErroMsg);

    }

      /**
       * Vinculamos o empenho gerado ao empenho da folha
       */
      $oDaoRHempenhoFolha                      = db_utils::getDao("rhempenhofolhaempenho");
      $oDaoRHempenhoFolha->rh76_numemp         = $oDaoEmpenho->e60_numemp;
      $oDaoRHempenhoFolha->rh76_rhempenhofolha = $this->empenhofolha;
      $oDaoRHempenhoFolha->rh76_lota = $this->lotacao;
      $oDaoRHempenhoFolha->incluir(null);
      if ($oDaoRHempenhoFolha->erro_status == 0) {

        $sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Erro ao incluir vincular empenho com empenho da folha \n{$oDaoRHempenhoFolha->erro_msg}";
        throw new DBException($sErroMsg);

      }

      $oDaoEmpenhoNl = db_utils::getDao("empempenhonl");
      $oDaoEmpenhoNl->e68_numemp = $oDaoEmpenho->e60_numemp;
      $oDaoEmpenhoNl->e68_data   = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoEmpenhoNl->incluir(null);

      /**
       * Reservamos o saldo da dotacao
       */
      $sSqlFCDotacao       = "select fc_lancam_dotacao({$this->dotacao},";
      $sSqlFCDotacao      .= "                         '".date("Y-m-d", db_getsession("DB_datausu"))."',";
      $sSqlFCDotacao      .= "                         {$iCodigoDocumento},";
      $sSqlFCDotacao      .= "                         '{$this->valorempenho}') as dotacao";
      $rsRetornoFCDotacao  = db_query($sSqlFCDotacao);
      $sRetornoFCDotacao   = db_utils::fieldsMemory($rsRetornoFCDotacao, 0)->dotacao;
      if (substr($sRetornoFCDotacao, 0, 1) == 0 ) {

		$sErroMsg  = "N�o foi possivel gerar empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "Erro na atualiza��o do or�amento \n ".substr($sRetornoFCDotacao, 1);
        throw new DBException($sErroMsg);

	  }

	  /**
	   * Validamos a procedencia do empenho para lancar o documento contabil
	   */
	  if (USE_PCASP) {

	    $oEmpenhoFinanceiro       = new EmpenhoFinanceiro($oDaoEmpenho->e60_numemp);
  	  $isProvisaoFerias         = $oEmpenhoFinanceiro->isProvisaoFerias();
  	  $isProvisaoDecimoTerceiro = $oEmpenhoFinanceiro->isProvisaoDecimoTerceiro();

  	  if ($isProvisaoFerias) {
  	    $iCodigoDocumento = 304;
  	  }

  	  if ($isProvisaoDecimoTerceiro) {
  	    $iCodigoDocumento = 308;
  	  }
	  }
	  /**
	   * Efetuados o lancamento de empenho
	   */
	  require_once("classes/lancamentoContabil.model.php");
	  $oLancamentoContabil = new lancamentoContabil($iCodigoDocumento,
	                                                $this->ano,
	                                                date("Y-m-d", db_getsession("DB_datausu")),
	                                                $this->valorempenho);
     $oLancamentoContabil->setCgm($oDaoEmpenho->e60_numcgm);
     $oLancamentoContabil->setEmpenho($oDaoEmpenho->e60_numemp, $this->ano, $oDaoEmpenho->e60_codcom);
     $oLancamentoContabil->setComplemento($oDaoEmpenho->e60_resumo);
     $oLancamentoContabil->setElemento($this->elemento);
     $oLancamentoContabil->setDotacao($this->dotacao);
     $oLancamentoContabil->setEmpenho($oDaoEmpenho->e60_numemp, $this->ano, $oDaoEmpenho->e60_codcom);
     $oLancamentoContabil->salvar();

     /**
      * 3 passo :
      * Liquidamos o empenho
      */
     require_once("classes/empenho.php");
     $oEmpenho = new empenho();

     $oEmpenho->setEmpenho($oDaoEmpenho->e60_numemp);
     $oItemNota             = new stdClass();
     $oItemNota->sequen     = 1;
     $oItemNota->quantidade = 1;
     $oItemNota->vlrtot     = $this->valorempenho;
     $oItemNota->vlruni     = $this->valorempenho;
     $aItens[]              = $oItemNota;
     require_once("classes/db_pagordemnota_classe.php");
     require_once("classes/db_pagordem_classe.php");
     require_once("classes/db_pagordemele_classe.php");
     $oRetorno = $oEmpenho->gerarOrdemCompra(
                                             's/n',
                                             $this->valorempenho,
                                             $aItens,
                                             true,
                                             date("d/m/Y", db_getsession("DB_datausu")),
                                             $oDaoEmpenho->e60_resumo,
                                             false,
                                             null,
                                             null,
                                             null,
                                             null,
                                             '',
                                             null,
                                             null,
                                             0,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             null,
                                             $cgmordenadorliquidacao
                                           );
      require_once("libs/JSON.php");
      $oJson               = new Services_JSON();
      $oRetornoLiquidacao  = $oJson->decode($oRetorno);
      if ($oRetornoLiquidacao->erro == 2) {

        $sErroMsg  = "N�o foi possivel liquidar empenho para empenho da folha ({$this->empenhofolha})\n";
        $sErroMsg .= "N�mero do elemento ({$this->elemento})\n";
        $sErroMsg .= urldecode($oRetornoLiquidacao->mensagem);
        throw new DBException($sErroMsg);
      }

      /*
       * Verificamos se o par�metro rh11_geraretencaoempenho � true ou false
       * true  : ser�o geradas as reten��es para o empenho
       * false : ser�o geradas as reten��es em forma de planilha em outra rotina
       */
      $oDaoCfpess    = db_utils::getDao("cfpess");
      $sSqlParam     = $oDaoCfpess->sql_query_file(
      		                                         $this->anoFolha,
      		                                         $this->mes,
      		                                         db_getsession("DB_instit"),
      		                                         "r11_geraretencaoempenho"
      		                                        );
      $rsParam       = $oDaoCfpess->sql_record($sSqlParam);
      $lGeraRetencao = db_utils::fieldsMemory($rsParam,0)->r11_geraretencaoempenho;
      if ( $lGeraRetencao == 't') {


        if ($lPrevidencia) {
            $aRetencoes =  $this->getRetencoes(2, 1);
        } else {
          if ($this->lRubrica == null) {
            $aRetencoes =  $this->getRetencoes();
          } elseif ($this->lRubrica == 'true' || ($this->lRubrica == 'false' && $this->sRetencoes != null)){
            $aRetencoes =  $this->getRetencoesRubricaPrincipal($this->sRetencoes);
          }
        }

        require_once("model/retencaoNota.model.php");
        $oRetencaoNota = new retencaoNota($oRetornoLiquidacao->iCodNota);
        $oRetencaoNota->setINotaLiquidacao($oRetornoLiquidacao->e50_codord);
        $oRetencaoNota->setCodigoMovimento($oRetornoLiquidacao->iCodMov);
        $oRetencaoNota->setInSession(false);
        foreach ($aRetencoes as $oRetencaoEmpenho) {

          $oRetencao = new stdClass();
          $oRetencao->iCodigoRetencao = $oRetencaoEmpenho->rh78_retencaotiporec;
          $oRetencao->nValorRetencao  = $oRetencaoEmpenho->valorretencao;
          $oRetencao->nValorDeducao   = "0";
          $oRetencao->nValorNota      = $this->valorempenho;
          $oRetencao->nValorbase      = $this->valorempenho;
          $oRetencao->nAliquota       = 1;
          $oRetencao->aMovimentos     = array();
          try {
            $oRetencaoNota->addRetencao($oRetencao);
          } catch (ParameterException $eRetencao) {

            $sErroMsg = "Empenho folha {$this->empenhofolha}:\n";
            $sErroMsg .= $eRetencao->getMessage();
            throw new ParameterException($sErroMsg);

          }
          $sRetencoesTipoRec .= $virgula.$oRetencaoEmpenho->rh78_retencaotiporec;
          $virgula = ", ";
        }
        $oRetencaoNota->salvar($oRetornoLiquidacao->e50_codord);

      if ($sRetencoesTipoRec != "" && $this->lotacao != null) {
        $aRubricas = $this->consultaRubricas($sRetencoesTipoRec);
        $oDaoEmpenhoRubrica = db_utils::getDao("rhempenhofolharhemprubrica");
        if ($aRubricas) {
          foreach ($aRubricas as $oRubrica) {
            $oDaoEmpenhoRubrica->rh81_lancamento = $this->empenhofolha;
            $oDaoEmpenhoRubrica->rh81_lota = $this->lotacao;
            $oDaoEmpenhoRubrica->alterar($oRubrica->rh81_sequencial);

            if ($oDaoEmpenhoRubrica->erro_status == 0) {
              $sErroMsg = "Empenho folha {$this->empenhofolha}:\n";
              $sErroMsg .= "Erro: {$oDaoEmpenhoRubrica->erro_msg}";
              throw new DBException($sErroMsg);
            }
          }
        }
      }
}

      if (!empty($this->ordemauxiliar)) {

        $oDaoOrdemAuxiliar = db_utils::getDao("empagenotasordem");
        $oDaoOrdemAuxiliar->e43_autorizado     = "true";
        $oDaoOrdemAuxiliar->e43_empagemov      = $oRetornoLiquidacao->iCodMov;
        $oDaoOrdemAuxiliar->e43_ordempagamento = $this->ordemauxiliar;
        $oDaoOrdemAuxiliar->e43_valor          = $this->valorempenho;
        $oDaoOrdemAuxiliar->incluir(null);
        if ($oDaoOrdemAuxiliar->erro_status == 0) {

          $sErroMsg  = "N�o foi possivel gerar ordem de pagamento auxiliar para empenho da folha ({$this->empenhofolha})\n";
          $sErroMsg .= "Erro: {$oDaoOrdemAuxiliar->erro_msg}";
          throw new DBException($sErroMsg);

        }
      }
    } else {

      $sErroMsg  = "N�o foi possivel gerar Autoriza��o de empenho para empenho da folha ({$this->empenhofolha})\n";
      if ($this->temempenho) {
        $sErroMsg .= "Empenho j� gerado com o empenho n�mero({$this->numeroempenho}).";
      } else if (!$this->temReservaSaldo()){
        $sErroMsg .= "N�o existe saldo Reservado.";
      }
      throw new BusinessException($sErroMsg);

    }
    return true;
  }

  /**
   * Define o rubricas do empenho gerado
   *
   * @param array $sRetencoesTipoRec
   */
  function consultaRubricas($sRetencoesTipoRec)
  {
    $sql = "select rh81_sequencial
            from rhempenhofolha
            inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial
            inner join rhempenhofolharubrica on rh73_sequencial = rh81_rhempenhofolharubrica
            inner join rhpessoalmov on rh73_seqpes  = rh02_seqpes and rh73_instit = rh02_instit
            inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial
            where
              rh02_lota = {$this->lotacao}
              and rh78_retencaotiporec in ({$sRetencoesTipoRec})
              and rh72_tipoempenho = 1
              and rh73_tiporubrica = 2
              and rh73_pd          = 2
              and rh72_siglaarq    = '{$this->siglafolha}'
              and rh72_anousu = {$this->ano}
              and rh72_mesusu = {$this->mes} ";
    $aTeste = db_utils::getColectionByRecord(db_query($sql));
    return $aTeste;
  }

  /**
   * Define o codigo da OP auxiliar
   *
   * @param integer $iOrdemAuxiliar
   */

  function setOPAuxiliar($iOrdemAuxiliar) {
    $this->ordemauxiliar = $iOrdemAuxiliar;
  }

  /**
   * @desc Estorna o empenho gerado
   * @abstract Estorna a liquidacao do empenho, Ordem de compra e anula o empenho gerado
   *
   * @return void
   */
  function estornarEmpenho() {


    /**
     * Verificamos se o empenho j� foi criado;
     * devemos verificar se o empenho j�  nao foi pago, a liquida��o estornada,
     * ou mesmo anulado esse empenho;
     */
    if ($this->temempenho) {

      require_once("classes/empenho.php");
      $oEmpenho  = new empenho();
      $oEmpenho->setEmpenho($this->numeroempenho);
      $oEmpenho->getDados($this->numeroempenho);
      /**
       * Verificamos o valor pago do empenho
       * caso j� estiver com algum valor pago, nao poderemos estornar o empenho
       */
      if ($oEmpenho->dadosEmpenho->e60_vlrpag > 0) {

        $sMsg = "Empenho da Folha ({$this->empenhofolha}), empenho ({$this->numeroempenho}) j� possui valor pago ";
        throw new BusinessException($sMsg);
      }
      /**
       * Verificamos se o empenho possui algum valor Anulado
       */
      if ($oEmpenho->dadosEmpenho->e60_vlranu > 0) {

        $sMsg = "Empenho da Folha ({$this->empenhofolha}), empenho ({$this->numeroempenho}) j� possui valor anulado ";
        throw new BusinessException($sMsg);

      }
      /**
       * Verificamos se o empenho teve algum valor de liquidacao estornado
       */

      if ($oEmpenho->dadosEmpenho->e60_vlrliq !=  $this->valorempenho) {

        $sMsg = "Empenho da Folha ({$this->empenhofolha}), empenho ({$this->numeroempenho}) teve sua liquidacao estornada";
        throw new BusinessException($sMsg);

      }

      /**
       * Estornamos a liquidac�o do Empenho
       */
      $rsNotas = $oEmpenho->getNotas($this->numeroempenho);
      $aNotas  = db_utils::getCollectionByRecord($rsNotas);
      require_once("libs/JSON.php");
      require_once("classes/db_pagordemnota_classe.php");
      require_once("classes/db_pagordem_classe.php");
      require_once("classes/db_pagordemele_classe.php");
      $oJson   = new Services_JSON();
      foreach ($aNotas as $oNota ) {

        $sRetorno = $oEmpenho->estornarLiquidacaoAJAX($this->numeroempenho , array($oNota->e69_codnota), null, false);
        $oRetornoEstornoNota = $oJson->decode($sRetorno);
        if ($oRetornoEstornoNota->erro == 2) {

          $sMsg  = "N�o foi poss�vel estornar liquidacao dp Empenho da Folha ({$this->empenhofolha}).\n";
          $sMsg .= "N�mero do elemento: {$this->getElemento()}.\n";
          $sMsg .= $oRetornoEstornoNota->mensagem;
          throw new BusinessException($sMsg);

        }
      }
      /**
       * Estornamos o empenho
       */
      $rsItensEmpenho      = $oEmpenho->getItensSaldo();
      $aItensEmpenho       = db_utils::getCollectionByRecord($rsItensEmpenho);
      $nValorTotalEmpenhos = 0;
      $aItensEmpenhoAnular = array();
      foreach ($aItensEmpenho as $oItem) {

        $oItemAnular  = new stdClass();
        $oItemAnular->e62_sequencial = $oItem->e62_sequencial;
        $oItemAnular->vlrtot         = round($oItem->saldovalor,2);
        $oItemAnular->quantidade     = $oItem->saldo;
        $nValorTotalEmpenhos        += round($oItem->saldovalor, 2);
        $aItensEmpenhoAnular[]       = $oItemAnular;

      }

      $oEmpenho->anularEmpenho($aItensEmpenhoAnular,
                                $oEmpenho->dadosEmpenho->e60_vlrliq,
                               "Estorno de pagamento empenho da folha n� {$this->empenhofolha}.",
                                null,
                                2,
                                false
                                );
      if ($oEmpenho->lSqlErro) {

        $sMsg  = "N�o foi poss�vel anular o Empenho da Folha ({$this->empenhofolha}).\n";
        $sMsg .= $oEmpenho->sErroMsg;
        throw new DBException($sMsg);
      }
    }
  }

  /**
   * Retorna as informa��es do empenho
   *
   */
  function getInfoEmpenho ($lRecisao = false, $lPrincipal = null) {

    $oDaoRhempenhoRubricas = db_utils::getDao("rhempenhofolharubrica");
    $sCampos               = "z01_nome, ";
    $sCampos              .= "rh72_sequencial, ";
    $sCampos              .= "rh73_seqpes, ";
    $sCampos              .= "rh72_orgao, ";
    $sCampos              .= "rh72_unidade, ";
    $sCampos              .= "rh72_projativ, ";
    $sCampos              .= "rh72_programa, ";
    $sCampos              .= "rh72_funcao, ";
    $sCampos              .= "rh72_subfuncao, ";
    $sCampos              .= "rh72_tabprev, ";
    $sCampos              .= "rh72_recurso, ";
    $sCampos              .= "rh72_codele, ";
    $sCampos              .= "rh72_concarpeculiar, ";
    $sCampos              .= "rh72_coddot, ";
    $sCampos              .= "sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end) as rh73_valor ";

    $sGroup                = "group by ";
    $sGroup              .= "z01_nome, ";
    $sGroup              .= "rh72_sequencial, ";
    $sGroup              .= "rh73_seqpes, ";
    $sGroup              .= "rh72_orgao, ";
    $sGroup              .= "rh72_unidade, ";
    $sGroup              .= "rh72_projativ, ";
    $sGroup              .= "rh72_programa, ";
    $sGroup              .= "rh72_funcao, ";
    $sGroup              .= "rh72_subfuncao, ";
    $sGroup              .= "rh72_tabprev, ";
    $sGroup              .= "rh72_recurso, ";
    $sGroup              .= "rh72_codele, ";
    $sGroup              .= "rh72_concarpeculiar, ";
    $sGroup              .= "rh72_coddot ";

    $sWhere                = "rh72_sequencial   = {$this->empenhofolha}";
    $sWhere               .= " and rh72_tipoempenho = {$this->tipoempenho}";
    if ($this->lRubrica == 'false') {
      $sWhere  .= " and rh73_pd = 1 ";
    }
    if ($this->lotacao != null) {
      $sWhere             .= " and rh02_lota = {$this->lotacao}";
    }

    $sSqlDadosEmpenho      = $oDaoRhempenhoRubricas->sql_query_pessoal(null,
                                                                       $sCampos,
                                                                       "z01_nome",
                                                                       $sWhere." ".$sGroup
                                                                     );

    $rsDadosEmpenho       = $oDaoRhempenhoRubricas->sql_record($sSqlDadosEmpenho);
    $aDadosEmpenhos       = db_utils::getCollectionByRecord($rsDadosEmpenho, false, false, true);
    $iTotalEmpenhos       = count($aDadosEmpenhos);
    for ($iEmpenho = 0; $iEmpenho < $iTotalEmpenhos; $iEmpenho++) {

      $sSqlRubricas   = "SELECT rh27_rubric,";
      $sSqlRubricas  .= "       rh27_descr, ";
      $sSqlRubricas  .= "       rh73_valor, ";
      $sSqlRubricas  .= "       rh73_seqpes, ";
      $sSqlRubricas  .= "       rh73_pd ";
      $sSqlRubricas  .= "  from rhempenhofolharubrica ";
      $sSqlRubricas  .= "       inner join rhempenhofolharhemprubrica  on rh73_sequencial = rh81_rhempenhofolharubrica ";
      $sSqlRubricas  .= "       inner join rhrubricas on rh27_rubric = rh73_rubric ";
      $sSqlRubricas  .= "                            and rh73_instit = rh27_instit ";
      $sSqlRubricas  .= " where rh81_rhempenhofolha = {$this->empenhofolha} ";
      $sSqlRubricas  .= "   and rh73_seqpes         = {$aDadosEmpenhos[$iEmpenho]->rh73_seqpes}";
      if ($this->lRubrica == 'false' || $lRecisao == true) {
        $sSqlRubricas  .= " and rh73_pd = 1 and rh73_tiporubrica = 1";
      }
      $sSqlRubricas  .= " order by rh73_pd,rh27_rubric";

      $rsRubricas     = db_query($sSqlRubricas);
      $aRubricas      = db_utils::getCollectionByRecord($rsRubricas, false, false, true);
      if ($lRecisao == true && $lPrincipal == true) {

        $sSqlRubricasRetencoes  = "SELECT rh27_rubric,";
        $sSqlRubricasRetencoes .= "       rh27_descr, ";
        $sSqlRubricasRetencoes .= "       sum(rh73_valor) as rh73_valor, ";
        $sSqlRubricasRetencoes .= "       rh73_seqpes, ";
        $sSqlRubricasRetencoes .= "       rh73_pd ";
        $sSqlRubricasRetencoes .= "  from rhempenhofolharubrica ";
        $sSqlRubricasRetencoes .= "       inner join rhempenhofolharhemprubrica  on rh73_sequencial = rh81_rhempenhofolharubrica ";
        $sSqlRubricasRetencoes .= "       inner join rhrubricas on rh27_rubric = rh73_rubric ";
        $sSqlRubricasRetencoes .= "                            and rh73_instit = rh27_instit ";
        $sSqlRubricasRetencoes .= "     inner join rhempenhofolha on rh81_rhempenhofolha = rh72_sequencial ";
        $sSqlRubricasRetencoes .= "     where rh72_anousu = {$this->ano} and rh72_mesusu = {$this->mes}";
        $sSqlRubricasRetencoes .= "   and rh73_seqpes = {$aDadosEmpenhos[$iEmpenho]->rh73_seqpes}";
        $sSqlRubricasRetencoes .= " and rh73_pd = 2";
        $sSqlRubricasRetencoes .= " group by rh27_rubric, rh27_descr, rh73_seqpes, rh73_pd ";
        $sSqlRubricasRetencoes .= " order by rh73_pd,rh27_rubric";

        $rsRubricasRetencoes = db_query($sSqlRubricasRetencoes);
        $aRubricasRetencoes  = db_utils::getCollectionByRecord($rsRubricasRetencoes, false, false, true);
        $aRubricas = array_merge($aRubricas, $aRubricasRetencoes);
      }
      $aDadosEmpenhos[$iEmpenho]->rubricas = $aRubricas;

    }

    return  $aDadosEmpenhos;
  }

  /**
   * Alterar os dados do empenho da folha
   *
   * @param integer $iOrgao C�digo do orgao
   * @param integer $iUnidade C�digo da unidade
   * @param integer $iProjAtiv Projeto/atividade/A�ao
   * @param integer $iElemento Desdobramento
   * @param integer $iRecurso Recurso
   * @param integer $iDotacao C�digo Reduzido da dota��o
   */
  function alterarDados($iOrgao, $iUnidade, $iProjAtiv, $iElemento, $iRecurso, $iDotacao, $iCaract, $iSeqPes = '', $iPrograma = '', $iFuncao = '', $iSubFuncao = '', $iTabPrev = '' ) {

    $oDaoRhEmpenhoFolha        = db_utils::getDao("rhempenhofolha");
    $oDaoRHempenhoFolhaReserva = db_utils::getDao("orcreservarhempenhofolha");

    $sWhere             = " rh72_sequencial <> {$this->empenhofolha}";
    $sWhere            .= " and rh72_orgao          = {$iOrgao}";
    $sWhere            .= " and rh72_unidade        = {$iUnidade}";
    $sWhere            .= " and rh72_projativ       = {$iProjAtiv}";

    if (!empty($iPrograma)) {
      $sWhere            .= " and rh72_programa = {$iPrograma}";
    }

    if (!empty($iFuncao)) {
      $sWhere            .= " and rh72_funcao = {$iFuncao}";
    }

    if (!empty($iSubFuncao)) {
      $sWhere            .= " and rh72_subfuncao = {$iSubFuncao}";
    }

    if (!empty($iTabPrev)) {
      $sWhere            .= " and rh72_tabprev = {$iTabPrev}";
    }

    $sWhere .= " and rh72_codele         = {$iElemento}";
    $sWhere .= " and rh72_recurso        = {$iRecurso}";
    $sWhere .= " and rh72_coddot         = {$iDotacao}";
    $sWhere .= " and rh72_mesusu         = {$this->mes}";
    $sWhere .= " and rh72_anousu         = {$this->anoFolha}";
    $sWhere .= " and rh72_siglaarq       = '{$this->siglafolha}'";
    $sWhere .= " and rh72_tipoempenho    = {$this->tipoempenho}";
    $sWhere .= " and rh72_coddot         = {$iDotacao}";
    $sWhere .= " and rh72_concarpeculiar = '{$iCaract}'";

    /**
     * Caso esteja gerando os dados da complementar verifica se ja existe outro seqcompl igual para a mesma competencia,
     * se n�o existir ir� posteriormente gerar um novo empenho para o seqcompl desta complementar.
     */
    if ($this->siglafolha == 'r48') {
      $sWhere  .= " and rh72_seqcompl = (select rh72_seqcompl from rhempenhofolha where rh72_sequencial = {$this->empenhofolha})";
    }

    if ($iSeqPes != "") {

      $sWhere       .= " and rh73_seqpes = {$iSeqPes}";
      $sSqlEmpenho   = $oDaoRhEmpenhoFolha->sql_query_rubricas(null,"distinct rhempenhofolha.*", null, $sWhere);
    } else {
      $sSqlEmpenho   = $oDaoRhEmpenhoFolha->sql_query_file(null,"*", null, $sWhere);
    }

    $rsEmpenho          = $oDaoRhEmpenhoFolha->sql_record($sSqlEmpenho);
    if ($oDaoRhEmpenhoFolha->numrows  == 1) {

      /*
       * Caso existam empenhos com os mesmos dados deste empenho ap�s a altera��o
       * Exclu�mos este empenho e passamos todas as suas rubricas para o empenho j� existente
       * Do contr�rio, apenas alteramos os dados do empenho
       *
       * Verificamos se o empenho possui saldo reservado, se possuir, a reserva dever� ser cancelada. Isto quando o empenho
       * for exclu�do.
       */

      $oDaoRHempenhoFolhaReserva->sql_record($oDaoRHempenhoFolhaReserva->sql_query_file(null,
                                                                                        "o120_sequencial",
                                                                                        null,
                                                                                        "o120_rhempenhofolha = {$this->empenhofolha}"));
      if($oDaoRHempenhoFolhaReserva->numrows > 0) {

      }

      $oEmpenhoAdicionar = db_utils::fieldsMemory($rsEmpenho, 0);
      /**
       * Consultamos todos as rubricas do empenho antigo, e mudamos para o novo empenho
       */
      $oDaoRhEmpenhoFolhaRubricas = db_utils::getDao("rhempenhofolharhemprubrica");
      $sSqlEmpenhosRubrica        = $oDaoRhEmpenhoFolhaRubricas->sql_query(null,
                                                                           "rh73_rubric,rhempenhofolharhemprubrica.*",
                                                                           null,
                                                                           "rh81_rhempenhofolha = {$this->empenhofolha}"
                                                                          );
      $rsEmpenhoRubricas = $oDaoRhEmpenhoFolhaRubricas->sql_record($sSqlEmpenhosRubrica);

      $aRubricas         = db_utils::getCollectionByRecord($rsEmpenhoRubricas);
      foreach ($aRubricas as $oRubricaAlterar) {

        $oDaoRhEmpenhoFolhaRubricas->rh81_rhempenhofolha = $oEmpenhoAdicionar->rh72_sequencial;
        $oDaoRhEmpenhoFolhaRubricas->rh81_sequencial     = $oRubricaAlterar->rh81_sequencial;
        $oDaoRhEmpenhoFolhaRubricas->alterar($oRubricaAlterar->rh81_sequencial);
        if ($oDaoRhEmpenhoFolhaRubricas->erro_status == 0) {

          $sErroMsg  = "Erro ao Re-vincular Rubrica ({$oRubricaAlterar->rh73_rubric})s\n ";
          $sErroMsg .= "da Empenho da folha($this->empenhofolha).";
          $sErroMsg .= "Erro Retornado:\n {$oDaoRhEmpenhoFolhaRubricas->erro_msg}";
          throw new DBException($sErroMsg);

        }
      }

      $oDaoRhEmpenhoFolha->excluir($this->empenhofolha);
      if ($oDaoRhEmpenhoFolha->erro_status == 0) {
       throw new DBException("N�o foi poss�vel modificar Empenho\nErro:\n{$oDaoRhEmpenhoFolha->erro_msg}");
      }
    } else if ($oDaoRhEmpenhoFolha->numrows == 0) {

      /**
       * Devemos Modificar os Dados Do Empenho para as novas Informa��es
       */
      $oDaoRhEmpenhoFolha->rh72_orgao          = $iOrgao;
      $oDaoRhEmpenhoFolha->rh72_unidade        = $iUnidade;
      $oDaoRhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
      $oDaoRhEmpenhoFolha->rh72_programa       = ($iPrograma?$iPrograma:"null");
      $oDaoRhEmpenhoFolha->rh72_funcao         = ($iFuncao?$iFuncao:"null");
      $oDaoRhEmpenhoFolha->rh72_subfuncao      = ($iSubFuncao?$iSubFuncao:"null");
      $oDaoRhEmpenhoFolha->rh72_codele         = $iElemento;
      $oDaoRhEmpenhoFolha->rh72_recurso        = $iRecurso;
      $oDaoRhEmpenhoFolha->rh72_coddot         = $iDotacao;
      $oDaoRhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
      $oDaoRhEmpenhoFolha->rh72_sequencial     = $this->empenhofolha;
      $oDaoRhEmpenhoFolha->alterar($this->empenhofolha);

      if ($oDaoRhEmpenhoFolha->erro_status == 0) {
        throw new DBException("N�o foi poss�vel modificar Empenho\nErro:\n{$oDaoRhEmpenhoFolha->erro_msg}");
      }

    } else if ($oDaoRhEmpenhoFolha->numrows > 1) {

      $sMsgErro = "Foram Encontradas mais de um empenho com os dados informados. \n Contate Suporte.";
      throw new BusinessException($sMsgErro);

    }

  }

  /**
   * Altera os dados das rubricas geradas do empenho
   *
   * @param integer $iSeqPes C�digo da movimentacao da matricula
   * @param integer $iOrgao C�digo do orgao
   * @param integer $iUnidade C�digo da unidade
   * @param integer $iProjAtiv Projeto/atividade/A�ao
   * @param integer $iElemento Desdobramento
   * @param integer $iRecurso Recurso
   * @param integer $iDotacao C�digo Reduzido da dota��o
   */
  function alterarDadosRubricas($iSeqPes, $iOrgao, $iUnidade, $iProjAtiv, $iElemento, $iRecurso, $iDotacao,$iCaract, $iPrograma = '', $iFuncao = '', $iSubFuncao = '') {

    $oDaoRhEmpenhoFolha = db_utils::getDao("rhempenhofolha");

    $sWhere             = " rh72_orgao              = {$iOrgao}";
    $sWhere            .= " and rh72_unidade        = {$iUnidade}";
    $sWhere            .= " and rh72_projativ       = {$iProjAtiv}";

    if (!empty($iPrograma)) {
      $sWhere            .= " and rh72_programa = {$iPrograma}";
    }

    if (!empty($iFuncao)) {
      $sWhere            .= " and rh72_funcao = {$iFuncao}";
    }

    if (!empty($iSubFuncao)) {
      $sWhere            .= " and rh72_subfuncao = {$iSubFuncao}";
    }

    $sWhere            .= " and rh72_codele         = {$iElemento}";
    $sWhere            .= " and rh72_recurso        = {$iRecurso}";
    $sWhere            .= " and rh72_coddot         = {$iDotacao}";
    $sWhere            .= " and rh72_mesusu         = {$this->mes}";
    $sWhere            .= " and rh72_anousu         = {$this->anoFolha}";
    $sWhere            .= " and rh72_siglaarq       = '{$this->siglafolha}'";
    $sWhere            .= " and rh72_tipoempenho    = {$this->tipoempenho}";
    $sWhere            .= " and rh72_coddot         = {$iDotacao}";
    $sWhere            .= " and rh72_concarpeculiar = '{$iCaract}'";

    $sSqlEmpenho        = $oDaoRhEmpenhoFolha->sql_query_file(null,"*", null, $sWhere);
    $rsEmpenho          = $oDaoRhEmpenhoFolha->sql_record($sSqlEmpenho);
    if ($oDaoRhEmpenhoFolha->numrows  == 1) {

      $oEmpenhoAdicionar = db_utils::fieldsMemory($rsEmpenho, 0);
      $oDaoRhEmpenhoFolhaRubricas = db_utils::getDao("rhempenhofolharhemprubrica");
      $sSqlEmpenhosRubrica        = $oDaoRhEmpenhoFolhaRubricas->sql_query(null,
                                                                           "rh73_rubric,rh73_valor,rhempenhofolharhemprubrica.*",
                                                                           null,
                                                                           "rh81_rhempenhofolha = {$this->empenhofolha}
                                                                           and rh73_seqpes      = {$iSeqPes}"
                                                                           );

      $rsEmpenhoRubricas = $oDaoRhEmpenhoFolhaRubricas->sql_record($sSqlEmpenhosRubrica);
      $aRubricas         = db_utils::getCollectionByRecord($rsEmpenhoRubricas);
      foreach ($aRubricas as $oRubricaAlterar) {

        $oDaoRhEmpenhoFolhaRubricas->rh81_rhempenhofolha = $oEmpenhoAdicionar->rh72_sequencial;
        $oDaoRhEmpenhoFolhaRubricas->rh81_sequencial     = $oRubricaAlterar->rh81_sequencial;
        $oDaoRhEmpenhoFolhaRubricas->alterar($oRubricaAlterar->rh81_sequencial);
        if ($oDaoRhEmpenhoFolhaRubricas->erro_status == 0) {

          $sErroMsg  = "Erro ao Re-vincular Rubrica ({$oRubricaAlterar->rh73_rubric})s\n ";
          $sErroMsg .= "da Empenho da folha($this->empenhofolha).";
          $sErroMsg .= "Erro Retornado:\n {$oDaoRhEmpenhoFolhaRubricas->erro_msg}";
          throw new DBException($sErroMsg);

        }
      }
      /**
       * Verificamos se o empenho ainda possui alguma rubrica.
       * se nao possuir, excluimos
       */
      if (count($this->getInfoEmpenho()) == 0) {

        $oDaoRhEmpenhoFolha->excluir($this->empenhofolha);
        if ($oDaoRhEmpenhoFolha->erro_status == 0) {
         throw new DBException("N�o foi poss�vel modificar Empenho\nErro:\n{$oDaoRhEmpenhoFolha->erro_msg}");
        }

      }
    } else if ($oDaoRhEmpenhoFolha->numrows == 0) {

      /**
       * Incluimos um novo registro na tabela, e atualizamos as rubricas para esse novo
       * empenho
       */
      $oDaoRhEmpenhoFolha->rh72_mesusu         = $this->mes;
      $oDaoRhEmpenhoFolha->rh72_anousu         = $this->anoFolha;
      $oDaoRhEmpenhoFolha->rh72_siglaarq       = $this->siglafolha;
      $oDaoRhEmpenhoFolha->rh72_orgao          = $iOrgao;
      $oDaoRhEmpenhoFolha->rh72_unidade        = $iUnidade;
      $oDaoRhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
      $oDaoRhEmpenhoFolha->rh72_programa       = $iPrograma;
      $oDaoRhEmpenhoFolha->rh72_funcao         = $iFuncao;
      $oDaoRhEmpenhoFolha->rh72_sunfuncao      = $iSubFuncao;
      $oDaoRhEmpenhoFolha->rh72_codele         = $iElemento;
      $oDaoRhEmpenhoFolha->rh72_recurso        = $iRecurso;
      $oDaoRhEmpenhoFolha->rh72_coddot         = $iDotacao;
      $oDaoRhEmpenhoFolha->rh72_concarpeculiar = $iCaract;
      $oDaoRhEmpenhoFolha->rh72_tipoempenho    = $this->tipoempenho;
      $oDaoRhEmpenhoFolha->rh72_seqcompl       = "0";
      $oDaoRhEmpenhoFolha->rh72_tabprev        = $this->tabelaprevidencia;
      $oDaoRhEmpenhoFolha->incluir(null);
      if ($oDaoRhEmpenhoFolha->erro_status == 0) {
        throw new DBException("N�o foi poss�vel modificar Empenho\nErro:\n{$oDaoRhEmpenhoFolha->erro_msg}");
      }

      $oDaoRhEmpenhoFolhaRubricas = db_utils::getDao("rhempenhofolharhemprubrica");
      $sSqlEmpenhosRubrica        = $oDaoRhEmpenhoFolhaRubricas->sql_query(null,
                                                                           "rh73_rubric,rh73_valor,rhempenhofolharhemprubrica.*",
                                                                           null,
                                                                           "rh81_rhempenhofolha = {$this->empenhofolha}
                                                                           and rh73_seqpes      = {$iSeqPes}"
                                                                           );

      $rsEmpenhoRubricas = $oDaoRhEmpenhoFolhaRubricas->sql_record($sSqlEmpenhosRubrica);
      $aRubricas         = db_utils::getCollectionByRecord($rsEmpenhoRubricas);
      foreach ($aRubricas as $oRubricaAlterar) {

        $oDaoRhEmpenhoFolhaRubricas->rh81_rhempenhofolha = $oDaoRhEmpenhoFolha->rh72_sequencial;
        $oDaoRhEmpenhoFolhaRubricas->rh81_sequencial     = $oRubricaAlterar->rh81_sequencial;
        $oDaoRhEmpenhoFolhaRubricas->alterar($oRubricaAlterar->rh81_sequencial);
        if ($oDaoRhEmpenhoFolhaRubricas->erro_status == 0) {

          $sErroMsg  = "Erro ao Re-vincular Rubrica ({$oRubricaAlterar->rh73_rubric})s\n ";
          $sErroMsg .= "da Empenho da folha($this->empenhofolha).";
          $sErroMsg .= "Erro Retornado:\n {$oDaoRhEmpenhoFolhaRubricas->erro_msg}";
          throw new DBException($sErroMsg);

        }
      }

     /**
       * Verificamos se o empenho ainda possui alguma rubrica.
       * se nao possuir, excluimos
       */
      if (count($this->getInfoEmpenho()) == 0) {

        $oDaoRhEmpenhoFolha->excluir($this->empenhofolha);
        if ($oDaoRhEmpenhoFolha->erro_status == 0) {
         throw new DBException("N�o foi poss�vel modificar Empenho\nErro:\n{$oDaoRhEmpenhoFolha->erro_msg}");
        }
      }
    }
  }


  /**
   * Calcula o valor total de retencoes do empenho
   *
   * @return float valor total da retencao
   */
  function getValorRetencao() {

    $nValorDesconto = 0;
    $sWhereLota = "";
    $sSqlValorDesconto  = "select coalesce(sum(rh73_valor),0) as retencao ";
    $sSqlValorDesconto .= "  from rhempenhofolha ";
    $sSqlValorDesconto .= "       inner join rhempenhofolharhemprubrica on rh72_sequencial = rh81_rhempenhofolha ";
    $sSqlValorDesconto .= "       inner join rhempenhofolharubrica on rh81_rhempenhofolharubrica = rh73_sequencial ";
    if ( $this->lotacao != null) {
      $sSqlValorDesconto  .= "   inner join rhpessoalmov on rh73_seqpes  = rh02_seqpes  ";
      $sSqlValorDesconto  .= "                           and rh73_instit = rh02_instit ";
      $sWhereLota = " and rh02_lota = {$this->lotacao} ";
    }
    $sSqlValorDesconto .= " where rh73_tiporubrica = 2";
    $sSqlValorDesconto .= " and rh72_sequencial    = {$this->empenhofolha} {$sWhereLota}";
    $rsValorDesconto    = db_query($sSqlValorDesconto);
    if ($rsValorDesconto) {
       $nValorDesconto    = db_utils::fieldsMemory($rsValorDesconto, 0)->retencao;
    }
    return $nValorDesconto;
  }

  /**
   * @return string
   */
  function getResumo() {

    if ($this->tipoEmpenhoResumo == 2) {
        return "Contribui��o Patronal referente ao m�s {$this->mes}/{$this->anoFolha}";
    }

    if ($this->tipoEmpenhoResumo == 3) {
        return "FGTS referente ao m�s {$this->mes}/{$this->anoFolha}";
    }

    return "Referente ao pagamento de {$this->tipofolha} do m�s {$this->mes}/{$this->anoFolha}".($this->lotacao != null ? " da lota��o {$this->lotacao}." : ".");

  }

  /**
   * Atribui tipo do empenho para defini��o do resumo/hist�rico do empenho
   * @param $iTipo Tipo informado
   */
  function setTipoEmpenhoResumo($iTipo) {
    $this->tipoEmpenhoResumo = $iTipo;
  }

}
use Illuminate\Support\Facades\DB;
function getAssinantes($orgao, $unidade,$cargo,$documento)
{

  $anousu = db_getsession("DB_anousu");
  $instit = db_getsession('DB_instit');

  $aAssinante = DB::table('configuracoes.assinatura_digital_assinante')
              ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
              ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
              ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
              ->where('configuracoes.assinatura_digital_assinante.db243_instit', '=', $instit)
              ->where('configuracoes.assinatura_digital_assinante.db243_orgao', '=', $orgao)
              ->where('configuracoes.assinatura_digital_assinante.db243_unidade', '=', $unidade)
              ->where('configuracoes.assinatura_digital_assinante.db243_anousu', '=', $anousu)
              ->where('configuracoes.assinatura_digital_assinante.db243_cargo', '=', $cargo)
              ->where('configuracoes.assinatura_digital_assinante.db243_documento', '=', $documento)
              ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo', 'protocolo.cgm.z01_numcgm', 'configuracoes.assinatura_digital_assinante.db243_data_inicio', 'configuracoes.assinatura_digital_assinante.db243_data_final')
              ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
              ->get()
              ->toArray();

  return $aAssinante;
}

function getOrdenadoresDespesa($orgao, $unidade)
{
  $clorcunidade = new cl_orcunidade;
  $anousu = db_getsession("DB_anousu");
  $instit = db_getsession('DB_instit');
  $whereUnidades = " o41_anousu = {$anousu} and o41_instit = {$instit} and o41_orgao = {$orgao} and o41_unidade = {$unidade} ";
  $o41_cgmordenador =  db_utils::fieldsMemory($clorcunidade->sql_record($clorcunidade->sql_query(db_getsession("DB_anousu"),null,null, " o41_orddespesa ",null,$whereUnidades)), 0)->o41_orddespesa;
  return ['cgmordenadordespesa' => $o41_cgmordenador];
}

function getOrdenadoresLiquidacao($orgao, $unidade)
{
  $clorcunidade = new cl_orcunidade;
  $anousu = db_getsession("DB_anousu");
  $instit = db_getsession('DB_instit');
  $whereUnidades = " o41_anousu = {$anousu} and o41_instit = {$instit} and o41_orgao = {$orgao} and o41_unidade = {$unidade} ";
  $o41_cgmordenador =  db_utils::fieldsMemory($clorcunidade->sql_record($clorcunidade->sql_query(db_getsession("DB_anousu"),null,null, " o41_ordliquidacao ",null,$whereUnidades)), 0)->o41_ordliquidacao;

  return ['cgmordenadorliquidacao' => $o41_cgmordenador];
}

function buscarParamentosOrdenadores()
{
  $clempparametro    = new cl_empparametro;
  return db_utils::fieldsMemory($clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_buscarordenadores", null, "")), 0)->e30_buscarordenadores;
}

function buscarParamentosOrdenadoresLiquidacao()
{
  $clempparametro    = new cl_empparametro;
  return db_utils::fieldsMemory($clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_buscarordenadoresliqui", null, "")), 0)->e30_buscarordenadoresliqui;
}

function getAllOrdenadores($aAssinantes)
{

  foreach($aAssinantes as $assinante) {

      $db243_data_inicio = $assinante->db243_data_inicio;
      $db243_data_final  = $assinante->db243_data_final;

      $e60_emiss = date("Y-m-d", db_getsession("DB_datausu"));
      $dataemissformatada  = $e60_emiss;
      $datainicioformatada = date('Y-m-d\TH:i:s\Z', strtotime($db243_data_inicio));
      $datafimformatada    = date('Y-m-d\TH:i:s\Z', strtotime($db243_data_final));

      $datafinal = explode('-', $db243_data_final);
      $datafinal = $datafinal[2] . '/' . $datafinal[1] . '/' . $datafinal[0];

      $datainicial = explode('-', $db243_data_inicio);
      $datainicial = $datainicial[2] . '/' . $datainicial[1] . '/' . $datainicial[0];

      if (substr($dataemissformatada, 0, 10) < substr($datainicioformatada, 0, 10) || substr($dataemissformatada, 0, 10) > substr($datafimformatada, 0, 10)) {
        continue;
      }
      return $assinante;
  }

}
?>
