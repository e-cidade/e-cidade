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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_usuariosonline.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if(isset($j37_quadra) && $j37_quadra != ""){
  $quadra = explode(",",$j37_quadra);
  $vir = "";
  $qua = "";
  for($i=0;$i<count($quadra);$i++){
    $qua .= $vir."'".$quadra[$i]."'";
    $vir = ",";
  }
}
if(isset($j37_setor) && $j37_setor != ""){
  $setor = explode(",",$j37_setor);
  $vir = "";
  $qua1 = "";
  $setor_old = "";
  for($i=0;$i<count($setor);$i++){
    if($setor[$i] != $setor_old){
      $qua1 .= $vir."'".$setor[$i]."'";
    }
    $setor_old = $setor[$i];
    $vir = ",";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<form name="form1" method="post" action="cad2_iptuconstr002.php" target="rel">
<center>
<table border="0">
  <tr>
    <td align="center">
      <strong>Op��es:</strong>
      <select name="ver" onChange="parent.iframe_g1.document.form1.temruas.value = this.value">
        <option name="cruas" value="t">Com as ruas selecionadas</option>
        <option name="cruas" value="f">Sem as ruas selecionadas</option>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap width="50%">
			<?
			$aux = new cl_arquivo_auxiliar;
			$aux->cabecalho = "<strong>RUAS</strong>";
			$aux->codigo = "j14_codigo";
			$aux->descr  = "j14_nome";
			$aux->nomeobjeto = 'ruas';
			$aux->funcao_js = 'js_mostra';
			$aux->funcao_js_hide = 'js_mostra1';
			$aux->sql_exec  = "";
			$aux->func_arquivo = "func_ruas.php";
			$aux->nomeiframe = "iframa_ruas";
			$aux->localjan = "";
			$aux->onclick = "";
			$aux->db_opcao = 2;
			$aux->tipo = 2;
			$aux->top = 0;
			$aux->linhas = 10;
			$aux->vwhidth = 400;
			$aux->funcao_gera_formulario();
			?>
    </td>
  </tr>
</table>
</center>
</form>
	</td>
  </tr>
</table>
</body>
<script>
parent.iframe_g1.document.form1.temruas.value = 't';
function js_ver_rua(){
  for(i=0;i<parent.iframe_g6.document.form1.length;i++){
    if(parent.iframe_g6.document.form1.elements[i].name == "ruas1[]"){
      for(x=0;x<parent.iframe_g6.document.form1.elements[i].length;x++){
        if(parent.iframe_g6.document.form1.elements[i].options[x].value == document.form1.j14_codigo.value){
       	  alert('Rua j� selecionada para n�o constar no relat�rio')
      	  document.form1.j14_codigo.value = '';
      	}
      }
    }
  }
}

function js_limpacampos(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
  }
}
</script>

</html>