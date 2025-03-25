<?

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libpessoal.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("classes/db_infoambiente_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_atividadesdesempenhadas_classe.php");
require_once("classes/db_agentesnocivos_classe.php");
require_once("classes/db_inforelativasresp_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrhpessoal               = new cl_rhpessoal;
$clinfoambiente            = new cl_infoambiente;
$clatividadesdesempenhadas = new cl_atividadesdesempenhadas;
$clagentesnocivos          = new cl_agentesnocivos;
$clinforelativasresp       = new cl_inforelativasresp;

$db_opcao = 33;
$db_botao = false;

if (isset($excluir)) {

    $sqlerro = false;

    db_inicio_transacao();

    function verificaRegistro($classe, $campo, $valor, $mensagemErro)
    {
        $result = $classe->sql_record($classe->sql_query(null, $campo, null, "$campo = $valor"));
        if ($classe->numrows > 0) {
            db_fim_transacao(false);
            db_msgbox($mensagemErro);
            echo "<script>window.history.back();</script>";
            exit;
        }
    }

    verificaRegistro(
        $clatividadesdesempenhadas,
        "rh231_regist",
        $rh230_regist,
        "Usuário:\n  Exclusão não efetuada!\n  Necessário remover dados da aba Atividades Desempenhadas."
    );

    verificaRegistro(
        $clagentesnocivos,
        "rh232_regist",
        $rh230_regist,
        "Usuário:\n  Exclusão não efetuada!\n  Necessário remover dados da aba Agentes Nocivos."
    );

    verificaRegistro(
        $clinforelativasresp,
        "rh234_regist",
        $rh230_regist,
        "Usuário:\n  Exclusão não efetuada!\n  Necessário remover dados da aba Informações Relativas ao Responsável."
    );

    if ($sqlerro == false) {

        $clinfoambiente->excluir($rh230_regist);
        $erro_msg = $clinfoambiente->erro_msg;

        if ($clinfoambiente->erro_status == 0) {

            $sqlerro = true;
        }
    }

    db_fim_transacao($sqlerro);

    $db_opcao = 3;
    $db_botao = true;
} else if (isset($chavepesquisa)) {

    $db_opcao = 3;
    $db_botao = true;

    $result = $clinfoambiente->sql_record($clinfoambiente->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
}

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, strings.js, prototype.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
                <center>
                    <?
                    include("forms/db_frminfoambiente.php");
                    ?>
                </center>
            </td>
        </tr>
    </table>
</body>
<?
if (isset($excluir)) {

    if ($sqlerro == false) {

        db_msgbox($erro_msg);
        echo "
      <script>
        function js_db_tranca(){
          parent.location.href='eso1_condicoesambientais003.php';
        }\n
        js_db_tranca();
      </script>\n
    ";
    }
}

if (isset($chavepesquisa)) {

    echo "
  <script>
      function js_db_libera(){         
     
        parent.document.formaba.atividades.disabled=false;
        CurrentWindow.corpo.iframe_atividades.location.href='eso1_atividades001.php?db_opcaoal=33&rh231_regist=" . @$rh230_regist . "';
        
        parent.document.formaba.agentesnocivos.disabled=false;
        CurrentWindow.corpo.iframe_agentesnocivos.location.href='eso1_agentesnocivos001.php?db_opcaoal=33&rh232_regist=" . @$rh230_regist . "';
        
        parent.document.formaba.inforelativasresp.disabled=false;
        CurrentWindow.corpo.iframe_inforelativasresp.location.href='eso1_inforelativasresp001.php?db_opcaoal=33&rh234_regist=" . @$rh230_regist . "';
      ";
    if (isset($liberaaba)) {
        echo "  parent.mo_camada('atividades');";
    }

    echo "}\n
       js_db_libera();
     </script>\n
   ";
}

if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>

</html>