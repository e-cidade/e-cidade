<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';

$oJson         = new services_json();
$sName         = $_POST["string"];
$oDomXml       = new DOMDocument();
$oDomXml->load('legacy_config/sigfis/receitasigfis.xml');
$aContas = $oDomXml->getElementsByTagName("receita");
$aReceitaRetorno = array();
foreach ($aContas as $oConta) {

  $iCodigo     = $oConta->getAttribute("codigo");
  $sDescricao  = $oConta->getAttribute("descricao");
  $iTamanhoPesquisa = strlen($sName);
  if (strpos(strtolower($sDescricao), strtolower($sName)) !== false ||
     substr($iCodigo, 0, $iTamanhoPesquisa) == $sName) {

    $oReceitaRetorno         = new stdClass();
    $oReceitaRetorno->cod    = $iCodigo;
    $oReceitaRetorno->label  = "{$iCodigo} - {$sDescricao}";
    $aReceitaRetorno[]       = $oReceitaRetorno;
  }

}

echo $oJson->encode($aReceitaRetorno);

?>
