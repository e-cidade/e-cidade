<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: issqn
$clparissqn->rotulo->label();
$clTipoAlvara->rotulo->label();
$clcertbaixanumero->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("q92_descr");
$clrotulo->label("q60_tiponumcertbaixa");
$clrotulo->label("db82_descricao");
$clrotulo->label("k01_descr");

/**
 * sql para retornar a descri��o do campo:$q60_codvenc_incentivo (Vencimento com Incentivo:)
 */
if (isset($q60_codvenc_incentivo) && $q60_codvenc_incentivo != null) {

	require_once("classes/db_cadvencdesc_classe.php");
	$oDaoCadvencdesc      = new cl_cadvencdesc;
	$sSqlDescr            = $oDaoCadvencdesc->sql_query_file($q60_codvenc_incentivo, "q92_descr as descricao_incentivo", null, null);
  $rsDescr              = $oDaoCadvencdesc->sql_record($sSqlDescr);
  $descricao_incentivo  = db_utils::fieldsMemory($rsDescr, 0)->descricao_incentivo;
}

/**
 * sql para retornar a descri��o do campo:$q60_tipo_notaavulsa (Tipo D�b. Nota Avulsa:)
 */
if (isset($q60_tipo_notaavulsa) && $q60_tipo_notaavulsa != null) {

	require_once("classes/db_arretipo_classe.php");
	$oDaoArretipo       = new cl_arretipo;
	$sSqlArretipo       = $oDaoArretipo->sql_query_file($q60_tipo_notaavulsa, "k00_descr as desc_tipo_nota", null, null);
  $rsSqlArretipo      = $oDaoArretipo->sql_record($sSqlArretipo);
  $desc_tipo_nota     = db_utils::fieldsMemory($rsSqlArretipo, 0)->desc_tipo_nota;
}

?>
<style>
fieldset {
 margin:  10px auto;
 width:   700px;
}
fieldset table tr td:first-child {
  left:0;
  width: 250px;
}
fieldset fieldset table tr td:first-child {
  left:0;
  width: 235px;
}


#fieldSetImpressaoAlvara select {
 width: 250px;
}

