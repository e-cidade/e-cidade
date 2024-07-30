<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_contabancaria_classe.php");
require_once("classes/db_db_operacaodecredito_classe.php");
require_once("classes/db_saltes_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcontabancaria     = new cl_contabancaria;
$cloperacaodecredito = new cl_db_operacaodecredito;
$clsaltes            = new cl_saltes;
$db_opcao            = 22;
$db_botao            = false;
$mostrarCampo        = 1;
$mostrarCampo2       = 1;

$cloperacaodecredito->rotulo->label();

if (isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_opcaonovo = 2;
  $result   = $clcontabancaria->sql_record($clcontabancaria->sql_query_cadcontanovo($chavepesquisa));

  db_fieldsmemory($result, 0);

  $iCodigoRecurso    = $c61_codigo;
  $sDescricaoRecurso = $o15_descr;
  $db83_codigotce    = $c61_codtce;
  $db83_reduzido     = $k13_reduz;
  $dtImplantacao     = explode("-", $k13_dtimplantacao, 3);
  $db83_dataimplantaoconta_dia = $dtImplantacao[2];
  $db83_dataimplantaoconta_mes = $dtImplantacao[1];
  $db83_dataimplantaoconta_ano = $dtImplantacao[0];
  $db83_contaplano = 't';

  $oDaoConDataConf  = new cl_condataconf();
  $oDataImplantacao = date('Y-m-d', strtotime(str_replace('/', '-', $k13_dtimplantacao)));
  $sWhere           = "    c99_data   >= '{$oDataImplantacao}'::date
                           and c99_instit  = " . db_getsession('DB_instit') ." and c99_anousu = " .db_getsession('DB_anousu') ;
  $sSqlValidaFechamentoContabilidade = $oDaoConDataConf->sql_query(null, null, '*', null, $sWhere);
  $rsValidaFechamentoContabilidade   = $oDaoConDataConf->sql_record($sSqlValidaFechamentoContabilidade);
  db_fieldsmemory($rsValidaFechamentoContabilidade, 0);
  
  $data_convertida                   = DateTime::createFromFormat('Y-m-d', $c99_data);
  if ($c99_data) {
    $c99_data = $data_convertida->format('d/m/Y');
  }

  if ($oDaoConDataConf->numrows > 0) {
    $db_opcao     = 3;
    $db_opcaonovo = 1;
  }

  $clcondataconf     = new cl_condataconf;
  $resultControle    = $clcondataconf->sql_record($clcondataconf->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit'),'c99_data as dataencerramento'));
  db_fieldsmemory($resultControle,0);
  $data_encerramento = DateTime::createFromFormat('Y-m-d', $dataencerramento);  
  if ($dataencerramento) {
    $dataencerramento = $data_encerramento->format('d/m/Y');
  } 
  
  $db_botao = true;
}
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table align="center" style="margin-top:20px;">
    <tr>
      <td>
        <center>
          <?
          include("forms/db_frmcontabancarianovo.php");
          ?>
        </center>
      </td>
    </tr>
  </table>
  <?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<?
if (isset($alterar)) {

  if ($clcontabancaria->erro_status == "0") {

    $clcontabancaria->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcontabancaria->erro_campo != "") {

      echo "<script> document.form1." . $clcontabancaria->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clcontabancaria->erro_campo . ".focus();</script>";
    }
  } else {
    $clcontabancaria->erro(true, true);
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1", "db83_descricao", true, 1, "db83_descricao", true);
</script>