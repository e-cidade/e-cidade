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

/**
 * Dependencias
 */
db_app::import('arrecadacao.boletos.EmissaoBoleto');


/**
 * EmissaoBoletoWebService
 *
 * @uses EmissaoBoleto
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br>
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo        <renan@dbseller.com.br>
 */
class EmissaoBoletoWebService extends EmissaoBoleto{

  /**
   * Retorna o arquivo pdf com base64_decode
   */
  public function getArquivoPDF() {

    $sArquivoPDF = file_get_contents($this->sCaminhoPdf);
    return base64_encode($sArquivoPDF);
  }

  /**
   * Retorna os dados do boleto gerado
   *
   * @access public
   * @return StdClass
   */
  public function getDadosBoleto () {

    $oRetorno = new StdClass();

    $oRetorno->valor_corrigido = $this->getValorCorrigido();
    $oRetorno->valor_historico = $this->getValorHistorico();
    $oRetorno->codigo_barras   = $this->getCodigoBarras();
    $oRetorno->linha_digitavel = $this->getLinhaDigitavel();
    $oRetorno->juros_multa     = $this->getJuroMulta();
    $oRetorno->codigo_guia     = $this->getNumpreBoleto();
    $oRetorno->arquivo_guia    = $this->getArquivoPDF();
    $oRetorno->debitos_guia    = $this->getDebitos();

    return $oRetorno;
  }

  /**
   * Regerar boleto
   *
   * @param integer $iNumpreDebito
   * @access public
   * @return void
   */
  public function regerarBoleto($iNumpreDebito, $sData, $iNumparDebito, $nDesconto=0, $sCompetencia=null) {
    /**
     * If verificar numpre
     *
     * Esta fun��o foi alterada devido ao fato de que os parametros estavam na ordem errada ($iNumpreDebito, $iNumparDebito, $sData)
     * Dessa forma, o numpar era sempre a data do debito. Al�m disso, a numpar n�o era passado, dessa forma foi necess�rio
     * criar a estrutura de condi��o abaixo para povoar o numpar.
     *
     */
    $nDesconto = abs($nDesconto);

    if(empty($iNumparDebito)){
      $iNumparDebito = date("m",strtotime($sData)) - 1;
    }

    try {

      db_inicio_transacao();

      $oDadosRecibopagaboleto = db_utils::getDao('recibopagaboleto');
      $sSqlDadosDebito = $oDadosRecibopagaboleto->sql_queryDadosDebito($iNumpreDebito, $iNumparDebito);
      $rsDadosDebito   = $oDadosRecibopagaboleto->sql_record($sSqlDadosDebito);

      /**
       * Erro ao buscar informacoes do debito
       */
      if ( $oDadosRecibopagaboleto->erro_status == "0" ) {
        throw new Exception($oDadosRecibopagaboleto->erro_msg.$sSqlDadosDebito);
      }

      $oDebito = db_utils::fieldsMemory($rsDadosDebito, 0);

      /**
       * Gera recibo novo
       */

      $this->adicionarDebito($oDebito->numpre_debito, $oDebito->numpar_debito);
      $this->setInscricao($oDebito->inscricao);
      $this->setMatricula($oDebito->matricula);
      $this->setCodigoCgm($oDebito->cgm);
      $this->setDataVencimento(new DBDate($sData));
      $this->setForcaVencimento(true);
      $this->setModeloImpressao(21);

      /**
      ** Desconto Aliquota reduzida NFE
      **/
      $this->setDescontoAliqReduz(0);
      if($nDesconto>0 && !empty($sCompetencia)){
      		$sCompetencia 	=	explode("/",$sCompetencia);

	      	if(parent::verificaDescontoIss(  $sData, $sCompetencia[0], $sCompetencia[1]) ){
		          $this->setDescontoAliqReduz($nDesconto);
	      	}

      }


      $this->gerarRecibo();
      $this->imprimir();



      db_fim_transacao();

    } catch(Exception $oErro) {

      db_fim_transacao(true);
      throw new Exception($oErro->getMessage());
    }
  }



}
