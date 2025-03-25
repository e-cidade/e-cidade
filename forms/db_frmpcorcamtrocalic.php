<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

$clrotulo = new rotulocampo;
$clrotulo->label("l20_regata");
$clrotulo->label("l20_interporrecurso");
$clrotulo->label("l20_descrinterporrecurso");

db_app::load("estilos.css, grid.style.css");
db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");


?>

<head>
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
</head>

<form name="form1" method="post" action="lic1_pcorcamtroca001.php">
	<center>
		<h2 style="margin-bottom: 0px;margin-top: 35px">Julgamento de Itens da Licitação Nº <?= $l20_codigo ?></h2>
		<table border="0" align="center">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
					<? if ($lListaModelos) { ?>
						<fieldset>
							<legend align="left">
								<b>Geração de Atas</b>
							</legend>
							<table>
								<tr id="modelotemplate">
									<td>
										<b>Modelos:</b>
									</td>
									<td>
										<?
										db_selectrecord('documentotemplateata', $rsModelos, true, $db_opcao);
										?>
									</td>
								</tr>
							</table>
						</fieldset>
					<? } ?>
				</td>
			</tr>
			<tr align="center">
				<td nowrap align="center">
					<?
					db_input('pc20_codorc',    8, "", true, 'hidden', 3);
					db_input('pc21_orcamforne', 8, "", true, 'hidden', 3);
					db_input('l20_codigo',     8, "", true, 'hidden', 3);
					?>
					<fieldset>
						<legend align="left">
							<b>Julgamento</b>
						</legend>
						<table style="width:100%">
							<tr>
								<td style="width:12%">
									<b>Data de julgamento:</b>
								</td>
								<td nowrap title="">
									<?=
									db_inputdata('dtjulgamento', '', '', '', true, 'text', $db_opcao, "");
									?>
								</td>
							</tr>
						</table>

					</fieldset>
					<fieldset id="anexoFiedset">
						<legend align="left">
							<b>Adicionar Documento</b>
						</legend>
						<table style="width:100%">
							<tr style="display: flex; justify-content: flex-end; width: 100%;">
							<tr style="display: flex; justify-content: center; width: 100%;">
								<td style="text-align: right; flex-shrink: 0;">
									<b>Ata de Julgamento:</b>
								</td>
								<td style="display: flex; align-items: center; padding-left: 10px;">
									<div id="ctnImportacao"></div>
									<input name="salvar" type="button" value="Salvar" onclick="js_salvarDocumento();" style="margin-left: 10px;">
								</td>
							</tr>

						</table>
						<fieldset style="width: 750px;">
							<legend><b>Documento Adicionado</b></legend>
							<div style="width: 750px;" id="ctnDbGridDocumentos"></div>
						</fieldset>
					</fieldset>



					</fieldset>
					<fieldset>
						<legend align="left">
							<b>Lista de Itens</b>
						</legend>
						<table>
							<tr>
								<td>
									<iframe name="iframe_solicitem" id="solicitem" marginwidth="0" marginheight="0"
										frameborder="0" src="lic1_trocpcorcamtroca.php" width="1100px" height="300px">
									</iframe>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>

		</table>
		<br>

		<table>

			<br>
			<table border="0">
				<tr>
					<td nowrap>
						<input name="confirmar" type="submit" value="Confirmar" onClick="return validaAnexo();"
							<?= ($db_opcao == 3 ? "disabled" : "") ?>
							<?= ($disable_confirmar == true ? "disabled" : "") ?>>
					</td>
					<td nowrap>
						<input name="voltar" type="button" value="Voltar"
							onClick="CurrentWindow.corpo.document.location.href = 'lic1_orcamlancval001.php?pc20_codorc=<?= $pc20_codorc ?>&lic=true&l20_codigo=<?= $l20_codigo ?>';">
					</td>
				</tr>
			</table>
	</center>
	<?
	db_input("itens", 500, "", true, "hidden", 3);
	?>
</form>

