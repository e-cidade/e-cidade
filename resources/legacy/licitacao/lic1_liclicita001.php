<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
Expandir
message.txt
20 KB
lic1_liclicita001.php
?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaproc_classe.php");
require_once("classes/db_pccflicitapar_classe.php");
require_once("classes/db_pccflicitanum_classe.php");
require_once("classes/db_pccfeditalnum_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_liclicitasituacao_classe.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_liclocal_classe.php");
require_once("classes/db_liccomissao_classe.php");
require_once("classes/db_condataconf_classe.php");
require_once("classes/db_liccomissaocgm_classe.php");
require_once("classes/db_liccategoriaprocesso_classe.php");



include("classes/db_decretopregao_classe.php");

db_postmemory($HTTP_POST_VARS);

$clliclicita         = new cl_liclicita;
$clliclicitaproc     = new cl_liclicitaproc;
$clpccflicitapar     = new cl_pccflicitapar;
$clpccflicitanum     = new cl_pccflicitanum;
$clpccfeditalnum     = new cl_pccfeditalnum;
$cldb_usuarios       = new cl_db_usuarios;
$clliclicitasituacao = new cl_liclicitasituacao;
$clcflicita          = new cl_cflicita;
$cldecretopregao     = new cl_decretopregao;
$clliccomissaocgm     = new cl_liccomissaocgm;
$cliccategoriaprocesso = new cl_liccategoriaprocesso;

$db_opcao = 1;
$db_botao = true;

$oParamNumManual = db_query("select * from licitaparam where l12_instit = " . db_getsession("DB_instit"));
$oParamNumManual = db_utils::fieldsmemory($oParamNumManual, 0);
$l12_numeracaomanual = $oParamNumManual->l12_numeracaomanual;

if ($l12_numeracaomanual == 't' && !isset($incluir)) {
	$instit     = db_getsession("DB_instit");
	$anousu     = db_getsession("DB_anousu");

	$result_numgeral = $clpccflicitanum->sql_record($clpccflicitanum->sql_query_file(null, "*", null, "l24_instit=$instit and l24_anousu=$anousu"));
	db_fieldsmemory($result_numgeral, 0);
	$l20_edital = $l24_numero + 1;

	$result_numedital = $clpccfeditalnum->sql_record($clpccfeditalnum->sql_query_file(null, "l47_numero", null, "l47_instit=$instit and l47_anousu=$anousu and l47_timestamp = (select max(l47_timestamp) from pccfeditalnum)"));
	db_fieldsmemory($result_numedital, 0);
	$l20_nroedital = $l47_numero + 1;
}


