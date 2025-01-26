<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aguabase_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$claguabase = new cl_aguabase;
$claguabase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.anousu.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" >
	    <table width="387" border="0" cellpadding="0" cellspacing="0">
<br><br>
          <tr>
            <td height="25">
				    <strong>Ano:</strong>
            </td>
            <td height="25">
              <?
	            $result=db_query("select " . db_getsession("DB_anousu") . "as j18_anousu");
	            if(pg_numrows($result) > 0){
		          ?>
		          <select name="anousu">
		          <?
  	          for($i=0;$i<pg_numrows($result);$i++){
		            db_fieldsmemory($result,$i);
	              ?>
	              <option value='<?=$j18_anousu?>'><?=$j18_anousu?></option>
	              <?
	              }
		          ?>
		        </select>
		        <?
	          }
	          ?>
          </td>
          </tr>

          <tr>
					<td height="25">
					<strong>Parcela Inicial:</strong>
					</td>
					<td height="25">
					<?
					$result1=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Mar�o","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
					if(!isset($parc_ini))
					  $parc_ini=intval(date('m',db_getsession("DB_datausu")));
					db_select("parc_ini",$result1,true,$db_opcao,"","","","","");
          ?>
          </td>
          </tr>

	        <tr>
					<td height="25">
					<strong>Parcela Final:</strong>
					</td>
					<td height="25">
					<?
					$result2=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Mar�o","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
					if(!isset($parc_fim))
					  $parc_fim=intval(date('m',db_getsession("DB_datausu")));
					db_select("parc_fim",$result2,true,$db_opcao,"","","","","");
          ?>
          </td>
          </tr>



					<tr>
            <td height="25">&nbsp;</td>
            <td height="25">
						  <input name="calcular"  type="submit" id="calcular" value="Calcular">
            </td>
          </tr>

        </table>
					<?
					if(isset($calcular)) {
								db_criatermometro('termometro', 'Concluido...', 'blue', 1);
					}
					?>


      </form>
     </td>
  </tr>
 <tr>
 </table>
</body>
</html>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<?


if(isset($calcular)){

	//termo("Pesquisando Matriculas para o Calculo Geral");

	$sql = "
		select distinct x01_matric from aguabase
		left join aguabasebaixa on x08_matric = x01_matric
		where x08_matric is null";

	$resultAguaBase = db_query($sql);

	$numrowsAguaBase = pg_numrows($resultAguaBase);

	if($numrowsAguaBase > 0) {

    // Percorre matriculas a serem calculadas
		for($w=0; $w<$numrowsAguaBase; $w++) {
			db_fieldsmemory($resultAguaBase, $w);

			db_atutermometro($w, $numrowsAguaBase, 'termometro');
			// Calcula Parcelas
			for($mesusu=$parc_ini; $mesusu<=$parc_fim; $mesusu++) {
    		db_query("BEGIN;");
        db_query("SELECT fc_putsession('__status_tg_arreold_atu', 'disable');");
    		db_query("SELECT fc_agua_calculoparcial($anousu, $mesusu, $x01_matric, 2, true, true) ;");
    		db_query("SELECT fc_putsession('__status_tg_arreold_atu', 'enable');");
    		db_query("COMMIT;");
			}
	  }

  } else {
  	$claguabase->erro_msg = 'Nenhuma Matricula para Calculo';
    $claguabase->erro_status = '0';
  }

}




?>
