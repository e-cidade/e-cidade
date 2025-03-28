<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$clpcorcam       = new cl_pcorcam;
$clpcorcamitem     = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamforne    = new cl_pcorcamforne;
$clpcorcamval      = new cl_pcorcamval;
$clpcorcamjulg     = new cl_pcorcamjulg;

$db_opcao = 1;
$db_botao = true;
$pc80_criterioadjudicacao;
//echo '<pre>';
//print_r($HTTP_POST_VARS);
if(isset($alterar) || isset($incluir)){
  $sqlerro=false;

  if(isset($valores) && trim($valores)!=""){
    $arrval = explode("valor_",$valores);
  }else{
    $sqlerro=true;
    $erro_msg = "Usu�rio: \\n\\nValores do or�amento n�o informados. \\nAltere antes de continuar. \\n\\nAdministrador: ";
  }
  if(isset($qtdades) && trim($qtdades)!=""){
    $arrqtd = explode("qtde_",$qtdades);
  }else{
    $sqlerro=true;
    $erro_msg = "Usu�rio: \\n\\nQuantidades do or�amento n�o informadas. \\nAltere antes de continuar. \\n\\nAdministrador: ";
  }
  if(isset($obss) && trim($obss)!=""){
    $arrmrk = explode("obs_",$obss);
  }
  if(isset($valoresun) && trim($valoresun)!=""){
    $arrvalun = explode("vlrun_",$valoresun);
  }
  if(isset($dataval) && trim($dataval)!=""){
    $arrdat = explode("#",$dataval);
  }
  /*OC3770*/
  if(isset($valorperc) && trim($valorperc)!=""){
    $arrvalperc = explode("percdesctaxa_",$valorperc);
  }
  if(isset($pc20_codorc) && trim($pc20_codorc)!=""){

    $rsResultado = db_query("
      SELECT DISTINCT pc80_criterioadjudicacao
        FROM pcorcamitem
          INNER JOIN pcorcam ON pcorcam.pc20_codorc = pcorcamitem.pc22_codorc
          INNER JOIN pcorcamitemproc ON pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem
          INNER JOIN pcprocitem ON pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem
          INNER JOIN pcproc ON pcprocitem.pc81_codproc = pcproc.pc80_codproc
          INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
          LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
          LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
          LEFT JOIN processocompraloteitem ON pc69_pcprocitem = pcprocitem.pc81_codprocitem
          LEFT JOIN processocompralote ON pc68_sequencial = pc69_processocompralote
          LEFT JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
          LEFT JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
            WHERE pc20_codorc = {$pc20_codorc}
    ");

    $pc80_criterioadjudicacao = db_utils::fieldsMemory($rsResultado, 0)->pc80_criterioadjudicacao;
  }
  /*FIM - OC3770*/
  //db_inicio_transacao();
  if(sizeof($arrval) > 0 && $sqlerro == false){

    if($sqlerro==false){

      $validadorc = $pc21_validadorc_ano."-".$pc21_validadorc_mes."-".$pc21_validadorc_dia;
      $prazoent   = $pc21_prazoent_ano."-".$pc21_prazoent_mes."-".$pc21_prazoent_dia ;

    if ($prazoent=="--" || trim($prazoent)==""){
      $prazoent=null;
    }

    if ($validadorc=="--" || trim($validadorc)==""){
      $validadorc=null;
    }

    $clpcorcamforne->pc21_validadorc = $validadorc;
    $clpcorcamforne->pc21_prazoent   = $prazoent;
    $clpcorcamforne->pc21_orcamforne = $pc21_orcamforne;
    $clpcorcamforne->alterar($pc21_orcamforne);

      if($clpcorcamforne->erro_status==0){
      $sqlerro=true;
        $erro_msg=$clpcorcamforne->erro_msg;
      }
    }

    //db_inicio_transacao();

    for($i=1;$i<sizeof($arrval);$i++){

      $codvalun = explode("_",$arrvalun[$i]);
      $codval   = explode("_",$arrval[$i]);
      $codqtd   = explode("_",$arrqtd[$i]);
      $desmrk   = explode("_",$arrmrk[$i]);
      /*OC3770*/
      $valpercc  = explode("_",$arrvalperc[$i]);
      /*FIM - OC3770*/

      if (trim(@$arrdat[$i])!=""){
        $validmin = $arrdat[$i];
      } else {
        $validmin = null;
      }

      if (trim(@$arrdat[$i])=="--"){
        $validmin = null;
      }

      if(isset($desmrk[1])){
        $orcammrk = str_replace("yw00000wy"," ",$desmrk[1]);
      }else{
        $orcammrk = "";
      }

      $orcamitem  = $codval[0];
      $orcamval   = $codval[1];
      $orcamitem2 = $codqtd[0];
      $orcamqtd   = $codqtd[1];
      $valorunit  = $codvalun[1];
      $valperc    = $valpercc[1];


    if(isset($alterar) && $sqlerro==false){
      $clpcorcamval->excluir($pc21_orcamforne,$orcamitem);

    if($clpcorcamval->erro_status==0){
        $erro_msg = $clpcorcamval->erro_msg;
        $sqlerro=true;
        unset($incluir);
      }else{
        $incluir="incluir";
      }

    }

    if(isset($incluir) && $sqlerro == false) {
      //echo '<script>alert("'.$pc80_criterioadjudicacao.'");</script>';entrou

      //if ($pc80_criterioadjudicacao == 1 || $pc80_criterioadjudicacao == 2 || $orcamval != 0) {

        $pc23_valor = $orcamval;
        $clpcorcamval->pc23_orcamforne = $pc21_orcamforne;
        $clpcorcamval->pc23_orcamitem  = $orcamitem;
        $clpcorcamval->pc23_valor      = $orcamval;
        $clpcorcamval->pc23_quant      = $orcamqtd;
        $clpcorcamval->pc23_obs        = $orcammrk;
        /*OC3770*/
        if (isset($pc80_criterioadjudicacao) && $pc80_criterioadjudicacao == 1) {
          $clpcorcamval->pc23_perctaxadesctabela = $valperc;
        }
        else if (isset($pc80_criterioadjudicacao) && $pc80_criterioadjudicacao == 2) {
          $clpcorcamval->pc23_percentualdesconto = $valperc;
        }
        /*FIM - OC3770*/
        if (isset($validmin) && trim(@$validmin)!="" && $validmin != null) {

          $arr_d  = explode("-",$validmin);
          $validmin = $arr_d[2]."-".$arr_d[1]."-".$arr_d[0];
        } else {
          $validmin = "null";
        }

        $clpcorcamval->pc23_validmin  = $validmin;
        $clpcorcamval->pc23_vlrun     = $valorunit;
        $clpcorcamval->importado      = $importado;
        $clpcorcamval->incluir($pc21_orcamforne,$orcamitem);

        $erro_msg = $clpcorcamval->erro_msg;

        if($clpcorcamval->erro_status==0){

          $sqlerro=true;
          break;
        }

      //}

    }

    if($sqlerro == false){
    $clpcorcamjulg->excluir($orcamitem);

    if($clpcorcamjulg->erro_status==0){

      $erro_msg = $clpcorcamjulg->erro_msg;
      $sqlerro=true;
    }


    /** OC3770
     * @todo passar para Classes
     * Aqui Come�a o Julgamento por item
     */
    $sSQL = "
      SELECT DISTINCT pc23_orcamitem,
       pc01_taxa,
       pc01_tabela
          FROM pcorcamitem
          INNER JOIN pcorcam ON pcorcam.pc20_codorc = pcorcamitem.pc22_codorc
          INNER JOIN pcorcamitemproc ON pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem
          INNER JOIN pcorcamval ON pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem
          INNER JOIN pcprocitem ON pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem
          INNER JOIN pcproc ON pcprocitem.pc81_codproc = pcproc.pc80_codproc
          INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
          LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
          LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
          WHERE pc23_orcamitem = {$orcamitem}
    ";
    $verificaItem = db_query($sSQL);
    db_fieldsmemory($verificaItem, 0);

    if ($pc80_criterioadjudicacao == 1) {
      if ($pc01_tabela == "t") {
        $result_itemfornec  = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne, pc23_orcamitem, pc23_valor, pc23_quant, pc23_perctaxadesctabela","pc23_perctaxadesctabela DESC"," pc23_valor <> 0 AND trim(pc23_valor::text) <> '' AND pc23_orcamitem=$orcamitem "));
      } else {
          $result_itemfornec = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne, pc23_orcamitem, pc23_valor, pc23_quant, pc23_perctaxadesctabela","pc23_valor"," pc23_valor <> 0 AND trim(pc23_valor::text) <> '' AND pc23_orcamitem=$orcamitem  "));
      }
    }
    else if ($pc80_criterioadjudicacao == 2) {
      if ($pc01_taxa == "t") {
        $result_itemfornec  = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne, pc23_orcamitem, pc23_valor, pc23_quant, pc23_percentualdesconto","pc23_percentualdesconto"," pc23_orcamitem=$orcamitem "));
      } else {
          $result_itemfornec = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne, pc23_orcamitem, pc23_valor, pc23_quant, pc23_percentualdesconto","pc23_valor"," pc23_orcamitem=$orcamitem "));
      }
    } else {
        $result_itemfornec  = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne,pc23_orcamitem,pc23_valor,pc23_quant","pc23_valor"," pc23_valor <> 0 AND trim(pc23_valor::text) <> '' AND pc23_orcamitem=$orcamitem "));
    }

    $numrows_itemfornec = $clpcorcamval->numrows;

    if (isset($sol) && $sol=="true") {
      $result_lancitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmatersol($orcamitem,"pc11_quant"));

    } else if (isset($sol) && $sol=="false") {
        $result_lancitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc($orcamitem,"pc11_quant"));
    }

    db_fieldsmemory($result_lancitem,0);
    $pontuacao = 1;
    for ($ii = 0; $ii < $numrows_itemfornec; $ii++) {
      db_fieldsmemory($result_itemfornec, $ii);
      if ($pc80_criterioadjudicacao == 1 && $pc11_quant == $pc23_quant) {
        if ($pc01_tabela == 't' && ($pc23_valor != 0  || $pc23_perctaxadesctabela != 0)) {
          $clpcorcamjulg->pc24_orcamitem  = $pc23_orcamitem;
          $clpcorcamjulg->pc24_orcamforne = $pc23_orcamforne;
          $clpcorcamjulg->pc24_pontuacao  = $pontuacao;
          $clpcorcamjulg->incluir($pc23_orcamitem,$pc23_orcamforne);
          if ($clpcorcamjulg->erro_status == 0) {
            $erro_msg = $clpcorcamjulg->erro_msg;
            $sqlerro=true;
            break;
          }
          $pontuacao++;
        }
        else if ($pc01_tabela == 'f' && $pc23_valor != 0) {
          $clpcorcamjulg->pc24_orcamitem  = $pc23_orcamitem;
          $clpcorcamjulg->pc24_orcamforne = $pc23_orcamforne;
          $clpcorcamjulg->pc24_pontuacao  = $pontuacao;
          $clpcorcamjulg->incluir($pc23_orcamitem,$pc23_orcamforne);
          if ($clpcorcamjulg->erro_status == 0) {
            $erro_msg = $clpcorcamjulg->erro_msg;
            $sqlerro=true;
            break;
          }
          $pontuacao++;
        }
      }

      else if ($pc80_criterioadjudicacao == 2 && $pc11_quant == $pc23_quant) {
        if ($pc01_taxa == 't' && ($pc23_valor != 0  || $pc23_percentualdesconto != 0)) {
          $clpcorcamjulg->pc24_orcamitem  = $pc23_orcamitem;
          $clpcorcamjulg->pc24_orcamforne = $pc23_orcamforne;
          $clpcorcamjulg->pc24_pontuacao  = $pontuacao;
          $clpcorcamjulg->incluir($pc23_orcamitem,$pc23_orcamforne);
          if ($clpcorcamjulg->erro_status == 0) {
            $erro_msg = $clpcorcamjulg->erro_msg;
            $sqlerro=true;
            break;
          }
          $pontuacao++;
        }
        else if ($pc01_taxa == 'f' && $pc23_valor != 0) {
          $clpcorcamjulg->pc24_orcamitem  = $pc23_orcamitem;
          $clpcorcamjulg->pc24_orcamforne = $pc23_orcamforne;
          $clpcorcamjulg->pc24_pontuacao  = $pontuacao;
          $clpcorcamjulg->incluir($pc23_orcamitem,$pc23_orcamforne);
          if ($clpcorcamjulg->erro_status == 0) {
            $erro_msg = $clpcorcamjulg->erro_msg;
            $sqlerro=true;
            break;
          }
          $pontuacao++;
        }
      }

      else if ($pc11_quant == $pc23_quant) {
        $clpcorcamjulg->pc24_orcamitem  = $pc23_orcamitem;
        $clpcorcamjulg->pc24_orcamforne = $pc23_orcamforne;
        $clpcorcamjulg->pc24_pontuacao  = $pontuacao;
        $clpcorcamjulg->incluir($pc23_orcamitem,$pc23_orcamforne);
        if ($clpcorcamjulg->erro_status == 0) {
          $erro_msg = $clpcorcamjulg->erro_msg;
          $sqlerro=true;
          break;
        }
        $pontuacao++;
      }

    }
  }
}

   /**
    * Quando o Orcamento � julgado por Lote
    */
     $oOrcamento = new OrcamentoCompra($pc20_codorc);
     if ($oOrcamento->getFormaJulgamento() == OrcamentoCompra::FORMA_JULGAMENTO_LOTE) {
       $oOrcamento->julgar(new JulgamentoOrcamentoLote());
     }
    /*FIM - OC3770*/
    //db_fim_transacao($sqlerro);
  }

}

 $rsVerificaAut = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null," distinct pc81_codproc ","","pc22_codorc=".$pc20_codorc." and e54_autori is not null and e54_anulad is null"));

 if ($clpcorcamitemproc->numrows > 0 ) {

   db_msgbox("N�o � poss�vel lan�ar valores, existe uma autoriza��o para esse or�amento de processo de compra!");
   db_redireciona("com1_selorc001.php?sol=false");
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
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js");
  db_app::load("widgets/dbtextField.widget.js, dbViewCadEndereco.classe.js");
  db_app::load("dbmessageBoard.widget.js, dbautocomplete.widget.js,dbcomboBox.widget.js, datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
  ?>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
  <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
  <?
  include("forms/db_frmorcamlancval.php");
  ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar)){
  if(isset($alterar)){
    $erro_msg = str_replace("Inclusao","Alteracao",$erro_msg);
    $erro_msg = str_replace("EXclus�o","Alteracao",$erro_msg);
  }
  if($sqlerro==true){
    $erro_msg = str_replace("\n","\\n",$erro_msg);
    db_msgbox($erro_msg);
  }else{
    echo "
    <script>
      x = document.form1;
      tf= false;
      for (i=0;i<x.length;i++) {
        if (x.elements[i].type == 'select-one') {
          numero = new Number(x.elements[i].length);
          for (ii=0;ii<numero;ii++) {
            if (x.elements[i].options[ii].selected==true) {
            numeroteste = new Number(ii+1);
            if (numeroteste<numero && tf==false) {
            x.elements[i].options[ii+1].selected = true;
            js_dalocation(x.elements[i].options[ii+1].value);
            tf = true;
        } else if(tf==false) {
            x.elements[i].options[0].selected = true;
            js_dalocation(x.elements[i].options[0].value);
            tf = true;
        }
      }
    }
  }
      }
    </script>
    ";
  }
}
?>
