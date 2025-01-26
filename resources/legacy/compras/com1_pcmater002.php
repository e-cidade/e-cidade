<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("classes/db_pcgrupo_classe.php");
include("classes/db_pcsubgrupo_classe.php");
include("classes/db_historicoitem_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_condataconf_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$clhistoricoitem = new cl_historicoitem;
$clcondataconf = new cl_condataconf;
$db_opcao = 22;
$db_botao = false;

if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar") {
  db_inicio_transacao();
  $sqlerro = false;
  $erro_justificativa = false;
  $db_opcao = 2;
  $clpcmater->pc01_data   = $pc01_data;
  /*OC3770*/
  $clpcmater->pc01_tabela = $pc01_tabela;
  $clpcmater->pc01_taxa   = $pc01_taxa;
  $clpcmater->pc01_regimobiliario   = $pc01_regimobiliario;

  /*FIM - OC3770*/

  if ($pc01_obras == "0") {
    $erro_msg = "Campo  Material Utilizado em Obras/servi�os?  nao informado.";
    $sqlerro  = true;
    db_msgbox($erro_msg);
  }
  $result_pesquisa_material = $clpcmater->sql_record($clpcmater->sql_query(null, "pc01_descrmater as pc01_descrmateranterior, pc01_complmater as pc01_complmateranterior", null, "pc01_codmater = $pc01_codmater"));
  db_fieldsmemory($result_pesquisa_material, 0);

  if ($pc01_complmateranterior != $pc01_complmater && $pc01_justificativa == "") {
    $erro_justificativa = true;
  }

  if ($pc01_descrmateranterior != $pc01_descrmater && $pc01_justificativa == "") {

    $erro_justificativa = true;
    $dt_session = date("Y-m-d", db_getsession("DB_datausu"));
    $anousu = db_getsession('DB_anousu');
    $instituicao = db_getsession('DB_instit');
    $result = $clcondataconf->sql_record($clcondataconf->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'), "c99_datapat", null, null));
    $c99_datapat = db_utils::fieldsMemory($result, 0)->c99_datapat;

    if (strtotime($dt_session) <= strtotime($c99_datapat) && $sqlerro != false) {
      $erro_msg = "O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.";
      $sqlerro  = true;
      db_msgbox($erro_msg);
    }
  }

  if ($erro_justificativa == true) {
    $erro_msg = "Campo Justificativa n�o Atualizado!.";
    $sqlerro  = true;
    db_msgbox($erro_msg);
  }

  if(strlen(trim($pc01_justificativa)) <= 5 && ($pc01_complmateranterior != $pc01_complmater || $pc01_descrmateranterior != $pc01_descrmater)){
    $erro_msg = "Campo Justificativa � necess�rio possuir no m�nimo 5 caracteres.";
    $sqlerro  = true;
    db_msgbox($erro_msg);
  }

  $rsValidacaoAlteracaoDescricao = db_query("select * from pcmater where pc01_codmater = $pc01_codmater and pc01_descrmater = '$pc01_descrmater' and pc01_complmater = '$pc01_complmater' ");
  if(pg_num_rows($rsValidacaoAlteracaoDescricao) == 0){
    //Salva o item na tabela historicomaterial para gerar no sicom OC20960
    $clhistoricomaterial = new cl_historicomaterial;

    $rsHistoricoMaterial = $clhistoricomaterial->sql_record($clhistoricomaterial->sql_query(null,"db150_sequencial",null,"db150_pcmater =$pc01_codmater and db150_tipocadastro = 2"));

    for($iMaterialTipo2 = 0; $iMaterialTipo2 < pg_num_rows($rsHistoricoMaterial); $iMaterialTipo2++ ){
        db_fieldsmemory($rsHistoricoMaterial, $iMaterialTipo2);

        $clhistoricomaterial->db150_sequencial                = $db150_sequencial;
        $clhistoricomaterial->db150_justificativaalteracao    = $pc01_justificativa;
        $clhistoricomaterial->db150_data                      = date("Y-m-d", db_getsession("DB_datausu"));
        $clhistoricomaterial->db150_mes                       = date("m", db_getsession("DB_datausu"));
        $clhistoricomaterial->db150_instit                    = db_getsession('DB_instit');
        $clhistoricomaterial->alterar($db150_sequencial);

        if ($clhistoricomaterial->erro_status == 0) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($clhistoricomaterial->erro_msg);
        }
    }

    $rsHistoricoMaterialTipo1 = $clhistoricomaterial->sql_record($clhistoricomaterial->sql_query(null,"db150_coditem,db150_pcmater,db150_dscitem,db150_unidademedida",null,"db150_pcmater =$pc01_codmater and db150_tipocadastro = 1 and db150_coditem not in (select db150_coditem from historicomaterial where db150_pcmater = $pc01_codmater and db150_tipocadastro = 2)"));
    for($iMaterialTipo1 = 0; $iMaterialTipo1 < pg_num_rows($rsHistoricoMaterialTipo1); $iMaterialTipo1++ ){
        db_fieldsmemory($rsHistoricoMaterialTipo1, $iMaterialTipo1);

        $clhistoricomaterial->db150_tiporegistro              = 10;
        $clhistoricomaterial->db150_coditem                   = $db150_coditem;
        $clhistoricomaterial->db150_pcmater                   = $db150_pcmater;
        $clhistoricomaterial->db150_dscitem                   = $db150_dscitem;
        $clhistoricomaterial->db150_unidademedida             = $db150_unidademedida;
        $clhistoricomaterial->db150_tipocadastro              = 2;
        $clhistoricomaterial->db150_justificativaalteracao    = $pc01_justificativa;
        $clhistoricomaterial->db150_data                      = date("Y-m-d", db_getsession("DB_datausu"));
        $clhistoricomaterial->db150_mes                       = date("m", db_getsession("DB_datausu"));
        $clhistoricomaterial->db150_instit                    = db_getsession('DB_instit');
        $clhistoricomaterial->incluir(null);

        if ($clhistoricomaterial->erro_status == 0) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($clhistoricomaterial->erro_msg);
        }
    }

  }

  if (!$sqlerro) {
    $clpcmater->pc01_dataalteracao = date("Y-m-d", db_getsession("DB_datausu"));
    $clpcmater->pc01_justificativa = $pc01_justificativa;
    $clpcmater->alterar($pc01_codmater);
    db_query($conn, "UPDATE acordoitem SET ac20_resumo = '$pc01_complmater' WHERE ac20_pcmater = $pc01_codmater");

    if ($clpcmater->numrows_alterar) {
      $clhistoricoitem->pc96_codigomaterial = $pc01_codmater;
      $clhistoricoitem->pc96_usuario = db_getsession('DB_id_usuario');
      $clhistoricoitem->pc96_dataalteracao = date("Y-m-d", db_getsession("DB_datausu"));
      $clhistoricoitem->pc96_dataservidor = date("Y-m-d");
      $clhistoricoitem->pc96_horaalteracao = time();
      $clhistoricoitem->pc96_descricaoanterior = $clpcmater->pc01_descrmater . " - " . $clpcmater->pc01_complmater;

      $clhistoricoitem->incluir();

      if ($clhistoricoitem->erro_status == 0) {
        $sqlerro = true;
      }
    }
  }


  if ($clpcmater->erro_status == 0) {
    $sqlerro = true;
  } else {

    $codmater =  $clpcmater->pc01_codmater;
  }

  //rotina que exclui todos os registros do pcmaterele
  if ($sqlerro == false) {
    $clpcmaterele->pc07_codmater = $codmater;
    $clpcmaterele->excluir($codmater);
    if ($clpcmaterele->erro_status == 0) {
      db_msgbox($clpcmaterele->erro_msg);
      $sqlerro = true;
    }
  }

  if ($sqlerro == false) {
    $arr =  explode("XX", $codeles);
    for ($i = 0; $i < count($arr); $i++) {
      $elemento = $arr[$i];
      if (trim($elemento) != "") {
        $result_matele = $clpcmaterele->sql_record($clpcmaterele->sql_query_file($codmater, $elemento));
        if ($clpcmaterele->numrows == 0) {
          $clpcmaterele->pc07_codmater = $codmater;
          $clpcmaterele->pc07_codele = $elemento;
          $clpcmaterele->incluir($codmater, $elemento);
          if ($clpcmaterele->erro_status == 0) {
            db_msgbox($clpcmaterele->erro_msg);
            $sqlerro = true;
          }
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
}

if (isset($chavepesquisa) || $pc01_codmater) {

  $chavepesquisa = $chavepesquisa ? $chavepesquisa : $pc01_codmater;

  $db_opcao = 2;
  $db_botao = true;

  $result = $clpcmater->sql_record($clpcmater->sql_query_file($chavepesquisa));
  db_fieldsmemory($result, 0);

  $result = $clpcmaterele->sql_record($clpcmaterele->sql_query_file($chavepesquisa));
  $numrows = $clpcmaterele->numrows;
  $coluna =  '';
  $sep = '';
  for ($i = 0; $i < $numrows; $i++) {
    db_fieldsmemory($result, $i);
    $coluna .=  $sep . $pc07_codele;
    $sep     = "XX";
  }
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
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
          <?
          include("forms/db_frmpcmater.php");
          ?>
        </td>
      </tr>
    </table>
  </center>
  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar") {
  if ($clpcmater->erro_status == "0") {
    $clpcmater->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clpcmater->erro_campo != "") {
      echo "<script> document.form1." . $clpcmater->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clpcmater->erro_campo . ".focus();</script>";
    };
  } else {
    $clpcmater->erro(true, true);
  };
};
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
