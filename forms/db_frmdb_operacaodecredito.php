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
//MODULO: contabilidade
require_once(modification("dbforms/db_classesgenericas.php"));
$clrotulo = new rotulocampo;

$clrotulo->label("op01_sequencial");
$clrotulo->label("op01_numeroleiautorizacao");
$clrotulo->label("op01_dataleiautorizacao");
$clrotulo->label("op01_datadepublicacaodalei");
$clrotulo->label("op01_valorautorizadoporlei"); 
$clrotulo->label("op01_numerocontratoopc");
$clrotulo->label("op01_dataassinaturacop");
$clrotulo->label("op01_credor"); 
$clrotulo->label("op01_tipocredor"); 
$clrotulo->label("op01_tipolancamento");
$clrotulo->label("op01_subtipolancamento");
$clrotulo->label("op01_detalhamentodivida");
$clrotulo->label("op01_objetocontrato");
$clrotulo->label("op01_descricaodividaconsolidada");
$clrotulo->label("op01_descricaocontapcasp");
$clrotulo->label("op01_moedacontratacao");
$clrotulo->label("op01_taxajurosdemaisencargos");
$clrotulo->label("op01_valorcontratacao");
$clrotulo->label("op01_dataquitacao");
$clrotulo->label("op01_datadecadastro");

$clrotulo->label("dv01_codoperacaocredito");
$clrotulo->label("dv01_principaldividalongoprazop");
$clrotulo->label("dv01_principaldividacurtoprazop");
$clrotulo->label("dv01_principaldividaf");
$clrotulo->label("dv01_jurosdividalongoprazop");
$clrotulo->label("dv01_jurosdividaf");

?>
<input type="hidden" id="field_search">
<input type="hidden" id="desabilita_entrada_pcasp" value="<?php echo $db_opcao ?>">

