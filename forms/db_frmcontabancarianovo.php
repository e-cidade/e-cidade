<?
$clcontabancaria->rotulo->label();
$cloperacaodecredito->rotulo->label();

$clrotulo = new rotulocampo;
$clsaltes->rotulo->label();
$clrotulo->label("db89_codagencia");
$ano = db_getsession("DB_anousu"); //ano


?>
<form name="form1" method="post" action="">
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
						db_input('db83_descricao', 50, $Idb83_descricao, true, 'text', $db_opcao, "","","","",40);
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
						db_input('db90_codban', 1, '', true, 'hidden', 3, '');
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
					<b>Identificador (CNPJ do Banco):</b>
					</td>
					<td>
						<?
						if ($db_opcaonovo == 1) {
							db_input('db83_identificador', 15, 1, true, 'text', $db_opcaonovo, "");
						} else {
							db_input('db83_identificador', 15, 1, true, 'text', $db_opcao, "");
						}
						
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
				<tr>
					<td nowrap title="db83_dataimplantaoconta?>">
						<b>Data da Implanta��o da Conta:</b>
					</td>
					<td>
						<?
						  db_inputdata('db83_dataimplantaoconta',@$db83_dataimplantaoconta_dia,
						  @$db83_dataimplantaoconta_mes,
						  @$db83_dataimplantaoconta_ano, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr> 
				<tr>
					<td nowrap title="<?= @$Tdb83_tipoconta ?>">
						<?= @$Ldb83_tipoconta ?>
					</td>
					<td>
						<?
						$x = array('' => 'Selecione','1' => 'Conta Corrente', '3' => 'Conta Aplicacao');
						db_select('db83_tipoconta', $x, true, $db_opcao, "style='width: 150px;'onchange='habilitarCampos(this.value)'");
						?>
					</td>
				</tr>
				<tr>

			
				<td>
				<?php

				if ($db_opcaonovo == 1) {
					db_ancora("<b>Fonte Principal:</b>", "js_pesquisaRecurso(true)", $db_opcaonovo);
				} else {
					db_ancora("<b>Fonte Principal:</b>", "js_pesquisaRecurso(true)", $db_opcao);
				}

				?>
				</td>
				<td>
					<?php
					if ($db_opcaonovo == 1) {
						db_input("iCodigoRecurso", 10, null, true, "text", $db_opcaonovo, "onchange='js_pesquisaRecurso(false);'");
					} else {
						db_input("iCodigoRecurso", 10, null, true, "text", $db_opcao, "onchange='js_pesquisaRecurso(false);'");
					}	
					db_input("sDescricaoRecurso", 50, null, true, "text", 3);
					?>
				</td>
				</tr>
				<tr id="idconvenio" style="display: none;">
					<td nowrap title="C�digo c206_sequencial">
						<?php 
							if ($db_opcaonovo == 1) {
								db_ancora("<b>Conv�nio</b>", "js_pesquisadb83_numconvenio(true);", $db_opcaonovo); 
							} else {
								db_ancora("<b>Conv�nio</b>", "js_pesquisadb83_numconvenio(true);", $db_opcao); 
							}	
						?>
					</td>
					<td>
						<?php
							if ($db_opcaonovo == 1) {
								db_input('db83_numconvenio', 11, $Idb83_numconvenio, true, 'text', $db_opcaonovo, "onChange='js_pesquisadb83_numconvenio(false);'");
							} else {
								db_input('db83_numconvenio', 11, $Idb83_numconvenio, true, 'text', $db_opcao, "onChange='js_pesquisadb83_numconvenio(false);'");
							}	
						db_input("c206_objetoconvenio", 50, 0, true, "text", 3);
						?>
					</td>
				</tr>
				<tr id="idnumerocontratoopc" style="display: none;">
					<td  nowrap title="<?= substr(@$Top01_numerocontratoopc, 18, 50) ?>">
						<?php 
							if ($db_opcaonovo == 1) {
								db_ancora("<b>".substr(@$Ldb83_numerocontratooc."</b>", 26, 50), "js_pesquisaop01_db_operacaodecredito(true);", $db_opcaonovo); 
							} else {
								db_ancora("<b>".substr(@$Ldb83_numerocontratooc."</b>", 26, 50), "js_pesquisaop01_db_operacaodecredito(true);", $db_opcao); 
							}
						 ?>
					</td>
					<td nowrap title="<?= @$Top01_dataassinaturacop ?>">
						<?php 
						if ($db_opcaonovo == 1) {
							db_input('db83_codigoopcredito', 10, 0, true, 'text', $db_opcaonovo, " onchange='js_pesquisaop01_db_operacaodecredito(false);'");
						} else {
							db_input('db83_codigoopcredito', 10, 0, true, 'text', $db_opcao, " onchange='js_pesquisaop01_db_operacaodecredito(false);'");
						}
						db_input('op01_numerocontratoopc', 38, $Iop01_numerocontratoopc, true, 'text', 3);

						$data = explode("-", $op01_dataassinaturacop);
						$op01_dataassinaturacop_dia = $data[2];
						$op01_dataassinaturacop_mes = $data[1];
						$op01_dataassinaturacop_ano = $data[0];
						db_inputData('op01_dataassinaturacop', $op01_dataassinaturacop_dia, @$op01_dataassinaturacop_mes, @$op01_dataassinaturacop_ano, true, 'text', 3);						?>
					</td>
				</tr>
				<tr style="display: none;">
					<td nowrap title="<?php echo $Tdb83_contaplano ?>">
						<?php echo $Ldb83_contaplano ?>
					</td>
					<td>
						<?php
						$aContaPlano = array('t' => 'Sim', 'f' => 'N�o');
						db_select('db83_contaplano', $aContaPlano, true, $db_opcao, "style='width: 150px;'");
						$db83_contaplano = 't';
						?>
					</td>
				</tr>
				<tr style="display: none;">
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
					<td nowrap title="db83_codigotce?>">
						<b>C�digo TCE:</b>
					</td>
					<td>
						<?
						db_input('db83_codigotce', 11, 1, true, 'text', 3, "","","");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="db83_reduzido?>">
						<b>Reduzido:</b>
					</td>
					<td>
						<?						
						db_input('db83_reduzido', 11, 1, true, 'text', 3, "");
						?>
					</td>
				</tr>
				<tr style="display: <?php echo $mostrarCampo; ?>;">
                  <td nowrap title="<?=@$Tk13_limite?>"> <?=@$Lk13_limite?> </td>
                  <td>
					<? 
						if ($db_opcaonovo == 1) {
							db_inputdata('k13_limite',@$k13_limite_dia,@$k13_limite_mes,@$k13_limite_ano,true,'text',$db_opcaonovo,"");
						} else {
							db_inputdata('k13_limite',@$k13_limite_dia,@$k13_limite_mes,@$k13_limite_ano,true,'text',$db_opcao,"");
						}
					?>
                  </td>
                </tr>
				<tr style="display: <?php echo $mostrarCampo2; ?>;">
				    <td>
				  		<b>Data da Reativa��o da Conta:</b>
					</td>
                  <td>
                    <?
						if ($db_opcaonovo == 1) {
                    		db_inputdata('k13_dtreativacaoconta',@$k13_dtreativacaoconta_dia,@$k13_dtreativacaoconta_mes,@$k13_dtreativacaoconta_ano,true,'text',$db_opcaonovo,"style='background-color:#E6E4F1'");
							$db_opcao = 2;
						} else {
							db_inputdata('k13_dtreativacaoconta',@$k13_dtreativacaoconta_dia,@$k13_dtreativacaoconta_mes,@$k13_dtreativacaoconta_ano,true,'text',$db_opcao,"style='background-color:#E6E4F1'");
						}
						 
					?>
                  </td>
                </tr>
				<tr style="display: none;">
					<td nowrap title="db83_instituicao?>">
						<b>Institui��o:</b>
					</td>
					<td>
						<?
						$db83_instituicao = db_getsession('DB_instit');
						db_input('db83_instituicao', 11, 1, true, 'text', $db_opcao, "");
						?>
					</td>
				</tr>
				<tr style="display: none;" id="nroseqaplicacao">
					<td nowrap title="db83_nroseqaplicacao?>">
						<b>N�mero sequencial da aplica��o</b>
					</td>
					<td>
						<?
						$color =  $db_opcao == 3 ? '' : "#E6E4F1";
						db_input('db83_nroseqaplicacao', 11, 1, true, 'text', $db_opcao, "","",$color);
						?>
					</td>
				</tr>

			</table>
		</fieldset>
	</center>
	<? $db_opcao ?>
	<input type='button' value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" onclick="js_functionVerificaIdentificador();">
	<input name="novo" type="button" id="novo" value="Novo" onclick="js_cleanScreen();" <?= ($db_opcao != 1 ? "disabled" : "") ?>>
	<input name="limpar" type="button" id="limpas" value="Limpar" onclick="js_cleanScreen();" <?= ($db_opcao != 1 ? "disabled" : "") ?>>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?= ($db_opcao == 1 ? "disabled" : "") ?>>

</form>
<script>
	var sUrlRpc = 'con1_contabancaria.RPC.php';
	function js_pesquisadb83_bancoagencia(mostra) 
	{
		if (mostra == true) {
			js_OpenJanelaIframe('top.corpo', 'db_iframe_bancoagencia', 'func_bancoagencia.php?digito=true&funcao_js=parent.js_mostrabancoagencia1|db89_sequencial|db89_codagencia|db89_digito|db90_codban', 'Pesquisa', true);
		} else {
			if (document.form1.db83_bancoagencia.value != '') {
				js_OpenJanelaIframe('top.corpo', 'db_iframe_bancoagencia', 'func_bancoagencia.php?digito=true&pesquisa_chave=' + document.form1.db83_bancoagencia.value + '&funcao_js=parent.js_mostrabancoagencia', 'Pesquisa', false);
			} else {
				document.form1.db89_codagencia.value = '';
			}
		}
	}

	function js_pesquisaop01_db_operacaodecredito(mostra) 
	{
		var tiposLancamento = [3, 4, 6, 7];

		if (mostra == true) {
			js_OpenJanelaIframe('top.corpo', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?tipos_lancamento=' +tiposLancamento+ '&funcao_js=parent.js_mostraoperacaodecredito1|op01_sequencial|op01_numerocontratoopc|op01_dataassinaturacop', 'Pesquisa', true);
		} else {
			js_OpenJanelaIframe('top.corpo', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?tipos_lancamento=' +tiposLancamento+ '&pesquisa_chave=' + document.form1.db83_codigoopcredito.value + '&funcao_js=parent.js_mostraoperacaodecredito', 'Pesquisa', false);
		}
	}

	function js_mostraoperacaodecredito1(chave, chave1, chave2, chave3, chave4) 
	{
		if(chave.includes('n�o Encontrado')) {
			document.form1.db83_codigoopcredito.value   = '';
			document.form1.op01_numerocontratoopc.value = '';
			document.form1.op01_dataassinaturacop.value = '';
        } else {
			document.form1.db83_codigoopcredito.value = chave;
			document.form1.op01_numerocontratoopc.value = chave1;
			var data = chave2.split("-", 3);
			document.form1.op01_dataassinaturacop.value = data[2] + "-" + data[1] + "-" + data[0];
			db_iframe_db_operacaodecredito.hide();
		}
	}

	function js_mostraoperacaodecredito(chave, chave1, erro) 
	{
		if(chave.includes('n�o Encontrado')) {
			document.form1.db83_codigoopcredito.value   = '';
            document.form1.op01_numerocontratoopc.value = chave;
	    	document.form1.op01_dataassinaturacop.value = '';
		} else {
	    	document.form1.op01_numerocontratoopc.value = chave;
            var data = chave1.split("-", 3);
            document.form1.op01_dataassinaturacop.value = data[2] + "-" + data[1] + "-" + data[0];
	  	}
	}

	function js_mostrabancoagencia(chave, chave1,chave2, erro) 
	{

		document.form1.db89_codagencia.value = chave;
		document.form1.db89_digito.value = chave1;
		document.form1.db90_codban.value = chave2;
		// document.form1.db83_bancoagencia.value = '';

	}

	function js_mostrabancoagencia1(chave1, chave2, chave3, chave4) 
	{
		document.form1.db83_bancoagencia.value = chave1;
		document.form1.db89_codagencia.value = chave2;
		document.form1.db89_digito.value = chave3;
		document.form1.db90_codban.value = chave4;
		db_iframe_bancoagencia.hide();
	}

	function js_pesquisa() 
	{
		js_OpenJanelaIframe('top.corpo', 'db_iframe_contabancaria', 'func_cadcontabancariacadastro.php?convenio=true&funcao_js=parent.js_preenchepesquisa|db83_sequencial', 'Pesquisa', true);
	}

	function js_preenchepesquisa(chave) 
	{
		db_iframe_contabancaria.hide();
		<?
		if ($db_opcao != 1) {
			echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
		}
		?>
	}

	function js_pesquisadb83_numconvenio(mostra) 
	{
		
		if (mostra == true) {
			js_OpenJanelaIframe('', 'db_iframe_convconvenios', 'func_convconvenios.php?pesquisa_iCodigoRecurso=' + document.form1.iCodigoRecurso.value + '&funcao_js=parent.js_mostradb83_numconvenio1|c206_sequencial|c206_objetoconvenio', 'Pesquisa', true);
		} else {
			if (document.form1.db83_numconvenio.value != '') {
				js_OpenJanelaIframe('', 'db_iframe_convconvenios', 'func_convconvenios.php?pesquisa_chave=' + document.form1.db83_numconvenio.value + '&pesquisa_iCodigoRecurso=' + document.form1.iCodigoRecurso.value + '&funcao_js=parent.js_mostradb83_numconvenio', 'Pesquisa', false);
			} else {
				document.form1.c206_objetoconvenio.value = '';
			}
		}
	}

	function js_mostradb83_numconvenio(chave, erro) 
	{
		document.form1.c206_objetoconvenio.value = chave;
		if (erro == true) {
			document.form1.db83_numconvenio.focus();
			document.form1.db83_numconvenio.value = '';
		}
		if (erro == 2) {
			alert("O Conv�nio deve ter a mesma fonte do campo Fonte Principal.");
			document.form1.db83_numconvenio.focus();
			document.form1.db83_numconvenio.value = '';	
		}
	}

	function js_mostradb83_numconvenio1(chave1, chave2) 
	{
		document.form1.db83_numconvenio.value = chave1;
		document.form1.c206_objetoconvenio.value = chave2;
		db_iframe_convconvenios.hide();
	}

	function js_functionVerificaIdentificador() 
	{

		var iIdentificador   = document.getElementById('db83_identificador').value;
		var dataEncerramento = "<?php echo $c99_data; ?>";
		var PeriodoEncerrado = "<?php echo $db_opcaonovo; ?>";

		if ($F("db83_descricao") == '') {
			alert("Campo Descri��o Obrigat�rio.");
			$('db83_descricao').focus();			
			return false;
		}

		if ($F("db83_bancoagencia") == '') {
			alert("Campo Ag�ncia Obrigat�rio.");
			$('db83_bancoagencia').focus();	
			return false;
		}

		if ($F("db83_conta") == '') {
			alert("Campo Conta Obrigat�rio.");
			$('db83_conta').focus();	
			return false;
		}

		if ($F("db83_dvconta") == '') {
			alert("Campo Digito Verificador Obrigat�rio.");
			$('db83_dvconta').focus();
			return false;
		}

		if (iIdentificador.length < 11) {
			alert("Campo identificador(CNPJ do Banco) inv�lido.");
			$('db83_identificador').focus();
			return false;
		}

		if ($F("db83_dataimplantaoconta") == '') {
			alert("Campo Data da Implanta��o da Conta obrigat�rio.");
			$('db83_dataimplantaoconta').focus();
			return false;
		}

		if ($F("db83_tipoconta") == '') {
			alert("Campo Tipo de Conta Obrigat�rio.");
			$('db83_tipoconta').focus();
			return false;
		}

		if ($F("iCodigoRecurso") == '') {
			alert("Campo Fonte Principal Obrigat�rio.");
			$('iCodigoRecurso').focus();
			return false;
		}

		if (PeriodoEncerrado == 1 ) {
			let dateLimite       = parseDate($F("k13_limite"));
			let dateEncerramento = parseDate(dataEncerramento);

			if (dateLimite < dateEncerramento) {
				alert("A data limite informada dever� ser maior que a data de encerramento do per�odo cont�bil("+ dataEncerramento +").");
				return false;
			}

		}

		if ($F("k13_limite")) {
			let dateLimite         = parseDate($F("k13_limite"));
			let dataimplantaoconta = parseDate($F("db83_dataimplantaoconta"));

			if (dateLimite < dataimplantaoconta) {
				alert("A data limite informada tem que ser maior ou igual a data de implanta��o.");
				return false;
			}

		}

		if ($F("k13_limite") && $F("k13_dtreativacaoconta")) {
			alert("A data da reativa��o de conta somente poder� ser preenchido se o campo data limite estiver vazio");
			return false;
		}

		if ($F("k13_dtreativacaoconta")) {	

			let dataEncerramento    = "<?php echo $dataencerramento; ?>";
			let dateEncerramento    = parseDate(dataEncerramento);
			let datereativacaoconta = parseDate($F("k13_dtreativacaoconta"));
			let dataimplantaoconta  = parseDate($F("db83_dataimplantaoconta"));

			if (datereativacaoconta < dateEncerramento) {
				alert("A data da reativa��o de conta informada dever� ser maior que a data de encerramento do per�odo cont�bil("+ dataEncerramento +").");
				return false;
			}

			if (datereativacaoconta < dataimplantaoconta) {
				alert("A data da reativa��o de conta informada tem que ser maior ou igual a data de implanta��o.");
				return false;
			}
		}

		const valoresPermitidos = ["570", "571", "572", "575", "631", "632", "633", "636", "665", "700", "701", "702", "703"];
		const valoresPermitidos2 = ["574", "634", "754"];
		const codigoRecurso = $F("iCodigoRecurso").substr(1,3);
		
		if (valoresPermitidos.includes(codigoRecurso) && $F("db83_numconvenio") == '') {
			alert("Campo conv�nio obrigat�rio para as fontes (570, 571, 572, 575, 631, 632, 633, 636, 665, 700, 701, 702 e 703).");
			$('db83_numconvenio').focus();
			return false;
		} 
		
		if (valoresPermitidos2.includes(codigoRecurso) && $F("db83_codigoopcredito") == '') {
			alert("Campo opera��o de cr�dito obrigat�rio para as fontes (574, 634 e 754).");
			$('db83_codigoopcredito').focus();
			return false;
		}

		js_salvarDados();
		return true;
	}

	function parseDate(dateStr) {
		let parts = dateStr.split('/');
		return new Date(parts[2], parts[1] - 1, parts[0]);
	}

	function js_salvarDados() 
	{
		js_divCarregando('Aguarde, processando.....', 'msgbox');
			
		var dbOpcao = "<?php echo $db_opcao; ?>";
		var oParam = new Object();
		
		if (dbOpcao == 1) {
			oParam.exec = "salvarDadosContas";	
		}else if (dbOpcao == 2 || dbOpcao == 22) {
			oParam.exec = "alterarDadosContas";
		} else {
			oParam.exec = "excluirDadosContas";
		}
		oParam.db83_sequencial         = $F("db83_sequencial");	
		oParam.db83_descricao		   = $F("db83_descricao");
		oParam.db83_bancoagencia       = $F("db83_bancoagencia");
		oParam.db83_codagencia 		   = $F("db89_codagencia");	
		oParam.db83_dvagencia 		   = $F("db89_digito");	
		oParam.db83_dvconta			   = $F("db83_dvconta");
		oParam.db83_codbanco		   = $F("db90_codban");
		oParam.db83_conta			   = $F("db83_conta");
		oParam.db83_identificador	   = $F("db83_identificador");	
		oParam.db83_codigooperacao     = $F("db83_codigooperacao");
		oParam.op01_dataassinaturacop  = $F("op01_dataassinaturacop");
		oParam.db83_tipoconta		   = $F("db83_tipoconta");
		oParam.db83_contaplano		   = $F("db83_contaplano");
		oParam.db83_numconvenio 	   = $F("db83_numconvenio");	 
		oParam.c206_objetoconvenio	   = $F("c206_objetoconvenio");
		oParam.db83_tipoaplicacao	   = $F("db83_tipoaplicacao");	
		oParam.db83_nroseqaplicacao	   = $F("db83_nroseqaplicacao");
		oParam.db83_dataimplantaoconta = $F("db83_dataimplantaoconta");
		oParam.k13_limite              = $F("k13_limite");
		oParam.k13_dtreativacaoconta   = $F("k13_dtreativacaoconta");
		oParam.db83_codigoopcredito	   = $F("db83_codigoopcredito");
		oParam.iCodigoRecurso		   = $F("iCodigoRecurso");	
		oParam.sDescricaoRecurso	   = $F("sDescricaoRecurso");	
		oParam.db83_codigotce		   = $F("db83_codigotce");
		oParam.db83_reduzido		   = $F("db83_reduzido");	
		oParam.db83_instituicao		   = $F("db83_instituicao"); 
		
		var oAjax = new Ajax.Request(sUrlRpc, {
		method: 'post',
		parameters: 'json=' + Object.toJSON(oParam),
		onComplete: js_retornoSalvarDadosContas
		})
	}
	function js_retornoSalvarDadosContas(oAjax) 
	{
		js_removeObj('msgbox');
		var oRetorno = eval("(" + oAjax.responseText + ")");
		var sMensagem = oRetorno.message.urlDecode();
		var dbOpcao = "<?php echo $db_opcao; ?>";

		if (oRetorno.status == 1 && dbOpcao == 3) {
			location.href = 'con1_cadcontabancaria003.php';
			alert(sMensagem);
			oGridPosicoes.clearAll(true);
			return false;
		} else {
			alert(sMensagem);
			oGridPosicoes.clearAll(true);
			return false;
		}
    }

	function habilitarCampos(value)
	{
		if (value == 3) {
			document.getElementById('nroseqaplicacao').style.display = '';
		} else {
			document.getElementById('nroseqaplicacao').style.display = 'none';
			document.form1.db83_nroseqaplicacao.value = '';
		}
	}

	function alignForm() 
	{
		document.querySelectorAll('input[type="text"]').forEach(input => {
    		input.size = 25;
		});

		document.getElementById('db83_tipoconta').style.width = '100%';
		document.getElementById('sDescricaoRecurso').style.width = '66.3%';
		document.getElementById('c206_objetoconvenio').style.width = '66.3%';


		const valoresPermitidos = ["570", "571", "572", "575", "631", "632", "633", "636", "665", "700", "701", "702", "703"];
		const valoresPermitidos2 = ["574", "634", "754"];
		const codigoRecurso = document.getElementById('iCodigoRecurso').value.substr(1,3);

		if (valoresPermitidos.includes(codigoRecurso) && document.getElementById('db83_numconvenio').value != '') {
			document.getElementById('idconvenio').style.display = '';
		} 
		
		if (valoresPermitidos2.includes(codigoRecurso) && document.getElementById('db83_codigoopcredito').value != '') {
			document.getElementById('idnumerocontratoopc').style.display = '';
		}

		window.onload = function() {
		var valorInicial = '<?php echo $db83_tipoconta; ?>';
		
		if (valorInicial) {
			habilitarCampos(valorInicial);
		}
		};

		
	} 
	function js_cleanScreen() 
	{
		location.reload();
    } 
	function js_pesquisaRecurso(lMostraWindow) 
	{
		if (lMostraWindow) {
			var sUrl = 'func_orctiporec.php?funcao_js=parent.js_preencheRecurso|o15_codigo|o15_descr';
			js_OpenJanelaIframe('','db_iframe_recurso',sUrl,'Pesquisa',true,'0');
		} else {

			if($("iCodigoRecurso").value != ''){
				var sUrl = 'func_orctiporec.php?pesquisa_chave='+$("iCodigoRecurso").value+'&funcao_js=parent.js_completaRecurso';
				js_OpenJanelaIframe('','db_iframe_recurso',sUrl,'Pesquisa',false);
			} else {
				$("sDescricaoRecurso").value = '';
			}
		}
	}
	function js_preencheRecurso(iCodigoRecurso, sDescricaoRecurso) 
	{
		const valoresPermitidos = ["570", "571", "572", "575", "631", "632", "633", "636", "665", "700", "701", "702", "703"];
		const valoresPermitidos2 = ["574", "634", "754"];
		const codigoRecurso = iCodigoRecurso.substr(1,3);

		document.getElementById('idnumerocontratoopc').style.display = 'none';
		document.getElementById('idconvenio').style.display = 'none';

		$('db83_numconvenio').value = ''; 
		$('c206_objetoconvenio').value = ''; 

		$('db83_codigoopcredito').value = ''; 
		$('op01_numerocontratoopc').value = ''; 
		$('op01_dataassinaturacop').value = ''; 

		if (valoresPermitidos.includes(codigoRecurso)) {
			document.getElementById('idconvenio').style.display = '';
			document.getElementById('idnumerocontratoopc').style.display = 'none';
			
		} 
		
		if (valoresPermitidos2.includes(codigoRecurso) ) {
			document.getElementById('idnumerocontratoopc').style.display = '';
			document.getElementById('idconvenio').style.display = 'none';
			
		}

		$('iCodigoRecurso').value    = iCodigoRecurso;
		$('sDescricaoRecurso').value = sDescricaoRecurso;
		db_iframe_recurso.hide();
	}
	function js_completaRecurso(sDescricaoRecurso, lErro) 
	{
		
		if (!lErro) {
			$('sDescricaoRecurso').value = sDescricaoRecurso;

			const valoresPermitidos = ["570", "571", "572", "575", "631", "632", "633", "636", "665", "700", "701", "702", "703"];
			const valoresPermitidos2 = ["574", "634", "754"];
			const codigoRecurso = $("iCodigoRecurso").value.substr(1,3);

			document.getElementById('idnumerocontratoopc').style.display = 'none';
			document.getElementById('idconvenio').style.display = 'none';

			$('db83_numconvenio').value = ''; 
			$('c206_objetoconvenio').value = ''; 

			$('db83_codigoopcredito').value = ''; 
			$('op01_numerocontratoopc').value = ''; 
			$('op01_dataassinaturacop').value = '';

			if (valoresPermitidos.includes(codigoRecurso)) {
				document.getElementById('idconvenio').style.display = '';
				
			} 
			
			if (valoresPermitidos2.includes(codigoRecurso) ) {
				document.getElementById('idnumerocontratoopc').style.display = '';
				 
			}
		} else {
			$('iCodigoRecurso').value    = '';
			$('sDescricaoRecurso').value = sDescricaoRecurso;
		}
	}

	alignForm();

	
</script>