#q60_campoutilcalc,
#q60_integrasani,
#q60_tipopermalvara,
#q60_tiponumcertbaixa,
#q60_bloqemiscertbaixa{
 width: 368px;
}
</style>
<form name="form1" id="form1" method="post" action="">
<fieldset>
  <legend><strong>Geral</strong></legend>

  <fieldset>
    <legend><strong>Par�metros do C�lculo</strong></legend>

    <table>
      <tr id="receita">
        <td>

          <input name="oid" type="hidden" value="<?=@$oid?>">

		 	    <?
			      db_ancora(@$Lq60_receit,"js_pesquisaq60_receit(true);",$db_opcao);
			    ?>

        </td>

        <td>
          <?php
            db_input('q60_receit', 10, $Iq60_receit, true, 'text', $db_opcao, " onchange='js_pesquisaq60_receit(false);'");

            db_input('k02_descr', 40, $Ik02_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>

      <tr id="tipodebito">
        <td>
          <?
            db_ancora(@$Lq60_tipo,"js_pesquisaq60_tipo(true);",$db_opcao);
          ?>
        </td>
        <td>
        	<?
          	db_input('q60_tipo', 10, $Iq60_tipo, true, 'text', $db_opcao, " onchange='js_pesquisaq60_tipo(false);'");
            db_input('k00_descr', 40, $Ik00_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>

      <tr id="aliquota">
        <td>
          <?=@$Lq60_aliq?>
        </td>
        <td>
          <?
      	    db_input('q60_aliq', 10, $Iq60_aliq, true, 'text', $db_opcao)
          ?>
        </td>
      </tr>

      <tr id="aliquotasreduzidas">
        <td>
          <?=@$Lq60_aliq_reduzida?>
        </td>
        <td>
          <?
            $aAliquotasReduzidas = array('0'=>'Desativado','1'=>'Ativado');
            db_select('q60_aliq_reduzida',$aAliquotasReduzidas, true, $db_opcao);
          ?>
        </td>
      </tr>

      <tr id="vencimento">
        <td>
          <?
            db_ancora(@$Lq60_codvencvar,"js_pesquisaq60_codvencvar(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?
            db_input('q60_codvencvar',10,$Iq60_codvencvar,true,'text',$db_opcao," onchange='js_pesquisaq60_codvencvar(false);'");
            db_input('q92_descr',40,$Iq92_descr,true,'text',3,'');
          ?>
        </td>
      </tr>

		<tr id="vencimento_incentivo">
			<td>
				<?
				db_ancora(@$Lq60_codvenc_incentivo,"js_pesquisaq60_codvenc_incentivo(true);",$db_opcao);
				?>
			</td>
			<td>
				<?
				db_input('q60_codvenc_incentivo',10,$Iq60_codvenc_incentivo,true,'text',$db_opcao," onchange='js_pesquisaq60_codvenc_incentivo(false);'");
				db_input('descricao_incentivo',40,$Iq92_descr,true,'text',3,'');
				?>
			</td>
		</tr>

      <tr id="historicocalculo">
        <td>
          <?php
            db_ancora($Lq60_histsemmov, "js_pesquisaHistoricoCalculo(true)", $db_opcao);
          ?>
        </td>
        <td>
          <?
            db_input('q60_histsemmov', 10, $Iq60_histsemmov, true, 'text', $db_opcao, "");
            db_input('k01_descr',      40, $Ik01_descr,      true, 'text', 3        , "");
          ?>
        </td>
      </tr>

      <tr id="parcelamento">
        <td>
          <?=@$Lq60_parcelasalvara?>
        </td>
        <td>
          <?php
            db_input('q60_parcelasalvara', 10, $Iq60_parcelasalvara, true, 'text', $db_opcao, "onchange='js_validaParcelas(this.value)'");
          ?>
        </td>
      </tr>

			<tr id="variavelcalculo">
        <td>
          <?=@$Lq60_campoutilcalc?>
        </td>
        <td>
          <?
     				$aCampoUtilCalc = array('1' => 'Area', '2' => 'Quantidade de funcionarios', '3' => 'Pontua��o');
            db_select('q60_campoutilcalc',$aCampoUtilCalc, true, $db_opcao);
          ?>
        </td>
      </tr>

    </table>

  </fieldset> <!-- Par�metros c�lculo -->

  <fieldset>
    <legend><strong>Par�metros do Alvar�</strong></legend>

    <table>
      <tr id="integracaosanitario">
        <td>
          <?=@$Lq60_integrasani?>
        </td>
        <td>
  	      <?
    	      $aIntegracaoSanitario = array('0'=>'Nenhuma','1'=>'Inscri��es com porte para pessoa juridica','2'=>'Integra��o por classe');
    	      db_select('q60_integrasani', $aIntegracaoSanitario, true, $db_opcao);
          ?>
        </td>
      </tr>

      <tr id="permitealteracao">
        <td nowrap>
          <?=@$Lq60_tipopermalvara?>
        </td>
        <td>
          <?
          	$aPermissao = array('0'=>'N�o utiliza controle de permiss�o', '1'=>'Liberado com permiss�o para alterar alvar� com cnpj.');
          	db_select('q60_tipopermalvara', $aPermissao, true, $db_opcao);
          ?>
        </td>
      </tr>

			<tr id="alvarapermanente">
				<td>
				  <input name="oid" type="hidden" value="<?=@$oid?>">
  				  <?
  					  db_ancora(@$Lq60_isstipoalvaraper,"js_pesquisaq60_isstipoalvaraper(true);",$db_opcao);
  					?>
				</td>
				<td>
  				<?
    				db_input('q60_isstipoalvaraper',10,$Iq60_isstipoalvaraper,true,'text',$db_opcao," onchange='js_pesquisaq60_isstipoalvaraper(false);'");
    				db_input('q98_descricaoper',40,$Iq98_descricao,true,'text',3,'');
  				?>
				</td>
			</tr>

			<tr id="alvaraprovisorio">
				<td>
				<input name="oid" type="hidden" value="<?=@$oid?>">
				  <?
					  db_ancora(@$Lq60_isstipoalvaraprov,"js_pesquisaq60_isstipoalvaraprov(true);",$db_opcao);
					?>
				</td>
				<td nowrap>
				  <?
            db_input('q60_isstipoalvaraprov',10,$Iq60_isstipoalvaraprov,true,'text',$db_opcao," onchange='js_pesquisaq60_isstipoalvaraprov(false);'");
            db_input('q98_descricaoprov',40,$Iq98_descricao,true,'text',3,'');
          ?>
				</td>
			</tr>

			<tr id="permitebaixaalvaradivida">
				<td>
				  <?=@$Lq60_alvbaixadiv?>
				</td>
				<td>
				  <?
				    $aAlvaraDivida = array('0'=>'N�o','1'=>'Sim');
				    db_select('q60_alvbaixadiv',$aAlvaraDivida, true, $db_opcao);
				  ?>
				</td>
			</tr>

			<tr>
			  <td colspan="2">

			    <fieldset style="margin: 0 auto; width: 600px" id="fieldSetImpressaoAlvara">
			      <legend><strong>Par�metros de Impress�o do Alvar�</strong></legend>

			      <table>
			        <tr id="imprimecodigoatividade">
					      <td>
					        <?=@$Lq60_impcodativ?>
					      </td>
					      <td>
								  <?
									  $aImprimeCodigoAtividade = array('f'=>'N�O','t'=>'SIM');
									  db_select('q60_impcodativ',$aImprimeCodigoAtividade, true, $db_opcao);
								  ?>
					      </td>
					    </tr>

  					  <tr id="imprimeobservacaoatividade">
  					    <td nowrap>
  					      <?=@$Lq60_impobsativ?>
  					    </td>
  					    <td>
  								<?
  									$aImprimeObservacoesAtividade = array('f'=>'N�O','t'=>'SIM');
  									db_select('q60_impobsativ',$aImprimeObservacoesAtividade, true, $db_opcao);
  								?>
  					    </td>
  					  </tr>

							<tr id="imprimedatas">
								<td>
								  <?=@$Lq60_impdatas?>
								</td>
								<td>
								  <?
								    $aImprimeDatas = array('f'=>'N�O','t'=>'SIM');
								    db_select('q60_impdatas', $aImprimeDatas, true, $db_opcao);
								  ?>
								</td>
							</tr>

							<tr id="imprimeobservacoesissqn">
								<td nowrap>
								  <?=@$Lq60_impobsissqn?>
								</td>
								<td>
								  <?
    								$aImprimeObservacoesIssqn = array('f'=>'N�O','t'=>'SIM');
    								db_select('q60_impobsissqn',$aImprimeObservacoesIssqn, true, $db_opcao);
  								?>
								</td>
							</tr>

							<tr id="modeloalvara">
								<td nowrap>
								  <?=@$Lq60_modalvara?>
								</td>
								<td>
								  <?
    								$aModeloAlvara = array('1'=>'A5',
                      								     '2'=>'A4',
                      								     '3'=>'Pr�-impresso',
                      								     '4'=>'A4 fonte reduzida',
                      								     '5'=>'Pr�-impresso tamanho A4',
                      								     '6'=> 'Pr�-impresso A4 com c�digo cnae',
                      								     '7'=> 'A4 Frente/Verso',
                      								     '8'=> 'A4 Processo/�rea',
                      								     '9'=> 'Documento Alvar�',
                                                         '99' => 'A4 com numera��o'
                                        );

    								db_select('q60_modalvara', $aModeloAlvara, true, $db_opcao, "onchange='js_trtemplatealvara();'");
  								?>
								</td>
							</tr>

							<tr style="display: none" id="lab_templatealvara">
								<td>
								  <?
								    $q60_templatealvara =1;
								    db_ancora(@$Lq60_templatealvara,"js_pesquisaq60_templatealvara(true);",$db_opcao);
								  ?>
								</td>
								<td>
								  <?
								    db_input('q60_templatealvara',10,$Iq60_templatealvara,true,'text',$db_opcao," onchange='js_pesquisaq60_templatealvara(false);'");
								    db_input('db82_descricao',40,$Idb82_descricao,true,'text',3,'');
								  ?>
								</td>
							</tr>

						</table>

			    </fieldset>
			  </td>
			</tr>
		</table>
  </fieldset><!-- Par�metros Alvar� -->

  <fieldset>
    <legend><strong>Nota Fiscal Avulsa Eletr�nica</strong></legend>

    <table>
			<tr id="notaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsapesjur?>
				</td>
				<td>
				  <?
				    $aNotaFiscalAvulsaPerjur = array("f"=>"N�O","t"=>"SIM");
				    db_select('q60_notaavulsapesjur', $aNotaFiscalAvulsaPerjur, true, $db_opcao);
				  ?>
				</td>
			</tr>

			<tr id="numeroviasnotaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsavias?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsavias', 10, $Iq60_notaavulsavias, true, 'text', $db_opcao);
				  ?>
				</td>
			</tr>

			<tr id="notaavulsavalorminimo">
				<td nowrap>
				  <?=@$Lq60_notaavulsavlrmin?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsavlrmin',10,$Iq60_notaavulsavlrmin,true,'text',$db_opcao,"");
				  ?>
				</td>
			</tr>

			<tr id="numeromaximonotasavulsas">
				<td>
				  <?=@$Lq60_notaavulsamax?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsamax',10,$Iq60_notaavulsamax,true,'text',$db_opcao,"")
				  ?>
				</td>
			</tr>

			<tr id="numeroultimanotaavulsa">
				<td>
				  <?=@$Lq60_notaavulsaultimanota?>
				</td>
				<td>
				  <?
    				if (!isset($q60_notaavulsaultimanota) || trim($q60_notaavulsaultimanota)=="") {
    				  $q60_notaavulsaultimanota = 1;
    				}
    				db_input('q60_notaavulsaultimanota',10,$Iq60_notaavulsaultimanota,true,'text',3,"");
          ?>
				</td>
			</tr>

			<tr id="diasprazonotaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsadiasprazo?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsadiasprazo',10,$Iq60_notaavulsadiasprazo,true,'text',$db_opcao,"");
				  ?>
				</td>
			</tr>
			<tr id="diasprazonotaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsalinkautenticacao?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsalinkautenticacao',50,$Iq60_notaavulsalinkautenticacao,true,'text',$db_opcao,"");
				  ?>
				</td>
			</tr>
		<tr id="tipodebitonotaavulsa">
			<td>
				<?
				db_ancora(@$Lq60_tipo_notaavulsa,"js_pesquisaq60_tipo_notaavulsa(true);",$db_opcao);
				?>
			</td>
			<td>
				<?
				db_input('q60_tipo_notaavulsa', 10, $Iq60_tipo_notaavulsa, true, 'text', $db_opcao, " onchange='js_pesquisaq60_tipo_notaavulsa(false);'");
				db_input('desc_tipo_nota', 40, $Ik00_descr, true, 'text', 3, '');
				?>
			</td>
		</tr>
		</table>
  </fieldset>

  <fieldset>
     <legend><strong>Certid�o de Baixa</strong></legend>
     <table>
      <tr id="tiponumeracaocertidao">
        <td nowrap >
          <?=@$Lq60_tiponumcertbaixa?>
        </td>
        <td>
          <?
            $aNumeracaoCertidaoBaixa = array('1'=>'Utiliza n�mero do processo','2'=>'Sequencial','3'=>'Sequencial por exerc�cio');
            db_select('q60_tiponumcertbaixa', $aNumeracaoCertidaoBaixa, true, $db_opcao, " onchange='js_habilitaTemplateCertidao();'");
          ?>
        </td>
      </tr>

      <tr id="ultcodcertbaixa" style="display:none;">

        <td nowrap>
           <?=@$Lq79_ultcodcertbaixa?>
        </td>

        <td>
          <?
            db_input('q79_ultcodcertbaixa',10,$Iq79_ultcodcertbaixa,true,'text',$db_opcao)
          ?>
        </td>
      </tr>

      <tr id="bloqueiaemissaocertidao">
        <td nowrap>
          <?=@$Lq60_bloqemiscertbaixa?>
        </td>
        <td>
          <?
            $aBloqueioEmissaoCertidao = array(1 => 'Nunca bloqueia',
                                              2 => 'Avisa que tem d�bito e n�o bloqueia',
                                              3 => 'Avisa que tem debito e bloqueia');
            db_select('q60_bloqemiscertbaixa', $aBloqueioEmissaoCertidao, true, $db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap="nowrap">
          <?
            db_ancora("<strong>Template Baixa Normal:</strong>","js_pesquisaCertidaoNormal(true);",$db_opcao);
          ?>
        </td>
        <td nowrap="nowrap">
          <?
            db_input('q60_templatebaixaalvaranormal',10,@$Iq60_templatebaixaalvaranormal,true,'text',$db_opcao,'onchange="js_pesquisaCertidaoNormal(false);"');
            db_input('db82_descricaocertidaonormal',40,$Idb82_descricao,true,'text',3,'','db82_descricaocertidaonormal');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap="nowrap">
          <?
            db_ancora("<strong>Template Baixa Oficial:</strong>","js_pesquisaCertidaoOficial(true);",$db_opcao);
          ?>
        </td>
        <td nowrap="nowrap">
          <?
            db_input('q60_templatebaixaalvaraoficial',10,@$Iq60_templatebaixaalvaraoficial,true,'text',$db_opcao,'onchange="js_pesquisaCertidaoOficial(false);"');
            db_input('db82_descricaocertidaooficial',40,$Idb82_descricao,true,'text',3,'','db82_descricaocertidaooficial');
          ?>
        </td>
      </tr>

     </table>
  </fieldset>
  <fieldset>
    <legend><strong>Outros Dados</strong></legend>

    <table>

			<tr id="dataimplantacaomei">
				<td nowrap>
				  <?=@$Lq60_dataimpmei?>
				</td>
				<td>
				  <?
				    db_inputdata('q60_dataimpmei',@$q60_dataimpmei_dia,@$q60_dataimpmei_mes,@$q60_dataimpmei_ano,true,'text',$db_opcao,'');
				  ?>
				</td>
			</tr>

		</table>
  </fieldset>

 </fieldset> <!-- Parametros Gerais -->

  <center>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>

<script type="text/javascript">
var MENSAGENS = "tributario.issqn.db_frmparissqn.";

(function(){

 js_habilitaTemplateCertidao();
})();

function js_habilitaTemplateCertidao() {

  if($F('q60_tiponumcertbaixa') == 2 || $F('q60_tiponumcertbaixa') == 3){
    $('ultcodcertbaixa').style.display       = '';
  }else{
    $('ultcodcertbaixa').style.display     = 'none';
  }
}

function js_validaParcelas(iParcelas) {

  if (iParcelas == '' || iParcelas == '0') {
    alert( _M( MENSAGENS + 'numero_parcelas_invalido' ) );
    return false;
  }
}
function js_pesquisaq60_isstipoalvaraper(mostra){

  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_grupotipo',
                        'func_isstipoalvara.php?cadastro=t&tipo=1&funcao_js=parent.js_mostratipoper|q98_sequencial|q98_descricao',
                        'Pesquisa',
                        true
                       );
  }else{
     if(document.form1.q60_isstipoalvaraper.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_grupotipo',
                            'func_isstipoalvara.php?cadastro=t&tipo=1&pesquisa_chave='+document.form1.q60_isstipoalvaraper.value+'&funcao_js=parent.js_mostratipoper1',
                            'Pesquisa',
                            false
                           );
     }else{
       document.form1.q98_descricaoper.value = '';
     }
  }
}

function js_mostratipoper(chave,chave2, erro) {

  document.form1.q60_isstipoalvaraper.value = chave;
  document.form1.q98_descricaoper.value          = chave2;
  if (erro==true) {

    document.form1.q60_isstipoalvaraper.focus();
    document.form1.q60_isstipoalvaraper.value = '';
  }
  db_iframe_grupotipo.hide();
}

function js_mostratipoper1(chave1,chave2) {

  document.form1.q98_descricaoper.value     = chave1;
  db_iframe_grupotipo.hide();
}

function js_pesquisaq60_isstipoalvaraprov(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_grupotipo',
                        'func_isstipoalvara.php?cadastro=t&tipo=2&funcao_js=parent.js_mostratipoprov|q98_sequencial|q98_descricao',
                        'Pesquisa',
                        true
                       );
  }else{
     if(document.form1.q60_isstipoalvaraprov.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_grupotipo',
                            'func_isstipoalvara.php?cadastro=t&tipo=2&pesquisa_chave='+document.form1.q60_isstipoalvaraprov.value+'&funcao_js=parent.js_mostratipoprov1',
                            'Pesquisa',
                            false
                           );
     }else{
       document.form1.q98_descricaoprov.value = '';
     }
  }
}