<form id="frm_divida_consolidada" name="form1" method="post" action="">
  <center>
    <fieldset>
      <legend align="center">
        <b>Dívida consolidada</b>   
      </legend>
      <table border="0">
        <tr>
          <td nowrap title="<?php echo @$Top01_sequencial ?>">
          <?php echo @$Lop01_sequencial ?>
          </td> 
          <td>
            <?
              db_input('op01_sequencial', 13, $Iop01_sequencial, true, 'text', 3, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_datadecadastro ?>">
          <?php echo @$Lop01_datadecadastro ?>  &nbsp *
          </td> 
          <td>
            <?
              db_inputdata('op01_datadecadastro', @$op01_datadecadastro_dia, @$op01_datadecadastro_mes, @$op01_datadecadastro_ano, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_numeroleiautorizacao?>">
          <?php echo @$Lop01_numeroleiautorizacao ?> &nbsp *
          </td>
          <td>
            <?
              db_input('op01_numeroleiautorizacao', 13, 0, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_dataleiautorizacao ?>">
          <?php echo @$Lop01_dataleiautorizacao ?> &nbsp *
          </td>
          <td>
            <?
              db_inputdata('op01_dataleiautorizacao', @$op01_dataleiautorizacao_dia, @$op01_dataleiautorizacao_mes, @$op01_dataleiautorizacao_ano, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_datadepublicacaodalei ?>">
          <?php echo @$Lop01_datadepublicacaodalei ?> &nbsp *
          </td>
          <td>
            <?
              db_inputdata('op01_datadepublicacaodalei', @$op01_datadepublicacaodalei_dia, @$op01_datadepublicacaodalei_mes, @$op01_datadepublicacaodalei_ano, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_valorautorizadoporlei ?>">
          <?php echo @$Lop01_valorautorizadoporlei ?>
          </td>
          <td>
            <?
              db_input('op01_valorautorizadoporlei', 13, 0, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_numerocontratoopc ?>">
            <?php echo @$Lop01_numerocontratoopc ?>  &nbsp *
          </td>
          <td>
            <?
              db_input('op01_numerocontratoopc', 40, 0, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_dataassinaturacop ?>">
          <?php echo @$Lop01_dataassinaturacop ?> &nbsp *
          </td>
          <td>
            <?
              db_inputdata('op01_dataassinaturacop', @$op01_dataassinaturacop_dia, @$op01_dataassinaturacop_mes, @$op01_dataassinaturacop_ano, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td><?db_ancora("<b>Credor:</b>","js_pesquisa_cgm(true);",1);?> &nbsp * </td>
          <td id="td_credor">
              <?
                db_input("op01_credor",10,1,true,"text",4,"onchange='js_pesquisa_cgm(false);'");
                db_input("z01_nome2",50,"", true,"text",3, "");
              ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_tipocredor ?>">
          <?php echo @$Lop01_tipocredor ?> &nbsp * 
          </td>
          <td>
            <?
            $aOptionsTipoCredor= [0 => 'Selecione', 1 => 'Consolidação', 2 => 'Intraorçamentária', 3 => 'Esfera Federal', 4 => 'Esfera Estadual', 5 => 'Esfera Municipal'];
            db_select('op01_tipocredor', $aOptionsTipoCredor, true, $db_opcao, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_tipolancamento ?>">
          <?php echo @$Lop01_tipolancamento ?> &nbsp * 
          </td>
          <td>
            <?
            $aOptionsTipoLancamento = [
              0 => 'Selecione',
              1 => '01 - Dívida Mobiliária',
              2 => '02 - Dívida Contratual de PPP',
              3 => '03 - Dívida Contratual de Financiamentos, Aquisição Financiada de Bens e Arrendamento Mercantil Financeiro',
              4 => '04 - Dívida Contratual de Empréstimos',
              6 => '06 - Dívida Contratual de Antecipação de Receita pela Venda a Termo de Bens e Serviços',
              7 => '07 - Dívida Contratual de Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 29, § 1º)',
              9 => '09 - Dívida Contratual de Parcelamento e Renegociação de Dívidas de Tributos',
              10 => '10 - Dívida Contratual de Parcelamento e Renegociação de Dívidas de Contribuições Previdenciárias',
              11 => '11 - Dívida Contratual de Parcelamento e Renegociação de Dívidas de Demais Contribuições Sociais',
              12 => '12 - Dívida Contratual de Parcelamento e Renegociação de Dívidas do FGTS',
              13 => '13 - Dívida Contratual de Parcelamento e Renegociação de Dívida com Instituição não Financeira',
              15 => '15 - Demais Dívidas Contratuais',
              17 => '17 - Precatórios Posteriores a 05/05/2000 (inclusive) - Vencidos e não Pagos',
              18 => '18 - Precatórios Posteriores a 05/05/2000 (Não incluídos na DC)',
              19 => '19 - Reestruturação da Dívida do Município',
              20 => '20 - Outras Dívidas',
              21 => '21 - Outras Operações de Crédito não Sujeitas ao Limite Para Fins de Contratação',
              22 => '22 - Operações Vedadas', 
              23 => '23 - Precatórios Anteriores a 05/05/2000'
            ];
            db_select('op01_tipolancamento', $aOptionsTipoLancamento, true, $db_opcao, "");
            ?>
          </td>
        </tr>

        <tr id="tr_subtipolancamento" style="visibility: hidden;">
          <td nowrap title="<?php echo @$Top01_subtipolancamento ?>">
          <?php echo @$Lop01_subtipolancamento ?> &nbsp * 
          </td>
          <td>
            <?
            $aOptionsSubtipoLancamento = [0 => 'Selecione', 1 => 'Interna', 2 => 'Externa'];
            db_select('op01_subtipolancamento', $aOptionsSubtipoLancamento, true, $db_opcao, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr id="tr_detalhamentodivida" style="visibility: hidden;">
          <td nowrap title="<?php echo @$Top01_detalhamentodivida ?>">
          <?php echo @$Lop01_detalhamentodivida ?> &nbsp * 
          </td>
          <td>
            <?
            $aOptionsDetalhamentoDivida = [
              0 => 'Selecione',
              1 => 'Financiamento',
              2 => 'Aquisição Financeira de Bens',
              3 => 'Arrendamento Mercantil',
              4 => 'Tributos Federais',
              5 => 'Tributos Estaduais',
              6 => 'Tributos Municipais',
              7 => 'Pessoal - Regime Especial',
              8 => 'Pessoal - Regime Ordinário',
              9 => 'Benefícios Previdenciários - Regime Especial',
              10 => 'Benefícios Previdenciários - Regime Ordinário',
              11 => 'Benefícios Assistenciais - Regime Especial',
              12 => 'Benefícios Assistenciais - Regime Ordinário',
              13 => 'Fornecedores - Regime Especial',
              14 => 'Fornecedores - Regime Ordinário',
              15 => 'Dívida de Consórcio Público',
              16 => 'Outras Dívidas'
            ];
            db_select('op01_detalhamentodivida',$aOptionsDetalhamentoDivida, true, $db_opcao, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_objetocontrato ?>">
          <?php echo @$Lop01_objetocontrato ?> &nbsp * 
          </td>
          <td>
            <?
              db_textarea('op01_objetocontrato', 3, 54, 'op01_objetocontrato', true, 'text', $db_opcao, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_descricaodividaconsolidada ?>">
          <?php echo @$Lop01_descricaodividaconsolidada ?> &nbsp *  &nbsp
          </td>
          <td>
            <?
              db_textarea('op01_descricaodividaconsolidada', 3, 54, 'op01_descricaodividaconsolidada', true, 'text', $db_opcao, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_descricaocontapcasp ?>">
          <?php echo @$Lop01_descricaocontapcasp ?> &nbsp * 
          </td>
          <td>
            <?
              db_input('op01_descricaocontapcasp', 60, 0, true, 'text', $db_opcao, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_moedacontratacao ?>">
          <?php echo @$Lop01_moedacontratacao ?>
          </td>
          <td>
            <?
              db_input('op01_moedacontratacao', 30, 0, true, 'text', $db_opcao, "style='width: 100%; background-color:#E6E4F1'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_taxajurosdemaisencargos ?>">
          <?php echo @$Lop01_taxajurosdemaisencargos ?>
          </td>
          <td>
            <?
              db_textarea('op01_taxajurosdemaisencargos', 3, 54, 'op01_taxajurosdemaisencargos', true, 'text', $db_opcao, "style='width: 100%; background-color:#E6E4F1'")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_valorcontratacao ?>">
          <?php echo @$Lop01_valorcontratacao ?>
          </td>
          <td>
            <?
              db_input('op01_valorcontratacao', 13, 0, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Top01_dataquitacao ?>">
          <?php echo @$Lop01_dataquitacao ?>
          </td>
          <td>
            <?
              db_inputdata('op01_dataquitacao', @$op01_dataquitacao_dia, @$op01_dataquitacao_mes, @$op01_dataquitacao_ano, true, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>

      </table>
    </fieldset>
  </center>
  <br>

  <input name="<?php echo ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?php echo ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" >
  <input type="button" id="novo" name="novo" value="Novo" onclick="js_novoRegistro()">
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>


<form id="frm_pcasp" name="frm_pcasp" style="display: none" method="post">

  <input type="hidden" name="dv01_anousu" id="dv01_anousu" value="<?php echo db_getsession("DB_anousu") ?>">

   <center>
      <fieldset>
        <legend align="center">
          <b>PCASP</b>
        </legend>

        <table border="0" >

          <tr>
            <td nowrap title="<?php echo @$Tdv01_codoperacaocredito ?>">
            <?php echo @$Lop01_sequencial ?>
            </td> 
            <td>
              <?
                db_input('dv01_codoperacaocredito', 10, $Idv01_codoperacaocredito, true, 'text', 3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_principaldividalongoprazop ?>">
            <?php echo db_ancora('<b>Principal da Dívida Longo Prazo P</b> &nbsp : ', "js_pesquisaContaPCASP(true, 'principaldividalongoprazop')", $db_opcao) ?>  
            </td> 
            <td>
              <?
                db_input('dv01_principaldividalongoprazop', 8, $Idv01_principaldividalongoprazop, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'principaldividalongoprazop\')"; onblur="js_pesquisaContaPCASP(false, \'principaldividalongoprazop\')"');
                db_input("dv01_texto_principaldividalongoprazop",40, "" , true,"text",3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_principaldividacurtoprazop ?>">
            <?php echo db_ancora('<b>Principal da Dívida Curto Prazo P</b> :', "js_pesquisaContaPCASP(true, 'principaldividacurtoprazop')", $db_opcao)?>  
            </td> 
            <td>
              <?
                db_input('dv01_principaldividacurtoprazop', 8, $Idv01_principaldividacurtoprazop, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'principaldividacurtoprazop\')"; onblur="js_pesquisaContaPCASP(false, \'principaldividacurtoprazop\')"');
                db_input("dv01_texto_principaldividacurtoprazop",40,"", true,"text",3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_principaldividaf ?>">
            <?php echo db_ancora('<b>Principal da Dívida F</b> :', "js_pesquisaContaPCASP(true, 'principaldividaf')", $db_opcao) ?> 
            </td> 
            <td>
              <?
                db_input('dv01_principaldividaf', 8, $Idv01_principaldividaf, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'principaldividaf\')"; onblur="js_pesquisaContaPCASP(false, \'principaldividaf\')"'); 
                db_input("dv01_texto_principaldividaf",40,"", true,"text",3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_jurosdividalongoprazop ?>">
            <?php echo db_ancora('<b>Juros da Dívida Longo Prazo P</b> :', "js_pesquisaContaPCASP(true, 'jurosdividalongoprazop')", $db_opcao) ?> 
            </td> 
            <td>
              <?
                db_input('dv01_jurosdividalongoprazop', 8, $Idv01_jurosdividalongoprazop, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'jurosdividalongoprazop\')"; onblur="js_pesquisaContaPCASP(false, \'jurosdividalongoprazop\')"');
                db_input("dv01_texto_jurosdividalongoprazop",40,"", true,"text",3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_jurosdividacurtoprazop ?>">
            <?php echo db_ancora('<b>Juros da Dívida Curto Prazo P</b> :', "js_pesquisaContaPCASP(true, 'jurosdividacurtoprazop')", $db_opcao) ?> 
            </td> 
            <td>
              <?
                db_input('dv01_jurosdividacurtoprazop', 8, $Idv01_jurosdividacurtoprazop, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'jurosdividacurtoprazop\')"; onblur="js_pesquisaContaPCASP(false, \'jurosdividacurtoprazop\')"');
                db_input("dv01_texto_jurosdividacurtoprazop",40,"", true,"text",3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_jurosdividaf ?>">
            <?php echo db_ancora('<b>Juros da Dívida F</b> :', "js_pesquisaContaPCASP(true, 'jurosdividaf')", $db_opcao) ?> 
            </td> 
            <td>
              <?
                db_input('dv01_jurosdividaf', 8, $Idv01_jurosdividaf, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'jurosdividaf\')"; onblur="js_pesquisaContaPCASP(false, \'jurosdividaf\')"');
                db_input("dv01_texto_jurosdividaf",40,"", true,"text",3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tdv01_controlelrf ?>">
            <?php echo db_ancora('<b>Controle LRF</b> :', "js_pesquisaContaPCASP(true, 'controlelrf')", $db_opcao) ?> 
            </td> 
            <td>
              <?
                db_input('dv01_controlelrf', 8, $Idv01_controlelrf, true, 'text', 1, 'onchange="js_pesquisaContaPCASP(false, \'controlelrf\')"; onblur="js_pesquisaContaPCASP(false, \'controlelrf\')"');
                db_input("dv01_texto_controlelrf",40,"", true,"text",3, "");
              ?>
            </td>
          </tr>

        </table>
      </fieldset>
   </center>
   <br>
  <input name="salvar_pcasp" type="submit" id="salvar_pcasp" value='<?php echo ($db_opcao == 3) ? "Excluir" : "Salvar" ?>'>
</form>

<style>
  textarea {
    resize: vertical;
    max-width: 100%;
  }

  .bordas .active {
    border-width: 3px 1px 0px 3px;
    border-style: outset inset outset outset;
    border-color: rgb(60, 60, 60) rgb(0, 0, 0) rgb(153, 153, 153);
    border-image: none;
    font-weight: bold;
    color: black;
    cursor: pointer;
  }

  .bordas {
    border-width: 1px 1px 1px 1px;
    border-style: outset inset outset outset;
    border-color: rgb(60, 60, 60) rgb(0, 0, 0) rgb(153, 153, 153);
    border-image: none;
    font-weight: normal;
    color: black;
    cursor: pointer;
  }
</style>

<script>

  var campos = [
    'dv01_principaldividalongoprazop',
    'dv01_principaldividacurtoprazop',
    'dv01_principaldividaf',
    'dv01_jurosdividalongoprazop',
    'dv01_jurosdividacurtoprazop',
    'dv01_jurosdividaf', 
    'dv01_controlelrf'
  ];

  var aOptionsDetalhamentoPadrao = {
    0:  'Selecione',
    1:  'Financiamento',
    2:  'Aquisição Financeira de Bens',
    3:  'Arrendamento Mercantil',
    4:  'Tributos Federais',
    5:  'Tributos Estaduais',
    6:  'Tributos Municipais',
    7:  'Pessoal - Regime Especial',
    8:  'Pessoal - Regime Ordinário',
    9:  'Benefícios Previdenciários - Regime Especial',
    10: 'Benefícios Previdenciários - Regime Ordinário',
    11: 'Benefícios Assistenciais - Regime Especial',
    12: 'Benefícios Assistenciais - Regime Ordinário',
    13: 'Fornecedores - Regime Especial',
    14: 'Fornecedores - Regime Ordinário',
    15: 'Dívida de Consórcio Público',
    16: 'Outras Dívidas'
  };

  var aOptionsDetalhamentoModeloA = {
    0: 'Selecione', 
    1: 'Financiamento', 
    2: 'Aquisição Financeira de Bens', 
    3: 'Arrendamento Mercantil'
  };

  var aOptionsDetalhamentoModeloB = {
    0: 'Selecione',
    4: 'Tributos Federais',
    5: 'Tributos Estaduais',
    6: 'Tributos Municipais'
  };

  var aOptionsDetalhamentoModeloC = {
    0:  'Selecione',
    7:  'Pessoal - Regime Especial',
    8:  'Pessoal - Regime Ordinário', 
    9:  'Benefícios Previdenciários - Regime Especial', 
    10: 'Benefícios Previdenciários - Regime Ordinário', 
    11: 'Benefícios Assistenciais - Regime Especial', 
    12: 'Benefícios Assistenciais - Regime Ordinário', 
    13: 'Fornecedores - Regime Especial', 
    14: 'Fornecedores - Regime Ordinário'
  };

  var aOptionsDetalhamentoModeloD = {
    0: 'Selecione',
    15: 'Dívida de Consórcio Público',
    16: 'Outras Dívidas'
  };

  document.addEventListener('DOMContentLoaded', function() {

    var detalhamentoSelecionado = document.getElementById('op01_detalhamentodivida').value;

    carregaOptions();
    atualizaCredoreInstituicao();
    exibeOcultaSubtipoDetalhamento();
    mantemDetalhamentoSelecionado(detalhamentoSelecionado);
    vinculaSequencialPcasp();
    enabledDisabledPesquisar();
    setWidthCredor();

    document.getElementById('op01_valorautorizadoporlei').addEventListener('input', function(e) {
        var value = e.target.value;
        formatFloatValue(e);
    });

    document.getElementById('op01_valorcontratacao').addEventListener('input', function(e) {
        var value = e.target.value;
        formatFloatValue(e);
    });

    document.getElementById('op01_tipolancamento').addEventListener('change', function(e) {
      carregaOptions();
    });

    document.getElementById('op01_numerocontratoopc').addEventListener('input', function(e) {
      document.getElementById('op01_descricaocontapcasp').value = getDescricaoContaPCASP();
    });

    document.getElementById('op01_descricaodividaconsolidada').addEventListener('input', function(e) {
      document.getElementById('op01_descricaocontapcasp').value = getDescricaoContaPCASP();
    });

    document.getElementById('op01_objetocontrato').addEventListener('input', function(e) {
      limitaTextArea('#op01_objetocontrato', 1000);
    });

    document.getElementById('op01_descricaodividaconsolidada').addEventListener('input', function(e) {
      limitaTextArea('#op01_descricaodividaconsolidada', 500);
    });

    document.getElementById('op01_taxajurosdemaisencargos').addEventListener('input', function(e) {
      limitaTextArea('#op01_taxajurosdemaisencargos', 1000);
    });

    removeFocus('#frm_divida_consolidada');
  });

  document.getElementById('db_opcao').addEventListener('click', function(e) {
    deleteCookie('save_op01_sequencial');
  })

  function setWidthCredor() {
    
    var cell = document.getElementById('td_credor');
    var inputFixed = document.getElementById('op01_credor');

    var inputFixedWidth = inputFixed.offsetWidth;
    var cellWidth = cell.offsetWidth;

    var percentCredor = (inputFixedWidth / cellWidth) * 100;
    
    var percentRestante = 100 - percentCredor;

    document.getElementById('z01_nome2').style.width = percentRestante - 0.6 + '%'
  }

  function enabledDisabledPesquisar() {
    var dbOpcao = <?php echo $db_opcao; ?>;
    if(dbOpcao == 1) {
      document.getElementById('pesquisar').style.visibility  = 'hidden';
    } else {
      document.getElementById('pesquisar').style.visibility = 'visible';
    }
  }

  function preencheSequencialIncluir() {

    var sequencial = getCookie('save_op01_sequencial');

    var dbOpcao = <?php echo $db_opcao; ?>;

    if(dbOpcao == 1 || dbOpcao == '1') {
      setTimeout(function() {
        document.getElementById('dv01_codoperacaocredito').value = sequencial;
        document.getElementById('op01_sequencial').value = sequencial;

        if(sequencial == "" || sequencial == null) {
          document.getElementById('novo').style.display = "none";
          document.getElementById('db_opcao').disabled = false;
        } else {
          document.getElementById('db_opcao').disabled = true;
        }
      }, 1500);

    } else {
      document.getElementById('novo').style.display = "none";
    }

    setWidthCredor();
  }

  function getCookie(name) {
    var cookie = document.cookie
        .split("; ")
        .find(row => row.startsWith(name + "="));
        
    return cookie ? decodeURIComponent(cookie.split('=')[1]) : null;
  }

  function deleteCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
  }

  function js_novoRegistro() {

    deleteCookie('save_op01_sequencial');

    var fullUrl  = window.location.href;
    var splitUrl = fullUrl.split('/w');
    var urlBase  = splitUrl[0];
    var number   = splitUrl[1].substring(1, 2);

    var redirect =  urlBase +'/w/' + number + '/con1_db_operacaodecredito001.php';
    var dbOpcao = <?php echo $db_opcao; ?>;

    if(dbOpcao == 2 || dbOpcao == 22) {
      location.href = redirect;
    } else {
      location.href = '';
    }
  }

  function vinculaSequencialPcasp() {
    var valorSequencial = document.getElementById('op01_sequencial').value;
    if(valorSequencial != "") {
      document.getElementById('dv01_codoperacaocredito').value = valorSequencial;
    }
  }

  function mantemDetalhamentoSelecionado(value) {
    document.getElementById('op01_detalhamentodivida').value = value;
  }

  function carregaOptions() {

    var tipoLancamento = document.getElementById('op01_tipolancamento');

    if(tipoLancamento.value == 3) {
      setOptionsDetalhamento(aOptionsDetalhamentoModeloA)
    } else if (tipoLancamento.value == 9) {
      setOptionsDetalhamento(aOptionsDetalhamentoModeloB)
    } else if (tipoLancamento.value == 17 || tipoLancamento.value == 18 || tipoLancamento.value == 23) {
      setOptionsDetalhamento(aOptionsDetalhamentoModeloC)
    } else if (tipoLancamento.value == 20) {
      setOptionsDetalhamento(aOptionsDetalhamentoModeloD)
    } else {
      setOptionsDetalhamento(aOptionsDetalhamentoPadrao)
    }

    exibeOcultaSubtipoDetalhamento(tipoLancamento);

    return;
  }

  function exibeOcultaSubtipoDetalhamento(tipoLancamento) {

    var tipoLancamento = document.getElementById('op01_tipolancamento');
    var tiposLancamentosHabilitaSubtipos     = [1,3,4,6,7,21];
    var tiposLancamentosHabilitaDetalhamento = [3,9,17,18,20,23];

    var trSubtipoLancamento  = document.getElementById('tr_subtipolancamento');
    var trDetalhamentoDivida = document.getElementById('tr_detalhamentodivida');

    if(tiposLancamentosHabilitaSubtipos.includes(parseInt(tipoLancamento.value))) {
      trSubtipoLancamento.style.visibility = 'visible';
    } else {
      trSubtipoLancamento.style.visibility  = 'hidden';
    } 

    if(tiposLancamentosHabilitaDetalhamento.includes(parseInt(tipoLancamento.value))) {
      trDetalhamentoDivida.style.visibility = 'visible';
    } else {
      trDetalhamentoDivida.style.visibility = 'hidden';
    }

  }

  function js_pesquisa() {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?funcao_js=parent.js_preenchepesquisa|op01_sequencial', 'Pesquisa', true);
  }

  function atualizaCredoreInstituicao() {
      var credorInput = document.getElementById('op01_credor');
      var event = new Event('change');
      credorInput.dispatchEvent(event);
  }

  function js_preenchepesquisa(chave) {
    db_iframe_db_operacaodecredito.hide();
    <? 
    if ($db_opcao != 1) {
      echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
    }
    ?>
  }

  function removeFocus(formSelector) {

    const form = document.querySelector(formSelector);
    const formElements = form.querySelectorAll('input, textarea, button');

    formElements.forEach(element => {
      element.blur();
    });
  }

  function getDescricaoContaPCASP() {

    var descricaoContaPcasp = document.getElementById('op01_descricaocontapcasp');
    descricaoContaPcasp = descricaoContaPcasp.text;

    var texto = document.getElementById('op01_numerocontratoopc').value + ' - ' + document.getElementById('op01_descricaodividaconsolidada').value

    if(texto.length > 50) {
      texto = texto.substring(0, 50);
    }

    return texto;
  }

  function formatFloatValue(e) {

    var value = e.target.value;

    value = value.replace(/,/g, '.');
    value = value.replace(/[^\d.]/g, '');

    var parts = value.split('.');

    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }

    if (parts[1] && parts[1].length > 2) {
        parts[1] = parts[1].slice(0, 2);
        value = parts.join('.');
    }

    e.target.value = value;

    if (!e.target.hasEventListener) {
        e.target.hasEventListener = true;
        e.target.addEventListener('blur', function() {
            let finalValue = parseFloat(e.target.value);
            if (!isNaN(finalValue)) {
                e.target.value = finalValue.toFixed(2);
            }
        });
    }
  }

  function setOptionsDetalhamento(arrayOptions) {

    var detalhamentoElement = document.getElementById('op01_detalhamentodivida');
    detalhamentoElement.innerHTML = '';
  
    for (const id in arrayOptions) {
        if (arrayOptions.hasOwnProperty(id)) {

            const option = document.createElement('option');
            option.value = id; 

            let optionText = arrayOptions[id];

            option.text = optionText;
            detalhamentoElement.appendChild(option);
        }
    }
  }

  function js_pesquisa_cgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_forne','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
    }else{
      if($('op01_credor').value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_forne','func_nome.php?pesquisa_chave='+$('op01_credor').value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
      }else{
        $('z01_nome2').value = '';
      }
    }
  }

  function js_mostracgm(erro,chave){
    $('z01_nome2').value = chave;
    if(erro==true){
      $('op01_credor').value = '';
      $('op01_credor').focus();
    }
  }

  function js_mostracgm1(chave1,chave2){
    $('op01_credor').value = chave1;
    $('z01_nome2').value = chave2;
    db_iframe_forne.hide();
  }

  function limitaTextArea(seletor, maxlength) {
    const textarea = document.querySelector(seletor);

    textarea.addEventListener('input', () => {
      if (textarea.value.length > maxlength) {
        textarea.value = textarea.value.substring(0, maxlength);
      }
    });
  }

  function js_completaInstituicao(sNomeInstit, lErro) {
    if (!lErro) {
      $('sDescricaoInstituicao').value = sNomeInstit;
    } else {
      $('op01_instituicao').value    = '';
      $('sDescricaoInstituicao').value = sNomeInstit;
    }
  }

  function js_OpenJanelaIframe(aondeJanela,nomeJanela,arquivoJanela,tituloJanela,mostraJanela,topoJanela,leftJanela,widthJanela,heigthJanela){

      if(mostraJanela==undefined)
        mostraJanela = true;
      if(topoJanela==undefined)
        topoJanela = '20';
      if(leftJanela==undefined)
        leftJanela = '1';
      if(widthJanela==undefined)
        widthJanela =  screen.availWidth-60;
      if(heigthJanela==undefined)
        heigthJanela = screen.availHeight-150;

      if(eval((aondeJanela!=""?aondeJanela+".":"document.")+nomeJanela)){

        var executa = (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".jan.location.href = '"+arquivoJanela+"'";
        executa = eval(executa);

      } else {

        var executa = (aondeJanela!=""?aondeJanela+".":"")+"criaJanela('"+nomeJanela+"','"+arquivoJanela+"','"+tituloJanela+"',"+mostraJanela+","+topoJanela+","+leftJanela+","+widthJanela+","+heigthJanela+")";
        executa = eval(executa);
      }

      if(mostraJanela==true){

        var executa = (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".mostraMsg(0,'white',"+widthJanela+","+heigthJanela+",0,0);";
        executa += (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".show();";
        executa += (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".focus();";

        executa = eval(executa);
      }
  }

  function actionAbas(field) {

    var dbOption = document.getElementById('desabilita_entrada_pcasp').value;
      
    campos.forEach(function(campo, index) {
      if(dbOption == 3) {
        var input = document.getElementById(campo);
        input.style.backgroundColor = '#DEB887';
        input.readOnly = true;
      } else {
        var input = document.getElementById(campo);
        input.style.backgroundColor = '#FFFFFF';
        input.readOnly = false;
      }
    });

    var tabDivida = document.getElementById('tab_divida_consolidada');
    var tabPcasp = document.getElementById('tab_pcasp');

    var formDivida = document.getElementById('frm_divida_consolidada');
    var formPcasp  = document.getElementById('frm_pcasp');

    if(field == "pcasp") {

      tabDivida.classList.remove('active');
      tabPcasp.classList.add('active');

      formDivida.style.display = 'none';
      formPcasp.style.display  = 'block';

    } else {

      tabPcasp.classList.remove('active');
      tabDivida.classList.add('active');

      formPcasp.style.display  = 'none';
      formDivida.style.display = 'block';
    }
  }

  function js_pesquisaContaPCASP(lMostra, field) {

    if(field == 'principaldividaf' || field == 'jurosdividaf' ){
      var sIndicador = 'F';
    } else {
      var sIndicador = 'P';
    }

    if(field == "controlelrf"){ 
      var sStrutInicial = '863';
    } else if(field == "principaldividalongoprazop" || field == "jurosdividalongoprazop") {
      var sStrutInicial = '22';
    } else {
      var sStrutInicial = '21';
    }

    document.getElementById('field_search').value = field;

    if(lMostra == true) {
      var sFuncao = 'func_conplano_pesquisareduz.php?strut_iniciado_em='+sStrutInicial+'&indicador='+sIndicador+'&funcao_js=parent.js_mostraContaPcasp|c61_reduz|c60_descr|';
    } else {
      var iConta = $F('dv01_' + field.trim());
      var sFuncao = 'func_conplano_pesquisareduz.php?strut_iniciado_em='+sStrutInicial+'&pesquisa_chave='+iConta+'&indicador='+sIndicador+'&funcao_js=parent.js_completaContaPCASP-'+field;
    }

    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_contaPcasp', sFuncao,'Pesquisar', lMostra, '10');
  }

  function js_completaContaPCASP(sDescricao, lErro, sStrut, fieldParam) {

    if(sDescricao.includes("não Encontrado") || sDescricao == '') {
      document.getElementById('dv01_' + fieldParam).value = '';
    } else {
      document.getElementById('dv01_texto_' + fieldParam).value = sDescricao;
    }

    document.getElementById('dv01_texto_' + fieldParam).value = sDescricao;
  }

  function js_mostraContaPcasp(iCodigo, sDescricao) {

    var fieldParam = document.getElementById('field_search').value;
    
    if(sDescricao != '') {
      document.getElementById('dv01_' + fieldParam).value = iCodigo;
      document.getElementById('dv01_texto_' + fieldParam).value = sDescricao;
    }

    db_iframe_contaPcasp.hide();
  }

</script>