<?php
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

use Illuminate\Database\Capsule\Manager as DB;

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("std/db_stdClass.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/db_liborcamento.php"));
require_once (modification("libs/db_libcontabilidade.php"));
require_once (modification("classes/db_pagordem_classe.php"));
require_once (modification("classes/db_pagordemnota_classe.php"));
require_once (modification("classes/db_pagordemele_classe.php"));
require_once (modification("classes/db_empnota_classe.php"));
require_once (modification("classes/db_empnotaele_classe.php"));

//////////////////////////////Controle Andamento da SOlicita��o de Compras/////////////////////
require_once (modification("classes/db_pcparam_classe.php"));
require_once (modification("classes/db_protprocesso_classe.php"));
require_once (modification("classes/db_proctransfer_classe.php"));
require_once (modification("classes/db_proctransand_classe.php"));
require_once (modification("classes/db_proctransferproc_classe.php"));
require_once (modification("classes/db_solicitemprot_classe.php"));
require_once (modification("classes/db_solandam_classe.php"));
require_once (modification("classes/db_solandamand_classe.php"));
require_once (modification("classes/db_solandpadraodepto_classe.php"));
require_once (modification("classes/db_solordemtransf_classe.php"));
require_once (modification("classes/db_procandam_classe.php"));
require_once (modification("classes/db_empempenhonl_classe.php"));
require_once (modification("libs/db_sql.php"));
require_once("model/protocolo/AssinaturaDigital.model.php");


$clpcparam = new cl_pcparam;

//////////////////////////////////////////////////////////////////////////////////////////////

$clpagordem     = new cl_pagordem;
$clpagordemele  = new cl_pagordemele;
$clpagordemnota = new cl_pagordemnota;
$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;
$clcgm = new cl_cgm;

$lAutorizacaoAcordo    = false;

require_once (modification("classes/db_empautpresta_classe.php"));
require_once (modification("classes/db_empempenho_classe.php"));
require_once (modification("classes/db_empprestatip_classe.php"));
require_once (modification("classes/db_emppresta_classe.php"));
require_once (modification("classes/db_empelemento_classe.php"));
require_once (modification("classes/db_emphist_classe.php"));
require_once (modification("classes/db_empemphist_classe.php"));
require_once (modification("classes/db_empempaut_classe.php"));
require_once (modification("classes/db_empempitem_classe.php"));
require_once (modification("classes/db_empautitem_classe.php"));
require_once (modification("classes/db_empautoriza_classe.php"));
require_once (modification("classes/db_empauthist_classe.php"));
require_once (modification("classes/db_empautidot_classe.php"));
require_once (modification("classes/db_emptipo_classe.php"));
require_once (modification("classes/db_empparametro_classe.php"));
require_once (modification("classes/db_cflicita_classe.php"));
require_once (modification("classes/db_db_depusu_classe.php"));
require_once (modification("classes/db_pctipocompra_classe.php"));
require_once (modification("classes/db_conplanoreduz_classe.php"));
require_once (modification("classes/db_empparamnum_classe.php"));
require_once (modification("classes/db_concarpeculiar_classe.php"));
require_once (modification("classes/db_empautorizliberado_classe.php"));
require_once (modification("classes/db_empefdreinf_classe.php"));
require_once modification("libs/db_app.utils.php");

require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/financeiro/ContaBancaria.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaCompensado.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroBanco.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroCaixa.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroExtraOrcamentaria.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiro.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaPatrimonial.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaOrcamentario.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaNaoAplicado.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));


db_app::import("Acordo");
db_app::import("financeiro.*");
db_app::import("orcamento.*");
db_app::import("AcordoComissao");
db_app::import("AcordoComissaoMembro");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");


db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.contacorrente.AC.*");
$clempautpresta   = new cl_empautpresta;
$clempempenho	  	= new cl_empempenho;
$clconplanoreduz  = new cl_conplanoreduz;
$clempprestatip	  = new cl_empprestatip;
$clemppresta	  	= new cl_emppresta;
$clempelemento	  = new cl_empelemento;
$clempempaut	  	=	new cl_empempaut;
$clempempitem	  	= new cl_empempitem;
$clempautoriza	  = new cl_empautoriza;
$clemphist	      = new cl_emphist;
$clempauthist	  	= new cl_empauthist;
$clempemphist	  	= new cl_empemphist;
$clemptipo	      = new cl_emptipo;
$clempautitem	  	= new cl_empautitem;
$clempautidot	  	= new cl_empautidot;
$clempparametro	  = new cl_empparametro;
$clorcunidade  = new cl_orcunidade;
$clcflicita	      = new cl_cflicita;
$clempparamnum	  = new cl_empparamnum;
$clconcarpeculiar = new cl_concarpeculiar;
$oDaoEmpenhoNl    = new cl_empempenhonl;
$cldb_depusu	  	= new cl_db_depusu;
$clpctipocompra	  = new cl_pctipocompra;
$clempautorizliberado = new cl_empautorizliberado;
$clempefdreinf    = new cl_empefdreinf;

//retorna os arrays de lancamento...
$cltranslan       = new cl_translan;

require_once (modification("classes/db_orcelemento_classe.php"));
require_once (modification("classes/db_orcdotacao_classe.php"));
require_once (modification("classes/db_orcreservaaut_classe.php"));
require_once (modification("classes/db_orcdotacaoval_classe.php"));
require_once (modification("classes/db_orcreserva_classe.php"));

$clorcreserva	  	= new cl_orcreserva;
$clorcdotacao	  	= new cl_orcdotacao;
$clorcreservaaut  = new cl_orcreservaaut;
$clorcelemento    = new cl_orcelemento;

require_once (modification("classes/db_conlancam_classe.php"));
require_once (modification("classes/db_conlancamele_classe.php"));
require_once (modification("classes/db_conlancamlr_classe.php"));
require_once (modification("classes/db_conlancamcgm_classe.php"));
require_once (modification("classes/db_conlancamemp_classe.php"));
require_once (modification("classes/db_conlancamval_classe.php"));
require_once (modification("classes/db_conlancamdot_classe.php"));
require_once (modification("classes/db_conlancamdoc_classe.php"));
require_once (modification("classes/db_conlancamcompl_classe.php"));
require_once (modification("classes/db_conlancamnota_classe.php"));

$clconlancam	  	= new cl_conlancam;
$clconlancamele	  = new cl_conlancamele;
$clconlancamlr	  = new cl_conlancamlr;
$clconlancamcgm	  = new cl_conlancamcgm;
$clconlancamemp	  = new cl_conlancamemp;
$clconlancamval	  = new cl_conlancamval;
$clconlancamdot	  = new cl_conlancamdot;
$clconlancamdoc	  = new cl_conlancamdoc;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamnota  = new cl_conlancamnota;

// Reten��es
require_once (modification("classes/db_empautret_classe.php"));
require_once (modification("classes/db_empempret_classe.php"));
require_once (modification("classes/db_empretencao_classe.php"));

// lan�amentos cont�beis
require_once (modification("classes/empenho.php"));

db_app::import("exceptions.*");
db_app::import("configuracao.*");
require_once (modification("model/CgmFactory.model.php"));
require_once (modification("model/CgmBase.model.php"));
require_once (modification("model/CgmJuridico.model.php"));
require_once (modification("model/CgmFisico.model.php"));
require_once (modification("model/Dotacao.model.php"));

require_once (modification("classes/db_condataconf_classe.php"));


$clempautret	  	= new cl_empautret;
$clempempret	  	= new cl_empempret;
$clempretencao	  = new cl_empretencao;

require_once(modification("classes/db_convconvenios_classe.php"));
$clconvconvenios = new cl_convconvenios;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);

if (isset($e54_concarpeculiar) && trim(@$e54_concarpeculiar) != ""){
    $concarpeculiar       = $e54_concarpeculiar;
    $descr_concarpeculiar = @$c58_descr;
}

$anousu           = db_getsession("DB_anousu");
$iInstituicao     = db_getsession("DB_instit");
// Considerar para compara��o de data de empenho sempre esta data.
$dDataMovimento = implode("-",array_reverse(explode("/", $e60_emiss)));
$alertar_retencao = false;
$lControlePacto   = false;
$aParametrosOrcamento = db_stdClass::getParametro("orcparametro",array(db_getsession("DB_anousu")));
if (count($aParametrosOrcamento) > 0) {
    if ( isset($aParametrosOrcamento[0]->o50_utilizapacto) ) {
        $lControlePacto = $aParametrosOrcamento[0]->o50_utilizapacto=="t"?true:false;
    }
}
if(isset($tipocompra)){
    $db_opcao = 1;
    $db_botao = true;
}else{
    $db_opcao = 33;
    $db_botao = false;
}

