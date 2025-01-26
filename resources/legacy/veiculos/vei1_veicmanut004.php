<?

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
//ini_set("display_errors","on");
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veicmanut_classe.php");
include("classes/db_veicmanutitem_classe.php");
include("classes/db_veicmanutoficina_classe.php");
include("classes/db_veicmanutretirada_classe.php");
include("classes/db_veicretirada_classe.php");
include("classes/db_veicabast_classe.php");
include("classes/db_veictipoabast_classe.php");
include("classes/db_veicmanutitempcmater_classe.php");
require_once("classes/db_pcmater_classe.php");
require_once("classes/db_condataconf_classe.php");
db_app::import("veiculos.*");

$clveiculos = new cl_veiculos;
$clveicmanut = new cl_veicmanut;
$clveicmanutitem = new cl_veicmanutitem;
$clveicmanutoficina = new cl_veicmanutoficina;
$clveicmanutretirada = new cl_veicmanutretirada;
$clveicretirada      = new cl_veicretirada;
$clveicabast = new cl_veicabast;

$clveictipoabast     = new cl_veictipoabast;
$clveicmanutitempcmater = new cl_veicmanutitempcmater;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

$sqlerro=false;

if (isset($incluir)) {

  $medida = $ve62_medida;
  $ultimamedida1 = $ultimamedida;
  $ve62_veiccadtiposervico = $ve62_veiccadtiposervico;
  $horaManutencao = $ve62_hora;
  $oDataManutencao = new DBDate($ve62_dtmanut);
  $datahoraManutencao = strtotime($oDataManutencao->getDate() . " " . $ve62_hora);
  $dataManutencao = $oDataManutencao->getDate();
  $retirada = $ve65_veicretirada;
  $tipogasto = $ve62_tipogasto;


    /*
    * verifica se o tipo de gasto est� preenchido
    */
    if($tipogasto=="0"){
      db_msgbox("Tipo de gasto n�o selecionado.");
      $sqlerro=true;
      $erro_msg="N�o foi poss�vel incluir.";
    }

    if($medida<$ultimamedida1){
        db_msgbox("Medida de manuten��o menor que Medida de manuten��o anterior.");
        $sqlerro=true;
        $erro_msg="N�o foi poss�vel incluir.";

    }
    if($ve62_veiccadtiposervico==""){
      db_msgbox("Tipo de servi�o n�o selecionado");
      $sqlerro=true;
      $erro_msg="N�o foi poss�vel incluir.";
      echo "<script> document.form1.ve62_veiccadtiposervico.style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.ve62_veiccadtiposervicofocus();</script>";

  }

    if($retirada == "" || $retirada == null){
        db_msgbox("Campo: Retirada n�o informado");
        $sqlerro=true;
        $erro_msg="N�o foi poss�vel incluir.";
    }

    /*
     * verifica retirada e devolu��o vinculados a manutenc�o
     */
    $result_retirada = $clveicabast->sql_record($clveicabast->sql_query_retirada(null, "ve60_codigo,ve60_medidasaida,ve61_medidadevol,to_timestamp(ve60_datasaida||' '||ve60_horasaida::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS datahrretirada,to_timestamp(ve61_datadevol||' '||ve61_horadevol::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS datahrdevolucao", null, "ve60_codigo=$ve65_veicretirada"));
    if (pg_num_rows($result_retirada) > 0) {
      $oRetirada = db_utils::fieldsMemory($result_retirada, 0);
      $ve60_medidasaida = $oRetirada->ve60_medidasaida;
      $ve61_medidadevol = $oRetirada->ve61_medidadevol;
      $datahoraRetirada = $oRetirada->datahrretirada;
      $datahoraDevolucao = $oRetirada->datahrdevolucao;
    }
    /*
     * verifica manutencao anterior
     */
    $result_manutencao1 = $clveicmanut->sql_record($clveicmanut->sql_query_manutencao(null, "ve62_medida,to_timestamp(ve62_dtmanut||' '||ve62_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve62_dtmanut","ve62_dtmanut desc limit 1;select * from (select ve62_medida,to_timestamp(ve62_dtmanut||' '||ve62_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve62_dtmanut from veicmanut where ve62_veiculos=$ve65_veicretirada ) AS x WHERE ve62_dtmanut < to_timestamp('2017-11-01'||' '||'11:00'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP order by ve62_dtmanut desc limit 1;", "where ve62_veiculos=$ve62_veiculos ) AS x WHERE ve62_dtmanut < to_timestamp('{$oDataManutencao->getDate()}'||' '||'$horaManutencao'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP "));
    if (pg_num_rows($result_manutencao1) > 0) {
      $oManut1 = db_utils::fieldsMemory($result_manutencao1, 0);
      $ve62_medida1 = $oManut1->ve62_medida;
      $ve62_datahora1 = $oManut1->ve62_dtmanut;
    }
    /*
     * verifica manutencao posterior
     */
    $result_manutencao3 = $clveicmanut->sql_record($clveicmanut->sql_query_manutencao(null, "ve62_medida,to_timestamp(ve62_dtmanut||' '||ve62_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve62_dtmanut", null, "where ve62_veiculos=$ve62_veiculos ) AS x WHERE ve62_dtmanut > to_timestamp('{$oDataManutencao->getDate()}'||' '||'$horaManutencao'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP LIMIT 1;"));
    if (pg_num_rows($result_manutencao3) > 0) {
      $oManut3 = db_utils::fieldsMemory($result_manutencao3, 0);
      $ve62_medida3 = $oManut3->ve62_medida;
      $ve62_datahora3 = $oManut3->ve62_dtmanut;
    }
    /*
     * verifica abastecimento anterior
     */
    $result_abast1 = $clveicabast->sql_record($clveicabast->sql_query_file_anula(null, "ve70_medida,to_timestamp(ve70_dtabast||' '||ve70_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve70_dtabast", "ve70_medida DESC LIMIT 1;", "ve60_codigo = $ve65_veicretirada) as x WHERE ve70_dtabast < to_timestamp('{$oDataManutencao->getDate()}'||' '||'$horaManutencao'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP"));
    if (pg_num_rows($result_abast1) > 0) {
      $oAbast1 = db_utils::fieldsMemory($result_abast1, 0);
      $ve70_medida1 = $oAbast1->ve70_medida;
      $ve70_datahora1 = $oAbast1->ve70_dtabast;
    }
    /*
     * verifica abastecimento posterior
     */
    $result_abast3 = $clveicabast->sql_record($clveicabast->sql_query_file_anula(null, "ve70_medida,to_timestamp(ve70_dtabast||' '||ve70_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve70_dtabast", "ve70_medida ASC LIMIT 1;", "ve60_codigo = $ve65_veicretirada) as x WHERE ve70_dtabast > to_timestamp('{$oDataManutencao->getDate()}'||' '||'$horaManutencao'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP"));
    if (pg_num_rows($result_abast3) > 0) {
      $oAbast3 = db_utils::fieldsMemory($result_abast3, 0);
      $ve70_medida3 = $oAbast3->ve70_medida;
      $ve70_datahora3 = $oAbast3->ve70_dtabast;
    }
    if (!empty($ve62_datahora1) && $datahoraManutencao < strtotime($ve62_datahora1)) {
      db_msgbox("Data ou Hora da manuten��o menor que manuten��o anterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } elseif (!empty($ve62_datahora3) && $datahoraManutencao > strtotime($ve62_datahora3)) {
      db_msgbox("Data ou Hora da manuten��o maior que manuten��o posterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } elseif (!empty($ve70_datahora1) && $datahoraManutencao < strtotime($ve70_datahora1)) {
      db_msgbox("Data ou Hora da manuten��o menor que abastecimento anterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } elseif (!empty($ve70_datahora3) && $datahoraManutencao > strtotime($ve70_datahora3)) {
      db_msgbox("Data ou Hora da manuten��o maior que abastecimento posterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } else if (!empty($ve62_medida1) && $medida < $ve62_medida1) {

      db_msgbox("Medida de manuten��o menor que Medida de manuten��o anterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } /*else if (!empty($ve62_medida3) && $medida > $ve62_medida3) {
      db_msgbox("Medida de manuten��o2 maior que Medida de manuten��o posterior.".$ve62_medida3." - ".$medida);
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    }*/ else if (!empty($ve70_medida1) && $medida < $ve70_medida1) {
      db_msgbox("Medida de manuten��o menor que Medida de abastecimento anterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } else if (!empty($ve70_medida3) && $medida > $ve70_medida3) {
      db_msgbox("Medida de manuten��o maior que Medida de abastecimento posterior.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } else if (!empty($datahoraRetirada) && $datahoraManutencao < strtotime($datahoraRetirada)) {
      db_msgbox("Data ou Hora da manuten��o menor que da Retirada.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } else if (!empty($datahoraDevolucao) && $datahoraManutencao > strtotime($datahoraDevolucao)) {
      db_msgbox("Data ou Hora da manuten��o maior que da Devolucao.");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } else if (!empty($ve60_medidasaida) && $medida < $ve60_medidasaida) {
      db_msgbox("Medida de manuten��o menor que Medida de Retirada");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    } else if (!empty($ve61_medidadevol) && $medida > $ve61_medidadevol) {
      db_msgbox("Medida de manuten��o maior que Medida de Devolucao");
      $sqlerro = true;
      $erro_msg = "N�o foi poss�vel incluir.";
    }

    /**
     * Verificar Encerramento Periodo Patrimonial
     */
    if (!empty($ve62_dtmanut)) {
        $clcondataconf = new cl_condataconf;
        if (!$clcondataconf->verificaPeriodoPatrimonial($ve62_dtmanut)) {
            $sqlerro  = true;
            $erro_msg=$clcondataconf->erro_msg;
        }
    }

    if ($sqlerro == false) {
      db_inicio_transacao();
      $clveicmanut->ve62_usuario = db_getsession("DB_id_usuario");
      $clveicmanut->ve62_data = date("Y-m-d", db_getsession("DB_datausu"));
      $clveicmanut->incluir(null);
      if ($clveicmanut->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clveicmanut->erro_msg;
      }
      $erro_msg = $clveicmanut->erro_msg;
      if ($sqlerro == false) {
        if (isset($ve66_veiccadoficinas) && $ve66_veiccadoficinas != "") {
          $clveicmanutoficina->ve66_veicmanut = $clveicmanut->ve62_codigo;
          $clveicmanutoficina->incluir(null);
          if ($clveicmanutoficina->erro_status == "0") {
            $erro_msg = $clveicmanutoficina->erro_msg;
            $sqlerro = true;
          }
        }
      }
      if ($sqlerro == false) {
        if (isset($ve65_veicretirada) && $ve65_veicretirada != "") {
          $clveicmanutretirada->ve65_veicmanut = $clveicmanut->ve62_codigo;
          $clveicmanutretirada->incluir(null);
          if ($clveicmanutretirada->erro_status == "0") {
            $erro_msg = $clveicmanutretirada->erro_msg;
            $sqlerro = true;
          }
        }
      }
      db_fim_transacao($sqlerro);
    }

  }
  $ve62_codigo = $clveicmanut->ve62_codigo;
  $db_opcao = 1;
  $db_botao = true;
  ?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      .cabec {
        text-align: left;
        font-size: 10;
        color: darkblue;
        background-color: #aacccc;
        border: 1px solid $FFFFFF;
      }
      .corpo {
        font-size: 9;
        color: black;
        background-color: #ccddcc;
      }
    </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin-right: 25px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <?
    /**
     * Altera��o realizada para atender os diversis layouts do SICOM. Para cada ano, existem atualiza��es
     * nos formularios que conflitam entre se. Portanto foi adotado que fossem criados formul�rios espec�ficos
     * para cada ano.
     */

    if(db_getsession('DB_anousu') > 2015) {

        include("forms/db_frmveicmanutcstitens.php");
    } else {
        include("forms/db_frmveicmanut.php");
    }
    ?>
  </body>
  </html>
  <?
  if(isset($incluir)){

    if($sqlerro==true){
      db_msgbox($erro_msg);
      echo "<script> document.getElementById('itensLancados').style.display = 'block';</script>";
      if($clveicmanut->erro_campo!=""){
        echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
        echo "<script> document.getElementById('itensLancados').style.display = 'block';</script>";
      };
    }else{
      $itemDescricoes = $_POST['itemDescricao']; // Isso ser� um array
      $itemQuantidades = $_POST['itemQuantidade']; // Isso ser� um array
      $itemValores = $_POST['itemVlrunitario']; // Isso ser� um array
      $itemCodigos = $_POST['itemCodigo'];
      $count = count($itemDescricoes); // Conta o n�mero de elementos no array
  
      // Usando um loop for para iterar sobre o array
      for ($i = 0; $i < $count; $i++) {
  
              $item = array(
                "ve63_descr" => $itemDescricoes[$i],
                "ve63_quant" => $itemQuantidades[$i],
                "ve63_vlruni" => $itemValores[$i]
              );
  
              if($sqlerro==false){
                db_inicio_transacao();
                $clveicmanutitem->incluir("",$item,$ve62_codigo);
                $erro_msg = $clveicmanutitem->erro_msg;
                if($clveicmanutitem->erro_status==0){
                  $sqlerro=true;
                }
              }
  
              if($sqlerro==false && $itemCodigos[$i] != ''){
  
                $aPcmater = array("ve64_pcmater" => $itemCodigos[$i]);
                $clveicmanutitempcmater->incluir(null,$aPcmater,$clveicmanutitem->ve63_codigo);
                if($clveicmanutitempcmater->erro_status==0){
                  $erro_msg = $clveicmanutitempcmater->erro_msg;
                  $sqlerro=true;
                }
  
              }
  
              db_fim_transacao($sqlerro);
  
      }
    }



    db_msgbox($erro_msg);
    //db_redireciona("vei1_veicmanut005.php?liberaaba=true&chavepesquisa=$ve62_codigo");
    db_redireciona("vei1_veicmanut004.php");

  }

?>