if (isset($incluir)) {

	$oPost = db_utils::postmemory($_POST);

	$sqlerro    = false;
	// ID's do l03_pctipocompratribunal com base no l20_codtipocom escolhido pelo usurio
	$sSql = $clcflicita->sql_query_file((int)$oPost->l20_codtipocom, 'distinct(l03_pctipocompratribunal)');
	$aCf = db_utils::getColectionByRecord($clcflicita->sql_record($sSql));
	$iTipoCompraTribunal = (int)$aCf[0]->l03_pctipocompratribunal;

	//Casos em que o Tipo de Licitao e Natureza do Procedimento devem ser verificados
	$aTipoLicNatProc = array(50, 48, 49, 53, 52, 54);

	$erro_msg = '';

	/*
    * Verifica��o de erros ao inserir numera��es manualmente.
  	*/
	if ($l12_numeracaomanual == 't') {
		$anousu = db_getsession("DB_anousu");
		$instit     = db_getsession("DB_instit");
		$oProcessoLicitatorio = db_query("select * from liclicita where l20_edital = $l20_edital and l20_anousu = $anousu and l20_instit = $instit;");
		if (pg_num_rows($oProcessoLicitatorio) > 0) {
			$erro_msg .= "J� existe licita��o com o processo licitat�rio n�mero $l20_edital\n\n";
			$sqlerro = true;
		}

		$oNumeracao = db_query("select * from liclicita where l20_numero = $l20_numero and l20_anousu = $anousu and l20_instit = $instit and l20_codtipocom = $l20_codtipocom;");
		if (pg_num_rows($oNumeracao) > 0) {
			$erro_msg .= "J� existe licita��o com a modalidade $l20_codtipocom numera��o $l20_numero\n\n";
			$sqlerro = true;
		}

		if (in_array($modalidade_tribunal, array(48, 49, 50, 52, 53, 54))) {
			$oEdital = db_query("select * from liclicita where l20_anousu = $anousu and l20_instit = $instit and l20_nroedital = $l20_nroedital;");
			if (pg_num_rows($oEdital) > 0) {
				$erro_msg .= "J� existe licita��o com o edital $l20_nroedital\n\n";
				$sqlerro = true;
			}
		}
	}


	/*
    Verifica se os Campos "Tipo de Licitao", "Natureza do Procedimento" no foram selecionados.
  */
	if (in_array($iTipoCompraTribunal, $aTipoLicNatProc)) {
		if ($oPost->modalidade_tribunal != 51) {
			if ($oPost->l20_tipliticacao == '0' || empty($oPost->l20_tipliticacao)) {
				$erro_msg .= 'Campo Tipo de Licitacao nao informado\n\n';
				$sqlerro = true;
			}
		}
		if ($oPost->l20_tipnaturezaproced == '0' || empty($oPost->l20_tipnaturezaproced)) {
			$erro_msg .= 'Campo Natureza do Procedimento nao informado\n\n';
			$sqlerro = true;
		}
	}
	$oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));
	$l12_pncp = $oParamLicicita[0]->l12_pncp;

	// if ($l20_leidalicitacao == 1 && $l12_pncp == 't') {
	// 	if ($oPost->l212_codigo == 0) {
	// 		$erro_msg .= 'Campo Amparo Legal n�o informado\n\n';
	// 		$sqlerro = true;
	// 	}
	// }

	if ($l20_categoriaprocesso == 0 && $l12_pncp == 't') {
		$erro_msg .= 'Campo categoria do processo n�o informado\n\n';
		$sqlerro = true;
	}

	//verifica modalidades e presencial
	$sSql = $clcflicita->sql_query_file(null, 'l03_presencial', '', "l03_codigo = $oPost->l20_codtipocom");
	$aCf = db_utils::getColectionByRecord($clcflicita->sql_record($sSql));
	$sPresencial = $aCf[0]->l03_presencial;

	if ($sPresencial == 't' && $l12_pncp == 't' && $l20_leidalicitacao == 1) {
		if ($oPost->l20_justificativapncp == '' || $oPost->l20_justificativapncp == null) {
			$erro_msg .= 'Campo Justificativa PNCP n�o informado\n\n';
			$sqlerro = true;
		}
	}

	/*
    Verifica se o Campo "Natureza do Objeto" no foi selecionado.
  */
	if ($oPost->modalidade_tribunal != 51) {
		if ($oPost->l20_naturezaobjeto == '0' || empty($oPost->l20_naturezaobjeto)) {
			$erro_msg .= 'Campo Natureza do Objeto nao informado\n\n';
			$sqlerro = true;
		}
	}

	if ($oPost->modalidade_tribunal == 100 || $oPost->modalidade_tribunal == 101 || $oPost->modalidade_tribunal == 102 || $oPost->modalidade_tribunal == 103) {
		if ($oPost->l20_razao == '') {
			$erro_msg .= 'Campo Raz�o n�o informado';
			$sqlerro = true;
		}

		if ($oPost->l20_justificativa == '') {
			$erro_msg .= 'Campo Justificativa n�o informado';
			$sqlerro = true;
		}

		if ($oPost->l20_tipoprocesso == '') {
			$erro_msg .= 'Campo Tipo de Processo n�o informado';
			$sqlerro = true;
		}
	}

	/*
   Valida��es dos membros da licita��o
   48 - Convite
   49 - Tomada de Pre�os
   50 - Concorr�ncia
   52 - Preg�o presencial
   53 - Preg�o eletr�nico
   54 - Leil�o
  */
	if ($oPost->modalidade_tribunal == 48 || $oPost->modalidade_tribunal == 49 || $oPost->modalidade_tribunal == 50 || $oPost->modalidade_tribunal == 52 || $oPost->modalidade_tribunal == 53 || $oPost->modalidade_tribunal == 54) {
		if ($respConducodigo == "") {
			$erro_msg .= 'Respons�vel pela condu��o do processo n�o informado\n\n';
			$nomeCampo = "respConducodigo";
			$sqlerro = true;
		}
		if ($respAbertcodigo == "") {
			$erro_msg .= 'Respons�vel pela abertura do processo n�o informado\n\n';
			$nomeCampo = "respAbertcodigo";
			$sqlerro = true;
		}
		if ($respEditalcodigo == "") {
			$erro_msg .= 'Respons�vel pela emiss�o do edital n�o informado\n\n';
			$nomeCampo = "respEditalcodigo";
			$sqlerro = true;
		}

		if ($oPost->l20_naturezaobjeto == 1) {
			if ($respObrascodigo == "") {
				$erro_msg .= 'Respons�vel pelos or�amentos, obras e servi�os n�o informado\n\n';
				$nomeCampo = "respObrascodigo";
				$sqlerro = true;
			}
		}
		if ($oPost->modalidade_tribunal == 54) {
			if ($respAvaliBenscodigo == "") {
				$erro_msg .= 'Respons�vel pela avalia��o de bens n�o informado\n\n';
				$nomeCampo = "respAvaliBenscodigo";
				$sqlerro = true;
			}
		}

		/*
    	Verifica se o campo "Regime de execu��o" foi selecionado
  		*/
		if ($oPost->l20_naturezaobjeto == 1 || $oPost->l20_naturezaobjeto == 7) {
			if ($oPost->l20_regimexecucao == 0) {
				$erro_msg .= 'Campo Regime da Execu��o n�o selecionado\n\n';
				$sqlerro = true;
			}
		}
	} else if ($oPost->modalidade_tribunal == 100 || $oPost->modalidade_tribunal == 101 || $oPost->modalidade_tribunal == 102 || $oPost->modalidade_tribunal == 103) {
		if ($respAutocodigo == "") {
			$erro_msg .= 'Respons�vel pela condu��o do processo n�o informado\n\n';
			$nomeCampo = "respAutocodigo";
			$sqlerro = true;
		}
		if ($oPost->l20_naturezaobjeto == 1) {
			if ($respObrascodigo == "") {
				$erro_msg .= 'Respons�vel pelos or�amentos, obras e servi�os n�o informado\n\n';
				$nomeCampo = "respObrascodigo";
				$sqlerro = true;
			}
		}
	}

	db_inicio_transacao();

	$instit     = db_getsession("DB_instit");
	$anousu     = db_getsession("DB_anousu");
	$l20_anousu = db_getsession("DB_anousu");


	if (in_array(db_utils::fieldsMemory($clcflicita->sql_record($clcflicita->sql_query($l20_codtipocomdescr, "distinct l03_pctipocompratribunal")), 0)->l03_pctipocompratribunal, array("52", "53"))) {
		$result = $cldecretopregao->sql_record($cldecretopregao->sql_query('', '*'));
		if ($cldecretopregao->numrows == 0) {
			$erro_msg = "N�o h� decreto preg�o";
			$sqlerro = true;
		}
	}
	//verifica se as duas modalidades esto configuradas.
	$result_modalidade = $clpccflicitapar->sql_record($clpccflicitapar->sql_query_modalidade(null, "*", null, "l25_codcflicita = $l20_codtipocom and l25_anousu = $anousu and l03_instit = $instit"));
	if ($clpccflicitapar->numrows == 0) {
		$erro_msg = "Verifique se esta configurado a numera��o de licita��o por modalidade.";
		$sqlerro = true;
	}

	$result_numgeral = $clpccflicitanum->sql_record($clpccflicitanum->sql_query_file(null, "*", null, "l24_instit=$instit and l24_anousu=$anousu"));
	if ($clpccflicitanum->numrows == 0) {
		$erro_msg = "Verifique se esta configurado a numera��o de licita��o por edital.";
		$sqlerro = true;
	}

	$result_numedital = $clpccfeditalnum->sql_record($clpccfeditalnum->sql_query_file(null, "l47_numero", null, "l47_instit=$instit and l47_anousu=$anousu and l47_timestamp = (select max(l47_timestamp) from pccfeditalnum)"));
	if (!$clpccfeditalnum->numrows && in_array($modalidade_tribunal, array(48, 49, 50, 52, 53, 54))) {
		$erro_msg = "Verifique se esta configurado a numera��o do edital por licita��o.";
		$sqlerro = true;
	}
	if ($l20_codtipocom == 99) {
		$erro_msg = "Selecione uma modalidade para a licita��o.";
		$sqlerro = true;
	}
	if ($oPost->modalidade_tribunal != 100 && $oPost->modalidade_tribunal != 101 && $oPost->modalidade_tribunal != 102 && $oPost->modalidade_tribunal != 103) {
		if ($l20_leidalicitacao == 1) {
			if ($l20_mododisputa == 0) {
				$erro_msg = "Selecione um modo de disputa para a licita��o.";
				$nomeCampo = "l20_mododisputa";
				$sqlerro = true;
			}
		}
	}

	//numeracao por modalidade
	if ($sqlerro == false) {
		if ($clpccflicitapar->numrows > 0) {
			db_fieldsmemory($result_modalidade, 0, 2);
			if ($l12_numeracaomanual != 't') {
				$l20_numero = $l25_numero + 1;
			}
		} else {
			$erro_msg = "Configure a numera��o de licita��o por modalidade.";
			$sqlerro = true;
		}

		//numera��o geral

		if ($clpccflicitanum->numrows > 0) {
			db_fieldsmemory($result_numgeral, 0);
			if ($l12_numeracaomanual != 't') {
				$l20_edital = $l24_numero + 1;
			}
		} else {
			$erro_msg = "Configure a numera��o de licita��o por edital.";
			$sqlerro = true;
		}

		if (!in_array($modalidade_tribunal, array(48, 49, 50, 52, 53, 54))) {
			$l20_nroedital = "null";
			$oPost->l20_nroedital = "null";
		}

		if (db_getsession('DB_anousu') >= 2020) {

			$aModalidades = array(48, 49, 50, 52, 53, 54);

			if (db_getsession('DB_anousu') >= 2021) {
				array_push($aModalidades, 102, 103);
			}

			if ($clpccfeditalnum->numrows) {
				db_fieldsmemory($result_numedital, 0);

				if (in_array($modalidade_tribunal, $aModalidades)) {
					if ($l12_numeracaomanual != 't') {
						$l20_nroedital = $l47_numero + 1;
					}
				}
			} else {
				if (in_array($modalidade_tribunal, $aModalidades)) {
					$erro_msg = "Configure a numera��o do edital.";
					$sqlerro = true;
				}
			}
		}

		//verifica se ja existe licitacao por modalidade
		$sqlveriflicitamod = $clpccflicitapar->sql_query_mod_licita(null, "l25_numero as xx", null, "l20_instit=$instit and l25_anousu=$anousu and l20_codtipocom=$l20_codtipocom and l20_numero=$l20_numero and l20_anousu=$anousu");
		$result_verif_licitamod = $clpccflicitapar->sql_record($sqlveriflicitamod);

		if ($clpccflicitapar->numrows > 0) {
			$erro_msg = "Ja existe licita��o numero $l20_numero.Verificar o cadastro por modalidade.";
			$sqlerro = true;
		}

		//verifica se existe licitao por edital
		$result_verif_licitaedital = $clpccflicitanum->sql_record($clpccflicitanum->sql_query_edital(null, "l20_edital as yy", null, "l20_instit=$instit and l25_anousu=$anousu and l20_edital= $l20_edital and l20_anousu=$anousu"));

		if ($clpccflicitanum->numrows > 0) {
			$erro_msg = "Ja existe licita��o numero $l20_edital.Verificar numera��o por edital.";
			$sqlerro = true;
		}

		//verifica se existe numero do edital

		if ($l20_nroedital) {
			$result_verif_editalnum = $clpccfeditalnum->sql_record($clpccfeditalnum->sql_query_edital(null, "l20_edital as yy", null, "l20_instit=$instit and l47_anousu=$anousu and l20_nroedital= $l20_nroedital and l20_anousu=$anousu"));
			if ($clpccfeditalnum->numrows > 0) {
				$erro_msg = "Ja existe edital da licita��o com numero $l47_edital.Verificar numera��o por edital.";
				$sqlerro = true;
			}
		}

		/**
		 * Verificar Encerramento Periodo Patrimonial
		 */
		if (!empty($l20_dtpubratificacao)) {
			$clcondataconf = new cl_condataconf;
			if (!$clcondataconf->verificaPeriodoPatrimonial($l20_dtpubratificacao)) {
				$erro_msg = $clcondataconf->erro_msg;
				$sqlerro  = true;
			}
		}

		if (in_array($modalidade_tribunal, [52, 53]) && $l20_leidalicitacao == "2") {

			$verifica = $clliclicita->verificaMembrosModalidade("pregao", $l20_equipepregao);
			if (!$verifica) {
				$erro_msg = "Para as modalidades Pregao presencial e Pregao eletronico  necessario\nque a Comissao de Licitacao tenham os tipos Pregoeiro e Membro da Equipe de Apoio";
				$sqlerro = true;
			}
		} else if (in_array($modalidade_tribunal, [48, 49, 50]) && $l20_leidalicitacao == "2") {

			$verifica = $clliclicita->verificaMembrosModalidade("outros", $l20_equipepregao);
			if (!$verifica) {
				$erro_msg = "Para as modalidades Tomada de Pre�os, Concorrencia e Convite  necessario\nque a Comissao de Licitacao tenham os tipos Secretario, Presidente e Membro da Equipe de Apoio";
				$sqlerro = true;
			}
		}

		//VALIDA�OES DE DATAS
		$dataaber = DateTime::createFromFormat('d/m/Y', $l20_dataaber);
		$datacria = DateTime::createFromFormat('d/m/Y', $l20_datacria);
		$dataaberproposta = DateTime::createFromFormat('d/m/Y', $l20_dataaberproposta);
		$aMod = array("100","101","102","103");

		if (!in_array($modalidade_tribunal, $aMod)) {
			if ($dataaberproposta < $dataaber) {
				$erro_msg = "A data informada no campo Abertura das Propostas deve ser  superior a Data Edital/Convite.";
				$nomeCampo = "l20_dataaberproposta";
				$sqlerro = true;
			}

			if ($dataaber < $datacria) {
				$erro_msg = "A data inserida no campo Data Emis/Alt Edital/Convite dever� ser maior ou igual a data inserida no campo Data Abertura Proc. Adm.";
				$nomeCampo = "l20_dataaber";
				$sqlerro = true;
			}

			if ($dataaberproposta < $datacria) {
				$erro_msg = "A data inserida no campo Data Abertura Proposta dever� ser maior ou igual a data inserida no campo Data Abertura Proc. Adm.";
				$nomeCampo = "l20_dataaberproposta";
				$sqlerro = true;
			}
		}

		if(in_array($modalidade_tribunal, $aMod) && $l20_leidalicitacao == 1){
			if($l20_tipoprocesso == "5" || $l20_tipoprocesso == "6"){
				$l20_criterioadjudicacao = $criterioadjudicao_dispensainexibilidade;
			}
			else {
				$l20_criterioadjudicacao = "null";
			}
		}
		if(($l20_tipoprocesso == '5' || $l20_tipoprocesso == '6') && $l20_criterioadjudicacaodispensa == '0'){

			$erro_msg = 'O campo crit�rio de adjudica��o � de preenchimento obrigat�rio"';
			$nomeCampo = 'l20_criteriodeadjudicacaodispensa';
			$sqlerro = true;
		}

		if($l20_tipoprocesso == '1' && $l20_dispensaporvalor == "" && $l20_leidalicitacao == '1'){
            $erro_msg = 'O campo Dispensa por valor � de preenchimento obrigat�rio';
            $nomeCampo = 'l20_dispensaporvalor';
            $sqlerro = true;
        }

		if($l20_tipoprocesso == "5" || $l20_tipoprocesso == "6"){
			$clliclicita->l20_usaregistropreco = "t";
		}
		
		if ($sqlerro == false) {
			$clliclicita->l20_amparolegal      	  =  $oPost->l212_codigo;
			$clliclicita->l20_numero      	  =  $l20_numero;
			$clliclicita->l20_edital      	  =  $l20_edital;
			if ($anousu >= 2020) {
				if (in_array($modalidade_tribunal, $aModalidades)) {
					$clliclicita->l20_nroedital      	=  $l20_nroedital;
				} else {
					$l20_nroedital = "null";
					$oPost->l20_nroedital = "null";
					$clliclicita->l20_nroedital      	=  $l20_nroedital;
				}
				$clliclicita->l20_exercicioedital =  $anousu;
			}
			$clliclicita->l20_anousu      	  =  $anousu;
			$clliclicita->l20_licsituacao = '0';
			$clliclicita->l20_instit      = db_getsession("DB_instit");
			$clliclicita->l20_tipliticacao = $l20_tipliticacao;

			if($l20_criterioadjudicacaodispensa != '0'){
                $clliclicita->l20_criterioadjudicacao   = $l20_criterioadjudicacaodispensa;
            }else{
                $clliclicita->l20_criterioadjudicacao   = $l20_criterioadjudicacao;
            }
			$clliclicita->l20_justificativapncp = $l20_justificativapncp;
			$clliclicita->l20_categoriaprocesso = $l20_categoriaprocesso;
			$clliclicita->l20_receita = $l20_receita;
			$clliclicita->l20_dataaber = $l20_dataaber;
			$clliclicita->l20_datacria = $l20_datacria;
			$clliclicita->l20_recdocumentacao = $l20_dataaberproposta;
			$clliclicita->l20_dataaberproposta = $l20_dataaberproposta;
            $clliclicita->l20_horaaberturaprop = $l20_horaaberturaprop;
            $clliclicita->l20_horaencerramentoprop = $l20_horaencerramentoprop;
            $clliclicita->l20_dispensaporvalor = $l20_dispensaporvalor;
			$clliclicita->l20_lances = $l20_lances;

			if($l20_dispensaporvalor == 't'){
				$clliclicita->l20_cadinicial = '0';
			}else{
				$clliclicita->l20_cadinicial = '1';
			}
			
			$clliclicita->incluir(null, null);
			
			if ($clliclicita->erro_status == "0") {
				$erro_msg = $clliclicita->erro_msg;
				$sqlerro = true;
			}
		}
		
		if (!$sqlerro && $lprocsis == 's') {

			$clliclicitaproc->l34_liclicita    = $clliclicita->l20_codigo;
			$clliclicitaproc->l34_protprocesso = $l34_protprocesso;
			$clliclicitaproc->incluir(null);

			if ($clliclicitaproc->erro_status == 0) {
				$erro_msg = $clliclicitaproc->erro_msg;
				$sqlerro  = true;
			}
			
		}
		
		
		if ($sqlerro == false) {

			$l11_sequencial = '';
			$clliclicitasituacao->l11_id_usuario  = DB_getSession("DB_id_usuario");
			$clliclicitasituacao->l11_licsituacao = '0';
			$clliclicitasituacao->l11_liclicita   = $clliclicita->l20_codigo;
			$clliclicitasituacao->l11_obs         = "Licitacao em andamento.";
			$clliclicitasituacao->l11_data        = date("Y-m-d", DB_getSession("DB_datausu"));
			$clliclicitasituacao->l11_hora        = DB_hora();
			$clliclicitasituacao->incluir($l11_sequencial);

			$erro_msg = " Licitacao {$l03_descr} n�mero {$l20_numero} incluida com sucesso.";

			if ($clliclicitasituacao->erro_status == 0) {
				$erro_msg = $clliclicitasituacao->erro_msg;
				$sqlerro = true;
			}
			
			$codigo   = $clliclicita->l20_codigo;
			$tipojulg = $clliclicita->l20_tipojulg;

			if ($l12_numeracaomanual == 't') {

				/* Verifica��o da numera��o do processo licitat�rio cujo o seu subsequente n�o tenha sido utilizado
				    e atualiza��o na tabela respons�vel por fazer o controle desta numera��o  */
				do {
					$l20_edital = $l20_edital + 1;
					$oLicitacao = db_query("select * from liclicita where l20_anousu = $anousu and l20_instit = $instit and l20_edital = $l20_edital;");
					if (pg_num_rows($oLicitacao) == 0) {
						$clpccflicitanum->l24_numero = $l20_edital - 1;
						$clpccflicitanum->alterar_where(null, "l24_instit=$instit and l24_anousu=$anousu");
						break;
					}
				} while (1);

				/* Verifica��o da numera��o da licita��o cujo o seu subsequente n�o tenha sido utilizado
				    e atualiza��o na tabela respons�vel por fazer o controle desta numera��o  */

				do {
					$l20_numero = $l20_numero + 1;
					$oLicitacao = db_query("select * from liclicita where l20_numero = $l20_numero and l20_anousu = $anousu and l20_instit = $instit and l20_codtipocom = $l20_codtipocom;");
					if (pg_num_rows($oLicitacao) == 0) {
						$clpccflicitapar->l25_numero = $l20_numero - 1;
						$clpccflicitapar->alterar_where(null, "l25_codigo = $l25_codigo and l25_anousu = $anousu");
						break;
					}
				} while (1);

				/* Verifica��o da numera��o do edital cujo o seu subsequente n�o tenha sido utilizado
				    e atualiza��o na tabela respons�vel por fazer o controle desta numera��o  */

				do {
					$l20_nroedital = $l20_nroedital + 1;
					$oLicitacao = db_query("select * from liclicita where l20_anousu = $anousu and l20_instit = $instit and l20_nroedital = $l20_nroedital;");
					if (pg_num_rows($oLicitacao) == 0) {
						if (db_getsession('DB_anousu') >= 2020) {
							if (in_array($modalidade_tribunal, $aModalidades)) {
								$clpccfeditalnum->l47_numero = $l20_nroedital - 1;
								$clpccfeditalnum->l47_instit = db_getsession('DB_instit');
								$clpccfeditalnum->l47_anousu = db_getsession('DB_anousu');
								$clpccfeditalnum->incluir(null);
							}
						}
						break;
					}
				} while (1);
			} else {
				$clpccflicitapar->l25_numero = $l25_numero + 1;
				$clpccflicitapar->alterar_where(null, "l25_codigo = $l25_codigo and l25_anousu = $anousu");

				$clpccflicitanum->l24_numero = $l24_numero + 1;
				$clpccflicitanum->alterar_where(null, "l24_instit=$instit and l24_anousu=$anousu");

				if (db_getsession('DB_anousu') >= 2020) {
					if (in_array($modalidade_tribunal, $aModalidades)) {
						$clpccfeditalnum->l47_numero = $l47_numero + 1;
						$clpccfeditalnum->l47_instit = db_getsession('DB_instit');
						$clpccfeditalnum->l47_anousu = db_getsession('DB_anousu');
						$clpccfeditalnum->incluir(null);
					}
				}
			}
		}
		if ($sqlerro == false) {
			if ($respConducodigo != "") {
				$clliccomissaocgm->l31_numcgm = $respConducodigo;
				$clliccomissaocgm->l31_tipo = 5;
				$clliccomissaocgm->l31_licitacao = $codigo;
				$clliccomissaocgm->incluir(null);
			}
			if ($respAbertcodigo != "") {
				$clliccomissaocgm->l31_numcgm = $respAbertcodigo;
				$clliccomissaocgm->l31_tipo = 1;
				$clliccomissaocgm->l31_licitacao = $codigo;
				$clliccomissaocgm->incluir(null);
			}
			if ($respEditalcodigo != "") {
				$clliccomissaocgm->l31_numcgm = $respEditalcodigo;
				$clliccomissaocgm->l31_tipo = 2;
				$clliccomissaocgm->l31_licitacao = $codigo;
				$clliccomissaocgm->incluir(null);
			}

			if ($respObrascodigo != "") {
				$clliccomissaocgm->l31_numcgm = $respObrascodigo;
				$clliccomissaocgm->l31_tipo = 10;
				$clliccomissaocgm->l31_licitacao = $codigo;
				$clliccomissaocgm->incluir(null);
			}
			if ($respAvaliBenscodigo != "") {
				$clliccomissaocgm->l31_numcgm = $respAvaliBenscodigo;
				$clliccomissaocgm->l31_tipo = 9;
				$clliccomissaocgm->l31_licitacao = $codigo;
				$clliccomissaocgm->incluir(null);
			}

			if ($respAutocodigo != "") {
				$clliccomissaocgm->l31_numcgm = $respAutocodigo;
				$clliccomissaocgm->l31_tipo = 1;
				$clliccomissaocgm->l31_licitacao = $codigo;
				$clliccomissaocgm->incluir(null);
			}
		}


		db_fim_transacao($sqlerro);
	}
}
$l20_liclocal = 0;