function js_mostratipoprov(chave,chave2, erro) {

  document.form1.q60_isstipoalvaraprov.value = chave;
  document.form1.q98_descricaoprov.value = chave2;
  if(erro==true){
    document.form1.q60_isstipoalvaraprov.focus();
    document.form1.q60_isstipoalvaraprov.value = '';
  }
  db_iframe_grupotipo.hide();
}

function js_mostratipoprov1(chave1,chave2) {

  if (chave2 == false) {
    document.form1.q98_descricaoprov.value     =  chave1;
  } else {

    document.form1.q98_descricaoprov.value = "Chave("+document.form1.q60_isstipoalvaraprov.value+") n�o Encontrado"
    document.form1.q60_isstipoalvaraprov.value = "";
  }
  db_iframe_grupotipo.hide();
}

function js_pesquisaq60_receit(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_receit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.q60_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = '';
     }
  }
}

function js_mostratabrec(chave,erro){

  document.form1.k02_descr.value = chave;
  if(erro==true){
    document.form1.q60_receit.focus();
    document.form1.q60_receit.value = '';
  }
}

function js_mostratabrec1(chave1,chave2){

  document.form1.q60_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisaq60_tipo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_tipo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.q60_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = '';
     }
  }
}

function js_pesquisaq60_tipo_notaavulsa(mostra){

	if(mostra==true){
		js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo_notaavulsa1|k00_tipo|k00_descr','Pesquisa',true);
	}else{
		if(document.form1.q60_tipo_notaavulsa.value != ''){
			js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.q60_tipo_notaavulsa.value+'&funcao_js=parent.js_mostraarretipo_notaavulsa','Pesquisa',false);
		}else{
			document.form1.desc_tipo_nota.value = '';
		}
	}
}