$lLiquidar               = "";
$lLiquidaMaterialConsumo = 'false';
$sGrupoDesdobramento = null;

if (!empty($iElemento)) {

    if (USE_PCASP) {

        $oGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($iElemento, db_getsession("DB_anousu"));

        if ($oGrupoContaOrcamento instanceof GrupoContaOrcamento) {

            if (in_array($oGrupoContaOrcamento->getCodigo(), array(7,8,9))) {

                $sGrupoDesdobramento = $oGrupoContaOrcamento->getDescricao();
                if ( $oGrupoContaOrcamento->getCodigo() == 9 ) {

                    $sMensagem = "O desdobramento deste empenho est� no grupo {$sGrupoDesdobramento} ";
                    $lLiquidar = "disabled='disabled'";
                    db_msgbox("$sMensagem. n�o ser� poss�vel liquidar o empenho diretamente");
                } else {
                    $lLiquidaMaterialConsumo = 'true';
                }
            }
        }
    }
}

$dtDataUsu = $dDataMovimento == null ? date("Y-m-d", db_getsession('DB_datausu')) : $dDataMovimento;
if(isset($incluir)) {

    $buscarOrdenadores = db_utils::fieldsMemory($clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_buscarordenadores", null, "")), 0)->e30_buscarordenadores;
    $ordenador = 0;
    if ($buscarOrdenadores == 2 ) {
    
        $aAssinante =  getAssinantesCgm($chavepesquisa, $o41_cgmordenador);

        foreach ($aAssinante as $assinante) {
            $db243_data_inicio = $assinante->db243_data_inicio;
            $db243_data_final  = $assinante->db243_data_final;  
        }

        $dataemiss           = explode('/', $e60_emiss);
        $dataemissformatada  = date('Y-m-d\TH:i:s\Z', strtotime($dataemiss[2] . '-' . $dataemiss[1] . '-' . $dataemiss[0]));
        $datainicioformatada = date('Y-m-d\TH:i:s\Z', strtotime($db243_data_inicio));
        $datafimformatada    = date('Y-m-d\TH:i:s\Z', strtotime($db243_data_final));

        $datafinal = explode('-', $db243_data_final);
        $datafinal = $datafinal[2] . '/' . $datafinal[1] . '/' . $datafinal[0];

        $datainicial = explode('-', $db243_data_inicio);
        $datainicial = $datainicial[2] . '/' . $datainicial[1] . '/' . $datainicial[0];
        
        if (substr($dataemissformatada, 0, 10) < substr($datainicioformatada, 0, 10) || substr($dataemissformatada, 0, 10) > substr($datafimformatada, 0, 10)) {
            if (substr($dataemissformatada, 0, 10) < substr($datainicioformatada, 0, 10)) {
                $erro_msg = ("O ordenador selecionado est� fora da data do empenho, data in�cio: ".$datainicial);
            }
            if (substr($dataemissformatada, 0, 10) > substr($datafimformatada, 0, 10)) {
                $erro_msg = ("O ordenador selecionado est� fora da data do empenho, data fim: ".$datafinal);
            }
            $sqlerro = true;
            $ordenador = 1;
            
            $aAssinantes = getAssinantes($chavepesquisa);
            $rowCount = count($aAssinantes);  
            $ordenadoresArray = array(); 
            $uniqueKeys = array(); 
            foreach ($aAssinantes as $assinante) {
                $uniqueKey = $assinante->nome . '|' . $assinante->z01_numcgm;
    
                if ($rowCount > 1) {
                    if (!in_array($uniqueKey, $uniqueKeys)) {
                        $ordenadoresArray[] = [
                            'nome' => $assinante->nome,
                            'z01_numcgm' => $assinante->z01_numcgm,
                            'db243_data_inicio' => $assinante->db243_data_inicio,
                            'db243_data_final'  => $assinante->db243_data_final,
                        ];
                        $uniqueKeys[] = $uniqueKey;
                    }
                } else {
                    
                    $o41_cgmordenador = $assinante->z01_numcgm;
                    $o41_nomeordenador = $assinante->nome;
                    $db243_data_inicio = $assinante->db243_data_inicio;
                    $db243_data_final  = $assinante->db243_data_final;
                }
            }
        }
    } 
    if($ordenador == 0) {

    $anoUsu = db_getsession("DB_anousu");
    $sWhere = "e56_autori = " . $e54_autori . " and e56_anousu = " . $anoUsu;
    $result = $clempautidot->sql_record($clempautidot->sql_query_dotacao(null, "o58_codigo", null, $sWhere));
    $numrows = $clempautidot->numrows;
    if ($numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    $result  = $clempempaut->sql_record($clempempaut->sql_query_empenho(null, " e60_tipodespesa , e60_esferaemendaparlamentar , e60_emendaparlamentar ",null, " e61_autori = $chavepesquisa"));
    $numrows = $clempempaut->numrows;
    if ($numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    $oCodigoAcompanhamento = new ControleOrcamentario();
    $oCodigoAcompanhamento->setTipoDespesa($e60_tipodespesa);
    $oCodigoAcompanhamento->setFonte($o58_codigo);
    $oCodigoAcompanhamento->setEmendaParlamentar($e60_emendaparlamentar);
    $oCodigoAcompanhamento->setEsferaEmendaParlamentar($e60_esferaemendaparlamentar);
    $oCodigoAcompanhamento->setDeParaFonteCompleta();
    $e60_codco = $oCodigoAcompanhamento->getCodigoParaEmpenho();

    $clcondataconf = new cl_condataconf;

    $result = db_query($clcondataconf->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit')));
    $c99_data = db_utils::fieldsMemory($result, 0)->c99_data;

    if(strtotime($dtDataUsu) <= strtotime($c99_data)){

        $erro_msg  = "N�o foi poss�vel incluir os lan�amentos do evento contabil.\n\n";
        $erro_msg .= "Erro T�cnico: ";
        $erro_sql   = "Valores lan�amentos (".pg_result(db_query('select max(c69_sequen)+1 from conlancamval'),0,0).") nao Inclu�do. Inclusao Abortada.";
        $erro_msg   .= "Usu�rio: \\n\\n ".$erro_sql." \\n\\n";
        $erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n "));
        $erro_msg   .=  " ERRO: DATA DO ENCERRAMENTO CONT�BIL. LIMITE: {$c99_data} \\n\\n";
        $erro_status = "0";

        unset($incluir);
        db_msgbox($erro_msg);

    }


    /* Ocorr�ncia 2630
     * Valida��es de datas para gera��o de autoriza��es de empenhos e gera��o de empenhos
     * 1. Validar impedimento para gera��o de autoriza��es/empenhos com data anterior a data de homologa��o
     * 2. Validar impedimento para gera��o de autoriza��es de empenhos de licita��es que n�o estejam homologadas.
     */
    $sSqlLicitacao = "select e54_emiss
                          from empautoriza
                          inner join liclicita  on ltrim(((string_to_array(e54_numerl, '/'))[1])::varchar,'0') = l20_numero::varchar AND l20_anousu::varchar = ((string_to_array(e54_numerl, '/'))[2])::varchar
                          where e54_autori = {$e54_autori} limit 1";

    if(pg_num_rows(db_query($sSqlLicitacao))) {

        if (strtotime(db_utils::fieldsMemory(db_query($sSqlLicitacao), 0)->e54_emiss) > strtotime($dDataMovimento)) {

            db_msgbox("N�o � permitido emitir empenhos de licita��es cuja data da autoriza��o (".date("d/m/Y",strtotime(db_utils::fieldsMemory(db_query($sSqlLicitacao), 0)->e54_emiss)) .") seja maior que a data de emiss�o do empenho (".$e60_emiss.").");
            db_redireciona("emp4_empempenho004.php");

        }

    }

    $sqlerro = false;
    db_inicio_transacao();

    // atualiza o campo do gestor do empenho
    $sSql = " UPDATE empautoriza SET e54_gestaut = '{$e54_gestaut}' "
        . " WHERE e54_autori={$e54_autori} AND e54_numcgm={$e54_numcgm} ";
    db_query($sSql);


    //////////////////////////////Controle Andamento da SOlicita��o de Compras/////////////////////
    //----------------------------REcebe processo se existe tranferencia -------------
    $result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_gerareserva,pc30_contrandsol"));
    db_fieldsmemory($result_pcparam, 0);
    if ($pc30_contrandsol == 't') {

        $sqltran     = "select distinct x.p62_codtran

			from ( select distinct p62_codtran,
                             p62_dttran,
                             p63_codproc,
                             descrdepto,
                             p62_hora,
                             login,
                             pc11_numero,
			                       pc11_codigo,
                             pc81_codproc,
                             e55_autori,
                             e54_autori,
			                       e54_anulad
		                    from proctransferproc

                             inner join solicitemprot        on pc49_protprocesso                   = proctransferproc.p63_codproc
                             inner join solicitem            on pc49_solicitem                      = pc11_codigo
                             inner join proctransfer         on p63_codtran                         = p62_codtran
						                 inner join db_depart            on coddepto                            = p62_coddepto
						                 inner join db_usuarios          on id_usuario                          = p62_id_usuario
						                 inner join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo
                             inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                             inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                            and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
						                 inner join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori
             			     where p62_coddeptorec = " . db_getsession("DB_coddepto") . "
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.e54_autori = {$e54_autori}";
        $result_tran = db_query($sqltran);

        if (pg_numrows($result_tran) != 0) {

            for ($w = 0; $w < pg_numrows($result_tran); $w++) {

                db_fieldsmemory($result_tran, $w);
                $recebetransf = recprocandsol($p62_codtran);

                if ($recebetransf == true) {

                    $sqlerro = true;
                    break;

                }

            }

        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = " . db_getsession("DB_anousu");
    $res = db_query($sql);

    $clempempaut->sql_record($clempempaut->sql_query(null, "*", "", "e61_autori = $e54_autori"));
    if ($clempempaut->numrows > 0) {
        $erro_msg = "Autoriza��o empenhada!";
        $sqlerro  = true;
    }

    $resaut = $clempautoriza->sql_record($clempautoriza->sql_query_file($e54_autori, "e54_anousu", "", ""));
    if ($clempautoriza->numrows > 0) {
        db_fieldsmemory($resaut, 0);
        if ($e54_anousu != db_getsession("DB_anousu")) {
            $erro_msg = "Autoriza��o de outro exerc�cio.! ($e54_anousu)";
            $sqlerro  = true;
        }
    } else {
        $erro_msg = "Autoriza��o n�o encontrada!";
        $sqlerro  = true;
    }

    //verifica o saldo dos itens com o saldo total da autoriza��o
    $resdiftot = $clempempaut->sql_record("select e54_autori,
                                    e54_valor,
									sum(e55_vltot) as e55_vltot
							   from empautoriza
							  inner join empautitem on e54_autori = e55_autori
							  where e54_autori = $e54_autori
							  group by e54_autori, e54_valor
							  having cast(sum(round(e55_vltot,2)) as numeric) <> cast(round(e54_valor,2) as numeric)
							  ");

    if ($clempempaut->numrows > 0) {

        db_fieldsmemory($resdiftot, 0);
        $erro_msg = "Valor total dos itens diferente do valor total da autoriza��o. Vlr. da Autoriza��o: $e54_valor - Vlr. Total dos Itens: $e55_vltot ";
        $sqlerro  = true;

    }

    if ($sqlerro == false) {
        // chama fun��o de critica para empenhos
        $sql = "select fc_verifica_lancamento(" . $e54_autori . ",'" . $dDataMovimento . "',1,00.00)";
        $result_erro = db_query($sql) or die($sql);
        $erro_msg = pg_result($result_erro, 0, 0);
        if (substr($erro_msg, 0, 2) > 0) {
            $erro_msg = substr($erro_msg, 3);
            $sqlerro  = true;
        }
    }


    /*inicio-conlancamval*/
    $cltranslan->db_trans_empenho($e54_codcom, db_getsession("DB_anousu"));
    $arr_debito     = $cltranslan->arr_debito;
    $arr_credito    = $cltranslan->arr_credito;
    $arr_histori    = $cltranslan->arr_histori;
    $arr_seqtranslr = $cltranslan->arr_seqtranslr;

    if (count($arr_credito) == 0) {
        $sqlerro  = true;
        $erro_msg = "N�o existem transa��es cadastradas para esta institui��o.";
    }

    //final

    /*rotina de incluir  na tabela empempenho*/
    if ($sqlerro == false) {
        //$clempempenho->e60_numemp  = $e60_numemp;
        $e60_numemp = '';

        /*
         *  N�O COMENTAR A LINHA ABAIXO,
        *  ELA SERVE PARA NUMERAR OS EMPENHOS EM BAGE, ONDE EXISTE O EMPENHO 1 NA PREFEITURA,1 NO DAEBE E 1 NA CAMARA
        *
        */
        $result = $clempparamnum->sql_record($clempparamnum->sql_query_file($anousu, db_getsession("DB_instit"), " (e29_codemp + 1) as e60_codemp"));

        if ($clempparamnum->numrows == 0) {

            $result = $clempparametro->sql_record($clempparametro->sql_query_file($anousu, "(e30_codemp+1) as e60_codemp, e30_notaliquidacao"));
            if ($clempparametro->numrows > 0) {
                db_fieldsmemory($result, 0);
                $clempempenho->e60_codemp = $e60_codemp;

                /*rotina que atualiza a tabela empparametro*/
                $clempparametro->e39_anousu         = $anousu;
                $clempparametro->e30_codemp         = $e60_codemp;
                $clempparametro->e30_notaliquidacao = $e30_notaliquidacao;
                $clempparametro->alterar($anousu);
                if ($clempparametro->erro_status == 0) {
                    $sqlerro = true;
                }
                /*final*/
            } else {
                $erro_msg = "Preencha os parametros da tabela empparametro para o exercicio $anousu!";
                $sqlerro  = true;
            }

        } else {
            if( isset($incluir) && !$e60_codemp){
                db_fieldsmemory($result, 0);
            }
            $clempempenho->e60_codemp = $e60_codemp;

            /*rotina que atualiza a tabela empparametro*/
            $clempparamnum->e29_anousu = $anousu;
            $clempparamnum->e29_instit = db_getsession('DB_instit');
            $clempparamnum->e29_codemp = $e60_codemp;
            $clempparamnum->alterar($anousu, db_getsession('DB_instit'));
            if ($clempparamnum->erro_status == 0) {
                $erro_msg = "Tabela de parametros por instituicao para o exercicio $anousu nao criada!";
                $sqlerro  = true;
            }
            /*final*/


        }
        /**
         * Verificamos se existe o empenho cadastrado com o numero na base de dados:
         */
        if (!$sqlerro) {

            $sWhereVerificacaoEmpenho = "e60_codemp = '{$e60_codemp}' ";
            $sWhereVerificacaoEmpenho .= "and e60_anousu = {$anousu} and e60_instit = {$iInstituicao}";
            $sSqlEmpenhoExistente = $clempempenho->sql_query_file(null, "e60_numemp", null, $sWhereVerificacaoEmpenho);
            $rsVerificacaoEmpenho = $clempempenho->sql_record($sSqlEmpenhoExistente);
            if ($clempempenho->numrows > 0) {

                $sqlerro  = true;
                $erro_msg = "J� existe um empenho de n�mero {$e60_codemp}/{$anousu} lan�ado na institui��o!" . pg_last_error();
            }
        }

        /**
         * Verificamos se o empenho fora da ordem cronol�gica:
         */
        if (!$sqlerro) {

            $result = $clempparametro->sql_record($clempparametro->sql_query_file($anousu, "e30_empordemcron"));
            db_fieldsmemory($result, 0);
            if ($clempparametro->numrows > 0) {

            }
            if($e30_empordemcron == 'f'){

                // maior ou igual a anterior
                $where = " e60_codemp::int8 > {$e60_codemp}";
                $order = " order by e60_codemp::int8 asc";
                $sSqlEmpenhoProximo = $clempempenho->sql_prox_data_empenho($anousu, $iInstituicao, $where, $order);
                $rsVerificacaoEmpenhoProximo = $clempempenho->sql_record($sSqlEmpenhoProximo);

                if ($clempempenho->numrows > 0) {
                    db_fieldsmemory($rsVerificacaoEmpenhoProximo, 0);
                    if(strtotime($dDataMovimento) > strtotime($emiss) ){
                        $sqlerro   = true;
                        $erro_msg  = "Empenho fora da ordem cronol�gica: ";
                        $erro_msg .= "a data ".date("d/m/Y",strtotime($dDataMovimento))." do Empenho {$e60_codemp} � maior que a data ".date("d/m/Y",strtotime($emiss))." do Empenho {$codemp}!" . pg_last_error();
                    }
                }
                // menor ou igual ao proximo e
                $where = " e60_codemp::int8 < {$e60_codemp}";
                $order = " order by e60_codemp::int8 desc";
                $sSqlEmpenhoAnterior = $clempempenho->sql_prox_data_empenho($anousu, $iInstituicao, $where, $order);
                $rsVerificacaoEmpenhoAnterior = $clempempenho->sql_record($sSqlEmpenhoAnterior);

                if ($clempempenho->numrows > 0) {
                    db_fieldsmemory($rsVerificacaoEmpenhoAnterior, 0);
                    if(strtotime($dDataMovimento) < strtotime($emiss)){
                        $sqlerro   = true;
                        $erro_msg  = "Empenho fora da ordem cronol�gica: ";
                        $erro_msg .= "a data ".date("d/m/Y",strtotime($dDataMovimento))." do Empenho {$e60_codemp} � menor que a data ".date("d/m/Y",strtotime($emiss))." do Empenho {$codemp}!" . pg_last_error();
                    }
                }

            }
        }

        $dados = (object) array(
            'tabela' => 'empautidot',
            'campo'  => 'e56_autori',
            'sigla'  => 'e56'
        );

        $veConvMSC = $clempempenho->verificaConvenioSicomMSC($e54_autori, $anousu, $dados);

        $fontesMsg = "122, 123, 124, 142, 163, 171, 172, 173, 176, 177, 178, 181, 182 e 183";

        if (db_getsession("DB_anousu") > 2022) {
            $fontesMsg = "15700000, 16310000, 17000000, 16650000, 17130070, 15710000, 15720000, 15750000, 16320000, 16330000, 16360000, 17010000, 17020000 e 17030000";
        }

        if ($veConvMSC > 0) {

            $rsResult = $clconvconvenios->sql_record("select c206_sequencial from convconvenios where c206_sequencial = $e60_numconvenio");

            if (!$rsResult) {
                $sqlerro  = true;

                $erro_msg  = "Inclus�o Abortada!\n";
                $erro_msg .= "� obrigat�rio informar o conv�nio para os empenhos de fontes:\n";
                $erro_msg .= $fontesMsg;

            }

        }

        if ($sqlerro == false) {

            $result = $clempautidot->sql_record($clempautidot->sql_query_file($e54_autori, "e56_anousu,e56_coddot"));
            db_fieldsmemory($result, 0);
            $clempempenho->e60_anousu = $e56_anousu;
            $clempempenho->e60_coddot = $e56_coddot;
            $clempempenho->e60_numcgm = $e54_numcgm;
            $clempempenho->e60_numcgmordenador = $o41_cgmordenador;
            $clempempenho->e60_emiss  = $dDataMovimento;
            $clempempenho->e60_vencim = $dDataMovimento;
            /*OC4401*/
            $clempempenho->e60_id_usuario = db_getsession("DB_id_usuario");
            /*FIM - OC4401*/

            $clempempenho->e60_numconvenio = $e60_numconvenio;

            $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e56_coddot", db_getsession("DB_anousu"));
            db_fieldsmemory($result, 0);
            $clempempenho->e60_vlrorc = $dot_ini;  //valor disponivel

            $result = $clempautitem->sql_record($clempautitem->sql_query_file($e54_autori, null, "sum(e55_vltot) as e60_vlremp"));
            db_fieldsmemory($result, 0);
            $e60_vlremp                       = number_format($e60_vlremp, "2", '.', "");
            $clempempenho->e60_vlremp         = $e60_vlremp;      //valor dos itens
            $clempempenho->e60_salant         = "$atual";
            $clempempenho->e60_vlrliq         = '0';
            $clempempenho->e60_vlrpag         = '0';
            $clempempenho->e60_vlranu         = '0';
            $clempempenho->e60_codcom         = $e54_codcom;
            $clempempenho->e60_tipol          = $e54_tipol;
            $clempempenho->e60_numerol        = $e54_numerl;
            $clempempenho->e60_destin         = $e54_destin;
            $clempempenho->e60_codtipo        = $e54_codtipo;
            $clempempenho->e60_resumo         = $e54_resumo;
            $clempempenho->e60_informacaoop   = $e50_obs;
            $clempempenho->e60_instit         = db_getsession("DB_instit");
            $clempempenho->e60_datasentenca   = $e60_datasentenca;
            $clempempenho->e60_concarpeculiar = $e54_concarpeculiar;
            $clempempenho->e60_codco          = $e60_codco;
            $clempempenho->e60_dividaconsolidada = $op01_numerocontratoopc;

            /**
             * Valida��o solicitada pela OC 6457
             * @author MarioJunior
             */

            $sSql = "SELECT si09_tipoinstit AS tipoinstit
              FROM infocomplementaresinstit
              WHERE si09_instit = " . db_getsession("DB_instit");

            $rsResult = db_query($sSql);
            db_fieldsMemory($rsResult, 0);

            //rotina para pegar o elemento da dota��o
            if ($sqlerro == false) {

                $result09  = $clorcdotacao->sql_record($clorcdotacao->sql_query_ele(db_getsession("DB_anousu"), $e56_coddot, "o56_elemento as elemento_emp"));
                $numrows09 = $clorcdotacao->numrows;
                if ($numrows09 > 0) {
                    db_fieldsmemory($result09, 0);
                } else {
                    $sqlerro  = true;
                    $erro_msg = "N�o existe elemento para dota��o $e56_coddot";
                }
            }

            $sqlElemento = "select o56_elemento from orcelemento where o56_codele ={$iElemento} and o56_anousu =".db_getsession("DB_anousu");
            $rsResultEle = db_query($sqlElemento);
            db_fieldsmemory($rsResultEle, 0);

            $aElementosDesdobramento = array('331900101','331900102','331900301','331900302','331900501','331900502','331900503','331909102',
                '331909103','331909201','331909202','331909203','331909403','331919102','331919103','331919201',
                '331919202','331919203','331969102','331969103','331969201','331969202','331969203','331969403');

            $aElementos = array('3319001','3319003','3319091','3319092','3319094','3319191','3319192','3319194');

            if ( ($tipoinstit == 5 || $tipoinstit == 6) &&
                (in_array(substr($o56_elemento, 0 , -4), $aElementosDesdobramento) || (in_array(substr($o56_elemento, 0 , -6), $aElementos) && db_getsession("DB_anousu") >= 2021) )
            ) {
                if($e60_tipodespesa != 0) {
                    $clempempenho->e60_tipodespesa = $e60_tipodespesa;
                    $sqlerro = false;
                }else{
                    $erro_msg = "Tipo de Despesa n�o informado";
                    db_msgbox($erro_msg);
                    $sqlerro = true;
                }
            }else{
                $clempempenho->e60_tipodespesa = null;
                $sqlerro = false;
            }

            if ($sqlerro==false) {

                $result_cgmzerado = db_query("SELECT z01_cgccpf FROM cgm WHERE z01_numcgm = {$e54_numcgm}");
                db_fieldsmemory($result_cgmzerado, 0)->z01_cgccpf;


                if (strlen($z01_cgccpf) != 14 && strlen($z01_cgccpf) != 11) {

                    $sqlerro = true;
                    $erro_msg = "ERRO!\nN�mero do CPF/CNPJ cadastrado est� incorreto.\nCorrija o CGM do fornecedor e tente novamente!";
                }
                if ($z01_cgccpf == '00000000000000' || $z01_cgccpf == '00000000000') {

                    $sqlerro = true;
                    $erro_msg = "ERRO!\nN�mero do CPF/CNPJ cadastrado est� zerado.\nCorrija o CGM do fornecedor e tente novamente!";
                }
            }

            /* Ocorr�ncia 11933
             * Valida se o par�metro Atesto de Controle Interno est� marcado como SIM
             * e valida se a autoriza��o de empenho est� desbloqueada na rotina Controle Interno - Procedimentos - Atesto de Controle Interno
             */
            $bAtestoContInt = db_utils::fieldsMemory($clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_atestocontinterno", null, "")), 0)->e30_atestocontinterno;
            $clempautorizliberado->sql_record($clempautorizliberado->sql_query(null, "*", "", "e232_autori = $chavepesquisa"));

            if ( $bAtestoContInt == 't' && $clempautorizliberado->numrows == 0 ) {
                $sqlerro = true;
                $erro_msg = "Usu�rio: Esta autoriza��o de empenho ainda n�o recebeu o Atesto do Controle Interno. Aguarde a libera��o para emiss�o do empenho!";
            }

            $sSqlDataAutEmp = "select e54_emiss from empautoriza where e54_autori = {$e54_autori} limit 1";

            if(pg_num_rows(db_query($sSqlDataAutEmp))) {
                if (strtotime(db_utils::fieldsMemory(db_query($sSqlDataAutEmp), 0)->e54_emiss) > strtotime($dDataMovimento)) {
                    $sqlerro = true;
                    $erro_msg = "N�o � permitido emitir empenho cuja data da autoriza��o (".date("d/m/Y",strtotime(db_utils::fieldsMemory(db_query($sSqlDataAutEmp), 0)->e54_emiss)) .") seja maior que a data de emiss�o do empenho (".$e60_emiss.").";
                }
            }

            if($sqlerro == false) {

                $clempempenho->incluir($e60_numemp);
                if ($clempempenho->erro_status == 0) {
                    $sqlerro = true;
                }
                $erro_msg   = $clempempenho->erro_msg;
                $ok_msg     = $clempempenho->erro_msg;
                $e60_numemp = $clempempenho->e60_numemp;
            }

        }
    }

    /**
     * Verificamos se foi vinculado algum contrato com o empenho
     */
    $lEmpenhoVinculadoContrato = false;
    if (!empty($ac16_sequencial) && !$sqlerro) {

        $oDaoEmpempenhoContrato              = db_utils::getDao("empempenhocontrato");
        $oDaoEmpempenhoContrato->e100_acordo = $ac16_sequencial;
        $oDaoEmpempenhoContrato->e100_numemp = $e60_numemp;

        $oDaoEmpempenhoContrato->incluir(null);

        if ($oDaoEmpempenhoContrato->erro_status == 0) {

            $erro_msg = $oDaoEmpempenhoContrato->erro_msg;
            $sqlerro  = true;
        }
        $lEmpenhoVinculadoContrato = true;
    }
    /*fim rotina empempenho*/
    /*rotina que inclui no emppresta*/
    /**
     * Variavel para verificar se o empenho eh uma prestacao de contas.
     * Caso seja uma prestacao de contas devemos seta-la para true
     *
     * @var $isPrestacaoContas
     */
    $isPrestacaoContas = false;
    if (isset($e44_tipo) && $e44_tipo != '' and $sqlerro == false) {

        $result = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo, "e44_obriga"));
        db_fieldsmemory($result, 0);

        if ($e44_obriga != 0) {

            $clemppresta->e45_numemp = $e60_numemp;
            $clemppresta->e45_data   = $dDataMovimento;
            $clemppresta->e45_tipo = $e44_tipo;
            $clemppresta->incluir(null);
            if ($clemppresta->erro_status == 0) {
                $sqlerro = true;
            }
            $isPrestacaoContas = true;
            $erro_msg          = $clemppresta->erro_msg;
        }
    }
    /*final*/

    //rotina para pegar o elemento da dota��o
    if ($sqlerro == false) {

        $result09  = $clorcdotacao->sql_record($clorcdotacao->sql_query_ele(db_getsession("DB_anousu"), $e56_coddot, "o56_elemento as elemento_emp"));
        $numrows09 = $clorcdotacao->numrows;
        if ($numrows09 > 0) {
            db_fieldsmemory($result09, 0);
        } else {

            $sqlerro  = true;
            $erro_msg = "N�o existe elemento para dota��o $e56_coddot";
        }
    }
    //final

    /*rotina que inclui na tabela empempitem*/
    if ($sqlerro == false) {

        $result  = $clempautitem->sql_record($clempautitem->sql_query_file($e54_autori));
        $numrows = $clempautitem->numrows;
        for ($i = 0; $i < $numrows; $i++) {

            db_fieldsmemory($result, $i);
            //rotina para pegar o elemento da dota��o
            $result09  = $clorcelemento->sql_record($clorcelemento->sql_query_file($e55_codele, db_getsession("DB_anousu"), "o56_elemento as elemento_item"));
            $numrows09 = $clorcelemento->numrows;
            if ($numrows09 > 0) {
                db_fieldsmemory($result09, 0);
            } else {

                $sqlerro  = true;
                $erro_msg = "N�o existe elemento para o item $e55_item";
            }
            //final

            //rotina que compara os elementos da dota��o do empenho com a dota��o dos itens
            if (substr($elemento_emp, 0, 6) != substr($elemento_item, 0, 6)) {
                $erro_msg = "Subelemento do item diferente da dota��o. Verifique!";
                $sqlerro  = true;
            }
            if ($sqlerro == false) {

                $clempempitem->e62_numemp            = $e60_numemp;
                $clempempitem->e62_item              = $e55_item;
                $clempempitem->e62_sequen            = $e55_sequen;
                $clempempitem->e62_quant             = $e55_quant;
                $clempempitem->e62_vltot             = $e55_vltot;
                $clempempitem->e62_vlrun             = $e55_vlrun;
                $clempempitem->e62_servicoquantidade = $e55_servicoquantidade == "f" ? "false" : "true";
                $e55_descr                           = AddSlashes($e55_descr);
                $clempempitem->e62_descr             = $e55_descr;
                $clempempitem->e62_codele            = $iElemento;
                $clempempitem->incluir($e60_numemp, $e55_sequen);
                $erro_msg = $clempempitem->erro_msg;

                if ($clempempitem->erro_status == 0) {
                    $sqlerro = true;
                    break;
                }

                /*
                * Verificamos se o item est� vinculado a uma autoriza��o de um pacto sem solicita��o
                * Se a autoriza��o foi gerada sem solicita�ao, controla o saldo do pacto, do contr�rio esse controle foi realizado
                * no momento da inclus�o da solicita��o
                *
                */
                $sSqlEmpAutorizaSol = "select *
          from empautoriza
          inner join empautitem on empautitem.e55_autori       = empautoriza.e54_autori
          left join pcprocitem on pcprocitem.pc81_codprocitem = empautitem.e55_sequen
          left join solicitem  on solicitem.pc11_codigo       = pcprocitem.pc81_solicitem
          where solicitem.pc11_numero is null
          and e54_autori = $e54_autori ";
                $rsEmpAutorizaSol   = db_query($sSqlEmpAutorizaSol);

                if (!$sqlerro && $lControlePacto && pg_numrows($rsEmpAutorizaSol) > 0) {

                    $sSqlItemPacto = $clempautitem->sql_query_item_pacto($e54_autori, $e55_sequen);
                    $rsItemPacto   = $clempautitem->sql_record($sSqlItemPacto);
                    if ($clempautitem->numrows > 0) {

                        $oItemPactoSol = db_utils::fieldsMemory($rsItemPacto, 0);
                        $oEmpenho      = new empenho();
                        try {
                            $oEmpenho->baixarSaldoPacto($clempempitem->e62_sequencial, $oItemPactoSol->o88_pactovalor, $e55_quant, $e55_vltot);

                        } catch (Exception $eEmpenho) {

                            $sqlerro  = true;
                            $erro_msg = $eEmpenho->getMessage();
                        }
                    }
                }
            }
        }
    }
    /*final rotina */

    /*rotina de incluir  na tabela empelemento*/
    if ($sqlerro == false) {
        $result                    = $clempautitem->sql_record($clempautitem->sql_query_elemento($e54_autori));
        $numrows                   = $clempautitem->numrows;
        $clempelemento->e64_numemp = $e60_numemp;
        for ($i = 0; $i < $numrows; $i++) {
            db_fieldsmemory($result, $i);
            $clempelemento->e64_codele = $iElemento;
            $clempelemento->e64_vlremp = number_format($e55_vltot, "2", '.', "");      //valor dos itens
            $clempelemento->e64_vlrliq = '0';
            $clempelemento->e64_vlrpag = '0';
            $clempelemento->e64_vlranu = '0';
            $clempelemento->incluir($e60_numemp, $iElemento);
            $erro_msg = $clempelemento->erro_msg;
            if ($clempelemento->erro_status == 0) {
                $sqlerro = true;
            }
            $clempelemento->e64_codele = null;
            $clempelemento->e64_vlremp = null;      //valor dos itens
            $clempelemento->e64_vlrliq = null;
            $clempelemento->e64_vlrpag = null;
            $clempelemento->e64_vlranu = null;
        }
    }


    /*rotina que inclui na tabela empefdreinf*/
    if ($sqlerro == false) {
        $clempefdreinf->efd60_aquisicaoprodrural = $efd60_aquisicaoprodrural;
        $clempefdreinf->efd60_prodoptacp = $efd60_prodoptacp;

        if ($efd60_cessaomaoobra == 2) {
            $clempefdreinf->efd60_cessaomaoobra = $efd60_cessaomaoobra;
            $clempefdreinf->efd60_possuicno = $efd60_possuicno;
            $clempefdreinf->efd60_numcno = $efd60_numcno;
            $clempefdreinf->efd60_indprestservico = $efd60_indprestservico;
            $clempefdreinf->efd60_prescontricprb = $efd60_prescontricprb;
            $clempefdreinf->efd60_tiposervico = $efd60_tiposervico;
        }

        if($clempefdreinf->numrows>0){
            $clempefdreinf->alterar($e60_numemp);
        } else {
            $clempefdreinf->efd60_numemp = $e60_numemp;
            $clempefdreinf->efd60_codemp = $e60_codemp;
            $clempefdreinf->efd60_anousu = db_getsession("DB_anousu");
            $clempefdreinf->efd60_instit = db_getsession("DB_instit");
            $clempefdreinf->incluir($e60_numemp);
        }
    }
    /*rotina que inclui na tabela empemphist*/
    if ($sqlerro == false && $e57_codhist != "Nenhum") {
        $clempemphist->e63_numemp  = $e60_numemp;
        $clempemphist->e63_codhist = $e57_codhist;
        $clempemphist->incluir($e60_numemp);
        $erro_msg = $clempemphist->erro_msg;
        if ($clempemphist->erro_status == 0) {
            $sqlerro = true;
        }
    }
    /*final rotina que inclui em empemphist*/

    /*rotina que inclui na tabela empempaut*/
    if ($sqlerro == false) {
        $clempempaut->e61_numemp = $e60_numemp;
        $clempempaut->e61_autori = $e54_autori;
        $clempempaut->incluir($e60_numemp);
        $erro_msg = $clempempaut->erro_msg;
        if ($clempempaut->erro_status == 0) {
            $sqlerro = true;
        }
    }
    /*final da rotina*/

    /**
     * Insere na tabela empempenhofinalidadepagamentofundeb
     */
    if (!$sqlerro) {

        try {

            $oEmpenhoFinanceiro = new EmpenhoFinanceiro($e60_numemp);
            $iRecursoDotacao    = $oEmpenhoFinanceiro->getDotacao()->getRecurso();

            if ($iRecursoDotacao == ParametroCaixa::getCodigoRecursoFUNDEB(db_getsession('DB_instit'))) {

                $oEmpenhoFinanceiro->setFinalidadePagamentoFundeb(FinalidadePagamentoFundeb::getInstanciaPorCodigo($e151_codigo));
                $oEmpenhoFinanceiro->salvarFinalidadePagamentoFundeb();
            }


        } catch (Exception $eErro) {

            $sqlerro  = true;
            $erro_msg = $eErro->getMessage();
        }
    }

    /*rotina que exclui orcreserva e  aut e sol*/


    if ($sqlerro == false) {
        $result = $clorcreservaaut->sql_record($clorcreservaaut->sql_query(null, "o83_codres", "", "o83_autori=$e54_autori"));
        db_fieldsmemory($result, 0);

        $clorcreservaaut->o83_codres = $o83_codres;
        $clorcreservaaut->excluir($o83_codres);
        $erro_msg = $clorcreservaaut->erro_msg;
        if ($clorcreservaaut->erro_status == 0) {
            $sqlerro = true;
        }
    }

    if ($sqlerro == false) {
        $clorcreserva->o80_codres = $o83_codres;
        $clorcreserva->excluir($o83_codres);
        $erro_msg = $clorcreserva->erro_msg;
        if ($clorcreserva->erro_status == 0) {
            $sqlerro = true;
        }
    }
    /*final rotina que exclui do orcreserva e aut*/

    if (!$sqlerro) {

        $oDaoEmpenhoNl->e68_numemp = $e60_numemp;
        $oDaoEmpenhoNl->e68_data   = $dDataMovimento;
        $oDaoEmpenhoNl->incluir(null);
        if ($oDaoEmpenhoNl->erro_status == 0) {

            $erro_msg = "Erro ao incluir empenho como nota de liquida��o;";
            $sqlerro  = true;

        }
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////LAN�AMENTO CONT�BIL//////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($sqlerro == false) {
        $result09  = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,o56_elemento,e64_vlremp"));
        $numrows09 = $clempelemento->numrows;
    }

    $anousu             = db_getsession("DB_anousu");
    $datausu            = $dDataMovimento;
    $c71_coddoc         = '1';

    $oEmpenhoFinanceiro = null;
    try {
        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($e60_numemp);
    } catch(Exception $eErro) {

        $erro_msg = $eErro->getMessage();
        $sqlerro = false;
    }

    if ($sqlerro == false && !empty($oEmpenhoFinanceiro)) {

        if (USE_PCASP) {

            if ($isPrestacaoContas) {
                $c71_coddoc = 410;
            }

            $isProvisaoFerias         = $oEmpenhoFinanceiro->isProvisaoFerias();
            $isProvisaoDecimoTerceiro = $oEmpenhoFinanceiro->isProvisaoDecimoTerceiro();
            $isAmortizacaoDivida      = $oEmpenhoFinanceiro->isAmortizacaoDivida();
            $isPrecatoria             = $oEmpenhoFinanceiro->isPrecatoria();

            if ($isProvisaoFerias) {
                $c71_coddoc = 304;
            }

            if ($isProvisaoDecimoTerceiro) {
                $c71_coddoc = 308;
            }

            if ($isAmortizacaoDivida) {
                $c71_coddoc = 504; // EMPENHO AMORT. DA D�VIDA
            }

            if ($isPrecatoria) {
                $c71_coddoc = 500; // EMPENHO DE PRECAT�RIOS
            }
        }


        for ($i = 0; $i < $numrows09; $i++) {

            db_fieldsmemory($result09, $i);

            $iCodigoDotacao = $e56_coddot;
            $sComplemento   = "Lan�amento do Empenho " . $oEmpenhoFinanceiro->getNumero() . "/" . $oEmpenhoFinanceiro->getAnoUso();

            if (isset($e54_resumo) && !empty($e54_resumo)) {
                $sComplemento .= "  {$e54_resumo}";
            }

            try {

                $oDotacao              = new Dotacao($iCodigoDotacao, $oEmpenhoFinanceiro->getAnoUso());
                $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
                $oContaCorrenteDetalhe->setDotacao($oDotacao);
                $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
                $oContaCorrenteDetalhe->setRecurso($oDotacao->getDadosRecurso());


                $oEventoContabil     = new EventoContabil($c71_coddoc, $anousu);
                $oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
                $oLancamentoAuxiliar->setCaracteristicaPeculiar($clempempenho->e60_concarpeculiar);
                $oLancamentoAuxiliar->setCodigoElemento($iElemento);
                $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
                $oLancamentoAuxiliar->setNumeroEmpenho($e60_numemp);
                $oLancamentoAuxiliar->setValorTotal($oEmpenhoFinanceiro->getValorEmpenho());
                $oLancamentoAuxiliar->setObservacaoHistorico($sComplemento);
                $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
                $oLancamentoAuxiliar->setCodigoDotacao($iCodigoDotacao);
                $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
                $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $dDataMovimento);

                /**
                 * Valida parametro de integracao da contabilidade com contratos
                 */
                // ap�s 2021 este lan�amento ser� feito no modulo de contratos.
                if(db_getsession('DB_anousu') < 2022){
                    /**
                     * Pesquisa contrato do empenho
                     * - caso exista gera lancamento
                     */
                    $oDataImplantacao = new DBDate($dDataMovimento);
                    $oInstituicao     = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
                    if (ParametroIntegracaoPatrimonial::possuiIntegracaoContrato($oDataImplantacao, $oInstituicao)) {

                        $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
                        $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_file(null, "e100_acordo", null, "e100_numemp = {$e60_numemp}");

                        $rsContrato = $oDaoEmpenhoContrato->sql_record($sSqlContrato);

                        if ($oDaoEmpenhoContrato->numrows > 0) {

                            $iCodigoAcordo         = db_utils::fieldsMemory($rsContrato, 0)->e100_acordo;
                            $oAcordo               = new Acordo($iCodigoAcordo);
                            $oEventoContabilAcordo = new EventoContabil(900, $anousu);

                            $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
                            $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
                            $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
                            $oLancamentoAuxiliarAcordo->setValorTotal($oEmpenhoFinanceiro->getValorEmpenho());
                            $oLancamentoAuxiliarAcordo->setDocumento($oEventoContabilAcordo->getCodigoDocumento());

                            $oContaCorrente = new ContaCorrenteDetalhe();
                            $oContaCorrente->setAcordo($oAcordo);
                            $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrente);
                            $oEventoContabilAcordo->executaLancamento($oLancamentoAuxiliarAcordo, $dDataMovimento);
                        }
                    }
                }


            } catch (Exception $eErro) {

                $erro_msg = $eErro->getMessage();
                $sqlerro  = true;
                break;
            }

        }

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //FINAL LAN�AMENTO CONT�BEIS////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LIQUIDA��O////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //rotina que pega os valores dos elementos e coloca na variavel $dados
    if ($sqlerro == false) {
        $result03  = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_vlremp,e64_codele"));
        $numrows03 = $clempelemento->numrows;
        $dados     = '';
        $sep       = '';
        for ($e = 0; $e < $numrows03; $e++) {
            db_fieldsmemory($result03, $e);
            $dados .= $sep . $e64_codele . "-" . $e64_vlremp;
            $sep = '#';
        }
    }
    //**********************************************************/

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //ARQUVO DE LIQUIDA��O////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($sqlerro == false) {
        if ($opc == 1) {
            //$dados tem todos os elementos e seus valores
            //vari�veis necess�rias//
            //$dados =  $elemento-$valorliquidar#$elemento-$valorliquidar#elemen...
            //$e60_numemp =  $e60_numemp;
            //$vlrliq     =  $vlrliq;
            $vlrliq = $e60_vlremp;
            require_once(modification("emp1_empliquidaarq.php"));
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //FINAL DE LIQUIDA��O////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //ARQUVO DE ORDEM DE PAGAMENTO////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($opc == 5 && $sqlerro == false) {
        //variaveis
        //$e50_numemp = $e50_numemp;
        //$e50_obs    = $e50_obs;
        //$dados = $elemento-$valor#elemento-valor#elem..
        //$chaves, � setado quando tiver notas

        $e50_numemp = $e60_numemp;
        $e50_obs    = "Ordem de pagamento";
        require_once(modification("emp1_pagordemarq.php"));
    }
    //FINAL ORDEM DE PAGAMENTO//

// INICIO LIQUIDA��O + NOTA DE LIQUIDA��O
    if ($opc == 2 && $sqlerro == false) {

        /**
         * esta op��o deve :
         * - lan�ar ordem de compra ( modulo compras )
         * - dar entrada dos �tens na ordem de compra com nota ( modulo almoxarifado )
         * - liquidar o empenho com nota , obrigatorio usar nota (modulo contabilidade)
         *
         * // ordem de compra
         *
         * - matordem :  lan�a 1 registro, ordem de compra
         * - matordemitem : �tens da ordem de compra
         *
         * // nota do fornecedor
         *
         * - empnota : nota
         * - empnotaord : liga��o da nota com a ordem de compra
         * - empnotaele : elementos de empenho ligados a nota
         *
         * // entrada no estoque
         *
         * - matestoqueini : estoque
         * - matmater : materiais/itens, cadastro de materiais ( 1:pcmatar ------ N:matmater )
         * - matestoque : uma tabela que sintetiza valores para relatorios do modulo  almox/estoque
         * - matestoqueitem : representa os �tens que estao no almox
         * - matestoqueitemunid : representa como as quantidades s�o representadas dos �tens
         * - matestoqueitemoc : itens ligados a ordem de com pra
         * - matestoqueitemnota : itens ligados a nota ( empnota )
         *
         */
        require_once(modification("mat1_matestoque_arqnota.php"));

        // liquida��o cont�bil
        $clempenho = new empenho();
        $clempenho->liquidar($e60_numemp, $e64_codele, $clempnota->e69_codnota, $e60_vlremp, $e54_resumo);
        $e50_codord = $clempenho->iPagOrdem;
        if ($clempenho->erro_status == '0') {
            $sqlerro  = true;
            $erro_msg = $clempenho->erro_msg;
            db_msgbox($erro_msg);
        }

    }


//ARQUVO DE RETEN��ES///
    if ($sqlerro == false) {
        /**
         * seleciona as reten��es lan�adas na autoriza��o e
         * lan�a para o empenho (duplica-as)
         */
        $result_retencao  = $clempautret->sql_record($clempautret->sql_query($e54_autori, null, "empretencao.*"));
        $numrows_retencao = $clempautret->numrows;
        for ($i = 0; $i < $numrows_retencao; $i++) {
            db_fieldsmemory($result_retencao, $i);
            $clempretencao->e65_seq      = null;
            $clempretencao->e65_receita  = $e65_receita;
            $clempretencao->e65_aliquota = $e65_aliquota;
            $clempretencao->e65_valor    = $e65_valor;
            $clempretencao->incluir($clempretencao->e65_seq);
            if ($clempretencao->erro_status == 0) {
                $erro_msg = $clempretencao->erro_msg;
                $sqlerro  = true;
                break;
            }
            if ($sqlerro == false) {
                $clempempret->e67_numemp      = $e60_numemp;
                $clempempret->e67_seqretencao = $clempretencao->e65_seq;
                $clempempret->incluir($e60_numemp, $clempretencao->e65_seq);
                if ($clempempret->erro_status == 0) {
                    $erro_msg = $clempempret->erro_msg;
                    $sqlerro  = true;
                    break;
                }
            }
        }// end loop
    }
//FINAL DE RETEN��ES//

//     $sqlerro = true;
    // db_msgbox("Registro n�o incluido , transa��o comentada no script emp1_empempenho004");
    /**[Extensao Ordenador Despesa] inclusao_ordenador*/

    db_fim_transacao($sqlerro);
//    if ($sqlerro == false) {
//        /** Solicita assinatura empenho  */
//        $oAssintaraDigital = new AssinaturaDigital();
//        if ($oAssintaraDigital->verificaAssituraAtiva()) {
//            try {
//                $oAssintaraDigital->solicitaAssinaturaEmpenho($oEmpenhoFinanceiro->getNumero());
//            } catch (Exception $eErro) {
//                $sqlerro = true;
//                db_msgbox($eErro);
//            }
//        }
//    }
}
    $db_opcao = 1;
    $db_botao = true;
}else if(isset($chavepesquisa)){
    $db_opcao = 1;

    $buscarOrdenadores = db_utils::fieldsMemory($clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_buscarordenadores", null, "")), 0)->e30_buscarordenadores;
    
    if ($buscarOrdenadores == 1 ) {
        
        $anoUsu = db_getsession("DB_anousu");
        $sWhere = "e56_autori = " . $chavepesquisa . " and e56_anousu = " . $anoUsu;
        $result = $clempautidot->sql_record($clempautidot->sql_query_dotacao(null, "*", null, $sWhere));
        $numrows = $clempautidot->numrows;
        if ($numrows > 0) {
            db_fieldsmemory($result, 0);
        }
        
        $whereUnidades = " o41_anousu = {$o58_anousu} and o41_instit = {$o58_instit} and o41_orgao = {$o58_orgao} and o41_unidade = {$o58_unidade} ";
        $o41_cgmordenador =  db_utils::fieldsMemory($clorcunidade->sql_record($clorcunidade->sql_query(db_getsession("DB_anousu"),null,null, " o41_orddespesa ",null,$whereUnidades)), 0)->o41_orddespesa;
        $result_cgmordespesa = db_query("SELECT z01_nome FROM cgm WHERE z01_numcgm = {$o41_cgmordenador}");
        db_fieldsmemory($result_cgmordespesa, 0)->z01_nome;
        $o41_nomeordenador = $z01_nome;

        $aAssinantes = getAssinantes($chavepesquisa);        
        foreach ($aAssinantes as $assinante) {
            $db243_data_inicio = $assinante->db243_data_inicio;
            $db243_data_final  = $assinante->db243_data_final;
        }
        
    } else {

        $aAssinantes = getAssinantes($chavepesquisa);
        $rowCount = count($aAssinantes);  
        $ordenadoresArray = array(); 
        $uniqueKeys = array(); 
        foreach ($aAssinantes as $assinante) {
            $uniqueKey = $assinante->nome . '|' . $assinante->z01_numcgm;
  
            if ($rowCount > 1) {
                if (!in_array($uniqueKey, $uniqueKeys)) {
                    $ordenadoresArray[] = [
                        'nome' => $assinante->nome,
                        'z01_numcgm' => $assinante->z01_numcgm,
                        'db243_data_inicio' => $assinante->db243_data_inicio,
                        'db243_data_final'  => $assinante->db243_data_final,
                    ];
                    $uniqueKeys[] = $uniqueKey;
                }
            } else {
                
                $o41_cgmordenador = $assinante->z01_numcgm;
                $o41_nomeordenador = $assinante->nome;
                $db243_data_inicio = $assinante->db243_data_inicio;
                $db243_data_final  = $assinante->db243_data_final;
            }
        }
    }
    $result = $clempautoriza->sql_record($clempautoriza->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);

    /* Ocorr�ncia 2630
     * Valida��es de datas para gera��o de autoriza��es de empenhos e gera��o de empenhos
     * 1. Validar impedimento para gera��o de autoriza��es/empenhos com data anterior a data de homologa��o
     * 2. Validar impedimento para gera��o de autoriza��es de empenhos de licita��es que n�o estejam homologadas.
     */
    $sSqlLicitacao = "select e54_emiss
                          from empautoriza
                          inner join liclicita  on ltrim(((string_to_array(e54_numerl, '/'))[1])::varchar,'0') = l20_numero::varchar AND l20_anousu::varchar = ((string_to_array(e54_numerl, '/'))[2])::varchar
                          where e54_autori = {$e54_autori} limit 1";

    if(pg_num_rows(db_query($sSqlLicitacao))) {
        if (strtotime(db_utils::fieldsMemory(db_query($sSqlLicitacao), 0)->e54_emiss) > strtotime($dtDataUsu)) {
            db_msgbox("N�o � permitido emitir empenhos de licita��es cuja data da autoriza��o (".date("d/m/Y",strtotime(db_utils::fieldsMemory(db_query($sSqlLicitacao), 0)->e54_emiss)) .") seja maior que a data de emiss�o do empenho (".$dtDataUsu.").");
            db_redireciona("emp4_empempenho004.php");
        }
    }

    $result=$clempauthist->sql_record($clempauthist->sql_query_file($e54_autori));
    if($clempauthist->numrows>0){
        db_fieldsmemory($result,0);
    }

    $result = $clempautpresta->sql_record($clempautpresta->sql_query_file(null,"*","e58_autori","e58_autori=$e54_autori"));
    if($clempautpresta->numrows>0) {
        db_fieldsmemory($result,0);
        $e44_tipo = $e58_tipo;
    }
    $db_botao = true;

    $result_empretencao = $clempautret->sql_record($clempautret->sql_query_file($e54_autori,null,"*"));
    if($clempautret->numrows){
        $alertar_retencao = true;
    }

    $oDaoAcordoEmpautoriza = db_utils::getDao("acordoempautoriza");
    $sSqlAcordoAutorizacao = $oDaoAcordoEmpautoriza->sql_queryAutorizacaoAcordo(null, "ac16_resumoobjeto,ac45_acordo", null, "ac45_empautoriza = {$chavepesquisa}");
    $rsAcordoAutorizacao   = $oDaoAcordoEmpautoriza->sql_record($sSqlAcordoAutorizacao);

    if ($oDaoAcordoEmpautoriza->numrows > 0) {

        $oDadosAcordo       = db_utils::fieldsMemory($rsAcordoAutorizacao, 0);
        $ac16_sequencial    = $oDadosAcordo->ac45_acordo;
        $ac16_resumoobjeto  = $oDadosAcordo->ac16_resumoobjeto;
        $lAutorizacaoAcordo = true;
    }

}
?>
    <html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/classes/empenho/ViewCotasMensais.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body style="background-color:#CCCCCC; margin-top:30px;" >
    <center>
        <?php
        require_once(Modification::getFile("forms/db_frmempempenhonota.php"));
        ?>
    </center>
    </body>
    </html>

    <script>
        function js_emiteEmpenho(iNumEmp) {

            jan = window.open('emp2_emitenotaemp002.php?e60_numemp='+iNumEmp, '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
            jan.moveTo(0,0);

            document.form1.incluir.disabled = true;
            document.form1.op.disabled = true;
        }

        function js_naoImprimir() {

            document.form1.incluir.disabled = true;
            document.form1.op.disabled = true;
        }
    </script>

<?
if($alertar_retencao == true){
    db_msgbox("Autoriza��o $e54_autori com reten��es!");
}
//rotina que alerta se o usuario n�o tem permiss�o
if(isset($erro_perm)){
    db_msgbox($erro_perm);
    db_redireciona("emp4_empempenho004.php");
}
if(isset($incluir)){

    if($sqlerro == true){
        db_msgbox($erro_msg);

        if ($clempempenho->erro_campo!="") {
            echo "<script> document.form1.".$clempempenho->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clempempenho->erro_campo.".focus();</script>";
        }

    } else {

        $ord ='';
        if(isset($e50_codord) && $e50_codord != ''){
            $ord = "Nota de Liquida��o: $e50_codord";
        }

        $ok_msg = "Inclus�o efetuada com sucesso! \\n Empenho: $e60_codemp\/".db_getsession("DB_anousu")." \\n $ord" ;
        db_msgbox($ok_msg);

        if ( empty($naoimprimir) ) {

            echo "<script>
	 	          js_emiteEmpenho({$e60_numemp});
	 	        </script>";

        } else {


            if (isset($lanc_emp)&&$lanc_emp==true){

                exit;

            } else {
                echo "<script>js_naoImprimir()</script>";
            }
        }
    }
}

echo "
       <script>
          parent.document.formaba.empempitem.disabled = false;\n
          parent.document.formaba.empempdot.disabled  = false;\n
          parent.document.formaba.empprazos.disabled  = false;\n
          CurrentWindow.corpo.iframe_empempitem.location.href = 'emp1_empempitem001.php?db_opcaoal=3&e55_autori=$e54_autori';\n
          CurrentWindow.corpo.iframe_empempdot.location.href  = 'emp1_empempdot001.php?e56_autori=$e54_autori';\n
          CurrentWindow.corpo.iframe_empprazos.location.href  = 'emp1_empempenho007.php?chavepesquisa=$e54_autori';\n
       </script>
   ";

if($db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
}

function getAssinantes($chavepesquisa) 
{
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
                ->leftJoin('orctiporec', 'orctiporec.o15_codigo', '=', 'empautidot.e56_orctiporec')
                ->select('*')
                ->where('empenho.empautidot.e56_autori', '=', $chavepesquisa)
                ->where('empenho.empautidot.e56_anousu', '=', $anoUsu)
                ->get()
                ->toArray();

    if (!empty($results)) {
        $autorizacao = $results[0];
        $o58_instit  =  $autorizacao->o58_instit;
        $o58_orgao   =  $autorizacao->o58_orgao;
        $o58_unidade =  $autorizacao->o58_unidade;
        $o58_anousu  =  $autorizacao->o58_anousu;
    } else {
        return []; 
    }

    $aAssinante = DB::table('configuracoes.assinatura_digital_assinante')
                ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
                ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
                ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
                ->where('configuracoes.assinatura_digital_assinante.db243_instit', '=', $o58_instit)
                ->where('configuracoes.assinatura_digital_assinante.db243_orgao', '=', $o58_orgao)
                ->where('configuracoes.assinatura_digital_assinante.db243_unidade', '=', $o58_unidade)
                ->where('configuracoes.assinatura_digital_assinante.db243_anousu', '=', $o58_anousu)
                ->where('configuracoes.assinatura_digital_assinante.db243_cargo', '=', 1)
                ->where('configuracoes.assinatura_digital_assinante.db243_documento', '=', 0)
                ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo', 'protocolo.cgm.z01_numcgm', 'configuracoes.assinatura_digital_assinante.db243_data_inicio', 'configuracoes.assinatura_digital_assinante.db243_data_final')
                ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
                ->get()
                ->toArray(); 

    return $aAssinante;
}

function getAssinantesCgm($chavepesquisa, $o41_cgmordenador) 
{
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
                ->leftJoin('orctiporec', 'orctiporec.o15_codigo', '=', 'empautidot.e56_orctiporec')
                ->select('*')
                ->where('empenho.empautidot.e56_autori', '=', $chavepesquisa)
                ->where('empenho.empautidot.e56_anousu', '=', $anoUsu)
                ->get()
                ->toArray();

    if (!empty($results)) {
        $autorizacao = $results[0];
        $o58_instit  =  $autorizacao->o58_instit;
        $o58_orgao   =  $autorizacao->o58_orgao;
        $o58_unidade =  $autorizacao->o58_unidade;
        $o58_anousu  =  $autorizacao->o58_anousu;
    } else {
        return []; 
    }

    $aAssinante = DB::table('configuracoes.assinatura_digital_assinante')
                ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
                ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
                ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
                ->where('configuracoes.assinatura_digital_assinante.db243_instit', '=', $o58_instit)
                ->where('configuracoes.assinatura_digital_assinante.db243_orgao', '=', $o58_orgao)
                ->where('configuracoes.assinatura_digital_assinante.db243_unidade', '=', $o58_unidade)
                ->where('configuracoes.assinatura_digital_assinante.db243_anousu', '=', $o58_anousu)
                ->where('protocolo.cgm.z01_numcgm', '=', $o41_cgmordenador)
                ->where('configuracoes.assinatura_digital_assinante.db243_cargo', '=', 1)
                ->where('configuracoes.assinatura_digital_assinante.db243_documento', '=', 0)
                ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo', 'protocolo.cgm.z01_numcgm', 'configuracoes.assinatura_digital_assinante.db243_data_inicio', 'configuracoes.assinatura_digital_assinante.db243_data_final')
                ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
                ->get()
                ->toArray(); 

    return $aAssinante;
}

