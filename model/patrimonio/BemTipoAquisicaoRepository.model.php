<?php
/**
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
 * Class BemTipoAquisicaoRepository
 */
class BemTipoAquisicaoRepository {

  /**
   * @var array
   */
  private $aItens = array();

  /**
   * @var BemTipoAquisicaoRepository
   */
  private static $oInstance;

  /** */
  private function __construct() {}

  private function __clone() {}

  /**
   * @param integer $iCodigo
   * @return BemTipoAquisicao
   */
  public static function getPorCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, BemTipoAquisicaoRepository::getInstancia()->aItens)) {
      BemTipoAquisicaoRepository::getInstancia()->aItens[$iCodigo] = new BemTipoAquisicao($iCodigo);
    }
    return BemTipoAquisicaoRepository::getInstancia()->aItens[$iCodigo];
  }

  /**
   * @return BemTipoAquisicaoRepository
   */
  public static function getInstancia() {

    if ( !isset(self::$oInstance) ) {
      self::$oInstance = new BemTipoAquisicaoRepository();
    }
    return self::$oInstance;
  }
}