function js_mostraarretipo(chave,erro){

  document.form1.k00_descr.value = chave;
  if(erro==true){
    document.form1.q60_tipo.focus();
    document.form1.q60_tipo.value = '';
  }
}

function js_mostraarretipo1(chave1,chave2){

  document.form1.q60_tipo.value  = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}

function js_mostraarretipo_notaavulsa(chave,erro){

	document.form1.desc_tipo_nota.value = chave;
	if(erro==true){
		document.form1.q60_tipo_notaavulsa.focus();
		document.form1.q60_tipo_notaavulsa.value = '';
	}
}

function js_mostraarretipo_notaavulsa1(chave1,chave2){

	document.form1.q60_tipo_notaavulsa.value  = chave1;
	document.form1.desc_tipo_nota.value = chave2;
	db_iframe_arretipo.hide();
}

function js_pesquisaq60_templatealvara(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplate','func_db_documentotemplate.php?funcao_js=parent.js_mostratemplatealvara1|db82_sequencial|db82_descricao&tipo=6','Pesquisa',true);
  }else{
     if(document.form1.q60_templatealvara.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplate','func_db_documentotemplate.php?pesquisa_chave='+document.form1.q60_templatealvara.value+'&funcao_js=parent.js_mostratemplatealvara&tipo=6','Pesquisa',false);
     }else{
       document.form1.db82_descricao.value = '';
     }
  }
}

