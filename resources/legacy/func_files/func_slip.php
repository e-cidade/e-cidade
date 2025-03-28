<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_slip_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clslip   = new cl_slip;
$clrotulo = new rotulocampo;

$clrotulo->label("k17_codigo");
$clrotulo->label("k17_debito");
$clrotulo->label("k17_credito");
$clrotulo->label("k17_data");
$clrotulo->label("k17_valor");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="" >
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td align="center" valign="top">
      <table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
	<tr>
	  <td align="center" valign="top">
	    <table width="35%" border="0" align="center" cellspacing="0">

		<tr>
		  <td align="left" nowrap title="<?=$Tk17_codigo?>"> <? db_ancora(@$Lk17_codigo,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_codigo",8,$Ik17_codigo,true,"text",4,"","chave_k17_codigo");	 ?></td>
		</tr>

		<tr>
		<td colspan=2>
		<fieldset>
		<table border=0>
		 <tr>
		  <td align="left" nowrap title="<?=$Tk17_data?>"> <? db_ancora(@$Lk17_data,"",3);?>  </td>
		  <td align="left" nowrap><? db_inputdata("k17_data",@$k17_data_dia,@$k17_data_mes,db_getsession("DB_anousu"),true,'text',1);    ?></td>
		</tr>
		<tr>
		  <td align="left" nowrap title="<?=$Tk17_debito?>"> <? db_ancora(@$Lk17_debito,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_debito",8,$Ik17_debito,true,"text",4,"","chave_k17_debito");	 ?></td>
		</tr>
		<tr>
		  <td align="left" nowrap title="<?=$Tk17_credito ?>"> <? db_ancora(@$Lk17_credito,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_credito",8,$Ik17_credito,true,"text",4,"","chave_k17_credito");	 ?></td>
		</tr>
        <tr>
		  <td align="left" nowrap title="<?=$Tk17_valor ?>"> <? db_ancora(@$Lk17_valor,"",3);?>  </td>
		  <td align="left" nowrap><?    db_input("k17_valor",8,$Ik17_credito,true,"text",4,"onKeyDown=this.value=this.value.replace(',','.')","chave_k17_valor");	 ?></td>
		</tr>
		</table>
		</fieldset>
		</td>
		</tr>

	    </table>
	    </td>
	</tr>
	<tr>
	  <td align="center">
	    <BR>
	    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
	    <input name="limpar" type="reset" id="limpar" value="Limpar" >
	    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_slip.hide();">
	    <BR>
	   </td>
	</tr>
  </form>
	<tr>
	  <td align="center" valign="top">
	    <?



if (isset ($campos) == false) {
	if (file_exists("funcoes/db_func_slip.php") == true) {
		include ("funcoes/db_func_slip.php");
	} else {
		$campos = "k17_codigo,k17_data,k17_debito,k17_credito,k17_valor,k17_hist,replace(slip.k17_texto,'\n',' ') as k17_texto";
	}
}
$wh  = null;
$wh2 = null;
$where_instit = " and k17_instit = ".db_getsession("DB_instit");
//$where_status = " and k17_situacao  = 1";
if (isset($valida)){


  $wh  = " and k17_situacao  $valida";
  $wh2 = " k17_situacao  $valida";

}else{
  if (isset($chave_k17_codigo)){
    $wh2=" k17_codigo=$chave_k17_codigo";
  }
}

if (isset($modulo) and $modulo == 'pessoal') {
	$wh .= ' and exists (select 1 from rhslipfolhaslip where rh82_slip = slip.k17_codigo) ';
}


$campos  = " distinct ".$campos;
$campos .= ", case when k17_situacao = 1 then 'N�o Autenticado'::varchar
                else
                  case when k17_situacao = 2 then 'Autenticado'::varchar
                    else
                      case when k17_situacao = 3 then 'Estornado'::varchar
                        else
                          case when k17_situacao = 4 then 'Anulado'::varchar
              end end end end as dl_Situacao ";



if (!isset ($pesquisa_chave)) {
	if (isset ($chave_k17_codigo) && trim($chave_k17_codigo) != "") {
  $wh2="$wh2 and k17_codigo=$chave_k17_codigo $where_instit";
  $sql = $clslip->sql_query($chave_k17_codigo, $campos,"k17_data","$wh2");
	} else {
		/*
		 *
		 */
		$data = "";
		if (isset ($k17_data_dia) && $k17_data_dia != "") {
			$data = "$k17_data_ano-$k17_data_mes-$k17_data_dia";
		}
		if (isset ($chave_k17_debito) && trim($chave_k17_debito) != "") {
			if ($data == "")
				$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_debito $wh $where_instit");
			else
				$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_debito $wh and k17_data='".$data."' $where_instit");
		} else
			if (isset ($chave_k17_credito) && trim($chave_k17_credito) != "") {
				if ($data == "")
					$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_credito $wh $where_instit");
				else
					$sql = $clslip->sql_query(null, $campos, null, " k17_debito = $chave_k17_credito $wh and k17_data='".$data."' $where_instit");
			} else
				if (isset ($chave_k17_valor) && trim($chave_k17_valor) != "") {
					if ($data == "")
						$sql = $clslip->sql_query(null, $campos, null, " k17_valor = $chave_k17_valor $wh $where_instit");
					else
						$sql = $clslip->sql_query(null, $campos, null, " k17_valor = $chave_k17_valor $wh  and k17_data='".$data."' $where_instit");
				} else {
					if ($data == "")
						$sql = $clslip->sql_query(null, $campos, null,  "k17_instit = " . db_getsession("DB_instit") . $wh);
					else
						$sql = $clslip->sql_query(null, $campos, null, "  k17_data='".$data."' $wh $where_instit");
				}

      /*OC4474*/
      if (isset($protocolo) && $protocolo == 1) {
        $sql = "
          select slip.k17_codigo,
          k17_data,
          r1.c61_reduz||'-'||c1.c60_descr as dl_debito_descr,
          r2.c61_reduz||'-'||c2.c60_descr as dl_credito_descr,
          (case when k17_situacao = 1 then 'N�o Autenticado'
                when k17_situacao = 2 then 'Autenticado'
                when k17_situacao = 3 then 'Estornado'
                when k17_situacao = 4 then 'Anulado'
           end
          ) as k17_situacao,
          k17_valor,
          k17_dtaut,
          z01_nome,
          k145_numeroprocesso
           from slip
           left join conplanoreduz r1 on r1.c61_reduz  = k17_debito
            and r1.c61_instit = k17_instit
            and r1.c61_anousu = ".db_getsession("DB_anousu")."
           left join conplano      c1 on c1.c60_codcon = r1.c61_codcon
            and c1.c60_anousu = r1.c61_anousu
           left join conplanoreduz r2 on r2.c61_reduz = k17_credito
            and r2.c61_instit = k17_instit
            and r2.c61_anousu= ".db_getsession("DB_anousu")."
           left join conplano c2 on c2.c60_codcon = r2.c61_codcon
            and c2.c60_anousu = r2.c61_anousu
           left join slipnum on slipnum.k17_codigo = slip.k17_codigo
           left join cgm on cgm.z01_numcgm = slipnum.k17_numcgm
           left join slipprocesso on slip.k17_codigo = slipprocesso.k145_slip
              where k17_instit = ".db_getsession('DB_instit')."
                order by slip.k17_codigo
        ";
      }

	}

	db_lovrot($sql,15,"()","",$funcao_js);

} else {
	if ($pesquisa_chave != null && $pesquisa_chave != "") {
    /*OC4474*/
    if (isset($protocolo) && $protocolo == 1) {
      $sql = "
        select slip.k17_codigo,
          k17_data,
          k17_valor,
          z01_nome
           from slip
           left join conplanoreduz r1 on r1.c61_reduz  = k17_debito
            and r1.c61_instit = k17_instit
            and r1.c61_anousu = ".db_getsession("DB_anousu")."
           left join conplano      c1 on c1.c60_codcon = r1.c61_codcon
            and c1.c60_anousu = r1.c61_anousu
           left join conplanoreduz r2 on r2.c61_reduz = k17_credito
            and r2.c61_instit = k17_instit
            and r2.c61_anousu= ".db_getsession("DB_anousu")."
           left join conplano c2 on c2.c60_codcon = r2.c61_codcon
            and c2.c60_anousu = r2.c61_anousu
           left join slipnum on slipnum.k17_codigo = slip.k17_codigo
           left join cgm on cgm.z01_numcgm = slipnum.k17_numcgm
           left join slipprocesso on slip.k17_codigo = slipprocesso.k145_slip
              where k17_instit = ".db_getsession('DB_instit')."
                and slip.k17_codigo = {$pesquisa_chave}
                order by slip.k17_codigo
      ";

      $result = db_query($sql);
      db_fieldsMemory($result,0);
     echo "<script>".$funcao_js."('$k17_codigo','$z01_nome','$k17_data','$k17_valor',false);</script>";
     die;
    }

    $result = $clslip->sql_record($clslip->sql_query(null,"*",null,"k17_codigo = $pesquisa_chave $wh  and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."' $where_instit"));
    if ($clslip->numrows != 0) {
			db_fieldsmemory($result, 0);
			echo "<script>".$funcao_js."('$k17_codigo','$k17_texto',false);</script>";
		} else {
			echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
		}
	} else {
		echo "<script>".$funcao_js."('',false);</script>";
	}
}
?>
	   </td>
	 </tr>
      </table>
     </td>
   </tr>
</table>
</body>
</html>
<?
if (!isset ($pesquisa_chave)) {
?>
  <script>
  </script>
  <?}
?>
