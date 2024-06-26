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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemCedente.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once("model/patrimonio/BemHistoricoMovimentacao.model.php");
require_once("model/patrimonio/BemDadosMaterial.model.php");
require_once("model/patrimonio/BemDadosImovel.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/CgmFactory.model.php");

$oGet = db_utils::postMemory($_GET, false);
$oBem = new Bem($oGet->t52_bem);
$oMaterial = $oBem->getDadosCompra();
$oDadosBaixa = $oBem->getDadosBaixa();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type='text/css'>
	.valores {background-color:#FFFFFF}
</style>
</head>
  <body>
    <center>
      <fieldset>
        <legend><strong>Dados Material</strong></legend>
          <table width="100%">

          	<tr>

          		<td width="20%"><b>Nota Fiscal: </b></td>

          		<td width="30%" class="valores">
          		  <?php
          		    if ($oMaterial != null) {
          		      echo $oMaterial->getNotaFiscal();
          		    }
          		  ?>
          		</td>

          		<td width="20%"><b>Empenho: </b></td>

          		<td width="30%" class="valores">
          			<?php
          			  if ($oMaterial != null) {
          			    echo $oMaterial->getEmpenho();
          			  }
          			?>
          		</td>

          	</tr>

          	<tr>

          		<td><b>Ordem de Compra: </b></td>

          		<td class="valores">
          			<?php
          			  if ($oMaterial != null) {
          			    echo $oMaterial->getOrdemCompra();
          			  }
          			?>
          		</td>

          		<td><b>Data Garantia: </b></td>

          		<td class="valores">
          			<?php
          			  if ($oMaterial != null) {
          			    echo $oMaterial->getDataGarantia();
          			  }
          			?>
          		</td>

          	</tr>

          	<tr>

          		<td><b>Credor: </b></td>

          		<td class="valores">
          			<?php
          			  if ($oMaterial != null) {
          			    echo $oMaterial->getCredor();
          			  }
          			?>
          		</td>

          		<td colspan="2"></td>

          	</tr>

          </table>
      </fieldset>
      <fieldset>
        <legend><strong>Dados Baixa</strong></legend>
        <table width="100%">
            <tr>
              <td width="20%"><b>Data da baixa:</b></td>
              <td width="30%" class="valores">
                <?php
                  if ($oDadosBaixa->databaixa != null) {
                    echo $oDadosBaixa->databaixa;
                  }
                ?>
              </td>
              <td colspan="2"></td>
            </tr>
            <tr>
              <td width="20%"><b>Observação:</b></td>
              <td width="30%" class="valores">
                <?php
                  if ($oDadosBaixa->observacao != null) {
                    echo $oDadosBaixa->observacao;
                  }
                ?>
              </td>
              <td colspan="2"></td>
            </tr>
            <tr>
              <td><b>Destino: </b></td>
              <td width="30%" class="valores">
                <?php
                  if ($oDadosBaixa->sDestino != null) {
                    echo $oDadosBaixa->sDestino;
                  }
                ?>
              </td>
              <td colspan="2"></td>
            </tr>
          </table>
      </fieldset>
    </center>
  </body>
</html>