function js_mostratemplatealvara(chave,erro){

  document.form1.db82_descricao.value = chave;
  if(erro==true){
    document.form1.q60_templatealvara.focus();
    document.form1.q60_templatealvara.value = '';
  }
}

function js_mostratemplatealvara1(chave1,chave2){

  document.form1.q60_templatealvara.value = chave1;
  document.form1.db82_descricao.value     = chave2;
  db_iframe_db_documentotemplate.hide();
}

function js_trtemplatealvara(){

  if( document.form1.q60_modalvara.value != '9' ) {
    document.getElementById('lab_templatealvara').style.display = 'none';
  }
}

function js_pesquisaq60_codvencvar(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc1|q92_codigo|q92_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_codvencvar.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+document.form1.q60_codvencvar.value+'&funcao_js=parent.js_mostracadvencdesc','Pesquisa',false);
     }else{
       document.form1.q92_descr.value = '';
     }
  }
}

function js_pesquisaq60_codvenc_incentivo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc_incentivo1|q92_codigo|q92_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_codvenc_incentivo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+document.form1.q60_codvenc_incentivo.value+'&funcao_js=parent.js_mostracadvencdesc_incentivo','Pesquisa',false);
     }else{
       document.form1.descricao_incentivo.value = '';
     }
  }
}

function js_mostracadvencdesc(chave,erro){

  document.form1.q92_descr.value = chave;
  if(erro==true){
    document.form1.q60_codvencvar.focus();
    document.form1.q60_codvencvar.value = '';
  }
}

function js_mostracadvencdesc_incentivo(chave,erro){

  document.form1.descricao_incentivo.value = chave;
  if(erro==true){
    document.form1.q60_codvenc_incentivo.focus();
    document.form1.q60_codvenc_incentivo.value = '';
  }
}

function js_mostracadvencdesc1(chave1,chave2){

  document.form1.q60_codvencvar.value = chave1;
  document.form1.q92_descr.value      = chave2;
  db_iframe_cadvencdesc.hide();
}

function js_mostracadvencdesc_incentivo1(chave1,chave2){

  document.form1.q60_codvenc_incentivo.value = chave1;
  document.form1.descricao_incentivo.value   = chave2;
  db_iframe_cadvencdesc.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_parissqn','func_parissqn.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}

function js_preenchepesquisa(chave){

  db_iframe_parissqn.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

js_trtemplatealvara();
function js_pesquisaHistoricoCalculo( lMostraJanela ){

  if ( lMostraJanela ) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostraHistoricoCalculoLookUp|k01_codigo|k01_descr','Pesquisa Hist�rico de Calculo', true);
  } else {

     if ( $F('q60_histsemmov') != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave=' + $F('q60_histsemmov') + '&funcao_js=parent.js_mostraHistoricoCalculoDigitacao', 'Pesquisa', false);
     }else{
       $('k01_descr').value = '';
     }
  }
}

function js_mostraHistoricoCalculoDigitacao(chave, lErro) {

  $('k01_descr').value = chave;
  if( lErro ) {

    $('q60_histsemmov').focus();
    $('q60_histsemmov').value = '';
  }
}

