<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_unidades_classe.php");
$clunidades = new cl_unidades;
$clrotulo = new rotulocampo;
$clrotulo->label("sd02_i_codigo");
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd03_i_codigo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
     <td align="right" >
       <b> Per�odo:</b>
     </td>
     <td>
       <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
        A
       <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
     </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tsd03_i_codigo?>">
       <?
       db_ancora("<b>M�dico</b>","js_pesquisasd03_i_codigo(true);",1);
       ?>
      </td>
      <td>
      <?db_input('sd03_i_codigo',10,"",true,'text',1," onchange='js_pesquisasd03_i_codigo(false);'")?>
      <?db_input('sd03_c_nome',50,"",true,'text',3,'')?>
     </td>
     </tr>
     <table border="1" cellpadding="0" cellspacing="0" width="750">
     <?
      $result = $clunidades->sql_record($clunidades->sql_query());
      if($clunidades->numrows > 0){
     ?>
       <tr>
        <td bgcolor="#D0D0D0" width="30"><input type="button" value="M" name="marca" title="Marcar/Desmarcar" onclick="marcar(<?=$clunidades->numrows?>, this)"></td>
        <td colspan="5"><b>Selecione as Unidades</b></td>
       </tr>
      <?$bg = "#E8E8E8";
        echo "<tr bgcolor='#b0b0b0'>";
        for($u=0; $u< $clunidades->numrows; $u++){
         db_fieldsmemory($result,$u);
         echo "<td align='center' width='30'><input type='checkbox' value='$sd02_i_codigo' name='unidade'></td><td align='center' width='50'>".$sd02_i_codigo."</td><td width='400'>".$sd02_c_nome."</td>";
          @$coluna = $coluna + 1;
          if ($coluna>1)
            {
             echo "</tr>";
             echo "<tr bgcolor='$bg'>";
             if($bg == "#E8E8E8"){
              $bg = "#B0B0B0";
             }else{
              $bg = "#E8E8E8";
             }
             $coluna = 0;
            }
        }
        }else{
         echo "<tr><td class='texto'>Unidades n�o cadastradas</td></tr>";
        }
      ?>
     <tr>
       <td colspan='6' align='center' >
         <input name='start' type='button' value='Gerar' onclick="valida(<?=$clunidades->numrows?>,this)">
       </td>
     </tr>
    </table>
    </form>
  </td>
 </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_pesquisasd03_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd03_i_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.sd03_c_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.sd03_c_nome.value = chave;
  if(erro==true){
    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value = '';
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.sd03_c_nome.value = chave2;
  db_iframe_medicos.hide();
}

function marcar(tudo,documento)
 {
  for(i=0;i<tudo;i++)
   {
    if(documento.value=="D")
     {
      document.form1.unidade[i].checked=false;
     }
    if(documento.value=="M")
     {
      document.form1.unidade[i].checked=true;
     }
   }
  if(document.form1.marca.value == "D")
   {
    document.form1.marca.value="M";
   }
  else
   {
    document.form1.marca.value="D";
   }
 }

function valida(tudo,documento){
   obj = document.form1;
   count = 0;
   query='';

    if((obj.data1_dia.value !='') && (obj.data1_mes.value !='') && (obj.data1_ano.value !='') && (obj.data1_dia.value !='') && (obj.data2_mes.value !='') && (obj.data2_ano.value !='')){
          query +="data1="+obj.data1_ano.value+"X"+obj.data1_mes.value+"X"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"X"+obj.data2_mes.value+"X"+obj.data2_dia.value;
          count+=1;
    }
    if( obj.sd03_i_codigo.value != ""){
      query +="&medico="+obj.sd03_i_codigo.value;
            count+=1;
    }else{
     alert("Selecione o M�dico");
     return false;
    }
    sep = "";
    query +="&unidades=";
    for(i=0;i<tudo;i++)
    {
     if(obj.unidade[i].checked == true)
      {
       query += sep+obj.unidade[i].value;
       sep = "X";
       count += 1;
      }
    }
    if(count<3 ){
      alert("Preencha os Campos Corretamente!");
    }else{
      jan = window.open('sau2_agendamentos002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }
}
</script>