?>
<html>

<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body style="background-color: #CCCCCC;">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="430" align="center" valign="top" bgcolor="#CCCCCC">
				<div class="container">
					<?php
					require_once("forms/db_frmliclicita.php");
					?>
				</div>
			</td>
		</tr>
	</table>
</body>

</html>
<?
if (isset($incluir)) {
	if ($erro) {
		echo "<script>alert('" . $msg . "');</script>";
		die();
	}
	if ($clliclicita->erro_status == "0") {
		$clliclicita->erro(true, false);
		$db_botao = true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if ($clliclicita->erro_campo != "") {
			echo "<script> document.form1." . $clliclicita->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1." . $clliclicita->erro_campo . ".focus();</script>";
		}
	} else {
		echo $erro_msg;
		db_msgbox($erro_msg);
		echo "<script> document.form1." . $nomeCampo . ".focus();</script>";
		echo "<script> document.form1." . $nomeCampo . ".style.backgroundColor='#99A9AE';</script>";
		if ($nomeCampo == "l20_mododisputa") {
			echo "<script>document.getElementById('disputa').style.display = '';</script>";
		}

		if ($sqlerro == false) {

			echo "<script>parent.document.getElementById('liclicpublicacoes').style.display = 'block';</script>";

			if (db_getsession("DB_anousu") >= 2016) {
				if ($l20_tipojulg == 3) {
					echo "<script>parent.document.formaba.liclicitemlote.disabled=false;</script>";
				}
				echo " <script>
		           parent.iframe_liclicita.location.href='lic1_liclicita002.php?chavepesquisa=$codigo';\n
		           parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao=$codigo';\n
		           parent.document.formaba.liclicitem.disabled=false;
		           parent.mo_camada('liclicitem');
	           </script> ";
			} else {
				if ($l20_tipojulg == 3) {
					echo "<script>parent.document.formaba.liclicitemlote.disabled=false;</script>";
				}
				echo " <script>
		           parent.iframe_liclicita.location.href='lic1_liclicita002.php?chavepesquisa=$codigo';\n
		           parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao=$codigo';\n
		           parent.mo_camada('liclicitem');
	           </script> ";
			}
		}
	}
}
?>