function js_mostraHistoricoCalculoLookUp( iCodigoHistorico, sDescricaoHistorico) {

  $('q60_histsemmov').value = iCodigoHistorico;
  $('k01_descr').value      = sDescricaoHistorico;
  db_iframe_histcalc.hide();
}

/**
 * Pesquisa dados via lookup ou digita��o
 * @param object   oElemento Elemento HTML base para pesquisa
 * @param boolean  lMostra   Valida se mostra a lookup de pesquisa
 */
function js_pesquisaCertidaoNormal(lMostra) {

 if (lMostra) {
   sArquivoPesquisa    = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraDocumentoLookUpCertidaoBaixaNormal|db82_sequencial|db82_descricao&tipo=46';
 } else {
   sArquivoPesquisa    = 'func_db_documentotemplate.php?pesquisa_chave=' + $F('q60_templatebaixaalvaranormal') + '&funcao_js=parent.js_mostraDocumentoDigitacaoCertidaoBaixaNormal&tipo=46';
 }

  /**
   * Abre a janela
   */
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_db_documentotemplate',
                      sArquivoPesquisa,
                      'Pesquisa Documentos Template Certid�o Baixa',
                      lMostra);
}

function js_mostraDocumentoDigitacaoCertidaoBaixaNormal(sRetorno, lErro){

  $('db82_descricaocertidaonormal').value = sRetorno;

  if (lErro) {
    $('db82_descricaocertidaonormal').focus();
    $('db82_descricaocertidaonormal').value = '';
  }
}

function js_mostraDocumentoLookUpCertidaoBaixaNormal(iCodigo, sRetorno) {

    $('q60_templatebaixaalvaranormal').value = iCodigo;
    $('db82_descricaocertidaonormal').value   = sRetorno;
    db_iframe_db_documentotemplate.hide();
}

/**
 * Retorna documento template do tipo oficial
 */
function js_pesquisaCertidaoOficial(lMostra) {

 if (lMostra) {
   sArquivoPesquisa    = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraDocumentoLookUpCertidaoBaixaOficial|db82_sequencial|db82_descricao&tipo=46';
 } else {
   sArquivoPesquisa    = 'func_db_documentotemplate.php?pesquisa_chave=' + $F('q60_templatebaixaalvaraoficial') + '&funcao_js=parent.js_mostraDocumentoDigitacaoCertidaoBaixaOficial&tipo=46';
 }

  /**
   * Abre a janela
   */
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_db_documentotemplate',
                      sArquivoPesquisa,
                      'Pesquisa Documentos Template Certid�o Baixa',
                      lMostra);
}

function js_mostraDocumentoDigitacaoCertidaoBaixaOficial(sRetorno, lErro){

  $('db82_descricaocertidaooficial').value = sRetorno;

  if (lErro) {
    $('db82_descricaocertidaooficial').focus();
    $('db82_descricaocertidaooficial').value = '';
  }
}

function js_mostraDocumentoLookUpCertidaoBaixaOficial(iCodigo, sRetorno) {

    $('q60_templatebaixaalvaraoficial').value  = iCodigo;
    $('db82_descricaocertidaooficial').value   = sRetorno;
    db_iframe_db_documentotemplate.hide();
}

/**
 * Hints do formulario
 */
var aEventoShow = new Array('onMouseover','onFocus');
var aEventoHide = new Array('onMouseout' ,'onBlur');

var oDbHintReceita = new DBHint('oDbHintReceita');
    oDbHintReceita.setText('Receita padr�o para o c�lculo geral de ISSQN. ');
    oDbHintReceita.setShowEvents(aEventoShow);
    oDbHintReceita.setHideEvents(aEventoHide);
    oDbHintReceita.make($('receita'));

var oDbHintTipoDebito = new DBHint('oDbHintTipoDebito');
    oDbHintTipoDebito.setText('Podera informar qual o tipo de d�bito que ser� lan�ado o valor calculado no c�lculo geral de ISSQN. ');
    oDbHintTipoDebito.setShowEvents(aEventoShow);
    oDbHintTipoDebito.setHideEvents(aEventoHide);
    oDbHintTipoDebito.make($('tipodebito'));

var oDbHintAliquota = new DBHint('oDbHintAliquota');
    oDbHintAliquota.setText('Nesse campo ir� informar a Aliquota Padr�o para o c�lculo de ISSQN complementar.');
    oDbHintAliquota.setShowEvents(aEventoShow);
    oDbHintAliquota.setHideEvents(aEventoHide);
    oDbHintAliquota.make($('aliquota'));

var oDbHintVencimento = new DBHint('oDbHintVencimento');
    oDbHintVencimento.setText('Nesse campo ir� informar o C�digo do Vencimento que o sistema utilizar� no c�lculo de ISSQN.');
    oDbHintVencimento.setShowEvents(aEventoShow);
    oDbHintVencimento.setHideEvents(aEventoHide);
    oDbHintVencimento.make($('vencimento'));

var oDbHintHistoricoCalculo = new DBHint('oDbHintHistoricoCalculo');
    oDbHintHistoricoCalculo.setText('Nesse campo ir� informar o Hist�rico de C�lculo que ser� vinculado ao valor c�lculado de ISSQN.');
    oDbHintHistoricoCalculo.setShowEvents(aEventoShow);
    oDbHintHistoricoCalculo.setHideEvents(aEventoHide);
    oDbHintHistoricoCalculo.make($('historicocalculo'));

