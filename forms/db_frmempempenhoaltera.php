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

//MODULO: empenho
$clempempenho->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc50_descr");
$clrotulo->label("e60_codcom");
$clrotulo->label("e63_codhist");
$clrotulo->label("e44_tipo");
$clrotulo->label("c58_descr");
$clrotulo->label("e60_convenio");
$clrotulo->label("e60_numconvenio");
$clrotulo->label("e60_dataconvenio");
$clrotulo->label("e60_datasentenca");
require_once("model/protocolo/AssinaturaDigital.model.php");

$AssinaturaDigital = new AssinaturaDigital();

$aDataEmpenho = explode("-", $e60_emiss);
?>
<form name="form1" method="post" action="">

    <input type=hidden id="chavepesquisa" value="">
    <input type="hidden" id="obriga_divida" name="obriga_divida">

    <center>
        <table border="0">
            <tr>
                <td nowrap title="<?=@$Te60_codemp?>">
                    <?=@$Le60_codemp?>
                </td>
                <td>
                    <?
                    db_input('e60_numemp',10,'',true,'hidden',3);
                    db_input('e60_emiss',10,'',true,'hidden',3);
                    db_input('e60_coddot',10,'',true,'hidden',3);
                    db_input('e60_vlremp',10,'',true,'hidden',3);
                    db_input('e60_codemp',10,$Ie60_codemp,true,'text',3);
                    ?>
                    <b>Data Empenho:</b>
                        <?
                        db_inputdata("data_empenho",$aDataEmpenho[2], $aDataEmpenho[1], $aDataEmpenho[0], true, "text", 2);
                        ?>
                        <input type="hidden" name="data_empenho_alterado" value="false">
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te60_numcgm?>">
                    <?=$Le60_numcgm?>
                </td>
                <td>
                    <?
                    db_input('e60_numcgm',10,$Ie60_numcgm,true,'text',3);
                    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=$Te60_numcgmordenador?>">
                    <?db_ancora('Ordenador da Despesa',"js_pesquisa_cgm(true);",$fimperiodocontabil);?>
                </td>
                <td>
                    <?  $e60_numcgmordenadoranterior = $e60_numcgmordenador;
                        db_input("e60_numcgmordenador",10,$Ie60_numcgmordenador,true,"text",$fimperiodocontabil,"onChange='js_pesquisa_cgm(false);'");
                        db_input("e60_nomeordenador",40,$Ie60_nomeordenador,true,"text",3);
                        db_input("e60_numcgmordenadoranterior",10,$Ie60_numcgmordenador,true,"hidden");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te60_codcom?>">
                    <?=$Le60_codcom?>
                </td>
                <td>
                    <?
                    db_input('e60_codcom',10,$Ie60_codcom,true,'text',3);
                    db_input('pc50_descr',40,$Ipc50_descr,true,'text',3,'');

                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te60_tipol?>">
                    <?=@$Le60_tipol?>
                </td>
                <td>
                    <?
                    if(isset($e60_codcom)){
                        $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",'',"l03_codcom=$e60_codcom"));
                        if($clcflicita->numrows>0){
                            db_selectrecord("e60_tipol",$result,true,1,"","","");
                            $dop=$db_opcao;
                        }else{
                            $e60_tipol='';
                            $e60_numerol='';
                            db_input('e60_tipol',10,$Ie60_tipol,true,'text',3);
                            $dop='3';
                        }
                        ?>

                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te60_numerol?>">
                    <?=@$Le60_numerol?>
                </td>
                <td>
                        <?
                        db_input('e60_numerol',10,$Ie60_numerol,true,'text',$dop);
                        ?>
                        <strong>Modalidade:</strong>
                        <?
                        db_input('e54_nummodalidade', 8, $e54_nummodalidade, true, 'text', 3, "");

                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te60_codtipo?>">
                    <?=$Le60_codtipo?>
                </td>
                <td>
                    <?
                    $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
                    db_selectrecord("e60_codtipo",$result,true,$db_opcao);

                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te63_codhist?>">
                    <?=$Le63_codhist?>
                </td>
                <td>
                    <?

                    $result=$clemphist->sql_record($clemphist->sql_query_file(null,"e40_codhist,e40_descr"));
                    db_selectrecord("e63_codhist",$result,true,1,"","","","Nenhum");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te44_tipo?>">
                    <?=$Le44_tipo?>
                </td>
                <td>
                    <?
                    $result=$clempprestatip->sql_record($clempprestatip->sql_query_file(null,"e44_tipo as tipo,e44_descr,e44_obriga","e44_obriga "));
                    $numrows =  $clempprestatip->numrows;
                    $arr = array();
                    for($i=0; $i<$numrows; $i++){
                        db_fieldsmemory($result,$i);
                        if($e44_obriga == 0 && empty($e44_tipo)){
                            $e44_tipo = $tipo;
                        }
                        $arr[$tipo] = $e44_descr;
                    }
                    db_select("e44_tipo",$arr,true,3);

                    ?>
                </td>
            </tr>

            <?
            if (isset($e60_numemp)) {

                $sql = "select pagordem.* from pagordem inner join pagordemdesconto on e34_codord = e50_codord
	  													where e50_numemp = $e60_numemp";
                //die($sql);
                $result = $clpagordem->sql_record($sql);
                $ldesconto = false;
                if ($clpagordem->numrows > 0) {
                    $ldesconto = true;
                }
            }

                ?>
                <tr>
                    <td nowrap title="Desdobramentos">
                        <b><?="Desdobramento:"?></b>
                    </td>
                    <td>
                        <?
                        $result = $clempempaut->sql_record($clempempaut->sql_query(null,"e61_autori,e54_anousu","","e61_numemp = $e60_numemp"));
                        if($clempempaut->numrows > 0){
                            $oResult = db_utils::fieldsMemory($result,0);
                            $e54_autori = $oResult->e61_autori;
                            $anoUsu =  $oResult->e54_anousu;
                            $sWhere = "e56_autori = ".$e54_autori." and e56_anousu = ".$anoUsu;
                            $result = $clempautidot->sql_record($clempautidot->sql_query_dotacao(null,"e56_coddot",null,$sWhere));
                            if($clempautidot->numrows > 0){
                                $oResult = db_utils::fieldsMemory($result,0);
                                $result = $clorcdotacao->sql_record($clorcdotacao->sql_query( $anoUsu,$oResult->e56_coddot,"o56_elemento,o56_codele"));
                                if ($clorcdotacao->numrows > 0) {

                                    $oResult = db_utils::fieldsMemory($result,0);
                                    $oResult->estrutural = criaContaMae($oResult->o56_elemento."00");
                                    $sWhere = "o56_elemento like '$oResult->estrutural%' and o56_codele <> $oResult->o56_codele and o56_anousu = $anoUsu";
                                    $sSql = "select distinct o56_codele,o56_elemento,o56_descr
											  from empempitem
											        inner join pcmater on pcmater.pc01_codmater    = empempitem.e62_item
											        inner join pcmaterele on pcmater.pc01_codmater = pcmaterele.pc07_codmater
											        left join orcelemento on orcelemento.o56_codele = pcmaterele.pc07_codele
											                              and orcelemento.o56_anousu = $anoUsu
											    where o56_elemento like '$oResult->estrutural%'
											    and e62_numemp = $e60_numemp and o56_anousu = $anoUsu";
                                    $result = $clorcelemento->sql_record($sSql);

                                    $oResult = db_utils::getCollectionByRecord($result);

                                    $numrows =  $clorcelemento->numrows;
                                    $aEle = array();

                                    foreach ($oResult as $oRow){
                                        $aEle[$oRow->o56_codele] = $oRow->o56_descr;
                                        $aCodele[] = substr($oRow->o56_elemento,1);
                                    }
                                    //die($clempautitem->sql_query_autoriza (null,null,"e55_codele",null,"e55_autori = $e54_autori"));
                                    $result = $clempelemento->sql_record($clempelemento->sql_query_file($e60_numemp,null,"e64_codele"));
                                    if($clempelemento->numrows > 0){
                                        $oResult = db_utils::fieldsMemory($result,0);
                                    }
                                    if(!isset($e56_codele)){
                                        $e56_codele = $oResult->e64_codele;
                                    }
                                    $e64_codele = $e56_codele;
                                    // db_input('e64_codele',10,0,true,'',3);
                                    db_select("e64_codele",$aCodele,true,1);
                                    db_select("e56_codele",$aEle,true,1);
                                }
                            }
                        }else{
                            $aEle = array();
                            $e56_codele = "";
                            db_select("e56_codele",$aEle,true,1);
                        }
                        ?>
                    </td>
            </tr>
            <tr>
                <td nowrap title="Tipos de despesa">
                    <strong>Tipos de despesa :</strong>
                </td>
                <td>
                    <?
                    $arr  = array('0'=>'N�o se aplica','1'=>'Executivo','2'=>'Legislativo');
                    db_select("e60_tipodespesa", $arr, true, 1);
                    ?>
                </td>
            </tr>
            <tr id="trCessaoMaodeObra" name="trCessaoMaodeObra" >
                    <td nowrap title="Cess�o de m�o de obra/empreitada">
                        <strong>Cess�o de m�o de obra/empreitada:</strong>
                    </td>
                    <td>
                        <?
                        $arr  = array(
                            '1' => 'N�o',
                            '2' => 'Sim'
                        );
                        db_select("efd60_cessaomaoobra", $arr, true, 1, "onchange='showForm(this);'");
                        ?>
                    </td>
                </tr>
                <?php if (substr($aCodele[0],0,6) == '339030') { ?>
                <tr id="trAquisicaoProducaoRural">
                    <td nowrap title="Aquisi��o de Produ��o Rural ">
                        <strong>Aquisi��o de Produ��o Rural :</strong>
                    </td>
                    <td>
                        <?
                         $arr  = array(
                            '0' => '0 - N�o se aplica',
                            '1' => '1 - Aquisi��o de produ��o de produtor rural pessoa f�sica ou segurado especial em geral',
                            '2' => '2 - Aquisi��o de produ��o de produtor rural pessoa f�sica ou segurado especial em geral por entidade executora do Programa de Aquisi��o de Alimentos PAA',
                            '4' => '4 - Aquisi��o de produ��o de produtor rural pessoa f�sica ou segurado especial em geral Produ��o isenta (Lei 13.606/2018)',
                            '5' => '5 - Aquisi��o de produ��o de produtor rural pessoa f�sica ou segurado especial em geral por entidade executora do PAA Produ��o isenta (Lei 13.606/2018)',
                            '7' => '7 - Aquisi��o de produ��o de produtor rural pessoa f�sica ou segurado especial para fins de exporta��o',

                        );
                        if (strlen($z01_cgccpf) == 14 ) {
                            $arr  = array(
                                '0' => '0 - N�o se aplica',
                                '3' => '3 - Aquisi��o de produ��o de produtor rural pessoa jur�dica por entidade executora do PAA',
                                '6' => '6 - Aquisi��o de produ��o de produtor rural pessoa jur�dica por entidade executora do PAA Produ��o isenta (Lei 13.606/2018)',
                            );

                        }

                        db_select("efd60_aquisicaoprodrural", $arr, true, 1, "onchange='showRowProdutorRural(this);'");
                        ?>
                    </td>
                </tr>
                <tr id="trProdutoroptacp" style="display: none;">
                                <td nowrap title="O Produtor Rural opta pela CP sobre a folha">
                                <strong>O Produtor Rural opta pela CP sobre a folha:</strong>
                                </td>
                                <td>
                                <?
                                $arr  = array(
                                    '1' => 'N�o',
                                    '2' => 'Sim'
                                );
                                db_select("efd60_prodoptacp", $arr, true, 1);
                                ?>
                                </td>
                            </tr>
               <?php } ?>
                <tr id="reinf">
                    <td id="reinftd" colspan="1">
                        <fieldset style="width:84%" >
                        <legend><strong>EFD-REINF</strong></legend>
                            <table >
                            <tr id="trCNO">
                                <td nowrap title="Possui Cadastro Nacional de Obras (CNO)">
                                <strong>Possui Cadastro Nacional de Obras (CNO):</strong>
                                </td>
                                <td>
                                <?
                                $arr  = array(
                                    '0' => 'Selecione',
                                    '1' => 'Sim',
                                    '2' => 'N�o'
                                );
                                db_select("efd60_possuicno", $arr, true, 1,"onchange='showRowCNO(this);'");
                                ?>
                                </td>
                            </tr>
                            <tr id="trNumCno" style="display: none;">
                                <td nowrap title="N�mero do CNO">
                                <b>N�mero do CNO:</b>
                                </td>
                                <td>
                                <?php
                                db_input('efd60_numcno', 12, $Iefd60_numcno, true, 'text', 1);

                                ?>
                                <script>
                                    function formatAndLimitCNO() {
                                    var cnoInput = document.getElementById('efd60_numcno');
                                    cnoInput.value = cnoInput.value.replace(/\D/g, '')
                                        .slice(0, 12)
                                        .replace(/(\d{2})(\d{3})(\d{5})(\d{2})/, '$1.$2.$3/$4');
                                    }
                                    document.getElementById('efd60_numcno').addEventListener('input', formatAndLimitCNO);
                                </script>
                                </td>
                            </tr>
                            <tr id="trIndicativoPrestServicos">
                                <td nowrap title="Possui Cadastro Nacional de Obras (CNO)">
                                <strong>Indicativo de Presta��o de Servi�os em Obra de Constru��o Civil:</strong>
                                </td>
                                <td>
                                <?
                                $arr  = array(
                                    '' =>  'Selecione',
                                    '0' => '0 - N�o � obra de constru��o civil ou n�o est� sujeita a matr�cula de obra',
                                    '1' => '1 - � obra de constru��o civil, modalidade empreitada total',
                                    '2' => '2 - � obra de constru��o civil, modalidade empreitada parcial.'
                                );
                                db_select("efd60_indprestservico", $arr, true, 1);
                                ?>
                                </td>
                            </tr>
                            <tr id="trPrestContrCPRB: ">
                                <td nowrap title="Prestador � contribuinte da CPRB">
                                <strong>Prestador � contribuinte da CPRB:</strong>
                                </td>
                                <td>
                                <?
                                $arr  = array(
                                    '' => 'Selecione',
                                    '0' => '0 - N�o � contribuinte da CPRB reten��o de 11%',
                                    '1' => '1 - � contribuinte da CPRB reten��o de 3,5%.'
                                );
                                db_select("efd60_prescontricprb", $arr, true, 1);
                                ?>
                                </td>
                            </tr>
                            <tr id="trTipoServico: ">
                                <td nowrap title=" Tipo de Servi�o">
                                <strong> Tipo de Servi�o:</strong>
                                </td>
                                <td>
                                <?
                                $arr = array(
                                    ''=> 'Selecione',
                                    '100000001'=> '100000001 - Limpeza, conserva��o ou zeladoria',
                                    '100000002'=> '100000002 - Vigil�ncia ou seguran�a',
                                    '100000003'=> '100000003 - Constru��o civil',
                                    '100000004'=> '100000004 - Servi�os de natureza rural',
                                    '100000005'=> '100000005 - Digita��o',
                                    '100000006'=> '100000006 - Prepara��o de dados para processamento',
                                    '100000007'=> '100000007 - Acabamento',
                                    '100000008'=> '100000008 - Embalagem',
                                    '100000009'=> '100000009 - Acondicionamento',
                                    '100000010'=> '100000010 - Cobran�a',
                                    '100000011'=> '100000011 - Coleta ou reciclagem de lixo ou de res�duos',
                                    '100000012'=> '100000012 - Copa',
                                    '100000013'=> '100000013 - Hotelaria',
                                    '100000014'=> '100000014 - Corte ou liga��o de servi�os p�blicos',
                                    '100000015'=> '100000015 - Distribui��o',
                                    '100000016'=> '100000016 - Treinamento e ensino',
                                    '100000017'=> '100000017 - Entrega de contas e de documentos',
                                    '100000018'=> '100000018 - Liga��o de medidores',
                                    '100000019'=> '100000019 - Leitura de medidores',
                                    '100000020'=> '100000020 - Manuten��o de instala��es, de m�quinas ou de equipamentos',
                                    '100000021'=> '100000021 - Montagem',
                                    '100000022'=> '100000022 - Opera��o de m�quinas, de equipamentos e de ve�culos',
                                    '100000023'=> '100000023 - Opera��o de ped�gio ou de terminal de transporte',
                                    '100000024'=> '100000024 - Opera��o de transporte de passageiros',
                                    '100000025'=> '100000025 - Portaria, recep��o ou ascensorista',
                                    '100000026'=> '100000026 - Recep��o, triagem ou movimenta��o de materiais',
                                    '100000027'=> '100000027 - Promo��o de vendas ou de eventos',
                                    '100000028'=> '100000028 - Secretaria e expediente',
                                    '100000029'=> '100000029 - Sa�de',
                                    '100000030'=> '100000030 - Telefonia ou telemarketing',
                                    '100000031'=> '100000031 - Trabalho tempor�rio na forma da Lei n� 6.019, de janeiro de 1974'
                                      );

                                db_select("efd60_tiposervico", $arr, true, 1);
                                ?>
                                </td>
                            </tr>
                            </table>
                    </td>
                </tr>
           <!-- Campos referentes ao sicom 2023 - OC20029  -->
           <tr id="trEmendaParlamentar" style="display: none;">
                <td nowrap title="Referente a Emenda Parlamentar">
                    <strong>Referente a Emenda Parlamentar:</strong>
                </td>
                <td>
                    <?
                        $arr  = array(
                            '0' => 'Selecione',
                            '1' => '1 - Emenda Parlamentar Individual',
                            '2' => '2 - Emenda Parlamentar de Bancada ou Bloco',
                            '3' => '3 - N�o se aplica',
                            '4' => '4 - Emenda N�o Impositiva');

                        db_select("e60_emendaparlamentar", $arr, true, 1, "onchange='js_verificaresfera();'");
                        ?>
                </td>
            </tr>

            <tr id="trEsferaEmendaParlamentar" style="display: none;">
                <td nowrap title="Esfera da Emenda Parlamentar">
                    <strong>Esfera da Emenda Parlamentar:</strong>
                </td>
                <td>
                    <?
                        $arr  = array(
                            '0' => 'Selecione',
                            '1' => '1 - Unio',
                            '2' => '2 - Estados');

                        db_select("e60_esferaemendaparlamentar", $arr, true, 1);
                        ?>
                </td>
            </tr>
            <!-- Final dos campos referentes ao sicom 2023 - OC20029 -->

            <tr id="trFinalidadeFundeb" style="display: none;">
                <td><b>Finalidade:</b></td>
                <td>
                    <?php
                    $oDaoFinalidadeFundeb = db_utils::getDao('finalidadepagamentofundeb');
                    $sSqlFinalidadeFundeb = $oDaoFinalidadeFundeb->sql_query_file(null, "e151_codigo, e151_descricao", "e151_codigo");
                    $rsBuscaFinalidadeFundeb = $oDaoFinalidadeFundeb->sql_record($sSqlFinalidadeFundeb);
                    db_selectrecord('e151_codigo', $rsBuscaFinalidadeFundeb, true, 1);
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Te60_destin?>">
                    <?=@$Le60_destin?>
                </td>
                <td>
                    <?
                    db_input('e60_destin',40,$Ie60_destin,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>


            <tr>
                <td nowrap title="Gestor do Empenho">
                    <?php
                    db_ancora('Gestor do Empenho:', "js_pesquisae54_gestaut(true);", $db_opcao);
                    ?>
                </td>
                <td>
                    <?php
                    db_input("e54_gestaut", 10, $Ie54_gestaut, true, "text", 3);
                    db_input("e54_autori", 10, $Ie54_autori, true, "hidden", 3);
                    db_input("e54_nomedodepartamento", 50, 0, true, "text", 3);

                    $iCodDepartamentoAtual = empty($e54_gestaut) ? db_getsession('DB_coddepto') : $e54_gestaut;
                    $sNomDepartamentoAtual = db_utils::fieldsMemory(db_query(" SELECT descrdepto FROM db_depart WHERE coddepto = {$iCodDepartamentoAtual} "), 0)->descrdepto;
                    ?>
                    <input type="hidden" name="gestor_alterado" id="gestor_alterado" value="false">
                </td>
            </tr>


            <tr>
            <td nowrap title="<?= @$Te54_resumo ?>" valign='top' colspan="2">

                <fieldset style="width:84%">
                    <legend><strong>Resumo:</strong></legend>
                    <?php
                    if (empty($e60_resumo))
                        $e60_resumo = $e54_resumo;
                    db_textarea('e60_resumo', 3, 109, $Ie54_resumo, true, 'text', $db_opcao,"","","#FFFFFF");
                    ?>
                </fieldset>
                </td>
                </tr>

                <tr>
                <td nowrap title="<?= @$Te54_resumo ?>" valign='top' colspan="2">

                <fieldset style="width:84%">
                    <legend><strong>Hist�rico Padr�o da OP:</strong></legend>
                    <?php
                    $empenhoLiquidado = $e60_vlremp-$e60_vlranu-$e60_vlrliq;
                    db_textarea('e60_informacaoop', 3, 109, $Ie54_resumo, true, 'text', $empenhoLiquidado > 0 ? $db_opcao : 3,"onchange=js_historico_alterado()","",$empenhoLiquidado > 0 ? "#FFFFFF" : "#DEB887");
                    ?>
                    <input type="hidden" name="historico_alterado" id="historico_alterado" value="false">
                </fieldset>
                </td>
            </tr>



            <?
            $anousu = db_getsession("DB_anousu");

            if ($anousu > 2007){
                ?>
                <tr>
                    <td nowrap title="<?=@$Te60_concarpeculiar?>"><?
                        db_ancora(@$Le60_concarpeculiar,"js_pesquisae60_concarpeculiar(true);",$db_opcao);
                        ?></td>
                    <td>
                        <?
                        db_input("e60_concarpeculiar",10,$Ie60_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisae60_concarpeculiar(false);'");
                        db_input("c58_descr",50,0,true,"text",3);
                        ?>
                    </td>
                </tr>
                <?
            } else {
                $e60_concarpeculiar = 0;
                db_input("e60_concarpeculiar",10,0,true,"hidden",3,"");

            }
            if (isset($e60_numemp) && isset($e30_notaliquidacao) && $e30_notaliquidacao != '') {
                $rsNotaLiquidacao  = $oDaoEmpenhoNl->sql_record(
                    $oDaoEmpenhoNl->sql_query_file(null,"e68_numemp","","e68_numemp = {$e60_numemp}"));
                if ($oDaoEmpenhoNl->numrows == 0) {
                    ?>
                    <tr>
                        <td nowrap title="Nota de liquida��o">
                            <b>Nota de liquida��o:</b>
                        </td>
                        <td>
                            <?
                            $aNota = array("s"=>"Sim","n" => "N�O");
                            db_select("e68_numemp",$aNota,true,1);
                            ?>
                        </td>
                    </tr>
                    <?
                }
            }
            ?>
            <!--
            <tr>
                <td nowrap title="<?//=@$Te60_convenio?>">
                    <?//=@$Le60_convenio?>
                </td>
                <td>
                    <?
            //$aConvenio = array('2' => 'N�o','1' => 'Sim');
            //db_select('e60_convenio', $aConvenio, true, $db_opcao,"");
            ?>
                </td>
            </tr>
            -->
            <tr>
                <td nowrap title="C�digo c206_sequencial">
                    <? db_ancora("Conv�nio","js_pesquisae60_numconvenio(true);",$db_opcao); ?>
                </td>
                <td>
                    <?
                    db_input('e60_numconvenio',11,$Ie60_numconvenio,true,'text',$db_opcao,"onChange='js_pesquisae60_numconvenio(false);'");
                    db_input("c206_objetoconvenio",50,0,true,"text",3);
                    ?>
                </td>
            </tr>

            <tr>
                <td nowrap title="D�vida Consolidada">
                    <? db_ancora("D�vida Consolidada", "js_pesquisaDivida(true);", $db_opcao); ?>
                </td>
                <td>
                    <? db_input("op01_numerocontratoopc",11,$op01_numerocontratoopc,true,"text",$db_opcao,"onChange='js_pesquisaDivida(false);'") ?>
                    <? db_input("op01_text_numerocontratoopc",50, $op01_text_numerocontratoopc,true,"text",3) ?>
                </td>
            </tr>

            <!--
            <tr>
                <td nowrap title="<?//=@$Te60_dataconvenio?>">
                    <?//=@$Le60_dataconvenio?>
                </td>
                <td>
                    <?
            //db_inputData('e60_dataconvenio',@$e60_dataconvenio_dia, @$e60_dataconvenio_mes,@$e60_dataconvenio_ano, true, 'text', $db_opcao);
            ?>
                </td>
            </tr>-->
            <tr>
                <td nowrap title="<?=@$Te60_datasentenca?>">
                    <?=@$Le60_datasentenca?>
                </td>
                <td>
                    <?
                    db_inputData('e60_datasentenca',@$e60_datasentenca_dia, @$e60_datasentenca_mes,@$e60_datasentenca_ano, true, 'text', $db_opcao);
                    ?>
                </td>
            </tr>

        </table>
    </center>

    <input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> onclick='return js_valida()' ; >

    <? if ($AssinaturaDigital->verificaAssituraAtiva()) { ?>
        <input name="alterar" type="submit" id="db_opcao" value="Solicitar Nova Assinatura Digital" <?=($db_botao==false?"disabled":"")?> onclick='return js_emiteEmpenho( <?=$e60_numemp?>)' ; >
    <? } ?>

    <input type="button" id="btnLancarCotasMensais" value="Manuten��o de Cotas Mensais" onclick="manutencaoCotasMensais()" />

    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar empenhos" onclick="js_pesquisa();" >
</form>

<style>
    #e60_tipodespesa{ width: 140px; }#e60_codtipodescr{width: 342px}#e63_codhistdescr{width: 342px}#pc50_descr{width: 333px}#e44_tipo{width: 228px;}#e57_codhistdescr{width: 158px;}#e54_codtipodescr{width: 158px;}#e54_codtipo{width: 67px;}#e54_tipol{width: 67px;}#e54_tipoldescr{width: 158px;}#e54_codcom{width: 67px;}#z01_nome{width: 333px;}#e54_destin{width: 424px;}#e54_gestaut{width: 67px;}#e54_nomedodepartamento{width: 354px;}#op01_text_numerocontratoopc{width: 349.96px;}#ac16_resumoobjeto{width: 364px;}#e60_numconvenio{width: 83px;}#op01_numerocontratoopc{width: 81.5px;}#e54_resumo,#e60_resumo,#e60_informacaoop{width: 588px;}#e50_obs{width: 588px;}#e56_codele{width: 140px}
</style>


<script>

    var lEsferaEmendaParlamentar = 'f';
    var bEmendaParlamentar = false;
    var bEsferaEmendaParlamentar = false;

    document.addEventListener('DOMContentLoaded', function() {

        var eventoChange = new Event('change', { bubbles: true});
        document.getElementById('op01_numerocontratoopc').dispatchEvent(eventoChange);

        document.getElementById('e64_codele').addEventListener('input', function(e) {
            var obrigaDivida = obrigaPreenchimentoDivida();
            document.getElementById('obriga_divida').value = obrigaDivida;
        });

        document.getElementById('op01_numerocontratoopc').addEventListener('input', function(e) {
            var valor = e.target.value;
            if(valor.trim() === '') {
                document.getElementById('op01_text_numerocontratoopc').value = '';
            }
        });

    });

    function getRegrasFiltrarDivida() {

        var desdobramento  = document.getElementById('e64_codele');
        var optionSelected = desdobramento.options[desdobramento.selectedIndex].text;

        var condicoes = "";

        var prefixosOutrasDividas    = ['3271', '3273', '3274', '4671', '4673', '4674'];
        var prefixoIntraor�amentaria = ['3291', '4691'];

        if(prefixosOutrasDividas.some(prefixo => optionSelected.startsWith(prefixo))) {
            condicoes = " AND op01_tipolancamento = 20 AND op01_detalhamentodivida = 15";
        } else if (prefixoIntraor�amentaria.some(prefixo => optionSelected.startsWith(prefixo))) {
            condicoes = " AND op01_tipocredor = 2";
        } else {
            condicoes = " AND op01_tipolancamento <> 20 AND op01_detalhamentodivida <> 15 AND op01_tipocredor <> 2";
        }

        return condicoes;
    }

    function obrigaPreenchimentoDivida() {

        var desdobramento  = document.getElementById('e64_codele');
        var optionSelected = desdobramento.options[desdobramento.selectedIndex].text;

        if (optionSelected.startsWith('32') || optionSelected.startsWith('46')) {
            return 'sim';
        }

        return 'nao';
    }

    function js_verificaresfera() {
        if ($F('e60_emendaparlamentar') != 3 && lEsferaEmendaParlamentar == 't') {
            $('trEsferaEmendaParlamentar').style.display = '';
            bEsferaEmendaParlamentar = true;
        } else {
            $('trEsferaEmendaParlamentar').style.display = 'none';
            bEsferaEmendaParlamentar = false;
        }
    }

    function showForm(selectElement)
    {
        var valor = selectElement
        if (selectElement.value) {
            valor = selectElement.value
        }

        var formreinf = document.getElementById('reinf');
        var formreinftd = document.getElementById('reinftd');

        if (valor === '2') {
            formreinf.style.display = "table-row";
            formreinftd.colSpan = 5;
        } else {
            formreinf.style.display = "none";
            formreinftd.colSpan = 1;
            document.form1.efd60_possuicno.value = 0;
            document.form1.efd60_numcno.value = '';
            document.form1.efd60_indprestservico.value = '';
            document.form1.efd60_prescontricprb.value = '';
            document.form1.efd60_tiposervico.value = '';
        }
    }
    function showRowCNO(selectElement)
    {
        var valor = selectElement
        if (selectElement.value) {
            valor = selectElement.value
        }
        var rowNumCno = document.getElementById('trNumCno');
        if (valor == '1') {
            rowNumCno.style.display = "table-row";
        } else {
            rowNumCno.style.display = "none";
            document.form1.efd60_numcno.value = '';
            if (valor == '2') {
                document.form1.efd60_indprestservico.value = 0;
            }
        }
    }
    function showRowProdutorRural(selectElement)
    {
        var valor = selectElement
        if (selectElement.value) {
            valor = selectElement.value
        }
        var rowProdutorRural = document.getElementById('trProdutoroptacp');

        if (valor != '0') {
            rowProdutorRural.style.display = "table-row";
            // document.form1.efd60_prodoptacp.value = '1';
        } else {
            rowProdutorRural.style.display = "none";
            // document.form1.efd60_prodoptacp.value = '0';
        }
    }
    function js_valida()
    {
        var obrigaDivida = obrigaPreenchimentoDivida();
        document.getElementById('obriga_divida').value = obrigaDivida;

        if (document.form1.efd60_cessaomaoobra.value == 2) {

            if (document.form1.efd60_possuicno.value == 0) {
                alert("Campo Possui Cadastro Nacional de Obras (CNO) nao Informado.")
                return false;
            }

            if (document.form1.efd60_possuicno.value == 1) {
                if (!document.form1.efd60_numcno.value) {
                    alert("Campo N�mero do CNO nao Informado ")
                    return false;
                }
                if (document.form1.efd60_numcno.value.length < 15) {
                    alert("Campo N�mero do CNO com formato inv�lido.")
                    return false;
                }
            }

            if (!document.form1.efd60_indprestservico.value) {
                alert("Campo Indicativo de Presta��o de Servi�os em Obra de Constru��o Civil nao Informado.")
                return false;
            }

            if (!document.form1.efd60_prescontricprb.value) {
                alert("Campo  Prestador � contribuinte da CPRB nao Informado.")
                return false;
            }

            if (!document.form1.efd60_tiposervico.value) {
                alert("Campo  Tipo de Servi�o nao Informado.")
                return false;
            }
        }

        if (document.form1.e60_numcgmordenadoranterior.value && !document.form1.e60_numcgmordenador.value) {
                alert("Campo  Ordenador da Despesa nao Informado.")
                return false;
        }
    }

    /*===========================================
    =            pesquisa 54_gestaut            =
    ===========================================*/

    function js_pesquisae54_gestaut() {
        js_OpenJanelaIframe(
            '',
            'db_iframe_db_depart',
            'func_db_depart.php?funcao_js=parent.js_preenchepesquisae54_gestaut|coddepto|descrdepto',
            'Pesquisa',
            true,
            '0',
            '1'
        );
    }

    function js_preenchepesquisae54_gestaut(codigo, descricao) {

        if (codigo == '' || descricao == '') {
            document.form1.e54_gestaut.value = '';
            document.form1.e54_gestaut.value.focus();
            return;
        }

        document.form1.e54_gestaut.value = codigo;
        document.form1.e54_nomedodepartamento.value = descricao;
        document.getElementById("gestor_alterado").value = true;

        db_iframe_db_depart.hide();

    }

    // executar a primeira vez
    document.form1.e54_gestaut.value = '<?= $iCodDepartamentoAtual ?>';
    document.form1.e54_nomedodepartamento.value = '<?= $sNomDepartamentoAtual ?>';

    /*=====  End of pesquisa 54_gestaut  ======*/


    function manutencaoCotasMensais () {

        oViewCotasMensais = new ViewCotasMensais('oViewCotasMensais', $F('e60_numemp'));
        oViewCotasMensais.setReadOnly(false);
        oViewCotasMensais.abrirJanela();
    }


    function js_pesquisae60_concarpeculiar(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr','Pesquisa',true,'0');
        }else{
            if(document.form1.e60_concarpeculiar.value != ''){
                js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.e60_concarpeculiar.value+'&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false,'0');
            }else{
                document.form1.c58_descr.value = '';
            }
        }
    }
    function js_mostraconcarpeculiar(chave,erro){
        document.form1.c58_descr.value = chave;
        if(erro==true){
            document.form1.e60_concarpeculiar.focus();
            document.form1.e60_concarpeculiar.value = '';
        }
    }
    function js_mostraconcarpeculiar1(chave1,chave2){
        document.form1.e60_concarpeculiar.value = chave1;
        document.form1.c58_descr.value          = chave2;
        db_iframe_concarpeculiar.hide();
    }
    function js_pesquisa(){
        js_OpenJanelaIframe('','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp|e60_codemp|e60_anousu|e60_numcgm','Pesquisa',true,'0');
    }
    function js_preenchepesquisa(chave, chave2, ano, chave3){
        db_iframe_empempenho.hide();
        <?
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
        ?>

        parent.document.formaba.alteracaoop.disabled=false;
        empenho = chave2+'/'+ano;
        CurrentWindow.corpo.iframe_alteracaoop.location.href='emp1_aba2ordempagamento002.php?pesquisa=1&empenho='+empenho+'&fornecedor='+chave3;
    }

    function js_pesquisaDivida(mostra) {

        var credor = document.getElementById('e60_numcgm').value;

        var regrasFiltros = getRegrasFiltrarDivida();

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?credor='+ credor +'&regras='+regrasFiltros+'&funcao_js=parent.js_mostraDivida|op01_sequencial|op01_numerocontratoopc|op01_dataassinaturacop', 'Pesquisa', true);
        } else {
            if (document.form1.op01_numerocontratoopc.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?credor='+ credor +'&regras='+regrasFiltros+'&pesquisa_chave=' + document.form1.op01_numerocontratoopc.value + '&funcao_js=parent.js_completaDivida', 'Pesquisa', false);
            } else {
                document.form1.op01_numerocontratoopc.value = '';
            }
        }
    }

    function js_mostraDivida(chave, chave1, chave2, chave3, chave4) {
        document.form1.op01_numerocontratoopc.value = chave;
        document.form1.op01_text_numerocontratoopc.value = chave1;
        db_iframe_db_operacaodecredito.hide();

        if(chave1.includes('n�o Encontrado')) {
            document.form1.op01_numerocontratoopc.value = '';
        }
    }

    function js_completaDivida(chave, chave1, erro) {
        document.form1.op01_text_numerocontratoopc.value = chave;

        if(chave.includes('n�o Encontrado')) {
            document.form1.op01_numerocontratoopc.value = '';
        }
    }

    function js_verificaFinalidadeEmpenho() {

        js_divCarregando("Aguarde, verificando recurso da dota��o...", "msgBox");
        var oParam                = new Object();
        oParam.exec               = "getFinalidadePagamentoFundebEmpenho";
        oParam.iSequencialEmpenho = $F('e60_numemp');
        oParam.iCodigoAutorizacaoEmpenho = $F('e54_autori');

        new Ajax.Request('emp4_empenhofinanceiro004.RPC.php',
            {method: 'post',
                parameters: 'json='+Object.toJSON(oParam),
                onComplete: function (oAjax) {

                    js_removeObj("msgBox");
                    var oRetorno = eval("("+oAjax.responseText+")");

                    lEsferaEmendaParlamentar = oRetorno.lEsferaEmendaParlamentar;

                    js_verificaresfera();
                    if (oRetorno.lEmendaParlamentar) {
                        $('trEmendaParlamentar').style.display = '';
                        bEmendaParlamentar = true;
                        if (oRetorno.lEmendaIndividual) {
                            document.getElementById("e60_emendaparlamentar").disabled = false;
                            document.getElementById("e60_emendaparlamentar").remove(3);
                            document.getElementById("e60_emendaparlamentar").remove(3);
                            document.getElementById("e60_emendaparlamentar").remove(2);
                        }

                        if (oRetorno.lEmendaIndividualEBancada) {
                            document.getElementById("e60_emendaparlamentar").disabled = false;
                            document.getElementById("e60_emendaparlamentar").remove(3);
                            document.getElementById("e60_emendaparlamentar").remove(3);
                        }
                    } else {
                        $('trEmendaParlamentar').style.display = 'none';
                        bEmendaParlamentar = false;
                    }

                    if (!oRetorno.lPossuiFinalidadePagamentoFundeb) {

                        $('trFinalidadeFundeb').style.display = 'none';

                    } else {

                        $('trFinalidadeFundeb').style.display = '';
                        $("e151_codigo").style.width      = "15%";
                        $("e151_codigodescr").style.width = "84%";

                        if (oRetorno.oFinalidadePagamentoFundeb) {

                            $('e151_codigo').value = oRetorno.oFinalidadePagamentoFundeb.e151_codigo;
                            js_ProcCod_e151_codigo('e151_codigo','e151_codigodescr');
                        }
                    }

                }
            });
    }

    js_verificaFinalidadeEmpenho();

    function js_pesquisae60_numconvenio(mostra) {
        if(mostra==true){
            js_OpenJanelaIframe('','db_iframe_convconvenios','func_convconvenios.php?funcao_js=parent.js_mostrae60_numconvenio1|c206_sequencial|c206_objetoconvenio','Pesquisa',true,'0');
        } else {
            if(document.form1.e60_numconvenio.value != ''){
                js_OpenJanelaIframe('','db_iframe_convconvenios','func_convconvenios.php?pesquisa_chave='+document.form1.e60_numconvenio.value+'&funcao_js=parent.js_mostrae60_numconvenio','Pesquisa',false,'0');
            }else{
                document.form1.c206_objetoconvenio.value = '';
            }
        }
    }

    function js_mostrae60_numconvenio(chave,erro){
        document.form1.c206_objetoconvenio.value = chave;
        if(erro==true){
            document.form1.e60_numconvenio.focus();
            document.form1.e60_numconvenio.value = '';
        }
    }

    function js_mostrae60_numconvenio1(chave1,chave2){
        document.form1.e60_numconvenio.value     = chave1;
        document.form1.c206_objetoconvenio.value = chave2;
        db_iframe_convconvenios.hide();
    }

    function js_historico_alterado(){
        document.getElementById("historico_alterado").value = true;
    }

    function js_pesquisa_cgm(mostra)
    {
        if(mostra==true){
            js_OpenJanelaIframe('','db_iframe_cgm','func_cgmordenador.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|tipo','Pesquisa',true);
        }else{
            if(document.form1.e60_numcgmordenador.value != ''){
                js_OpenJanelaIframe('','db_iframe_cgm','func_cgmordenador.php?pesquisa_chave='+document.form1.e60_numcgmordenador.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
            }else{
                document.form1.e60_nomeordenador.value = '';
            }
        }
    }

    function js_mostracgm(erro, chave,chave2)
    { 
        if (chave2 == 'JURIDICA') {
            alert("� obrigat�rio que o ordenador seja uma pessoa f�sica.")
            document.form1.e60_numcgmordenador.value = '';
            document.form1.e60_nomeordenador.value = '';
            document.form1.e60_numcgmordenador.focus();
        } else {
            document.form1.e60_nomeordenador.value = chave;
        }
        if (erro == true) {
            document.form1.e60_numcgmordenador.focus();
            document.form1.e60_numcgmordenador.value = '';
        }
    }

    function js_mostracgm1(chave, chave1,chave2) 
    {
       
        if (chave2 == 'JURIDICA') {
            alert("� obrigat�rio que o ordenador seja uma pessoa f�sica.")
            document.form1.e60_numcgmordenador.value = '';
            document.form1.e60_nomeordenador.value = '';
            return false;
        } else {
            document.form1.e60_numcgmordenador.value = chave;
            document.form1.e60_nomeordenador.value = chave1;
            db_iframe_cgm.hide();
        }
      
    }

    showForm(document.form1.efd60_cessaomaoobra.value );
         /**
     * Ajustes no layout
     */

        $('e60_codemp').style.width = "15%";
        $('e60_numcgm').style.width = "15%";
        $('z01_nome').style.width = "64.4%";
        $('e60_numcgmordenador').style.width = "15%";
        $('e60_nomeordenador').style.width = "64.4%";
        $('e60_codcom').style.width = "15%";
        $('pc50_descr').style.width = "64.4%";
        $('e60_tipol').style.width = "15%";
        $('e60_numerol').style.width = "40%";
        $('e54_nummodalidade').style.width = "26%";
        $("e60_codtipo").style.width      = "15%";
        $("e60_codtipodescr").style.width = "64.4%";
        $("e63_codhist").style.width      = "15%";
        $("e63_codhistdescr").style.width = "64.4%";
        $("e44_tipo_select_descr").style.width = "80.4%";
        $('e60_tipodespesa').style.width = "80.4%";
        $('efd60_cessaomaoobra').style.width = "80.4%";
        $('e60_emendaparlamentar').style.width = "80.4%";
        $("e60_destin").style.width       = "80.4%";
        $('e54_gestaut').style.width = "15%";
        $('e60_resumo').style.width = "100%";
        $('e60_informacaoop').style.width = "100%";
        $("e54_nomedodepartamento").style.width       = "64.4%";
        $("c58_descr").style.width       = "64.4%";
        $("c206_objetoconvenio").style.width       = "64.4%";
        $("op01_text_numerocontratoopc").style.width       = "64.4%";
        $("e44_tipo").style.width         = "100%";
        $('e60_numconvenio').style.width = "15%";
        $('op01_numerocontratoopc').style.width = "15%";
        $('e60_datasentenca').style.width = "15%";
        $('e60_concarpeculiar').style.width = "15%";
        $('e54_autori').style.width = "10%";
        $('efd60_possuicno').style.width = "99%";
        $('efd60_numcno').style.width = "99%";
        $('efd60_indprestservico').style.width = "99%";
        $('efd60_prescontricprb').style.width = "99%";
        $('efd60_tiposervico').style.width = "99%";
        $('e64_codele').style.width = "22%";
        if ($('e60_tipoldescr')) {
            $('e60_tipoldescr').style.width = "64.4%";
        }
        if ($("efd60_aquisicaoprodrural")) {
            $("efd60_aquisicaoprodrural").style.width = "80.4%";
        }
        if ($("efd60_prodoptacp")) {
            $('efd60_prodoptacp').style.width = "80.4%";
        }
        if ($("e56_codele")) {
            $("e56_codele").style.width       = "58.5%";
        }

    showRowCNO(document.form1.efd60_possuicno.value);
    if ($("efd60_aquisicaoprodrural").value) {
        showRowProdutorRural(document.form1.efd60_aquisicaoprodrural.value);
    }

</script>
