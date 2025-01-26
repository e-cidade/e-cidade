<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *  junto com este programa; se n�o, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


require_once('std/DBLargeObject.php');

/**
 * Caminho das mensagens json do documento
 */
define('URL_MENSAGEM_PROCESSO_DOCUMENTO', 'patrimonial.licitacao.LicitacaoDocumento.');

/**
 * Model para documentos anexados ao processo do protocolo
 *
 * @package Protocolo
 * @version $Revision: 1.17 $
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class LicitacaoDocumento
{

  /**
   * Codigo do documento
   * - campo p01_sequencial
   *
   * @var int
   * @access private
   */
  private $iCodigo;

  /**
   * Processo do protocolo
   * - campo p01_protprocesso
   *
   * @var LicitacaoAnexo
   * @access private
   */
  private $oLicitacaoAnexo;


  /**
   * OID do documento anexado ao processo
   * - campo p01_documento
   *
   * @var int
   * @access private
   */
  private $iOid;


  /**
   * Tamanho limite do arquivo em bytes
   * - Limite 30mb
   *
   * @var int
   * @access private
   */
  private $iLimiteTamanho = 31457280;

  /**
   * Extens�es n�o permitidas para os documentos
   *
   * @var array
   * @access private
   */
  private $aExtensoesInvalidas = array('exe');

  /**
   * Caminho completo do arquivo
   * - Usado para salvar ou exportar do banco
   *
   * @var string
   * @access private
   */
  private $sCaminhoArquivo;

  private $sNomeArquivo;

  /**
   * Contrutor da classe, executa lazy load
   *
   * @param int $iCodigo
   * @access public
   * @return void
   */
  public function __construct($iCodigo = null)
  {

    /**
     * Documento nao inforamdo, contrutor nao fara nada
     */
    if (empty($iCodigo)) {
      return false;
    }

    $oDaoLicanexopncpdocumento = db_utils::getDao('licanexopncpdocumento');
    $dbwhere = "l216_sequencial = " . $iCodigo;
    $sSqlDocumento = $oDaoLicanexopncpdocumento->sql_query_file(null, "*", null, $dbwhere);

    $rsDocumento   = $oDaoLicanexopncpdocumento->sql_record($sSqlDocumento);


    if ($oDaoLicanexopncpdocumento->erro_status  == "0") {

      $oStdMsgErro = (object)array("iDocumento" => "$iCodigo");
      throw new BusinessException('Erro no construtor');
    }

    $oDocumento = db_utils::fieldsMemory($rsDocumento, 0);
    $this->iCodigo            = $oDocumento->l216_sequencial;
    $this->oLicitacaoAnexo    = $oDocumento->l216_licanexospncp;
    $this->iOid               = $oDocumento->l216_documento;
    $this->sNomeDocumento     = $oDocumento->l216_nomedocumento;
    $this->iTipoanexo         = $oDocumento->l216_tipoanexo;

    $oDaoTipoanexo = db_utils::getDao('tipoanexo');
    $sSqlTipo = $oDaoTipoanexo->sql_query_file($oDocumento->l216_tipoanexo);
    $rsTipo   = $oDaoTipoanexo->sql_record($sSqlTipo);


    if ($oDaoTipoanexo->erro_status  == "0") {

      $oStdMsgErro = (object)array("iDocumento" => "$iCodigo");
      throw new BusinessException('Erro no construtor');
    }

    $oTipo = db_utils::fieldsMemory($rsTipo, 0);
    $this->sDescricaoTipo            = $oTipo->l213_descricao;
  }

  /**
   * Retorna o codigo do documento
   *
   * @access public
   * @return int
   */
  public function getCodigo()
  {
    return $this->iCodigo;
  }

  /**
   * Define processo protocolo
   *
   * @param LicitacaoAnexo $oLicitacaoAnexo
   * @access public
   * @return void
   */
  public function setProcessoProtocolo(LicitacaoAnexo $oLicitacaoAnexo)
  {
    $this->oLicitacaoAnexo = $oLicitacaoAnexo;
  }

  /**
   * Retorno o processo do protocolo
   *
   * @access public
   * @return LicitacaoAnexo
   */
  public function getProcessoProtocolo()
  {
    return $this->oLicitacaoAnexo;
  }


  /**
   * Define o OID do documento
   *
   * @param int $iOid
   * @access public
   * @return void
   */
  public function setOID($iOid)
  {
    $this->iOid = $iOid;
  }

  /**
   * Retorna o OID do documento
   *
   * @access public
   * @return int
   */
  public function getOID()
  {
    return $this->iOid;
  }

  /**
   * Define o caminho do arquivo
   *
   * @access public
   * @return int
   */
  public function setCaminhoArquivo($sCaminhoArquivo)
  {
    $this->sCaminhoArquivo = $sCaminhoArquivo;
  }

  /**
   * Retorna o caminho do arquivo
   *
   * @access public
   * @return int
   */
  public function getCaminhoArquivo()
  {
    return $this->sCaminhoArquivo;
  }

  /**
   * Retorna o nome do documento
   * @access public
   * @return string
   */
  public function getNomeDocumento()
  {
    return $this->sNomeDocumento;
  }

  public function getDescricaoTipo()
  {
    return $this->sDescricaoTipo;
  }


  /**
   * Define o caminho do arquivo
   *
   * @access public
   * @return int
   */
  public function setTipoanexo($iTipoanexo)
  {
    $this->iTipoanexo = $iTipoanexo;
  }


  /**
   * Retorna o tipo do documento
   * @access public
   * @return string
   */
  public function getTipoanexo()
  {
    return $this->iTipoanexo;
  }

  public function setNomeArquivo($sNomeArquivo)
  {
      $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * Validar arquivo
   * - tamanho limite
   * - extens�o
   *
   * @access public
   * @return boolean
   */
  private function validarArquivo()
  {

    $oStdMensagemErro    = new stdClass();
    $oStdMensagemErro->sCaminhoArquivo = $this->sCaminhoArquivo;
    $oStdMensagemErro->iLimiteTamanho  = $this->iLimiteTamanho;

    /** filesize($this->sCaminhoArquivo) > $this->iLimiteTamanho
     * Arquivo nao encontrado
     */
    if (!file_exists($this->sCaminhoArquivo)) {
      throw new BusinessException('Arquivo n�o existe');
    }

    $aInformacoesArquivo = pathinfo($this->sCaminhoArquivo);

    /**
     * Arquivo maior que o permitido
     */
    if (filesize($this->sCaminhoArquivo) > $this->iLimiteTamanho) {
      throw new BusinessException('Tamanho maior que 30mb');
    }

    /**
     * Arquivo com extensao invalida
     */
    if (!empty($aInformacoesArquivo['extension']) && in_array($aInformacoesArquivo['extension'], $this->aExtensoesInvalidas)) {

      $oStdMensagemErro->sExtensao = $aInformacoesArquivo['extension'];
      throw new BusinessException('Extens�o Invalida');
    }

    return true;
  }

  /**
   * Salvar
   *
   * @access public
   * @return boolean
   */
  public function salvar()
  {

    if (!db_utils::inTransaction()) {
      throw new DBException('Nenhuma transa��o de banco');
    }


    /**
     * Valida arquivo, tamanho e extensao
     */
    $this->validarArquivo();

    return $this->incluir();
  }

  /**
   * Inclui documento para o processo do protocolo
   * - salva arquivo no banco
   *
   * @access private
   * @return boolean
   */
  private function incluir()
  {

    /**
     * Processo do protocolo nao informado
     */
    if (!($this->oLicitacaoAnexo instanceof LicitacaoAnexo) && $this->getProcessoProtocolo()->getProcessoLicitacao() != '') {
      throw new Exception('Licita��o n�o informada.');
    }

    // $this->iOi = $this->salvarArquivoBanco();
    $this->sNomeDocumento = basename($this->sCaminhoArquivo);
    $oDaoLicanexopncpdocumento = db_utils::getDao('licanexopncpdocumento');
    $oDaoLicanexopncpdocumento->l216_sequencial    = null;
    $oDaoLicanexopncpdocumento->l216_licanexospncp  = $this->getProcessoProtocolo()->getProcessoLicitacao();
    $oDaoLicanexopncpdocumento->l216_documento     = $this->sNomeDocumento;
    $oDaoLicanexopncpdocumento->l216_nomedocumento = $this->sNomeArquivo;
    $oDaoLicanexopncpdocumento->l216_tipoanexo = $this->iTipoanexo;
    $oDaoLicanexopncpdocumento->incluir(null);

    /**
     * Erro ao incluir documento
     */
    if ($oDaoLicanexopncpdocumento->erro_status == "0") {
      throw new Exception('Erro ao incluir o documento');
    }

    $this->iCodigo = $oDaoLicanexopncpdocumento->l216_sequencial;
    return  'Anexo salvo com sucesso!'; //true;
  }


  /**
   * Salva arquivo no banco
   * - gera OID
   *
   * @access private
   * @return int
   */
  private function salvarArquivoBanco()
  {

    $iOid = DBLargeObject::criaOID(true);
    $lEscreveuArquivo = DBLargeObject::escrita($this->sCaminhoArquivo, $iOid);

    if (!$lEscreveuArquivo) {
      throw new BusinessException('Erro ao escrever arquivo no banco.');
    }

    return $iOid;
  }

  /**
   * Download documento
   * - retorna o caminho do arquivo para download
   *
   * @access public
   * @return string - caminho do arquivo extraido do banco
   */
  public function download()
  {

    $sCaracteres = "/[^a-z0-9\\_\.]/i";
    $sNomeArquivo = str_replace(" ", '_', $this->sNomeDocumento);
    $sNomeArquivo = preg_replace($sCaracteres, '', $sNomeArquivo);
    $sCaminhoArquivo  = '/tmp/' . $sNomeArquivo;

    if (!copy('model/licitacao/PNCP/anexoslicitacao/' . $this->iOid, $sCaminhoArquivo)) {
        throw new BusinessException("Erro ao copiar arquivo para tmp.");
    }

    return $sCaminhoArquivo;
    
  }

  /**
   * Exclui documento
   *
   * @access public
   * @return boolean
   */
  public function excluir()
  {

    if (empty($this->iCodigo)) {
      throw new Exception('Documento n�o especificado.');
    }

    $oDaoLicanexopncpdocumento = db_utils::getDao('licanexopncpdocumento');

    $oDaoLicanexopncpdocumento->excluir($this->iCodigo);

    if ($oDaoLicanexopncpdocumento->erro_status  == "0") {
      throw new Exception('Erro ao excluir o Arquivo.');
    }

//    $oDaolicanexopncp = db_utils::getDao('licanexopncp');
//    $oDaolicanexopncp->excluir($this->oLicitacaoAnexo);
//
//    if ($oDaolicanexopncp->erro_status  == "0") {
//      throw new Exception('Erro ao excluir o Arquivo.'.$oDaolicanexopncp->erro_msg);
//    }

    return true;
  }

  public function buscartipo()
  {

    if (empty($this->iCodigo)) {
      throw new Exception('Documento n�o especificado.');
    }


    return $this->getTipoanexo();
  }

  public function alterartipo($idocumento, $itipoanexo)
  {

    if (empty($idocumento)) {
      throw new Exception('Documento n�o especificado.');
    }
    if (empty($itipoanexo)) {
      throw new Exception('Tipo n�o especificado.');
    }

    $oDaoLicanexopncpdocumento = db_utils::getDao('licanexopncpdocumento');
    $oDaoLicanexopncpdocumento->l216_tipoanexo = $itipoanexo;
    $oDaoLicanexopncpdocumento->alterar($idocumento);

    if ($oDaoLicanexopncpdocumento->erro_status  == "0") {
      throw new Exception('Erro ao alterar o Arquivo.');
    }

    return true;
  }

  public function verificavinculo($ilicitacao)
  {

    if (empty($ilicitacao)) {
      throw new Exception('Licita��o n�o especificado.');
    }


    $oDaoLicanexopncpdocumento = db_utils::getDao('licanexopncpdocumento');

    $oDaoLicanexopncpdocumento->sql_record($oDaoLicanexopncpdocumento->verificalic($ilicitacao));




    if ($oDaoLicanexopncpdocumento->numrows  == 0) {
      throw new Exception('A Licita��o selecionada � decorrente da Lei n� 14133/2021, sendo assim, � necess�rio anexar no m�nimo um documento na rotina Anexos Envio PNCP.');
    }
    if ($oDaoLicanexopncpdocumento->erro_status  == "0") {
      throw new Exception('Erro ao verificar vinculo com o PNCP');
    }
    return true;
  }
}
