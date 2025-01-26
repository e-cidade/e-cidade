<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once ("std/DBDate.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_emppresta_classe.php");
require_once("classes/db_empprestaitem_classe.php");
require_once ("model/contabilidade/planoconta/ContaPlano.model.php");
require_once ("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once ("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/Dotacao.model.php");
require_once ("model/contabilidade/planoconta/ContaOrcamento.model.php");
db_app::import("configuracao.*");
db_app::import("empenho.*");
db_app::import("orcamento.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.*");

db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("Dotacao");


require_once ("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once ("model/empenho/PrestacaoConta.model.php");
require_once ("model/contabilidade/planoconta/SistemaConta.model.php");


db_app::import("exceptions.*");

$clemppresta = new cl_emppresta;
  /*
$clempprestaitem = new cl_empprestaitem;
  */

db_postmemory($HTTP_POST_VARS);

$db_opcao        = 22;
$db_botao        = false;
$lPrestacaoConta = true;

if ( isset($alterar) ) {

  $sqlerro = false;

  db_inicio_transacao();

  $clemppresta->alterar($e45_sequencial);

  if ( $clemppresta->erro_status == 0 ) {
    $sqlerro=true;
  }

  $erro_msg = $clemppresta->erro_msg;

  if (USE_PCASP) {

    try {
     $dataLancamento = new DBDate($e45_conferido);
     $dataLancamento = $dataLancamento->getDate();

     if(pg_num_rows(db_query("SELECT * FROM condataconf WHERE c99_anousu = ".db_getsession('DB_anousu')." "))>0){
      $oConsultaFimPeriodoContabil = db_query("SELECT * FROM condataconf WHERE (c99_data < '$dataLancamento' OR c99_data IS NULL) AND c99_anousu = ".db_getsession('DB_anousu')." ");

      if(pg_num_rows($oConsultaFimPeriodoContabil) == 0){
        throw new Exception("Data informada inferior � data do fim do per�odo cont�bil.");
      }
    }

    $oConsultaDataLancamento = db_query("SELECT *
      FROM conlancamemp
      JOIN conlancam ON c75_codlan=c70_codlan
      WHERE c75_numemp = $e45_numemp AND c75_data > '$dataLancamento'
      ORDER by c75_data DESC");

    if(pg_num_rows($oConsultaDataLancamento) > 0){
      throw new Exception("J� existe um lan�amento com a data posterior � informada.");
    }

    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($e45_numemp);
    $oPrestacaoConta    = new PrestacaoConta($oEmpenhoFinanceiro, $e45_sequencial, $e45_conferido);

    if (count($oPrestacaoConta->getItens()) == 0) {
      throw new Exception("Nenhum item lan�ado para presta��o de contas.");
    }

    $clEmpempenho = new cl_empempenho;
    $rsEmpenho = $clEmpempenho->sql_record($clEmpempenho->sql_query($e45_numemp,"o15_codigo"));
    $sRecurso = db_utils::fieldsMemory($rsEmpenho, 0)->o15_codigo;
    $oEmpenhoFinanceiro->setRecurso($sRecurso);

    $oPrestacaoConta->processarLancamento($observacao_lancamento);

  } catch (Exception $eException) {

    $erro_msg = $eException->getMessage();
    $sqlerro = true;

  } catch (BusinessException $eBusinessException) {

    $erro_msg = $$eBusinessException->getMessage();
    $sqlerro = true;
  }
}
db_fim_transacao($sqlerro);

$db_opcao = 2;
$db_botao = true;
} else if(isset($chavepesquisa)) {

 $db_opcao = 2;
 $db_botao = true;
 $result = $clemppresta->sql_record( $clemppresta->sql_query_emp( null,
  '*',
  null,
  "e45_numemp = {$chavepesquisa} and e45_codmov = {$chavemovimento}") );
 db_fieldsmemory($result,0);
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style="margin-top: 30px;">
  <center>
   <?
   include("forms/db_frmemppresta09.php");
   ?>
 </center>
</body>
</html>
<?php
if ( isset($alterar) ) {

  if ( $sqlerro == true ) {

    db_msgbox(str_replace("\n","\\n",$erro_msg));

    if($clemppresta->erro_campo!=""){

      echo "<script> document.form1.".$clemppresta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clemppresta->erro_campo.".focus();</script>";
    };

  } else {

    db_msgbox(str_replace("\n","\\n",$erro_msg));

    echo "
    <script>
      parent.location.href = 'emp1_emppresta008.php';
    </script>\n
    ";
  }
}

if ( isset($chavepesquisa) && !isset($alterar) ) {
 echo "
 <script>
  function js_db_libera() {

    parent.document.formaba.encerra.disabled       = false;
    parent.document.formaba.empprestaitem.disabled = false;

    CurrentWindow.corpo.iframe_empprestaitem.location.href = 'emp1_empprestaitem001.php?tranca=true&e60_codemp=$e60_codemp&e46_numemp=".@$e45_numemp."&e45_sequencial={$e45_sequencial}';
    CurrentWindow.corpo.iframe_encerra.location.href       = 'emp1_empprestaencerra.php?tranca=true&e60_codemp=$e60_codemp&e60_numemp=".@$e45_numemp."&e45_sequencial={$e45_sequencial}';
  }\n

  js_db_libera();
</script>\n
";
}

if ( $db_opcao == 22 || $db_opcao == 33 ) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>
