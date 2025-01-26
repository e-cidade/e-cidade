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

//MODULO: Configuracoes
$clcontabancaria->rotulo->label();
$cloperacaodecredito->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("db89_codagencia");
$ano = db_getsession("DB_anousu"); //ano


?>
<script>
	function js_functionVerificaIdentificador() {

		var iIdentificador = document.getElementById('db83_identificador').value;

		if (iIdentificador.length < 11) {

			alert("Campo identificador(CNPJ) inv�lido.");
			return false;
		}

		return true;
	}
</script>
<form name="form1" method="post" action="" onsubmit="return js_functionVerificaIdentificador();">
	<center>
		<fieldset>
			<legend>
				<b>Cadastro de Conta Banc�ria</b>
			</legend>
			<table border="0">
				<tr>
					<td nowrap title="<?= @$Tdb83_descricao ?>">
						<?= @$Ldb83_descricao ?>
					</td>
					<td>
						<?
						db_input('db83_sequencial', 10, $Idb83_sequencial, true, 'text', 3, "");
						db_input('db83_descricao', 50, $Idb83_descricao, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?= @$Tdb83_bancoagencia ?>">
						<?
						db_ancora(@$Ldb83_bancoagencia, "js_pesquisadb83_bancoagencia(true);", $db_opcao);
						?>
					</td>
					<td>
						<?
						db_input('db83_bancoagencia', 10, $Idb83_bancoagencia, true, 'text', $db_opcao, " onchange='js_pesquisadb83_bancoagencia(false);'");
						db_input('db89_codagencia', 10, $Idb89_codagencia, true, 'text', 3, '');
						db_input('db89_digito', 1, '', true, 'text', 3, '');
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?= @$Tdb83_conta ?>">
						<?= @$Ldb83_conta ?>
					</td>
					<td>
						<?
						db_input('db83_conta', 15, $Idb83_conta, true, 'text', $db_opcao, "");
						db_input('db83_dvconta', 1, $Idb83_dvconta, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?= @$Tdb83_identificador ?>">
						<?= @$Ldb83_identificador ?>
					</td>
					<td>
						<?
						db_input('db83_identificador', 15, 1, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?= @$Tdb83_codigooperacao ?>">
						<?= @$Ldb83_codigooperacao ?>
					</td>
					<td>
						<?
						db_input('db83_codigooperacao', 4, $Idb83_codigooperacao, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr>
				<!-- OC16314 -->
				<tr>
					<td nowrap title="<?= substr(@$Top01_numerocontratoopc, 18, 50) ?>">
						<?= db_ancora(substr(@$Ldb83_numerocontratooc, 26, 50), "js_pesquisaop01_db_operacaodecredito(true);", $db_opcao); ?>
					</td>
					<td nowrap title="<?= @$Top01_dataassinaturacop ?>">
						<?
						db_input('db83_codigoopcredito', 10, 0, true, '', 1, " onchange='js_pesquisaop01_db_operacaodecredito(false);'");
						db_input('op01_numerocontratoopc', 38, $Iop01_numerocontratoopc, true, 'text', 3);

						$data = explode("-", $op01_dataassinaturacop);
						$op01_dataassinaturacop_dia = $data[2];
						$op01_dataassinaturacop_mes = $data[1];
						$op01_dataassinaturacop_ano = $data[0];
						db_inputData('op01_dataassinaturacop', $op01_dataassinaturacop_dia, @$op01_dataassinaturacop_mes, @$op01_dataassinaturacop_ano, true, 'text', 3);
						// db_input('op01_dataassinaturacop', 10, $Idop01_dataassinaturacop, true, 'text', 3);
						?>
					</td>
				</tr>
				<!-- OC16314 -->
				<tr>
					<td nowrap title="<?= @$Tdb83_tipoconta ?>">
						<?= @$Ldb83_tipoconta ?>
					</td>
					<td>
						<?
						$x = array('1' => 'Conta Corrente', '2' => 'Conta Poupanca', '3' => 'Conta Aplicacao');
						db_select('db83_tipoconta', $x, true, $db_opcao, "style='width: 150px;'");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?php echo $Tdb83_contaplano ?>">
						<?php echo $Ldb83_contaplano ?>
					</td>
					<td>
						<?php
						$aContaPlano = array('t' => 'Sim', 'f' => 'N�o');
						db_select('db83_contaplano', $aContaPlano, true, $db_opcao, "style='width: 150px;'");
						?>
					</td>
				</tr>
				<!--
	  <tr>
	    <td nowrap title="<? //=@$Tdb83_convenio
							?>">
	      <? //=@$Ldb83_convenio
			?>
	    </td>
	    <td>
				<?
				//$aConvenio = array('2' => 'N�o','1' => 'Sim');
				//db_select('db83_convenio', $aConvenio, true, $db_opcao,"");
				?>
	    </td>
	  </tr>
    -->
				<tr>
					<td nowrap title="C�digo c206_sequencial">
						<? db_ancora("Conv�nio", "js_pesquisadb83_numconvenio(true);", $db_opcao); ?>
					</td>
					<td>
						<?
						db_input('db83_numconvenio', 11, $Idb83_numconvenio, true, 'text', $db_opcao, "onChange='js_pesquisadb83_numconvenio(false);'");
						db_input("c206_objetoconvenio", 50, 0, true, "text", 3);
						?>
					</td>
				</tr>
				<!--
	  <tr>
	    <td nowrap title="<? //=@$Tdb83_dataconvenio
							?>">
	      <?= @$Ldb83_dataconvenio ?>
	    </td>
	    <td>
				<?
				//db_inputData('db83_dataconvenio',@$db83_dataconvenio_dia, @$db83_dataconvenio_mes,@$db83_dataconvenio_ano, true, 'text', $db_opcao);
				?>
	    </td>
	  </tr>
    -->
				<tr>
					<td nowrap title="<?= @$Tdb83_tipoaplicacao ?>">
						<?= @$Ldb83_tipoaplicacao ?>
					</td>
					<td>
						<?
						if (db_getsession("DB_anousu") < 2018) {
							$aTipoAplicacao = array(
								'00' => 'N�O INFORMADO',
								'01' => 'T�tulos do Tesouro Nacional - SELIC - Art. 7�, I, "a"',
								'02' => 'FI 100% t�tulos TN - Art. 7�, I, "b"',
								'03' => 'Opera��es Compromissadas - Art. 7�, II',
								'04' => 'FI Renda Fixa / Referenciado RF - Art. 7�, III',
								'05' => 'FI de renda fixa - Art. 7�, IV',
								'06' => 'Poupan�a - Art. 7�, V',
								'07' => 'FI em direitos credit�cios - aberto - Art. 7�, VI',
								'08' => 'FI em direitos credit�cios - fechado - Art. 7�, VII, "a"',
								'09' => 'FI renda fixa "Cr�dito Privado" - - Art. 7�, VII, "b"',
								'10' => 'FI Previdenci�rio em A��es - Art. 8�, I, "b"',
								'11' => 'FI de �ndice referenciado em A��es - - Art. 8�, II',
								'12' => 'FI em A��es - - Art. 8�, III',
								'13' => 'FI Multimercado aberto - - Art. 8�, IV',
								'14' => 'FI em participa��es fechado - Art. 8�, V',
								'15' => 'FI Imobili�rio - cotas negociadas em bolsa - - Art. 8�, VI'
							);
							db_select('db83_tipoaplicacao', $aTipoAplicacao, true, $db_opcao, "");
						} else {
							$aTipoAplicacao = array(
								'00' => 'N�O INFORMADO',
								'16' => 'T�tulos P�blicos de emiss�o do Tesouro Nacional (SELIC) - Art. 7�, I, a',
								'17' => 'Fundos referenciados 100% T�tulos P�blicos - Art.7�, I, b',
								'18' => 'Fundos de �ndices carteira 100% T�tulos P�blicos -Art. 7�, I, c',
								'19' => 'Opera��es Compromissadas - Art. 7�, II',
								'20' => 'Fundos Referenciados em indicadores RF - Art. 7�,III, a',
								'21' => 'Fundos de �ndices (ETF) em indicadores T�tulos P�blicos - Art. 7�, III, b',
								'22' => 'Fundos de Renda Fixa em geral - Art. 7�, IV, a',
								'23' => 'Fundos de �ndices (ETF) - quaisquer indicadores - Art. 7�, IV, b',
								'24' => 'Letra Imobili�ria Garantida (LIG) - Art. 7�, V, b',
								'25' => 'Certificado de Dep�sito Banc�rio (CDB) - Art. 7�, VI, a',
								'26' => 'Poupan�a - Art. 7�, VI, b',
								'27' => 'FIDCs - Cota S�nior - Art. 7�, VII, a',
								'28' => 'Fundos de Renda Fixa - Cr�dito Privado - Art. 7�,VII, b',
								'29' => 'Fundos de Deb�ntures de Infraestrutura - Art. 7�,VII, c',
								'30' => 'Fundo de A��es (�ndices c/ no m�nimo 50 a��es)-Art. 8�, I, a',
								'31' => 'ETF (�ndices c/ no m�nimo 50 a��es) - Art. 8�, I, b',
								'32' => 'Fundo de A��es em geral (com at� 20% de ativos) - Art. 8�, II, a',
								'33' => 'ETF (�ndices em geral) - Art. 8�, II, b',
								'34' => 'Fundos Multimercado (com at� 20% ativos exterior)- Art. 8�, III',
								'35' => 'Fundos de Investimento em Participa��es - FIP - Art. 8�, IV, a',
								'36' => 'Fundo de Investimento Imobili�rio - FII - Art. 8�, IV, b',
								'37' => 'Fundos de Investimento classificados como "A��es - Mercado de Acesso" - Art. 8�, IV, "c"',
								'38' => 'Fundos de Investimento classificados como "Renda Fixa - D�vida Externa" - Art. 9�-A, I',
								'39' => 'Fundos de Investimento - Sufixo Investimento no Exterior - Art. 9�-A, II',
								'40' => 'Fundos de A��es BDR N�vel 1 - Art. 9�-A, III',
								'60' => 'Aplica��es financeiras da taxa de administra��o do RPPS',
								'61' => 'T�tulos e valores em enquadramento',
								'62' => 'T�tulos e valores n�o sujeitos ao enquadramento'

							);
							db_select('db83_tipoaplicacao', $aTipoAplicacao, true, $db_opcao, "");
						}
						?>
					</td>
				</tr>

				<tr>
					<td nowrap title="db83_nroseqaplicacao?>">
						<b>N�mero sequencial da aplica��o</b>
					</td>
					<td>
						<?
						db_input('db83_nroseqaplicacao', 11, 1, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr>
			</table>
		</fieldset>
	</center>
	<input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script>
	function js_pesquisadb83_bancoagencia(mostra) {
		if (mostra == true) {
			js_OpenJanelaIframe('top.corpo', 'db_iframe_bancoagencia', 'func_bancoagencia.php?digito=true&funcao_js=parent.js_mostrabancoagencia1|db89_sequencial|db89_codagencia|db89_digito', 'Pesquisa', true);
		} else {
			if (document.form1.db83_bancoagencia.value != '') {
				js_OpenJanelaIframe('top.corpo', 'db_iframe_bancoagencia', 'func_bancoagencia.php?digito=true&pesquisa_chave=' + document.form1.db83_bancoagencia.value + '&funcao_js=parent.js_mostrabancoagencia', 'Pesquisa', false);
			} else {
				document.form1.db89_codagencia.value = '';
			}
		}
	}

	function js_pesquisaop01_db_operacaodecredito(mostra) {

		var tiposLancamento = [3, 6, 7];

		if (mostra == true) {
			js_OpenJanelaIframe('top.corpo', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?tipos_lancamento=' +tiposLancamento+ '&funcao_js=parent.js_mostraoperacaodecredito1|op01_sequencial|op01_numerocontratoopc|op01_dataassinaturacop', 'Pesquisa', true);
		} else {
			if (document.form1.db83_codigoopcredito.value != '') {
				js_OpenJanelaIframe('top.corpo', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?tipos_lancamento=' +tiposLancamento+ '&pesquisa_chave=' + document.form1.db83_codigoopcredito.value + '&funcao_js=parent.js_mostraoperacaodecredito', 'Pesquisa', false);
			} else {
				document.form1.op01_numerocontratoopc.value = '';
				document.form1.op01_dataassinaturacop.value = '';
			}
		}
	}

	function js_mostrabancoagencia(chave, chave1, erro) {

		document.form1.db89_codagencia.value = chave;
		document.form1.db89_digito.value = chave1;
		document.form1.db83_bancoagencia.value = '';

	}

	function js_mostrabancoagencia1(chave1, chave2, chave3) {
		document.form1.db83_bancoagencia.value = chave1;
		document.form1.db89_codagencia.value = chave2;
		document.form1.db89_digito.value = chave3;
		db_iframe_bancoagencia.hide();
	}

	function js_mostraoperacaodecredito1(chave, chave1, chave2, chave3, chave4) {
		document.form1.db83_codigoopcredito.value = chave;
		document.form1.op01_numerocontratoopc.value = chave1;
		var data = chave2.split("-", 3);
		document.form1.op01_dataassinaturacop.value = data[2] + "-" + data[1] + "-" + data[0];
		db_iframe_db_operacaodecredito.hide();
	}

	function js_mostraoperacaodecredito(chave, chave1, erro) {
		document.form1.op01_numerocontratoopc.value = chave;
		var data = chave1.split("-", 3);
		document.form1.op01_dataassinaturacop.value = data[2] + "-" + data[1] + "-" + data[0];
	}


	function js_pesquisa() {
		js_OpenJanelaIframe('top.corpo', 'db_iframe_contabancaria', 'func_contabancariacadastro.php?convenio=true&funcao_js=parent.js_preenchepesquisa|db83_sequencial', 'Pesquisa', true);
	}

	function js_preenchepesquisa(chave) {
		db_iframe_contabancaria.hide();
		<?
		if ($db_opcao != 1) {
			echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
		}
		?>
	}

	function js_pesquisadb83_numconvenio(mostra) {
		if (mostra == true) {
			js_OpenJanelaIframe('', 'db_iframe_convconvenios', 'func_convconvenios.php?funcao_js=parent.js_mostradb83_numconvenio1|c206_sequencial|c206_objetoconvenio', 'Pesquisa', true);
		} else {
			if (document.form1.db83_numconvenio.value != '') {
				js_OpenJanelaIframe('', 'db_iframe_convconvenios', 'func_convconvenios.php?pesquisa_chave=' + document.form1.db83_numconvenio.value + '&funcao_js=parent.js_mostradb83_numconvenio', 'Pesquisa', false);
			} else {
				document.form1.c206_objetoconvenio.value = '';
			}
		}
	}

	function js_mostradb83_numconvenio(chave, erro) {
		document.form1.c206_objetoconvenio.value = chave;
		if (erro == true) {
			document.form1.db83_numconvenio.focus();
			document.form1.db83_numconvenio.value = '';
		}
	}

	function js_mostradb83_numconvenio1(chave1, chave2) {
		document.form1.db83_numconvenio.value = chave1;
		document.form1.c206_objetoconvenio.value = chave2;
		db_iframe_convconvenios.hide();
	}
</script>