var oDbHintParcelamento = new DBHint('oDbHintParcelamento');
    oDbHintParcelamento.setText('Nesse campo ir� informar o n�mero m�ximo de parcelas do c�lculo de alvar�.');
    oDbHintParcelamento.setShowEvents(aEventoShow);
    oDbHintParcelamento.setHideEvents(aEventoHide);
    oDbHintParcelamento.make($('parcelamento'));

var oDbHintVariavelCalculo = new DBHint('oDbHintVariavelCalculo');
    oDbHintVariavelCalculo.setText('Poder� selecionar qual a vari�vel que ser� usada no c�lculo de Alvar� e Vistoria. Pode ser quantidade de funcion�rios , �rea ou pontua��o.');
    oDbHintVariavelCalculo.setShowEvents(aEventoShow);
    oDbHintVariavelCalculo.setHideEvents(aEventoHide);
    oDbHintVariavelCalculo.make($('variavelcalculo'));

var oDbHintIntegracaoSanitario = new DBHint('oDbHintIntegracaoSanitario');
    oDbHintIntegracaoSanitario.setText('Poder� selecionar se ao incluir uma inscri��o ser� gerado automaticamente um alvar� sanit�rio.');
    oDbHintIntegracaoSanitario.setShowEvents(aEventoShow);
    oDbHintIntegracaoSanitario.setHideEvents(aEventoHide);
    oDbHintIntegracaoSanitario.make($('integracaosanitario'));

var oDbHintPermiteAlteracao = new DBHint('oDbHintPermiteAlteracao');
    oDbHintPermiteAlteracao.setText('Poder� definir se permite alterar o CGM vinculado a inscri��o.');
    oDbHintPermiteAlteracao.setShowEvents(aEventoShow);
    oDbHintPermiteAlteracao.setHideEvents(aEventoHide);
    oDbHintPermiteAlteracao.make($('permitealteracao'));

var oDbHintAlvaraPermanente = new DBHint('oDbHintAlvaraPermanente');
    oDbHintAlvaraPermanente.setText('Define qual � o tipo de alvar� padr�o quando for inclu�do alvar� autom�tico.');
    oDbHintAlvaraPermanente.setShowEvents(aEventoShow);
    oDbHintAlvaraPermanente.setHideEvents(aEventoHide);
    oDbHintAlvaraPermanente.make($('alvarapermanente'));

var oDbHintAlvaraProvisorio = new DBHint('oDbHintAlvaraProvisorio');
    oDbHintAlvaraProvisorio.setText('Define qual � o tipo de alvar� padr�o quando for inclu�do alvar� autom�tico.');
    oDbHintAlvaraProvisorio.setShowEvents(aEventoShow);
    oDbHintAlvaraProvisorio.setHideEvents(aEventoHide);
    oDbHintAlvaraProvisorio.make($('alvaraprovisorio'));

var oDbHintPermiteBaixaAlvaraDivida = new DBHint('oDbHintPermiteBaixaAlvaraDivida');
    oDbHintPermiteBaixaAlvaraDivida.setText('Permite baixar inscri��es com d�vidas no sistema.');
    oDbHintPermiteBaixaAlvaraDivida.setShowEvents(aEventoShow);
    oDbHintPermiteBaixaAlvaraDivida.setHideEvents(aEventoHide);
    oDbHintPermiteBaixaAlvaraDivida.make($('permitebaixaalvaradivida'));

var oDbHintImprimeCodigoAtividade = new DBHint('oDbHintImprimeCodigoAtividade');
    oDbHintImprimeCodigoAtividade.setText('Exibe o campo \'C�digo das Atividades\' no Alvar�.');
    oDbHintImprimeCodigoAtividade.setShowEvents(aEventoShow);
    oDbHintImprimeCodigoAtividade.setHideEvents(aEventoHide);
    oDbHintImprimeCodigoAtividade.make($('imprimecodigoatividade'));

var oDbHintImprimeObservacoesAtividade = new DBHint('oDbHintImprimeObservacoesAtividade');
    oDbHintImprimeObservacoesAtividade.setText('Exibe o campo \'Observa��es das Atividades\' no Alvar�.');
    oDbHintImprimeObservacoesAtividade.setShowEvents(aEventoShow);
    oDbHintImprimeObservacoesAtividade.setHideEvents(aEventoHide);
    oDbHintImprimeObservacoesAtividade.make($('imprimeobservacaoatividade'));

var oDbHintImprimeDatas = new DBHint('oDbHintImprimeDatas');
    oDbHintImprimeDatas.setText('Exibe as datas de vencimento do Alvar�.');
    oDbHintImprimeDatas.setShowEvents(aEventoShow);
    oDbHintImprimeDatas.setHideEvents(aEventoHide);
    oDbHintImprimeDatas.make($('imprimedatas'));

var oDbHintImprimeObservacoesIssqn = new DBHint('oDbHintImprimeObservacoesIssqn');
    oDbHintImprimeObservacoesIssqn.setText('Exibe as datas de vencimento do Alvar�.');
    oDbHintImprimeObservacoesIssqn.setShowEvents(aEventoShow);
    oDbHintImprimeObservacoesIssqn.setHideEvents(aEventoHide);
    oDbHintImprimeObservacoesIssqn.make($('imprimeobservacoesissqn'));

var oDbHintModeloAlvara = new DBHint('oDbHintModeloAlvara');
    oDbHintModeloAlvara.setText('Nesse campo poder� ser selecionado o modelo que ser� impresso o Alvar�.');
    oDbHintModeloAlvara.setShowEvents(aEventoShow);
    oDbHintModeloAlvara.setHideEvents(aEventoHide);
    oDbHintModeloAlvara.make($('modeloalvara'));

