<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_eventos1005_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cleventos1005 = new cl_eventos1005;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
    db_inicio_transacao();
    $db_opcao = 2;
    $cleventos1005->alterar($eso06_sequencial);
    db_fim_transacao();
}else if(isset($chavepesquisa)){
    $db_opcao = 2;
    $result = $cleventos1005->sql_record($cleventos1005->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
    $db_botao = true;
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<div style=" width: 65%; margin-left: 20%;">
    <?
    include("forms/db_frmeventos1005.php");
    ?>
</div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
    if($cleventos1005->erro_status=="0"){
        $cleventos1005->erro(true,false);
        $db_botao=true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if($cleventos1005->erro_campo!=""){
            echo "<script> document.form1.".$cleventos1005->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$cleventos1005->erro_campo.".focus();</script>";
        }
    }else{
        $cleventos1005->erro(true,true);
    }
}
if($db_opcao==22){
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
    js_tabulacaoforms("form1","eso06_tipoinscricao",true,1,"eso06_tipoinscricao",true);
</script>