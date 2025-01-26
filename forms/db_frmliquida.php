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
use Illuminate\Database\Capsule\Manager as DB;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o56_elemento");
$clrotulo->label("e69_numero");

$clorctiporec->rotulo->label();
$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();
$clpagordemnota->rotulo->label();
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();
$cltabrec->rotulo->label();

if ($tela_estorno){

   $operacao  = 2;//operacao a ser realizada:1 = liquidacao, 2 estorno
   $labelVal  = "SALDO A ESTORNAR";
   $metodo    = "estornarLiquidacaoAJAX";
   $sCredor   = "none";

}else{

   $operacao  = 1;//operacao a ser realizada:1 = liquidacao, 2 estorno
   $labelVal  = "SALDO A LIQUIDAR";
   $metodo    = "liquidarAjax";
   $sCredor   = "normal";
}
$db_opcao_inf=1;
?>
<style>
.tr_tab{
  background-color:white;
  font-size: 8px;
  height : 8px;
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewNotasPendentes.classe.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<form name=form1 action="" method="POST">
 <input type=hidden name=retencoes value ="">
 <input type=hidden name=e69_codnota value="<?=@$e69_codnota ?>">
 <center>
<table>
 <tr>
 <td valign="top">
 <fieldset><legend><b>Empenho</b></legend>
    <table >
          <tr>
            <td nowrap><?=db_ancora($Le60_codemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td nowrap><? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', 3)?> </td>
            <td nowrap><?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3)?> </td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_numcgm,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numcgm', 13, $Ie60_numcgm, true, 'text', 3); ?> </td>
            <td colspan=2><? db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');?></td>
          </tr>
          <tr style='display:<?=$sCredor?>'>
            <td><?=db_ancora('<b>Credor:</b>',"js_pesquisae49_numcgm(true)",1)?></td>
            <td><? db_input('e49_numcgm', 13, $Ie60_numcgm, true, 'text', 1,"onchange='js_pesquisae49_numcgm(false)'"); ?> </td>
            <td colspan=2><? db_input('z01_credor', 50, $Iz01_nome, true, 'text', 3, '');?></td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",$db_opcao_inf)?></td>
            <td><? db_input('e60_coddot', 13, $Ie60_coddot, true, 'text', 3); ?></td>
            <td colspan=2><? db_input('estruturalDotacao', 50, 3, true, 'text', 3); ?></td>
            <td><? db_input('o15_codigo', 7, $Io15_codigo, true, 'hidden', 3); db_input('o15_descr', 29, $Io15_descr, true, 'hidden', 3)?></td>
          </tr>
          <tr>
            <td><?db_ancora("<b>Conta Pagadora:</b>","js_pesquisa_contapagadora(true);",1);?></td>
            <td>
                <?
                    db_input("e83_conta",13,1,true,"text",4,"onchange='js_pesquisa_contapagadora(false);'");
                    db_input("e83_codtipo",5,1,true,"hidden");
                ?>
            </td>
            <td colspan='3'><? db_input("e83_descr",50,"",true,"text",3); ?></td>
          </tr>
        <?php if($operacao === 1){ ?>
          <tr>
              <?php

                $paramentosOrdenadores = buscarParamentosOrdenadores();
                db_input('paramentosOrdenadores',10,$IparamentosOrdenadores,true,'hidden',3);
                
                if ($paramentosOrdenadores == 1) {

                  $dado = listarOrdenadores($numemp);
                  foreach($dado as $dados) {
                    $o41_cgmordenador = $dados['z01_numcgm'];
                  }
                  
                  $where = $o41_cgmordenador ? " z01_numcgm in ($o41_cgmordenador) " :  "z01_numcgm is null";
                  $result_cgm = $clcgm->sql_record($clcgm->sql_buscar_ordenador(null," o41_nomeordenador desc ",$where));
                  if ($clcgm->numrows == 0 ) {
                    $result_cgm = $clcgm->sql_record($clcgm->sql_query_ordenador(null," o41_nomeordenador asc ",$where));
                  } 
                 
                 if ($dados['erro'] == false) { ?>

                    <tr>
                      <td nowrap title="<?=$To41_cgmordenador?>">
                          <?db_ancora('<b>Ordenador da Liquida��o</b>',"js_pesquisa_cgm(true);",$fimperiodocontabil);?>
                      </td>
                      <td colspan="4">
                          <?  
                              db_input("o41_cgmordenador",10,$Io41_cgmordenador,true,"text",$fimperiodocontabil,"onChange='js_pesquisa_cgm(false);'");
                              db_input("o41_cgmordenadordescr",40,$Io41_cgmordenadordescr,true,"text",3);
                          ?>
                      </td>
                    </tr>
                    <?php    
                      } else {
                    ?>
                    <td nowrap title="<?= @$Te54_numcgm ?>">
                      <b> Ordenador da Liquida��o:</b>
                    </td>
                    <td colspan="4">
                        <?=
                          db_selectrecord("o41_cgmordenador", $result_cgm, true, $db_opcao, "", "", "", "","");
                        ?>
                    </td>  
                <?php 
                  }
                } elseif ($paramentosOrdenadores == 2) { 
                  if ($numemp) {
                    $ordenadoresArray = listarOrdenadores($numemp);
                    $arrayCount = count($ordenadoresArray); 
                    $cont       = 0;
                    $numCgm     = '';

                    if ($arrayCount > 1) {
                      foreach($ordenadoresArray as $ordenadores) {
                          $cont++; 
                          $numCgm .= "'";
                          $numCgm .= $ordenadores['z01_numcgm']; 
                          if ($arrayCount == $cont) {
                            $numCgm .= "'";   
                          } else {
                            $numCgm .= "',";  
                          }        
                      }
                      $where = " z01_numcgm in ($numCgm) ";
                      $result_cgm = $clcgm->sql_record($clcgm->sql_query_ordenador(null," sort_order asc, o41_nomeordenador asc ",$where));
                      
                      if ($ordenadores['erro'] == false) { ?>

                        <tr>
                          <td nowrap title="<?=$To41_cgmordenador?>">
                              <?db_ancora('<b>Ordenador da Liquida��o</b>',"js_pesquisa_cgm(true);",$fimperiodocontabil);?>
                          </td>
                          <td colspan="4">
                              <?  
                                  db_input("o41_cgmordenador",10,$Io41_cgmordenador,true,"text",$fimperiodocontabil,"onChange='js_pesquisa_cgm(false);'");
                                  db_input("o41_cgmordenadordescr",40,$Io41_cgmordenadordescr,true,"text",3);
                                  db_input('db243_data_inicio',10,$Idb243_data_inicio,true,'hidden',3);
                                  db_input('db243_data_final',10,$Idb243_data_final,true,'hidden',3);
                              ?>
                          </td>
                        </tr>
                      <?php    
                      } else {
                      ?>
                          <td nowrap title="<?= @$Te54_numcgm ?>">
                            <b> Ordenador da Liquida��o:</b>
                          </td>
                          <td colspan="5"><?=
                          db_selectrecord("o41_cgmordenador", $result_cgm, true, $db_opcao, "", "", "", "","js_dadosordenador(this.value,$numemp)");
                          ?></td>    
                          <td ><?=
                          db_input('o41_nomeordenador', 55, $Iz01_nome, true, 'hidden', 3);
                          
                        ?></td>  
                        <?php
                         db_input('db243_data_inicio',10,$Idb243_data_inicio,true,'hidden',3);
                         db_input('db243_data_final',10,$Idb243_data_final,true,'hidden',3);
                      }
                    } else {

                        $dado = listarOrdenadores($numemp);
                        foreach($dado as $dados) {
                          $o41_cgmordenador = $dados['z01_numcgm'];
                        }
                        
                        $where = $o41_cgmordenador ? " z01_numcgm in ($o41_cgmordenador) " :  "z01_numcgm is null";
                        $result_cgm = $clcgm->sql_record($clcgm->sql_buscar_ordenador(null," o41_nomeordenador asc ",$where));
                        if ($clcgm->numrows == 0 ) {
                          $result_cgm = $clcgm->sql_record($clcgm->sql_query_ordenador(null," o41_nomeordenador asc ",$where));
                        } 
                          
                        if ($dados['erro'] == 0) { ?>

                          <tr>
                          <td nowrap title="<?=$To41_cgmordenador?>">
                              <?db_ancora('<b>Ordenador da Liquida��o</b>',"js_pesquisa_cgm(true);",$fimperiodocontabil);?>
                          </td>
                          <td colspan="4">
                              <?  
                                  db_input("o41_cgmordenador",8,$Io41_cgmordenador,true,"text",$fimperiodocontabil,"onChange='js_pesquisa_cgm(false);'");
                                  db_input("o41_cgmordenadordescr",40,$Io41_cgmordenadordescr,true,"text",3);
                                  db_input('db243_data_inicio',10,$Idb243_data_inicio,true,'hidden',3);
                                  db_input('db243_data_final',10,$Idb243_data_final,true,'hidden',3);
                              
                              ?>
                          </td>
                          </tr>
                          <?php    
                        } else {

                        ?>
                        <td nowrap title="<?= @$Te54_numcgm ?>">
                            <b> Ordenador da Liquida��o:</b>
                        </td>
                        <td colspan="4">
                            <?=
                               db_selectrecord("o41_cgmordenador", $result_cgm, true, $db_opcao, "onFocus='js_dadosordenador(this.value,$numemp)'", "", "", "","js_dadosordenador(this.value,$numemp)");
                               db_input('db243_data_inicio',10,$Idb243_data_inicio,true,'hidden',3);
                               db_input('db243_data_final',10,$Idb243_data_final,true,'hidden',3);
                            ?>
                        </td>  
                        <?php
                      }
                   }
                }   
              } else {
          ?>

            <tr>
              <td nowrap title="<?=$To41_cgmordenador?>">
                <?db_ancora('<b>Ordenador da Liquida��o</b>',"js_pesquisa_cgm(true);",$fimperiodocontabil);?>
              </td>
              <td colspan="4">
                <?  
                  db_input("o41_cgmordenador",8,$Io41_cgmordenador,true,"text",$fimperiodocontabil,"onChange='js_pesquisa_cgm(false);'");
                  db_input("o41_cgmordenadordescr",40,$Io41_cgmordenadordescr,true,"text",3);
                  db_input('db243_data_inicio',10,$Idb243_data_inicio,true,'hidden',3);
                  db_input('db243_data_final',10,$Idb243_data_final,true,'hidden',3);
                         
                ?>
              </td>
              </tr>
           
              <?php
            }
          }
          ?>       
              </tr> 
          <?
           if($operacao == 1){
              ?>
                <tr>
                  <td ><strong>Conta Fornecedor: </strong></td>
                  <td colspan='3'>
                    <?db_select("e50_contafornecedor",null,true,1, "style='width:100%'");?>
                  </td>
                </tr>
              <?
              echo "<tr>";
              echo "<td nowrap> ";
              echo "    <b>Data da Liquida��o:</b> ";
              echo "    </td> ";
              echo "    <td colspan='3'> ";
              db_inputdata('dataLiquidacao','','','',true,'text',1);
              echo "        <b>Data de Vencimento:</b> ";
              db_inputdata('dataVencimento','','','',true,'text',1);
              echo " </td>";
              echo " </tr>";
            }else if($operacao == 2){
              echo "<tr>";
              echo "<td nowrap> ";
              echo "    <b>Data do Estorno:</b> ";
              echo "    </td> ";
              echo "    <td colspan='3'> ";
              db_inputdata('dataEstorno','','','',true,'text',1);
              echo " </td>";
              echo " </tr>";
            }
          ?>
          <tr>
            <td>
              <strong>Processo Administrativo:</strong>
            </td>
            <td colspan="2">
              <?php db_input('e03_numeroprocesso', 13, '', true, 'text', $db_opcao_inf, null, null, null, null, 15)?>
            </td>
          </tr>
           <!-- OC 12746 -->
           <tr>
               <td nowrap id="competDespLabel" style="display: none"><b>Compet�ncia da Despesa: </b></td>
               <td style="display: none" id="competDespInput">
                  <?db_inputData('e50_compdesp', '', '', '', true, 'text', 1); ?>
                  <input type="hidden" name="sEstrutElemento" id="sEstrutElemento"/>
               </td>
           </tr>

          <!--[Extensao OrdenadorDespesa] inclusao_ordenador-->

     <?
      echo " <tr> ";
      echo "    <td>&nbsp;</td> ";
      echo "    <td colspan='3'> ";
       if ($operacao == 1 ) {

         echo "       <input type='checkbox' checked id='emitedocumento'> ";
         echo "       <label for='emitedocumento'>Emitir Ordem de Pagamento</label> ";

       }
        echo "   &nbsp; </td> ";
        echo " </tr> ";
        echo " <tr> ";
        echo "    <td>&nbsp;</td> </tr>";
        if ($operacao == 2 ) {

          echo " <tr> ";
          echo "    <td>&nbsp;</td> </tr>";
        }
      ?>
        </table>
        </fieldset>
  </td>
  <td rowspan="1" valign="top" style='height:100%;'>
 <fieldset><legend><b>Valores do Empenho</b></legend>
    <table >
          <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td colspan=2 align=center class=table_header><?=$labelVal?></td></tr>
          <!-- Extens�o CotaMensalLiquidacao - pt 1 -->
          <tr>
            <td><b> SALDO </b></td>
            <td align=right>
              <?
              if($db_opcao==3){
                @$saldo_disp = db_formatar(($e53_valor-$e53_vlranu-$e53_vlrpag),'p');
              }else{
                @$saldo_disp = db_formatar(($e60_vlremp-$e60_vlranu-$e60_vlrliq),'p');
              }
              db_input('saldo_disp', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right');
              ?>
            </td>
          </tr>
      </table>
     </fieldset>
  </td>
  </tr>
  <tr>
  <td colspan="2">
 <fieldset id='esocial'><legend><b>eSocial</b></legend>
 <table >
 <tr>
                <td>
                <strong>Incide Contribui��o Previdenci�ria:</strong>
                <td colspan="4">
                <?
                  $aIncide = array('1'=>'Sim', '2'=>'N�o');
                  db_select('aIncide', $aIncide, true, 1, "onchange='mensagemesocial()'");
                ?>
                </td>
        </tr>
        <tr>
                  <td id='cattrab'><?=db_ancora('<b>Categoria do Trabalhador:</b>',"js_pesquisaCatTrabalhador(true)",1)?></td>
                  <td id='cattrab1'><? db_input('ct01_codcategoria', 15, $Ict01_codcategoria, true, 'text', 1,"onchange='js_pesquisaCatTrabalhador(false)'"); ?> </td>
                  <td id='cattrab2'><? db_input('ct01_descricaocategoria', 48, $Ict01_descricaocategoria, true, 'text', 3, '');?></td>
        </tr>
        <tr>
              <td id="idvinculos">
                <strong>O trabalhador possui outro v�nculo/atividade com desconto previdenci�rio: </strong>
                <td colspan="4">
                <?
                  $multiplosvinculos = array(''=>'Selecione','1'=>'Sim', '2'=>'N�o');
                  db_select('multiplosvinculos', $multiplosvinculos, true, 1, "onchange='validarVinculos()'");
                ?>
                </td>
        </tr>

        <tr>
                <td id='idcontri'>
                <strong>Indicador de Desconto da Contribui��o Previdenci�ria:</strong>
                <td colspan="4">
                <?
                  $aContribuicao = array(''=>'Selecione', '1'=>'1 - O percentual da al�quota ser� obtido considerando a remunera��o total do trabalhador',
                  '2'=>'2 - O declarante aplica a al�quota de desconto do segurado sobre a diferen�a entre o limite m�ximo do sal�rio de contribui��o e a remunera��o de outra empresa para as quais o trabalhador informou que houve o desconto',
                  '3'=>'3 - O declarante n�o realiza desconto do segurado, uma vez que houve desconto sobre o limite m�ximo de sal�rio de contribui��o em outra empresa',
                  );
                  db_select('contribuicaoPrev', $aContribuicao, true, 1, "");
                ?>
                </td>
        </tr>
        <tr >
                  <td id='empresa'><?=db_ancora('<b>Empresa que efetuou desconto:</b>',"js_pesquisaEmpresa(true)",1)?></td>
                  <td id='empresa1'><? db_input('numempresa', 15, $Inumempresa, true, 'text', 1,"onchange='js_pesquisaEmpresa(false)'"); ?> </td>
                  <td id='empresa2'><? db_input('nomeempresa', 48, $Inomeempresa, true, 'text', 3, '');?></td>
        </tr>
        <tr>
                  <td id='catremuneracao'><?=db_ancora('<b>Categoria do trabalhador na qual houve a remunera��o:</b>',"js_pesquisaCatTrabalhadorremuneracao(true)",1)?></td>
                  <td id='catremuneracao1'><? db_input('ct01_codcategoriaremuneracao', 15, $Ict01_codcategoriaremuneracao, true, 'text', 1,"onchange='js_pesquisaCatTrabalhadorremuneracao(false)'"); ?> </td>
                  <td id='catremuneracao2'><? db_input('ct01_descricaocategoriaremuneracao', 48, $Ict01_descricaocategoriaremuneracao, true, 'text', 3, '');?></td>
        </tr>
        <tr>
                  <td id='vlrremuneracao'><strong>Valor da Remunera��o:</strong></td>
                  <td id='vlrremuneracao1'>
                    <?
                    db_input('valorremuneracao', 15, '4', true, 'text', 1, "onchange='casasdecimais(1)'",'','','text-align:right');
                    ?>
                  </td>
        </tr>
        <tr>
                  <td id='vlrdesconto'><strong>Valor do Desconto:</strong></td>
                  <td id='vlrdesconto1'>
                    <?
                    db_input('valordesconto', 15, '4', true, 'text', 1, "onchange='casasdecimais(2)'",'','','text-align:right');
                    ?>
                  </td>
                </tr>
        <tr >
                  <td id='idcompetencia'>
                    <b>Compet�ncia:</b>
                  </td>
                  <td id='idcompetencia2'>
                    <?
                      db_inputdata('competencia',@$ac10_datamovimento_dia,
                                                        @$ac10_datamovimento_mes,
                                                        @$ac10_datamovimento_ano, true, 'text', $db_opcao, 'style="width: 99px"');
                    ?>
                  </td>
                  <td>&nbsp;</td>
        </tr>
  </table>
 </fieldset>
 <tr>
  <td colspan="2">
    <?
    include("forms/db_frmliquidaboxreinf.php");
    ?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
    <?
    include("forms/db_frmliquidaboxdiarias.php");
    ?>
  </td>
 </tr>
  <tr>
  <td colspan="2">
 <fieldset><legend><b>Notas</b></legend>
       <div style='border:2px inset white'>
        <table  cellspacing=0 cellpadding=0 width='100%'>
          <tr>
            <th class='table_header'>
	          <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>
           	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></th>
            <th class='table_header'>OP</th>
            <th class='table_header'>Ordem De Compra</th>
            <th class='table_header'>Nota Fiscal</th>
            <th class='table_header'>Data</th>
            <th class='table_header'>Valor</th>
            <th class='table_header'>Anulado</th>
            <th class='table_header'>Liquidado</th>
            <th class='table_header'>Pago</th>
            <th class='table_header'>Retido</th>
          </tr>
          <tbody id='dados' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
          </tbody>
        </table>
        </div>
        </fieldset>
  </td>
  </tr>
  <tr>
  <td colspan="2px">
 <fieldset><legend><b>Hist�rico</b></legend>
   <table width="100%">
        <tr>
          <td id='opcredito' style='display:none'>
          <?
          db_textarea('informacaoop',4,128,0,true,'text',1,"");
          ?>
          </td>
          <tr >
          <td id='ophisotrico' style='display:none'>
          <?
          db_textarea('historico',4,128,0,true,'',1,"" );
          ?>
          </td>
        </tr>
    </table>
    </fieldset>
    </td>
  </tr>
</table>
 <input name="confirmar" type="button" id="confirmar" value="Confirmar" onclick="return js_liquidar('<?=$metodo?>')" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa('');" >
    <?
    if($operacao === 1){
      echo '<input name="importar" type="button" id="importar" value="Importar Hist�rico da OC" onclick="js_importarHistorico();" >';
      }
    ?>
</center>
  <? db_input("receitas_valores",30,"",true,'hidden',1,'readonly','','','text-align:right')?>
  <? db_input("valor_liquidar",30,"",true,'hidden',1,'readonly','','','text-align:right')?>
</form>
<script>
iOperacao     = <?=$operacao;?>;
lPesquisaFunc = false;
var oDBToogleDiarias = new DBToogle('diariaFieldset', true);
function js_emitir(codordem){
  jan = window.open('emp2_emitenotaliq002.php?codordem='+codordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}
function js_pesquisa(iNumEmp) {
  if (iNumEmp == '') {
    document.form1.reset();
    js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho', 'func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp', 'Pesquisa', true);
  } else {
    js_consultaEmpenho(iNumEmp,<?=$operacao?>);
  }
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  js_consultaEmpenho(chave,<?=$operacao?>);
  iNumEmp = chave;
      if (iNumEmp){
      <?
        echo "location.href = '" . basename($_SERVER['PHP_SELF']) . "?numemp=' + chave;";
      ?>
      }
  lPesquisaFunc = true; //mostramos as mensagens se o usu�rio clicou na func.
}
function casasdecimais(escolha){
         var valorremuneracao = (document.form1.valorremuneracao.value).indexOf('.');
         var valordesconto = (document.form1.valordesconto.value).indexOf('.');
         if(escolha == '1' && valorremuneracao > 0 ){
          var virgula = document.form1.valorremuneracao.value.substring(valorremuneracao + 0);
          document.form1.valorremuneracao.value = document.form1.valorremuneracao.value.substring(0, valorremuneracao)+virgula.substring(0,3);
         }if(escolha == '2' && valordesconto > 0){
          var virgula = document.form1.valordesconto.value.substring(valordesconto + 0);
          document.form1.valordesconto.value = document.form1.valordesconto.value.substring(0, valordesconto)+virgula.substring(0,3);
         }
}

function validarVinculos(){
  if(document.form1.multiplosvinculos.value == '1'){
      document.getElementById('idcontri').style.display = "table-cell";
      document.getElementById('contribuicaoPrev').style.display = "table-cell";
      document.getElementById('idcompetencia').style.display = "table-cell";
      document.getElementById('idcompetencia2').style.display = "table-cell";
      document.getElementById('empresa').style.display = "table-cell";
      document.getElementById('empresa1').style.display = "table-cell";
      document.getElementById('empresa2').style.display = "table-cell";
      document.getElementById('catremuneracao').style.display = "table-cell";
      document.getElementById('catremuneracao1').style.display = "table-cell";
      document.getElementById('catremuneracao2').style.display = "table-cell";
      document.getElementById('vlrremuneracao1').style.display = "table-cell";
      document.getElementById('vlrremuneracao').style.display = "table-cell";
      document.getElementById('vlrdesconto').style.display = "table-cell";
      document.getElementById('vlrdesconto1').style.display = "table-cell";
  }
  if(document.form1.multiplosvinculos.value == '2'){
      document.getElementById('idcontri').style.display = "none";
      document.getElementById('contribuicaoPrev').style.display = "none";
      document.getElementById('idcompetencia').style.display = "none";
      document.getElementById('idcompetencia2').style.display = "none";
      document.getElementById('empresa').style.display = "none";
      document.getElementById('empresa1').style.display = "none";
      document.getElementById('empresa2').style.display = "none";
      document.getElementById('catremuneracao').style.display = "none";
      document.getElementById('catremuneracao1').style.display = "none";
      document.getElementById('catremuneracao2').style.display = "none";
      document.getElementById('vlrremuneracao').style.display = "none";
      document.getElementById('vlrremuneracao1').style.display = "none";
      document.getElementById('vlrdesconto').style.display = "none";
      document.getElementById('vlrdesconto1').style.display = "none";
      document.form1.numempresa.value = '';
      document.form1.nomeempresa.value = '';
      document.form1.ct01_codcategoriaremuneracao.value = '';
      document.form1.ct01_descricaocategoriaremuneracao.value = '';
      document.form1.valorremuneracao.value = '';
      document.form1.valordesconto.value = '';
      document.form1.competencia.value= '';
      document.form1.contribuicaoPrev.value= '';
  }
}

function mensagemesocial(){
  if(document.form1.aIncide.value == '2'){
    var r=confirm("Tem certeza de que n�o h� incid�ncia de contribui��o previdenci�ria para este prestador? ");
    if (r==true)
      {
      document.getElementById('idcontri').style.display = "none";
      document.getElementById('cattrab').style.display = "none";
      document.getElementById('cattrab1').style.display = "none";
      document.getElementById('cattrab2').style.display = "none";
      document.getElementById('ct01_codcategoria').style.display = "none";
      document.getElementById('ct01_descricaocategoria').style.display = "none";
      document.getElementById('contribuicaoPrev').style.display = "none";
      document.getElementById('idcompetencia').style.display = "none";
      document.getElementById('idcompetencia2').style.display = "none";
      document.getElementById('empresa').style.display = "none";
      document.getElementById('empresa1').style.display = "none";
      document.getElementById('empresa2').style.display = "none";
      document.getElementById('catremuneracao').style.display = "none";
      document.getElementById('catremuneracao1').style.display = "none";
      document.getElementById('catremuneracao2').style.display = "none";
      document.getElementById('vlrremuneracao').style.display = "none";
      document.getElementById('vlrremuneracao1').style.display = "none";
      document.getElementById('vlrdesconto').style.display = "none";
      document.getElementById('vlrdesconto1').style.display = "none";
      document.getElementById('multiplosvinculos').style.display = "none";
      document.getElementById('idvinculos').style.display = "none";
      document.form1.numempresa.value = '';
      document.form1.nomeempresa.value = '';
      document.form1.ct01_codcategoriaremuneracao.value = '';
      document.form1.ct01_descricaocategoriaremuneracao.value = '';
      document.form1.valorremuneracao.value = '';
      document.form1.valordesconto.value = '';
      document.form1.competencia.value= '';
      document.form1.multiplosvinculos.value= '';
      }
    else
      {
      document.form1.aIncide.value = '1';
      return false;
      }
  }else{
    document.getElementById('cattrab').style.display = "table-cell";
      document.getElementById('cattrab1').style.display = "table-cell";
      document.getElementById('cattrab2').style.display = "table-cell";
      document.getElementById('ct01_codcategoria').style.display = "table-cell";
      document.getElementById('ct01_descricaocategoria').style.display = "table-cell";
      document.getElementById('multiplosvinculos').style.display = "table-cell";
      document.getElementById('idvinculos').style.display = "table-cell";
      document.form1.contribuicaoPrev.value = '';
      document.form1.numempresa.value = '';
      document.form1.nomeempresa.value = '';
      document.form1.ct01_codcategoriaremuneracao.value = '';
      document.form1.ct01_descricaocategoriaremuneracao.value = '';
      document.form1.ct01_codcategoria.value = '';
      document.form1.ct01_descricaocategoria.value = '';
      document.form1.valorremuneracao.value = '';
      document.form1.valordesconto.value = '';
      document.form1.competencia.value= '';
  }
}

      document.getElementById('idcompetencia').style.display = "none";
      document.getElementById('idcompetencia2').style.display = "none";
      document.getElementById('empresa').style.display = "none";
      document.getElementById('empresa1').style.display = "none";
      document.getElementById('empresa2').style.display = "none";
      document.getElementById('catremuneracao').style.display = "none";
      document.getElementById('catremuneracao1').style.display = "none";
      document.getElementById('catremuneracao2').style.display = "none";
      document.getElementById('vlrremuneracao').style.display = "none";
      document.getElementById('vlrremuneracao1').style.display = "none";
      document.getElementById('vlrdesconto').style.display = "none";
      document.getElementById('vlrdesconto1').style.display = "none";
      document.getElementById('idcontri').style.display = "none";
      document.getElementById('contribuicaoPrev').style.display = "none";

function js_marca(){

	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkmarca');
	 for (i = 0;i < itens.length;i++){
     if (itens[i].disabled == false){
        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
     }
	 }
}

/**
 * Fun��o que busca os dados do empenho
 */
function js_consultaEmpenho(iEmpenho,operacao){

   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getEmpenhos","pars":"'+iEmpenho+'","operacao":"'+operacao+'","iEmpenho":"'+iEmpenho+'"}';
   $('dados').innerHTML    = '';
   //$('pesquisar').disabled = true;
   url     = 'emp4_liquidacao004.php';
   oAjax   = new Ajax.Request(
                            url,
                              {
                               method: 'post',
                               parameters: 'json='+strJson,
                               onComplete: js_saida
                              }
                             );

}

/* Extens�o CotaMensalLiquidacao - pt 3 */

 /**
  * Preenche o formul�rio com os dados do empenho
  */
  var opcaoReinf = 0
function js_saida(oAjax){
   document.form1.reset();
   js_removeObj("msgBox");

    var iNumEmpOld = $F('e60_numemp');
    obj  = eval("("+oAjax.responseText+")");

    $('e60_codemp').value = obj.e60_codemp;
    $('e60_numemp').value = obj.e60_numemp;
    $('e60_numcgm').value = js_decodeUrl(obj.e60_numcgm);
    $('z01_nome').value   = js_decodeUrl(obj.z01_nome);
    $('e49_numcgm').value = '';
    $('z01_credor').value = '';
    $('e60_coddot').value = js_decodeUrl(obj.e60_coddot);
    $('estruturalDotacao').value = js_decodeUrl(obj.estruturalDotacao);
    $('o15_codigo').value = obj.o58_codigo;
    $('o15_descr').value  = js_decodeUrl(obj.o15_descr);
    $('e60_vlremp').value = obj.e60_vlremp;
    $('e60_vlranu').value = obj.e60_vlranu;
    $('e60_vlrpag').value = obj.e60_vlrpag;
    $('e60_vlrliq').value = obj.e60_vlrliq;
    $('informacaoop').value  = obj.e60_informacaoop.urlDecode();

    if(obj.e60_informacaoop){
      $('historico').value  = obj.e60_informacaoop.urlDecode();}
    else{
      $('historico').value  = obj.e60_resumo.urlDecode();
    }
    $('saldo_disp').value = obj.saldo_dis;
    $('sEstrutElemento').value = obj.sEstrutural;
    saida                 = '';
    iTotNotas             = 0;
    $('dados').innerHTML  = '';
    estrutural            = obj.sEstrutural;
    desdobramento         = obj.sDesdobramento;
    obrigaDiaria          = obj.obrigaDiaria == 't' ? true : false;
    $('e50_compdesp').value = '';
    $('e83_conta').value    = '';
    $('e83_descr').value    = '';
    $('e83_codtipo').value  = '';
    $('reinfRetencao').value = 0;
    $('naturezaCod').value   = '';
    $('naturezaDesc').value  = '';
    $('reinfRetencaoEstabelecimento').value = 0;
    js_validarEstabelecimentos();
    
    if (iOperacao == 1) {
      $("e50_contafornecedor").innerHTML = ''
      opcaoPadrao = document.createElement('option');
      opcaoPadrao.value = 0;
      opcaoPadrao.textContent = "Sem conta definida.";
      $("e50_contafornecedor").appendChild(opcaoPadrao);

      if (obj.contas.length > 0) {      
        obj.contas.forEach(function(conta) {
          const novaConta = document.createElement('option');
          novaConta.value = conta.pc63_contabanco;
          if (conta.tipo == 1) {
            novaConta.textContent = conta.conta+' - '+conta.tipoconta+' (Padr�o)';
          } else {
            novaConta.textContent = conta.conta+' - '+conta.tipoconta;
          }
          $("e50_contafornecedor").appendChild(novaConta);
          if (conta.tipo == 1) {
            $("e50_contafornecedor").value = conta.pc63_contabanco;
          }
        })
      }
    }
    oDBToogleDiarias.show(obrigaDiaria);

    if(obj.e60_informacaoop!=''){
      document.getElementById('opcredito').style.display = "table-cell";
      document.getElementById('ophisotrico').style.display = "none";
    } else {
      document.getElementById('opcredito').style.display = "none";
      document.getElementById('ophisotrico').style.display = "table-cell";
    }
    var db_opcao = "<?php print $op; ?>";

    if(db_opcao != '3' && obj.Tipofornec == 'cpf'  &&  !(desdobramento.substr(0, 3) == '331' || desdobramento.substr(0, 3) == '345'
                                                      || desdobramento.substr(0, 3) == '346' || desdobramento.substr(0, 7) == '3339018'
                                                      || desdobramento.substr(0, 7) == '3339019' || desdobramento.substr(0, 7) == '3339014'
                                                      || desdobramento.substr(0, 7) == '3339008' || desdobramento.substr(0, 7) == '3339059'
                                                      || desdobramento.substr(0, 7) == '3339046' || desdobramento.substr(0, 7) == '3339048'
                                                      || desdobramento.substr(0, 7) == '3339049' || desdobramento == '333903602'
                                                      || desdobramento == '333903603' || desdobramento == '333903607' || desdobramento == '333903608'
                                                      || desdobramento == '333903609' || desdobramento == '333903614' || desdobramento == '333903640'
                                                      || desdobramento == '333903641')){
      tipodesdobramento = 1;
      opcao = 1;
      document.getElementById('esocial').style.display = "table-cell";
    }else{
      document.getElementById('esocial').style.display = "none";
      tipodesdobramento = 0;
      opcao = 3;
    }
    if(db_opcao != '3' && (obj.Tipofornec == 'cnpj' && !(desdobramento.substr(0, 3) == '331' || desdobramento.substr(0, 3) == '345' || desdobramento.substr(0, 3) == '346'
                                  || desdobramento.substr(0, 3) == '332' || desdobramento.substr(0, 7) == '3335041' || desdobramento.substr(0, 7) == '3333041'
                                  || desdobramento.substr(0, 7) == '3337041' || desdobramento.substr(0, 7) == '3339008' || desdobramento.substr(0, 7) == '3339041'
                                  || desdobramento.substr(0, 7) == '3339043' || desdobramento.substr(0, 7) == '3339045' || desdobramento.substr(0, 7) == '3339046'
                                  || desdobramento.substr(0, 7) == '3339047' || desdobramento.substr(0, 7) == '3339048' || desdobramento.substr(0, 7) == '3339049'
                                  || desdobramento.substr(0, 7) == '3339059' || desdobramento.substr(0, 7) == '3339086' || desdobramento.substr(0, 5) == '33371'
                                  || desdobramento.substr(0, 5) == '34471' || desdobramento.substr(0, 7) == '3335043'))
                                  || (obj.Tipofornec == 'cpf' && desdobramento == '333903614')){
      $('reinf').style.display = "table-cell";
      js_validarEstabelecimentos(true);
      if(obj.Tipofornec == 'cpf' && desdobramento == '333903614'){
        $('naturezaCod').value = '13002';
        $('naturezaDesc').value = 'Rendimentos de Alugu�is, Loca��o ou Subloca��o';
        $('reinfRetencao').value = 'sim';
      }
      opcaoReinf = 1;
    }else{
      $('reinf').style.display = "none";
      opcaoReinf = 3;
    }

    const desdobramentoDiaria = desdobramento.substr(5, 2);
    if((desdobramentoDiaria == '14' || desdobramentoDiaria == '33') && obrigaDiaria == true && iOperacao === 1){
      $('diariaFieldset').style.display = "table-cell";
      $('diariaViajante').value = js_decodeUrl(obj.z01_nome);

      let oParam        = new Object();
      oParam.exec     = 'consultaMatricula';
      oParam.iNumCgm    = obj.e60_numcgm;
      oAjax    = new Ajax.Request(
                              'pes1_rhpessoal.RPC.php',
                                {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: function(oAjax){
                                  oRetorno = eval("("+oAjax.responseText+")");
                                  if(oRetorno.iStatus === 1){
                                    $('e140_matricula').value = oRetorno.rh01_regist;
                                    $('e140_cargo').value = oRetorno.rh37_descr;
                                  }
                                }
                                }
                              );
      }else{
        $('diariaFieldset').style.display = "none";
    }

    if (obj.aItensPendentesPatrimonio.length > 0) {

      oDBViewNotasPendentes = new DBViewNotasPendentes('oDBViewNotasPendentes');
      oDBViewNotasPendentes.setCodigoNota(obj.aItensPendentesPatrimonio);
  	  oDBViewNotasPendentes.show();
    }

    if (obj.numnotas > 0) {

      for (var i = 0; i < obj.data.length; i++) {

        sClassName = 'normal';
        if (obj.data[i].libera == 'disabled') {
          sClassName = ' disabled ';
        }

        if (in_array(obj.data[i].e69_codnota, obj.aItensPendentesPatrimonio)) {
          sClassName = ' disabled ';
        }

        if (iOperacao == 1) { //liquidacao

          var nSaldoNota = (js_strToFloat(obj.data[i].e70_valor)-js_strToFloat(obj.data[i].e70_vlrliq) -
		                        js_strToFloat(obj.data[i].e70_vlranu)-js_strToFloat(obj.data[i].e53_vlrpag)).toFixed(2);

          aMatrizEntrada = ['3319092', '3319192', '3319592', '3319692'];

            if (aMatrizEntrada.indexOf(estrutural) !== -1) {
               document.getElementById('competDespLabel').style.display = "table-cell";
               document.getElementById('competDespInput').style.display = "table-cell";
            } else {
                document.getElementById('competDespLabel').style.display = "none";
                document.getElementById('competDespInput').style.display = "none";
            }

        } else if (iOperacao == 2) { //estorno
          if (js_strToFloat(obj.data[i].e53_vlrpag) > 0) {
            var nSaldoNota = 0;
          } else {
            var nSaldoNota = (js_strToFloat(obj.data[i].e70_vlrliq)-js_strToFloat(obj.data[i].e70_vlranu)).toFixed(2);
          }
        }

        if (nSaldoNota > 0) {

          iTotNotas++;
          nValorRetencao = obj.data[i].vlrretencao;
          numnota        = js_decodeUrl(obj.data[i].e69_numero);
          iCodOrd        = obj.data[i].e50_codord;
          saida += "<tr class='" + sClassName + "' id='trchk" + obj.data[i].e69_codnota + "' style='height:1em'>";
          saida += "<td class='linhagrid' style='text-align:center'>";
          saida += "<input type='hidden' id='sInfoAgenda"+obj.data[i].e69_codnota+"' value='"+js_decodeUrl(obj.data[i].sInfoAgenda)+"'>";
          /* Extens�o CotaMensalLiquidacao - pt 4 */
          saida += "<input id='m51_obs"+obj.data[i].e69_codnota+"' type='text' style='display: none' value='" + obj.data[i].m51_obs+"'>";
          saida += "<input id='e70_valor"+obj.data[i].e69_codnota+"' type='text' style='display: none' value='" + obj.data[i].e70_valor+"'>";
          saida += "<input type='checkbox' " + obj.data[i].libera + " onclick='js_marcaLinha(this)'";
          saida += " class='chkmarca' name='chk" + obj.data[i].e69_codnota + "'";
          saida += " id='chk" + obj.data[i].e69_codnota + "' value='" + obj.data[i].e69_codnota + "' "+sClassName+"></td>";
          saida += "<td class='linhagrid' style='text-align:center'>" + iCodOrd + "</td>";

          saida += "<td class='linhagrid' style='text-align:center' id='numero"+obj.data[i].e69_codnota+"' >" +obj.data[i].m72_codordem + "</td>";
          saida += "<td class='linhagrid' style='text-align:center'><b>"
          saida += "<a href='' onclick='js_consultaNota("+obj.data[i].e69_codnota+");return false'>";
          saida += numnota + "</a></b></td>";
          saida += "<td class='linhagrid' style='text-align:center'>" + obj.data[i].e69_dtnota + "</td>";
          saida += "<td class='linhagrid' style='text-align:center'>" + obj.data[i].e70_valor + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>" + obj.data[i].e70_vlranu + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>" + obj.data[i].e70_vlrliq + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>" + obj.data[i].e53_vlrpag + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>";
          if (iOperacao == 1) {

            saida += "<a href='' id='retencao"+obj.data[i].e69_codnota+"' ";
            saida += "   onclick='js_lancarRetencao("+obj.data[i].e69_codnota+",\""+iCodOrd+"\",\""+obj.data[i].e70_valor+"\");";
            saida += "return false;'>"+nValorRetencao+"</a>";
          } else {
            saida += nValorRetencao;
          }
          saida += "</td></tr>";

        }
      }

        if(obj.Zerado == true){
            alert('"ERRO: N�mero do CPF/CNPJ est� zerado. Corrija o CGM do fornecedor e tente novamente"');
            location.href = 'emp1_empliquida001.php';
        }

      obj.data.each(function (oCodigoNota, iLinha) {

        if (in_array(oCodigoNota.e69_codnota, obj.aItensPendentesPatrimonio)) {
					alert("Os bens v�nculados a esta nota est�o pendentes de tombamento ou dispensa de tombamento no m�dulo patrimonial! ");
        }
      });

      $('confirmar').disabled = false;
    } else {

      alert("Empenho sem notas lan�adas ou pendentes de inclus�o no M�dulo Patrim�nio!");
      $('confirmar').disabled = true;
    }
    if (obj.validaContrato == 'f') {
      var respContrato = confirm("Empenho sem contrato vinculado. Deseja continuar mesmo assim?");
      if (respContrato == false) {
        location.href = 'emp1_empliquida001.php';
      }
    }
    saida += "<tr style='height:auto'><td colspan='10'>&nbsp;</td></tr>";
    $('dados').innerHTML  = saida;
    if (iTotNotas  == 0) {
      if (iOperacao == 1 ) {
        var sAcao = "liquidar";
      }else if (iOperacao == 2) {
        var sAcao = "estornar";
      }
      $('confirmar').disabled = true;
    }
    if (js_strToFloat(obj.saldo_dis) == 0) {

      if (lPesquisaFunc) {
        alert("Empenho sem <?= strtolower($labelVal);?>");
      }
      $('confirmar').disabled = true;
    }
    $('pesquisar').disabled = false;
   /* Extens�o CotaMensalLiquidacao - pt 6 */
}
function js_marcaLinha(obj){

  if (obj.checked){

    $('tr'+obj.id).className='marcado';
  }else{

   $('tr'+obj.id).className='normal';

  }

}

function js_liquidar(metodo){

   if (metodo == "liquidarAjax") {

       aMatrizEntrada = ['3319092', '3319192', '3319592', '3319692'];

       if (aMatrizEntrada.indexOf($F('sEstrutElemento')) !== -1) {

           if ($F('e50_compdesp') == ''){
               alert('Campo Compet�ncia da Despesa deve ser informado.');
               $('e50_compdesp').focus();
               $('pesquisar').disabled = false;
               $('confirmar').disabled = false;
               return false;
           }
       }
   }
   if (iOperacao == 1) {
    if($F('o41_cgmordenador') == '' || $F('o41_cgmordenador') == 0){
          alert('� obrigat�rio informar o ordenador da liquida��o!');
          return false;
    }
   } 

   if(document.form1.aIncide.value == 1){
    if(opcao != '3' && !document.form1.ct01_codcategoria.value &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Categoria do Trabalhador Obrigatorio")
        return false;
    }
    if(opcao != '3' && !document.form1.multiplosvinculos.value &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Possui m�ltiplos v�nculos Obrigatorio")
        return false;
    }
    if(document.form1.multiplosvinculos.value == 1 && opcao != '3' && !document.form1.contribuicaoPrev.value &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Indicador de Desconto da Contribui��o Previdenci�ria Obrigatorio")
        return false;
    }
    if(!document.form1.numempresa.value &&  opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Empresa que efetuou desconto Obrigatorio")
        return false;
    }
    if(!document.form1.ct01_codcategoriaremuneracao.value &&  opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Categoria do trabalhador na qual houve a remunera��o Obrigatorio")
        return false;
    }

    if(!document.form1.valorremuneracao.value &&  opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Valor da Remunera��o Obrigatorio")
        return false;
    }
    if(!document.form1.valordesconto.value &&  opcao != '3' && (document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Valor do Desconto Obrigatorio")
        return false;
    }
    if(!document.form1.competencia.value &&  opcao != '3' && (document.form1.contribuicaoPrev.value == '1' ||document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') &&  obj.Tipofornec =='cpf' && tipodesdobramento == '1' ){
        alert("Campo Compet�ncia Obrigatorio")
        return false;
    }
  }

  if(opcaoReinf != '3'){
    if($('reinfRetencao').value != 0){
      if($('reinfRetencao').value == 'sim' && ($('naturezaCod').value == '' || $('naturezaDesc').value == '')){
        alert("Campo Natureza de Bem ou Servi�o Obrigatorio")
        return false;
      }
    }else{
      alert("Campo Incide Reten��o do Imposto de Renda Obrigatorio")
      return false;
    }
  }

  if($('reinfRetencaoEstabelecimento').value != 0 && opcaoReinf != '3'){
    if(aEstabelecimentos.length == 0){
      alert("Informe um estabelecimento");
    return false;
    }
  }

  const aNAturezasCpf = ['15','17','18','19','20'];
  if(obj.Tipofornec =='cpf' && (aNAturezasCpf.includes(($('naturezaCod').value).substr(0,2))) &&  opcaoReinf != '3'){
    alert("A natureza do rendimento � incompat�vel com o tipo de credor CPF")
    return false;
  }

  const aNaturezasCnpj = ['10','19'];
  if(obj.Tipofornec =='cnpj' && (aNaturezasCnpj.includes(($('naturezaCod').value).substr(0,2))) &&  opcaoReinf != '3'){
    alert("A natureza do rendimento � incompat�vel com o tipo de credor CNPJ")
    return false;
  }

  const desdobramentoDiaria = desdobramento.substr(5, 2);
  if((desdobramentoDiaria == '14' || desdobramentoDiaria == '33') && obrigaDiaria == true && iOperacao === 1){

      if($F('e140_matricula') == '' || $F('e140_matricula') == null){
        alert('Campo Matricula Obrigat�rio.');
        return false;
      }
      if($F('e140_cargo') == '' || $F('e140_cargo') == null){
        alert('Campo Cargo Obrigat�rio.');
        return false;
      }
      if($F('diariaOrigemMunicipio') == '' || $F('diariaOrigemMunicipio') == null
        || $F('diariaOrigemUf') == '' || $F('diariaOrigemUf') == null){
        alert('Campo Origem Obrigat�rio.');
        return false;
      }
      if($F('diariaDestinoMunicipio') == '' || $F('diariaDestinoMunicipio') == null
        || $F('diariaDestinoUf') == '' || $F('diariaDestinoUf') == null){
        alert('Campo Destino Obrigat�rio.');
        return false;
      }
      if($F('e140_dtautorizacao') == '' || $F('e140_dtautorizacao') == null){
        alert('Campo Data da Autoriza��o Obrigat�rio.');
        return false;
      }
      if($F('e140_dtinicial') == '' || $F('e140_dtinicial') == null){
        alert('Campo Data Inicial da Viagem Obrigat�rio.');
        return false;
      }
      if($F('e140_horainicial') == '' || $F('e140_horainicial') == null){
        alert('Campo Hora Inicial Obrigat�rio.');
        return false;
      }
      if($F('e140_dtfinal') == '' || $F('e140_dtfinal') == null){
        alert('Campo Data Final da Viagem Obrigat�rio.');
        return false;
      }
      if($F('e140_horafinal') == '' || $F('e140_horafinal') == null){
        alert('Campo Hora Final Obrigat�rio.');
        return false;
      }
      if($F('e140_objetivo') == '' || $F('e140_objetivo') == null){
        alert('Campo Objetivo da Viagem Obrigat�rio.');
        return false;
      }
  }

  if(iOperacao === 1){
    if($F('dataLiquidacao') == ''){
      alert('Campo Data da Liquida��o obrigat�rio!');
      $('dataLiquidacao').focus();
      return false;
    }

    if ($F('paramentosOrdenadores') == 2 && $F('db243_data_inicio') && $F('db243_data_final')) {
      let dataliquidacao = $F('dataLiquidacao').split('/');
      let dataliquidacaoformatada = new Date(dataliquidacao[2], dataliquidacao[1] - 1, dataliquidacao[0]).toISOString();

      var  datainicioformatada = new Date($F('db243_data_inicio')).toISOString();
      var datafimformatada = new Date($F('db243_data_final')).toISOString();

      var datafinal = $F('db243_data_final').split('-');
      datafinal     = datafinal[2]+'/'+datafinal[1]+'/'+datafinal[0];

      var datainicial = $F('db243_data_inicio').split('-');
      datainicial     = datainicial[2]+'/'+datainicial[1]+'/'+datainicial[0];

      if (dataliquidacaoformatada.substring(0,10) < datainicioformatada.substring(0,10) || dataliquidacaoformatada.substring(0,10) > datafimformatada.substring(0,10)) {
          if (dataliquidacaoformatada.substring(0,10) < datainicioformatada.substring(0,10)) {
            alert('O ordenador selecionado est� fora da vig�ncia da liquida��o, data in�cio: '+datainicial);
          }
          if (dataliquidacaoformatada.substring(0,10) > datafimformatada.substring(0,10)) {
            alert('O ordenador selecionado est� fora da vig�ncia da liquida��o, data fim: '+datafinal);
          }
          $('o41_cgmordenador').focus();
          return false;
      } 
    }  
  }

  if(iOperacao === 2){
    if($F('dataEstorno') == ''){
      alert('Campo Data de Estorno obrigat�rio!');
      $('dataEstorno').focus();
      return false;
    }
  }

   itens = js_getElementbyClass(form1,'chkmarca');
   notas = '';
   sV    = '';
   var aNotas = new Array();
   for (i = 0;i < itens.length;i++){
     if (itens[i].checked == true){
       aNotas.push(itens[i].value);
      }
    }

    if(aNotas.length > 1 && obrigaDiaria == true && iOperacao === 1){
      alert("N�o � poss�vel realizar liquida��o de mais de uma nota quando h� Di�rias");
      return false;
    }

   if((desdobramentoDiaria == '14' || desdobramentoDiaria == '33') && obrigaDiaria == true && iOperacao === 1 && aNotas.length != 0){
    let totalDespesa = $F('diariaVlrDespesa') == '' ? 0 : parseFloat($F('diariaVlrDespesa'));
    let vlrLiquidado = parseFloat($F('e70_valor'+aNotas[0]).replace('.','').replace(',','.'));
    if(vlrLiquidado != totalDespesa){
      alert('Valor da Liquida��o precisa ser igual ao Valor Total da Despesa');
      return false;
    }
   }


    if(aNotas.length > 1 && $('reinfRetencaoEstabelecimento').value == 1){
      alert("N�o � poss�vel realizar liquida��o de mais de uma nota quando h� Reten��o Realizada por Terceiros");
      return false;
    }

    $('pesquisar').disabled = true;
    $('confirmar').disabled = true;
   if (aNotas.length != 0){

     if (metodo == "estornarLiquidacaoAJAX") {

       var sMensagem = "Aguarde, estornando liquidacao das notas";
       if (!confirm("Confirma o estorno da liquida��o?")) {
         $('pesquisar').disabled = false;
         $('confirmar').disabled = false;
         return false;
       }

     } else {
       var sMensagem = "Aguarde, Liquidando notas";

       /* Extens�o CotaMensalLiquidacao - pt 5 */
     }
     js_divCarregando(sMensagem, "msgLiq");

     var oParam        = new Object();
     oParam.method     = metodo;
     oParam.iEmpenho   = $F('e60_numemp');
     oParam.notas      = aNotas;
     oParam.informacaoop  = encodeURIComponent($F('informacaoop'));

     if(obj.e60_informacaoop){
        oParam.historico  = encodeURIComponent($F('informacaoop'));
     }
     else{
        oParam.historico  = encodeURIComponent($F('historico'));
     }

     oParam.e03_numeroprocesso = encodeURIComponent(tagString($F("e03_numeroprocesso")));

     oParam.pars       = $F('e60_numemp');
     oParam.z01_credor = $F('e49_numcgm');
     oParam.e50_numcgmordenador = iOperacao == 1 ? $F('o41_cgmordenador') : '';
     oParam.e50_compdesp = $F('e50_compdesp');
     oParam.e83_codtipo  = $F('e83_codtipo');
     oParam.cattrabalhador = encodeURIComponent($F('ct01_codcategoria'));
     oParam.numempresa = encodeURIComponent($F('numempresa'));
     oParam.contribuicaoPrev = $F('contribuicaoPrev');
     oParam.cattrabalhadorremuneracao = $F('ct01_codcategoriaremuneracao');
     oParam.valorremuneracao = encodeURIComponent($F('valorremuneracao'));
     oParam.valordesconto = encodeURIComponent($F('valordesconto'));
     oParam.competencia = $F('competencia');
     oParam.e50_retencaoir = $F('reinfRetencao');
     oParam.e50_naturezabemservico = $F('naturezaCod');
     if(iOperacao === 1){
      oParam.e50_contafornecedor = $F('e50_contafornecedor');
      oParam.dDataLiquidacao = $F('dataLiquidacao');
      oParam.dDataVencimento = $F('dataVencimento');
     }
     if(iOperacao === 2){
      oParam.dDataEstorno = $F('dataEstorno');
     }
     url      = 'emp4_liquidacao004.php';
     oAjax    = new Ajax.Request(
                            url,
                              {
                               method: 'post',
                               parameters: 'json='+Object.toJSON(oParam),
                               onComplete: js_saidaLiquidacao
                              }
                             );
   } else{

     alert('Selecione ao menos 1 (uma) nota para liquidar');
     $('pesquisar').disabled = false;
     $('confirmar').disabled = false;

   }
}

function js_saidaLiquidacao(oAjax){

  obj      = eval("("+oAjax.responseText+")");
    if(aEstabelecimentos.length > 0 && obj.erro == 1 && iOperacao === 1){
      var oParam = new Object();
      oParam.method = "incluiRetencaoImposto"
      oParam.iCodOrdem = obj.sOrdensGeradas
      oParam.aEstabelecimentos = aEstabelecimentos;
      url      = 'emp4_pagordemreinf.RPC.php';
      oAjax    = new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json='+js_objectToJson(oParam)
        }
      );
    }
  js_validarEstabelecimentos(true);

  //Inclui Diaria

  if(iOperacao === 1 && obj.erro == 1){
    const desdobramentoDiaria = desdobramento.substr(5, 2);
    if((desdobramentoDiaria == '14' || desdobramentoDiaria == '33') && obrigaDiaria == true){

      var oParam = new Object();
      oParam.exec = 'incluiDiaria';
      oParam.e140_codord                = obj.sOrdensGeradas;
      oParam.e140_dtautorizacao         = $F('e140_dtautorizacao');
      oParam.e140_matricula             = $F('e140_matricula');
      oParam.e140_cargo                 = $F('e140_cargo');
      oParam.e140_dtinicial             = $F('e140_dtinicial');
      oParam.e140_dtfinal               = $F('e140_dtfinal');
      oParam.e140_horainicial           = $F('e140_horainicial');
      oParam.e140_horafinal             = $F('e140_horafinal');
      oParam.e140_origem                = $F('diariaOrigemMunicipio') + " - " + $F('diariaOrigemUf');
      oParam.e140_destino               = $F('diariaDestinoMunicipio') + " - " + $F('diariaDestinoUf');
      oParam.e140_qtddiarias            = $F('e140_qtddiarias') == '' ? 0 : $F('e140_qtddiarias');
      oParam.e140_vrldiariauni          = $F('e140_vrldiariauni') == '' ? 0 : $F('e140_vrldiariauni');
      oParam.e140_qtddiariaspernoite    = $F('e140_qtddiariaspernoite') == '' ? 0 : $F('e140_qtddiariaspernoite');
      oParam.e140_vrldiariaspernoiteuni = $F('e140_vrldiariaspernoiteuni') == '' ? 0 : $F('e140_vrldiariaspernoiteuni');
      oParam.e140_qtdhospedagens        = $F('e140_qtdhospedagens') == '' ? 0 : $F('e140_qtdhospedagens');
      oParam.e140_vrlhospedagemuni      = $F('e140_vrlhospedagemuni') == '' ? 0 : $F('e140_vrlhospedagemuni');
      oParam.e140_transporte            = $F('e140_transporte');
      oParam.e140_vlrtransport          = $F('e140_vlrtransport') == '' ? 0 : $F('e140_vlrtransport');
      oParam.e140_objetivo              = $F('e140_objetivo');
      url      = 'emp4_empdiaria.RPC.php';
      oAjax    = new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json='+js_objectToJson(oParam)
        }
        );
      }
  }
  js_removeObj("msgLiq");

    $('pesquisar').disabled = false;
    $('confirmar').disabled = false;

    if (obj.lErro == true) {
      alert(obj.sMensagem.urlDecode()); return false;
    }

    mensagem = obj.mensagem.replace(/\+/g," ");
    mensagem = unescape(mensagem);
    if (obj.erro == 2){
       alert(mensagem);
    }
    if (obj.erro == 1){
       if (!obj.estorno) {
         js_emitir(obj.sOrdensGeradas);
       }
       lPesquisaFunc = false;
       js_consultaEmpenho($F('e60_numemp'),<?=$operacao?>);

        document.form1.ct01_codcategoria.value = '';
        document.form1.ct01_descricaocategoria.value = '';
        document.form1.contribuicaoPrev.value = '';
        document.form1.ct01_codcategoriaremuneracao.value = '';
        document.form1.ct01_descricaocategoriaremuneracao.value = '';
        document.form1.numempresa.value = '';
        document.form1.nomeempresa.value = '';
        document.form1.valorremuneracao.value = '';
        document.form1.valordesconto.value = '';
        document.form1.competencia.value = '';
    }

}
function js_decodeUrl(sTexto){

   texto = sTexto.replace(/\+/g," ");
   texto = unescape(texto);
   return texto;

}
function js_pesquisaCatTrabalhador(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_categoriatrabalhador.php?funcao_js=parent.js_mostraCatTrabalhador1|ct01_codcategoria|ct01_descricaocategoria','Pesquisa',true);
  }else{
     if(document.form1.ct01_codcategoria.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_categoriatrabalhador.php?pesquisa_chave='+document.form1.ct01_codcategoria.value+'&funcao_js=parent.js_mostraCatTrabalhador','Pesquisa',false);
     }else{
       document.form1.ct01_codcategoria.value = '';
       document.form1.ct01_descricaocategoria.value = '';
     }
  }
}
function js_pesquisaCatTrabalhadorremuneracao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_categoriatrabalhador.php?funcao_js=parent.js_mostraCatTrabalhadorremuneracao1|ct01_codcategoria|ct01_descricaocategoria','Pesquisa',true);
  }else{
     if(document.form1.ct01_codcategoriaremuneracao.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_categoriatrabalhador.php?pesquisa_chave='+document.form1.ct01_codcategoriaremuneracao.value+'&funcao_js=parent.js_mostraCatTrabalhadorremuneracao','Pesquisa',false);
     }else{
       document.form1.ct01_codcategoriaremuneracao.value = '';
       document.form1.ct01_descricaocategoriaremuneracao.value = '';
     }
  }
}
function js_pesquisae49_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.e49_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e49_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_credor.value = '';
     }
  }
}
function js_pesquisaEmpresa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostraEmpresa1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.numempresa.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.numempresa.value+'&funcao_js=parent.js_mostraEmpresa','Pesquisa',false);
     }else{
       document.form1.nomeempresa.value = '';
     }
  }
}
function  js_dadosordenador(dadosOrdenador,numempenho)
{
        var oParam = new Object();
        oParam.method     = "getOrdenador";
        oParam.sOrdenador = dadosOrdenador;
        oParam.sNumEmpenho = numempenho;
        js_divCarregando('Aguarde, Buscando dados ordenador', 'msgBox');
        var oAjax = new Ajax.Request(
            'emp4_liquidacao004.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornogetOrdenador
            }
        );
}
function js_retornogetOrdenador(oAjax) 
  {

        js_removeObj('msgBox');
        var oResposta = JSON.parse(oAjax.responseText);
        document.getElementById('db243_data_inicio').value = oResposta.db243_data_inicio;
        document.getElementById('db243_data_final').value = oResposta.db243_data_final;

}    
function js_mostraCatTrabalhador(erro,chave){
  document.form1.ct01_descricaocategoria.value = chave;
  if(erro==true){
    document.form1.ct01_codcategoria.focus();
    document.form1.ct01_codcategoria.value = '';
  }
}
function js_mostraCatTrabalhador1(chave1,chave2){
  document.form1.ct01_codcategoria.value = chave1;
  document.form1.ct01_descricaocategoria.value = chave2;
  db_iframe_cgm.hide();
}
function js_mostraCatTrabalhadorremuneracao(erro,chave){
  document.form1.ct01_descricaocategoriaremuneracao.value = chave;
  if(erro==true){
    document.form1.ct01_codcategoriaremuneracao.focus();
    document.form1.ct01_codcategoriaremuneracao.value = '';
  }
}
function js_mostraCatTrabalhadorremuneracao1(chave1,chave2){
  document.form1.ct01_codcategoriaremuneracao.value = chave1;
  document.form1.ct01_descricaocategoriaremuneracao.value = chave2;
  db_iframe_cgm.hide();
}
function js_mostraEmpresa(erro,chave){
  document.form1.nomeempresa.value = chave;
  if(erro==true){
    document.form1.numempresa.focus();
    document.form1.numempresa.value = '';
  }
}
function js_mostraEmpresa1(chave1,chave2){
  document.form1.numempresa.value = chave1;
  document.form1.nomeempresa.value = chave2;
  db_iframe_cgm.hide();
}
function js_mostracgm(erro,chave){
  document.form1.z01_credor.value = chave;
  if(erro==true){
    document.form1.e49_numcgm.focus();
    document.form1.e49_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e49_numcgm.value = chave1;
  document.form1.z01_credor.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa_cgm(mostra)
{
        if(mostra==true){
            js_OpenJanelaIframe('','db_iframe_cgm','func_cgmordenador.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|tipo','Pesquisa',true);
        }else{
            if(document.form1.o41_cgmordenador.value != ''){
                js_OpenJanelaIframe('','db_iframe_cgm','func_cgmordenador.php?pesquisa_chave='+document.form1.o41_cgmordenador.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
            }else{
                document.form1.o41_cgmordenadordescr.value = '';
            }
        }
}

function js_mostracgm(erro, chave,chave2)
{ 
        if (chave2 == 'JURIDICA') {
            alert("� obrigat�rio que o ordenador seja uma pessoa f�sica.")
            document.form1.o41_cgmordenador.value = '';
            document.form1.o41_cgmordenadordescr.value = '';
            document.form1.o41_cgmordenador.focus();
        } else {
            document.form1.o41_cgmordenadordescr.value = chave;
        }
        if (erro == true) {
            document.form1.o41_cgmordenador.focus();
            document.form1.o41_cgmordenador.value = '';
        }
}

function js_mostracgm1(chave, chave1,chave2) 
{
       
  if (chave2 == 'JURIDICA') {
    alert("� obrigat�rio que o ordenador seja uma pessoa f�sica.")
    document.form1.o41_cgmordenador.value = '';
    document.form1.o41_cgmordenadordescr.value = '';
    return false;
  } else {
    document.form1.o41_cgmordenador.value = chave;
    document.form1.o41_cgmordenadordescr.value = chave1;
    db_iframe_cgm.hide();
  }
      
}

function removerObj(id) {

   obj = $(id);
   parent
   parent = obj.parentNode;
   parent.removeChild(obj);
}

function js_consultaNota(iCodNota) {
  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_nota', 'emp2_consultanotas002.php?e69_codnota='+iCodNota, 'Pesquisa Dados da Nota', true);
}

function js_lancarRetencao(iCodNota, iCodOrd, nValor){

   var iNumEmp  = $F('e60_numemp');
   var lSession = <?=$operacao==2?"false":"true"?>;
   var iNumCgm  = $F('e49_numcgm');
   $('e49_numcgm').disabled = true;
   js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_retencao',
                       'emp4_lancaretencoes.php?iNumNota='+iCodNota+'&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+
                       '&iNumCgm='+iNumCgm+
                       "&lSession="+lSession+"&nValorBase="+js_strToFloat(nValor)+"&iCodMov=&callback=true",
                       'Lancar Reten��es', true);

}

function js_atualizaValorRetencao(iCodMov, nValor, iNota, iCodOrdem) {

   if (nValor > 0) {
     $('e49_numcgm').disabled      = true;
   } else {
     $('e49_numcgm').disabled      = false;
   }
   $('retencao'+iNota).innerHTML = js_formatar(nValor,'f');
   db_iframe_retencao.hide();

}

/**
 * Fun��o que simula o in_array do PHP
 */
function in_array(valor,vetor){

  for (var i in vetor) {

    if (valor == vetor[i]) {
      return true;
    }
  }
  return false;
};

function js_pesquisa_contapagadora(mostra) {

    if (mostra==true) {
        js_OpenJanelaIframe('top.corpo','db_iframe_empagetipo','func_empagetipo.php?e60_numemp='+$('e60_numemp').value+'&funcao_js=parent.js_mostracontapagadora1|e83_codtipo|e83_conta|e83_descr','Pesquisa',true);
    } else {

        if ($('e83_conta').value != '') {
            js_OpenJanelaIframe('top.corpo','db_iframe_empagetipo','func_empagetipo.php?pesquisa_chave='+$('e83_conta').value+'&e60_numemp='+$('e60_numemp').value+'&e83_conta='+$('e83_conta').value+'&funcao_js=parent.js_mostracontapagadora','Pesquisa',false);
        } else {
            $('e83_descr').value    = '';
            $('e83_codtipo').value  = '';
        }
    }

}

function js_mostracontapagadora(chave1,chave2,erro) {

    $('e83_descr').value    = chave1;
    $('e83_codtipo').value  = chave2;
    if (erro == true) {

        $('e83_codtipo').value = '';
        $('e83_conta').value    = '';
        $('e83_codtipo').focus();

    }

}

function js_mostracontapagadora1(chave1,chave2,chave3) {

    $('e83_codtipo').value  = chave1;
    $('e83_conta').value    = chave2;
    $('e83_descr').value    = chave3;
    db_iframe_empagetipo.hide();

}

function js_importarHistorico(){
    itens = js_getElementbyClass(form1,'chkmarca');
    let aNotas = new Array();
    for (i = 0;i < itens.length;i++){
      if (itens[i].checked == true){
         aNotas.push(itens[i].value);
      }
    }

    if(aNotas.length === 0){
      alert('Selecione uma nota para importar o hist�rico.')
      return false
    }else if(aNotas.length > 1){
      alert('Erro. Mais de uma nota selecionada.')
      return false
    }else{
      let m51_obs = 'm51_obs'+aNotas[0]
      if ($('opcredito').style.display == "table-cell"){
        $('informacaoop').value = $(m51_obs).value;
      }else if($('ophisotrico').style.display == "table-cell"){
        $('historico').value = $(m51_obs).value;
      }
    }
  }

$('contribuicaoPrev').style.width =' 495px';
$('aIncide').style.width =' 120px';
$('multiplosvinculos').style.width =' 120px';
if (iOperacao == 1) {
  $('o41_cgmordenador').style.width =' 22%';
  $('o41_cgmordenadordescr').style.width =' 76.5%';
}

$('e60_codemp').disabled = true;
$('e60_numemp').disabled = true;
$('e60_numcgm').disabled = true;
$('z01_nome').disabled = true;
$('z01_credor').disabled = true;
$('e60_coddot').disabled = true;
$('estruturalDotacao').disabled = true;
$('e83_descr').disabled = true;
$('e60_vlremp').disabled = true;
$('e60_vlranu').disabled = true;
$('e60_vlrliq').disabled = true;
$('e60_vlrpag').disabled = true;
$('saldo_disp').disabled = true;
$('ct01_descricaocategoria').disabled = true;
$('nomeempresa').disabled = true;
$('ct01_descricaocategoriaremuneracao').disabled = true;
$('o41_cgmordenador').focus();
document.addEventListener("DOMContentLoaded", function() {
var elementosNoSelect = document.querySelectorAll('input:disabled');
    elementosNoSelect.forEach(function (elemento) {
        elemento.style.color = 'black'
    });
  });

</script>
<?php
function buscarParamentosOrdenadores()
{
  $clempparametro    = new cl_empparametro;
  return db_utils::fieldsMemory($clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_buscarordenadoresliqui", null, "")), 0)->e30_buscarordenadoresliqui;
}
function listarOrdenadores($e60_numemp) 
{
  $o58_anousu  = 0;
  $o58_instit  = 0;
  $o58_orgao   = 0;
  $o58_unidade = 0;
  $e56_autori  = 0;
  $clempautidot      = new cl_empautidot;
  $clorcunidade      = new cl_orcunidade;
  $oDaoElementos     = db_utils::getDao('orcelemento');

  $sWhereEmpenho     = " e60_numemp =  {$e60_numemp}";
  $sSqlBuscaElemento = $oDaoElementos->sql_query_estrut_empenho(null, "substr(o56_elemento,1,7) AS o56_elemento,e60_coddot,e60_emiss", null, $sWhereEmpenho);
  $rsBuscaElemento   = $oDaoElementos->sql_record($sSqlBuscaElemento);
  $coddotacao = db_utils::fieldsMemory($rsBuscaElemento, 0)->e60_coddot;

  $anoUsu = db_getsession("DB_anousu");
  $sWhere = "e56_coddot =	" . $coddotacao . " and ( e56_anousu = " . $anoUsu ."or e64_vlrpag > 0 ) and e61_numemp = ".$e60_numemp; 
  $rsResult = $clempautidot->sql_record($clempautidot->sql_query_dotacao_empenho(null, "*", null, $sWhere));
  $numrows = $clempautidot->numrows;

  if ($numrows > 0) {
     
      $o58_anousu  = db_utils::fieldsMemory($rsResult, 0)->o58_anousu;
      $o58_instit  = db_utils::fieldsMemory($rsResult, 0)->o58_instit;
      $o58_orgao   = db_utils::fieldsMemory($rsResult, 0)->o58_orgao;
      $o58_unidade = db_utils::fieldsMemory($rsResult, 0)->o58_unidade;
      $e56_autori  = db_utils::fieldsMemory($rsResult, 0)->e56_autori;
  }

  $buscarOrdenadores = buscarParamentosOrdenadores();
  if ($buscarOrdenadores == 1 ) {

     $whereUnidades = " o41_anousu = {$anoUsu} and o41_instit = {$o58_instit} and o41_orgao = {$o58_orgao} and o41_unidade = {$o58_unidade} ";
     $o41_cgmordenador =  db_utils::fieldsMemory($clorcunidade->sql_record($clorcunidade->sql_query($anoUsu,null,null, " o41_ordliquidacao ",null,$whereUnidades)), 0)->o41_ordliquidacao;
     $result_cgmorliquidaca = db_query("SELECT z01_nome as nomeordenador FROM cgm WHERE z01_numcgm = {$o41_cgmordenador}");
     $o41_nomeordenador = db_utils::fieldsMemory($result_cgmorliquidaca, 0)->nomeordenador;

     if ($numrows > 0) {
       $ordenadoresArray[] = [

        'z01_numcgm' => $o41_cgmordenador,
        'o41_nomeordenador' => $o41_nomeordenador,
        'erro'        =>  true,
      ];
      return $ordenadoresArray; 
     }

     return ['erro' => false];
    
  } else {

   $anoUsu = db_getsession("DB_anousu");
 $results = DB::table('empenho.empautidot')
               ->join('orcdotacao', function($join) {
               $join->on('orcdotacao.o58_anousu', '=', 'empautidot.e56_anousu')
                    ->on('orcdotacao.o58_coddot', '=', 'empautidot.e56_coddot');
               })
               ->join('empautoriza', 'empautoriza.e54_autori', '=', 'empautidot.e56_autori')
               ->join('orcelemento', function($join) {
                   $join->on('orcelemento.o56_codele', '=', 'orcdotacao.o58_codele')
                        ->on('orcelemento.o56_anousu', '=', 'orcdotacao.o58_anousu');
               })
               ->join('empempaut', 'empempaut.e61_autori', '=', 'empautidot.e56_autori')
               ->join('empelemento', 'empelemento.e64_numemp', '=', 'empempaut.e61_numemp')
               ->leftJoin('orctiporec', 'orctiporec.o15_codigo', '=', 'empautidot.e56_orctiporec')
               ->select('*')
               ->where('empenho.empautidot.e56_autori', '=',$e56_autori)
               ->where(function ($query) {
                $query->where('empenho.empautidot.e56_anousu', '=', $anoUsu)
                      ->orWhere('empelemento.e64_vlrpag', '>', 0);
               })
               ->get()->toArray();
        
   foreach ($results as $autorizacao) {  
       $o58_instit  =  $autorizacao->o58_instit; 
       $o58_orgao   =  $autorizacao->o58_orgao; 
       $o58_unidade =  $autorizacao->o58_unidade; 
       $o58_anousu  =  $autorizacao->o58_anousu; 
   }   

   $aAssinantes = DB::table('configuracoes.assinatura_digital_assinante')
               ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
               ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
               ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
               ->where('configuracoes.assinatura_digital_assinante.db243_instit', '=', $o58_instit)
               ->where('configuracoes.assinatura_digital_assinante.db243_orgao', '=', $o58_orgao)
               ->where('configuracoes.assinatura_digital_assinante.db243_unidade', '=', $o58_unidade)
               ->where('configuracoes.assinatura_digital_assinante.db243_anousu', '=', $anoUsu)
               ->where('configuracoes.assinatura_digital_assinante.db243_cargo', '=', 2)
               ->where('configuracoes.assinatura_digital_assinante.db243_documento', '=', 1)
               ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo','protocolo.cgm.z01_numcgm','configuracoes.assinatura_digital_assinante.db243_data_inicio','configuracoes.assinatura_digital_assinante.db243_data_final')
               ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
               ->get()->toArray();         

   $rowCount = count($aAssinantes);  
   $ordenadoresArray = array(); 
   $uniqueKeys = array(); 
   
   foreach ($aAssinantes as $assinante) {
    $uniqueKey = $assinante->nome . '|' . $assinante->z01_numcgm;

        if (!in_array($uniqueKey, $uniqueKeys)) {
            $ordenadoresArray[] = [
                'nome' => $assinante->nome,
                'z01_numcgm' => $assinante->z01_numcgm,
                'db243_data_inicio' => $assinante->db243_data_inicio,
                'db243_data_final' => $assinante->db243_data_final,
                'rowCount'    => $rowCount,
                'erro'        =>  $numrows,
            ];
            $uniqueKeys[] = $uniqueKey;
        }
}
if ($rowCount > 0) {
   return $ordenadoresArray;
}

return ['erro' => false];
}
}
?>