var oDbHintModeloTemplateAlvara = new DBHint('oDbHintModeloTemplateAlvara');
    oDbHintModeloTemplateAlvara.setText('Nesse campo poder� ser selecionado o template que ser� impresso o Alvar�.');
    oDbHintModeloTemplateAlvara.setShowEvents(aEventoShow);
    oDbHintModeloTemplateAlvara.setHideEvents(aEventoHide);
    oDbHintModeloTemplateAlvara.make($('lab_templatealvara'));

var oDbHintNotaAvulsa = new DBHint('oDbHintNotaAvulsa');
    oDbHintNotaAvulsa.setText('Permite emitir  Nota Avulsa mesmo que o cadastro seja de pessoa jur�dica.');
    oDbHintNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintNotaAvulsa.make($('notaavulsa'));

var oDbHintNumeroViasNotaAvulsa = new DBHint('oDbHintNumeroViasNotaAvulsa');
    oDbHintNumeroViasNotaAvulsa.setText('Informar quantidade de vias que ser�o emitidas da Nota Avulsa.');
    oDbHintNumeroViasNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintNumeroViasNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintNumeroViasNotaAvulsa.make($('numeroviasnotaavulsa'));

var oDbHintNotaAvulsaValorMinimo = new DBHint('oDbHintNotaAvulsaValorMinimo');
    oDbHintNotaAvulsaValorMinimo.setText('Valor m�nimo da nota avulsa, caso n�o tenha, basta informar zero.');
    oDbHintNotaAvulsaValorMinimo.setShowEvents(aEventoShow);
    oDbHintNotaAvulsaValorMinimo.setHideEvents(aEventoHide);
    oDbHintNotaAvulsaValorMinimo.make($('notaavulsavalorminimo'));

var oDbHintNumeroMaximoNotasAvulsas = new DBHint('oDbHintNumeroMaximoNotasAvulsas');
    oDbHintNumeroMaximoNotasAvulsas.setText('Poder� definir o n�mero m�ximo de notas avulsas que cada CGM ou Inscri��o poder� gerar.');
    oDbHintNumeroMaximoNotasAvulsas.setShowEvents(aEventoShow);
    oDbHintNumeroMaximoNotasAvulsas.setHideEvents(aEventoHide);
    oDbHintNumeroMaximoNotasAvulsas.make($('numeromaximonotasavulsas'));

var oDbHintNumeroUltimaNotaAvulsa = new DBHint('oDbHintNumeroUltimaNotaAvulsa');
    oDbHintNumeroUltimaNotaAvulsa.setText('Nesse campo ser� informado o n�mero da Nota Avulsa gerada.');
    oDbHintNumeroUltimaNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintNumeroUltimaNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintNumeroUltimaNotaAvulsa.make($('numeroultimanotaavulsa'));

var oDbHintDiasPrazoNotaAvulsa = new DBHint('oDbHintDiasPrazoNotaAvulsa');
    oDbHintDiasPrazoNotaAvulsa.setText('Poder�  informar a quantidade de dias que ser� o vencimento da Nota Avulsa emitida.');
    oDbHintDiasPrazoNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintDiasPrazoNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintDiasPrazoNotaAvulsa.make($('diasprazonotaavulsa'));

var oDbHintTipoNumeracaoCertidao = new DBHint('oDbHintTipoNumeracaoCertidao');
    oDbHintTipoNumeracaoCertidao.setText('Define a numera��o que ser� impresso na certid�o de baixa.');
    oDbHintTipoNumeracaoCertidao.setShowEvents(aEventoShow);
    oDbHintTipoNumeracaoCertidao.setHideEvents(aEventoHide);
    oDbHintTipoNumeracaoCertidao.make($('tiponumeracaocertidao'));

var oDbHintBloqueiaEmissaoCertidao = new DBHint('oDbHintBloqueiaEmissaoCertidao');
    oDbHintBloqueiaEmissaoCertidao.setText('Poder� definir se permite a gera��o de certid�o de baixa quando a inscri��o possuir d�bitos.');
    oDbHintBloqueiaEmissaoCertidao.setShowEvents(aEventoShow);
    oDbHintBloqueiaEmissaoCertidao.setHideEvents(aEventoHide);
    oDbHintBloqueiaEmissaoCertidao.make($('bloqueiaemissaocertidao'));

var oDbHintDataImplantacaoMei = new DBHint('oDbHintDataImplantacaoMei');
    oDbHintDataImplantacaoMei.setText('Data em que foi feita a implanta��o da rotina do MEI.');
    oDbHintDataImplantacaoMei.setShowEvents(aEventoShow);
    oDbHintDataImplantacaoMei.setHideEvents(aEventoHide);
    oDbHintDataImplantacaoMei.make($('dataimplantacaomei'));

var oDbHintTipoDebitoNotaAvulsa = new DBHint('oDbHintTipoDebitoNotaAvulsa');
	oDbHintDataImplantacaoMei.setText('Podera informar qual o tipo de d�bito que ser� lan�ado o valor calculado no c�lculo de ISSQN da Nota Avulsa. ');
	oDbHintDataImplantacaoMei.setShowEvents(aEventoShow);
	oDbHintDataImplantacaoMei.setHideEvents(aEventoHide);
	oDbHintDataImplantacaoMei.make($('tipodebitonotaavulsa'));
/**
 * Fim hints
 */
</script>