<script>
	var sUrlRpc = 'lic1_anexospncp.RPC.php';

	var fiedsetExibido = false;
	var alertExibido = false; // Variável para controlar se o alert já foi exibido
	var valorAnterior = document.getElementById('dtjulgamento').value; // Armazena o valor inicial do campo

	mostraFiedset(); // Chama a função na inicialização

	// Verifica mudanças no valor do campo a cada 500ms
	setInterval(() => {
		const dtjulgamento = document.getElementById('dtjulgamento');
		if (dtjulgamento.value !== valorAnterior) {
			valorAnterior = dtjulgamento.value; // Atualiza o valor anterior
			mostraFiedset(); // Chama a função quando o valor muda
		}
	}, 500);
	<?php

		$rsCodtribunal = db_query("SELECT l03_pctipocompratribunal FROM liclicita
		INNER JOIN cflicita ON l20_codtipocom = l03_codigo
		WHERE l20_codigo = $l20_codigo;");

		$codtribunal = db_utils::fieldsMemory($rsCodtribunal, 0);
		$codtribunal = $codtribunal->l03_pctipocompratribunal;
		?>

		var codTribunal = <?php echo json_encode($codtribunal); ?>;
			
			//console.log("Código Tribunal:", codTribunal);


	function mostraFiedset() {
		const  codTribunal = <?php echo json_encode($codtribunal); ?>;
		const l20_anousu = <?= json_encode($l20_anousu) ?>;
		const l20_recdocumentacao = <?= json_encode($l20_recdocumentacao) ?>;
		//const dtjulgamento = new Date(document.getElementById('dtjulgamento').value);
		const dataLimite = new Date('2024-12-31');
		let data = document.getElementById('dtjulgamento').value;
   
		let dtjulgamento = null;
		if (data) {
			let partes = data.split('/');
			if (partes.length === 3) {
				data = `${partes[2]}-${partes[1]}-${partes[0]}`;
				dtjulgamento = new Date(data);
			}
		}
		const tribunalPermitido = ![54 , 100, 101, 102, 103].includes(parseInt(codTribunal));
		if (tribunalPermitido && (l20_anousu >= 2025 || new Date(l20_recdocumentacao) > dataLimite)) {
        document.getElementById('anexoFiedset').style.display = '';
        fiedsetExibido = true;
    } else {
        document.getElementById('anexoFiedset').style.display = 'none';
        fiedsetExibido = false;
    }
	
    if (tribunalPermitido && dtjulgamento > dataLimite && !fiedsetExibido) {
        if (!alertExibido) {
            alert('Usuário: Para as licitações julgadas no exercício de 2025, é necessário anexar a Ata de Julgamento');
            alertExibido = true;
        }
        document.getElementById('anexoFiedset').style.display = '';
        oGridDocumentos.renderRows();
        fiedsetExibido = true;
    }
	}


	// Verificar as condições

	function js_confirmar() {
		document.form1.itens.value = iframe_solicitem.document.form2.itens.value;

		document.form1.submit();
	}


	function js_liberar() {
		document.form1.confirmar.disabled = 0;
	}

	//INICIA A PARTE DE ANEXOS 
	var oGridDocumentos = new DBGrid('gridDocumentos');

	oGridDocumentos.nameInstance = "oGridDocumentos";

	oGridDocumentos.setHeight(50);
	oGridDocumentos.setCellAlign(["center", "center", "center", "center"]);
	oGridDocumentos.setCellWidth(["10%", "60%", "20%", "20%"]);
	oGridDocumentos.setHeader(["Código", "Descrição", "Download", "Ação"]);
	oGridDocumentos.show($('ctnDbGridDocumentos'));

	var oWindowAuxVinculos = '';
	var oGridVinculos = '';
	var sRpc = 'lic1_pcrocamanexos.RPC.php';
	var lTemInconsistencia = false;

	var oFileUpload = new DBFileUpload({
		callBack: retornoEnvioArquivo
	});
	oFileUpload.show($('ctnImportacao'));
	var inputFile = document.querySelector('input[type="file"]');
	if (inputFile) {
		inputFile.setAttribute('accept', 'application/pdf');
	}

	/**
	 * Função de retorno ao selecionar um arquivo para upload
	 * Valida se foi registrado algum erro ou se o arquivo possui uma extensão inválida
	 * @param oRetorno
	 * @returns {boolean}
	 */
	function retornoEnvioArquivo(oRetorno) {

		if (oRetorno.error) {

			alert(oRetorno.error);
			$('btnProcessar').disabled = true;
			return false;
		}

	}

	function js_salvarDocumento() {
		let codorc = $('pc20_codorc').value;
		var oParam = new Object();
		oParam.sExecuta = 'processar';
		oParam.codorc = codorc;
		oParam.sNomeArquivo = oFileUpload.file;
		oParam.arquivo = oFileUpload.filePath;
		if (oParam.sNomeArquivo) {
			const extension = oParam.sNomeArquivo.split('.').pop().toLowerCase();
			if (extension !== 'pdf') {
				alert("Arquivo inválido! Somente arquivos PDF são permitidos.");
				return false;
			}
		}

		if (!oParam.sNomeArquivo) {
			alert('Selecione um arquivo a ser salvo!');
			return false;
		}

		oParam.sCaminhoArquivo = oFileUpload.filePath;
		js_divCarregando('Aguarde... Salvando Documento', 'msgbox');
		var oAjax = new Ajax.Request(
			sRpc, {
				parameters: 'json=' + Object.toJSON(oParam),
				method: 'post',
				asynchronous: false,
				onComplete: js_retornoSalvarDocumento
			});
	}

	function js_retornoSalvarDocumento(oAjax) {
		js_removeObj("msgbox");
		var oRetorno = eval('(' + oAjax.responseText + ')');
		if (oRetorno.status == 1) {
			js_getDocumento();
			document.querySelector('.inputUploadFile').value = '';
			oFileUpload.file = null;
			oFileUpload.filePath = null;
		} else {
			alert(oRetorno.message.urlDecode());
		}
	}


	js_getDocumento();

	function js_getDocumento() {
		const dataLimite = new Date('2024-12-31');

		let codorc = $('pc20_codorc').value;
		var oParametros = {};
		oParametros.sExecuta = 'getDocumento';
		oParametros.codorc = codorc;

		var oAjaxRequest = new AjaxRequest(sRpc, oParametros, js_retornogetDocumento);

		oAjaxRequest.execute();
	}

	function js_retornogetDocumento(oRetorno, lErro) {

		oGridDocumentos.clearAll(true);

		oRetorno.dados.forEach(function(item) {
			var aLinha = [];
			aLinha[0] = item.pc98_sequencial;
			aLinha[1] = item.pc98_nomearquivo;
			aLinha[2] = `<input type="button" value="Download" onclick="js_Download(${item.pc98_sequencial});">`;
			aLinha[3] = `<input type="button" value="Excluir" onclick="js_excluirDocumento(${item.pc98_sequencial});">`;
			oGridDocumentos.addRow(aLinha);
		});

		oGridDocumentos.renderRows();
	}

	function js_excluirDocumento(sequencial) {

		if (!confirm('Confirma a Exclusão do Documento?')) {
			return false;
		}

		var oParametros = {};
		oParametros.sExecuta = 'excluirDocumentos';
		oParametros.sequencial = sequencial;

		var oAjaxRequest = new AjaxRequest(sRpc, oParametros, js_retornoExcluirDocumento);

		oAjaxRequest.execute();
	}

	function js_retornoExcluirDocumento(oRetorno, lErro) {
		if (oRetorno.status == 1) {
			js_getDocumento();
			document.querySelector('.inputUploadFile').value = '';
			alert("Documento excluido com sucesso !");
		}
	}

	function js_Download(sequencial) {
		if (!confirm('Deseja realizar o Download do Documento?')) {
			return false;
		}
		let codorc = $('pc20_codorc').value;
		var oParametros = {};
		oParametros.sExecuta = 'download';
		oParametros.sequencial = sequencial;
		oParametros.codorc = codorc;


		var oAjaxRequest = new AjaxRequest(sRpc, oParametros, js_retornoDownload);
		oAjaxRequest.execute();
	}

	function js_retornoDownload(oRetorno, lErro) {
		if (oRetorno.status == 2) {
			alert("Não foi possível carregar o documento:\n " + oRetorno.message);
		} else {
			// Abre o arquivo no browser para download
			window.open("db_download.php?arquivo=" + oRetorno.nomearquivo);

		}
	}


	function validaAnexo() {
		mostraFiedset();
		let codorc = $('pc20_codorc').value;
		var oParam = new Object();
		oParam.sExecuta = 'VerificaAnexo';
		oParam.codorc = codorc;

		// Executa a requisição AJAX
		var oAjax = new Ajax.Request(
			sRpc, {
				parameters: 'json=' + Object.toJSON(oParam),
				method: 'post',
				asynchronous: false, // Sincrono para esperar o retorno antes de continuar
				onComplete: function(oRetorno) {
					// Chama a função de retorno de validação
					var valida = js_retornoValidaAnexo(oRetorno);
					if (!valida) {
						return false; // Impede o envio se a validação falhar
					} else {
						// Se a validação passar, chama a função de confirmação
						js_confirmar();
						return true;
					}
				}
			}
		);
	}


	
	function js_retornoValidaAnexo(oRetorno) {
    var responseJSON = JSON.parse(oRetorno.responseText);
    var anexo = responseJSON.anexo[0];

    const l20_anousu = <?php echo json_encode($l20_anousu); ?>;
    const l20_recdocumentacao = <?php echo json_encode($l20_recdocumentacao); ?>;
    const anexoAdicionado = anexo;
    const tribunalNaoPermitido = ![54, 100, 101, 102, 103].includes(parseInt(codTribunal));

    let data = document.getElementById('dtjulgamento').value;

    let dtjulgamento = null;
    if (data) {
        let partes = data.split('/');
        if (partes.length === 3) {
            data = `${partes[2]}-${partes[1]}-${partes[0]}`;
            dtjulgamento = new Date(data);
        }
    }

    const dataLimite = new Date('2024-12-31');

    if ((tribunalNaoPermitido && (l20_anousu >= 2025 || new Date(l20_recdocumentacao) > dataLimite)) && !anexoAdicionado) {
        alert("Usuário: Para as licitações incluídas e julgadas no exercício de 2025, é necessário anexar a Ata de Julgamento.");
        return false;
    }

    if (tribunalNaoPermitido && dtjulgamento > dataLimite && !anexoAdicionado) {
        alert("Usuário: Para as licitações incluídas e julgadas no exercício de 2025, é necessário anexar a Ata de Julgamento.");
        return false;
    }

    return true;
}

</script>