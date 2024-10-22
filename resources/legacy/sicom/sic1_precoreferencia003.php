<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_precoreferencia_classe.php");
include("classes/db_pcproc_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clprecoreferencia = new cl_precoreferencia;
$clpcproc = new cl_pcproc;
$clprecoreferenciaacount = new cl_precoreferenciaacount;
$db_botao = false;
$db_opcao = 33;
if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clprecoreferencia->sql_record($clprecoreferencia->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <?php
    db_app::load("estilos.bootstrap.css");
    db_app::load("just-validate.js");
    ?>
</head>
<style>
    .container {
        margin-top: 140px; /* Espaço acima do container */
        background-color: #f5fffb;
        padding: 20px;
        max-width: 1250px; /* Largura máxima do conteudo */
        width: 100%; /* Para garantir responsividade */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
        font-family: Arial;
        font-size: 12px;
    }
</style>
<body bgcolor=#f5fffb leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<div class="container">
    <?php
    include("forms/db_frmprecoreferencia.php");
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</div>
</body>
</html>
<?
if(isset($excluir)){
  if($clprecoreferencia->erro_status=="0"){
    $clprecoreferencia->erro(true,false);
  }else{
    $clprecoreferencia->erro(true,true);
  }
}
if($db_opcao==33){
  if($clpcproc->numrows > 0){
    echo "<script>alert('Existe licitação vinculada a esse Preço de Referência');</script>";
  }
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
