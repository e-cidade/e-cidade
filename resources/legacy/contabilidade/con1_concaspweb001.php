<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_vinculocaspweb_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clvinculocaspweb = new cl_vinculocaspweb;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
    db_inicio_transacao();
    $clvinculocaspweb->incluir($c232_estrutecidade,$c232_estrutcaspweb,$c232_anousu);
    db_fim_transacao();
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
    </tr>
</table>
<center>
    <table width="350" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
                <?
                include("forms/db_frmvinculocaspweb.php");
                ?>
            </td>
        </tr>
    </table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
    js_tabulacaoforms("form1","c232_estrutecidade",true,1,"c232_estrutecidade",true);
</script>
<?
if(isset($incluir)){
    if($clvinculocaspweb->erro_status=="0"){
        $clvinculocaspweb->erro(true,false);
        $db_botao=true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if($clvinculocaspweb->erro_campo!=""){
            echo "<script> document.form1.".$clvinculocaspweb->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clvinculocaspweb->erro_campo.".focus();</script>";
        }
    }else{
        $clvinculocaspweb->erro(true,true);
    }
}
?>