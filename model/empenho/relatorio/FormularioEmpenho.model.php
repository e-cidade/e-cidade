<?php

require_once("fpdf151/impcarne.php");
require_once("fpdf151/scpdf.php");
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_empempitem_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_cgmalt_classe.php");
require_once("classes/db_pcforneconpad_classe.php");
require_once("classes/db_emite_nota_empenho.php");
require_once("model/orcamento/ControleOrcamentario.model.php");
require_once("model/configuracao/InstituicaoRepository.model.php");
require_once("libs/exceptions/BusinessException.php");
require_once("model/protocolo/AssinaturaDigital.model.php");
require_once("model/empenho/EmpenhoFinanceiro.model.php" );

class FormularioEmpenho
{
    private $oAssintaraDigital;
    private $pdfEmpenho;
    private $anousu;
    private $clempparametro;
    private $clempautitem;
    private $clcgmalt;
    private $cldb_pcforneconpad;
    private $clemite_nota_emp;
    private $empenho;
    private $empParametro;
    private $numEmpenho;

    private $oInstituicao;

    public function __construct()
    {
        $this->oAssintaraDigital = new AssinaturaDigital();
        $pdfBase = new scpdf();
        $pdfBase->Open();
        $this->pdfEmpenho = new db_impcarne($pdfBase, '6');
        $this->anousu = db_getsession("DB_anousu");
        $this->clempparametro = new cl_empparametro;
        $this->clempautitem = new cl_empautitem;
        $this->clcgmalt = new cl_cgmalt;
        $this->cldb_pcforneconpad = new cl_pcforneconpad;
        $this->clemite_nota_emp = new cl_emite_nota_empenho;
        $this->getEmpParametros();
        $this->getInstituicao();
    }

    private function criaPDF()
    {

        $this->pdfEmpenho->objpdf->SetTextColor(0, 0, 0);
        $dbwhere     = " e60_numemp = $this->numEmpenho ";
        $sqlemp = $this->clemite_nota_emp->get_sql_empenho(db_getsession("DB_anousu"), db_getsession("DB_instit"), $dbwhere);
        $result = db_query($sqlemp);

        if (pg_num_rows($result) == 0) {
            db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado !  ");
        }

        $this->empenho = db_utils::fieldsMemory($result, 0);
        $this->pdfEmpenho->nvias = $this->empParametro->e30_nroviaemp;
        $this->pdfEmpenho->casadec = $this->empParametro->e30_numdec;
        $this->pdfEmpenho->dadosbancoemprenho = $this->empParametro->e30_dadosbancoempenho;
        $this->pdfEmpenho->evento = $this->empenho->evento;

        $sSqlPcFornecOnPad = $this->cldb_pcforneconpad->sql_query(null, "*", null, "pc63_numcgm = {$this->empenho->e60_numcgm}");
        $rsSqlPcFornecOnPad = $this->cldb_pcforneconpad->sql_record($sSqlPcFornecOnPad);

        if (!$rsSqlPcFornecOnPad == false && $this->cldb_pcforneconpad->numrows > 0) {
            $oPcFornecOnPad = db_utils::fieldsMemory($rsSqlPcFornecOnPad, 0);
        } else {

            $oPcFornecOnPad = new stdClass();
            $oPcFornecOnPad->pc63_banco = '';
            $oPcFornecOnPad->pc63_agencia = '';
            $oPcFornecOnPad->pc63_agencia_dig = '';
            $oPcFornecOnPad->pc63_conta = '';
            $oPcFornecOnPad->pc63_conta_dig = '';
        }

        $sSqlPacto = $this->clemite_nota_emp->get_sql_pacto($this->empenho->e61_autori);
        $rsPacto = db_query($sSqlPacto);

        $o74_descricao = null;
        $o78_pactoplano = null;
        if (@pg_num_rows($rsPacto) > 0) {

            $oPacto = db_utils::fieldsMemory($rsPacto, 0);
            $o74_descricao = $oPacto->o74_descricao;
            $o78_pactoplano = $oPacto->o74_sequencial;
        }

        $sProcessoAdministrativo = $this->clemite_nota_emp->get_sql_processo_administrativo($this->empenho->e61_autori);

        $sCondtipos = "";
        if (isset($tipos) && !empty($tipos)) {
            $sCondtipos = " $tipos as tipos, ";
        }

        $sqlitem = $this->clemite_nota_emp->get_sql_item($sCondtipos, $this->empenho->e60_numemp);
        $resultitem = db_query($sqlitem);

        db_fieldsmemory($resultitem);

        $result_cgmalt = $this->clcgmalt->sql_record($this->clcgmalt->sql_query_file(null, "z05_numcgm as z01_numcgm,z05_nome as z01_nome,z05_telef as z01_telef,z05_ender as z01_ender,z05_numero as z01_numero,z05_munic as z01_munic,z05_cgccpf as z01_cgccpf,z05_cep as z01_cep", " abs(z05_data_alt - date '".$this->empenho->e60_emiss."') asc, z05_sequencia desc limit 1", "z05_numcgm = ".$this->empenho->z01_numcgm." and z05_data_alt > '".$this->empenho->e60_emiss."' "));
        $oCgmalt = null;
        if ($this->clcgmalt->numrows > 0) {
            $oCgmalt = db_utils::fieldsMemory($result_cgmalt, 0);
        }

        /**
         * Verificamos o cnpj da unidade. caso diferente de null, e diferente do xcnpj da instituição,
         * mostramso a descrição e o cnpj da unidade
         */
        if ($oCgmalt) {
            $nomeinst = $oCgmalt->o41_descr;
            $cgc = $oCgmalt->o41_cnpj;
        }

        $sSqlFuncaoOrdenaPagamento = $this->clemite_nota_emp->get_sql_funcao_ordena_pagamento($this->empenho->cgmpaga,
            date('Y', strtotime($this->empenho->e60_emiss)),
            date('m', strtotime($this->empenho->e60_emiss)));


        $this->pdfEmpenho->cargoordenapagamento = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenaPagamento), 0)->cargoordenapagamento;

        $sSqlFuncaoOrdenadespesa = $this->clemite_nota_emp->get_sql_funcao_ordena_despesa(
            $this->empenho->cgmordenadespesa,
            date('Y', strtotime($this->empenho->e60_emiss)),
            date('m', strtotime($this->empenho->e60_emiss))
        );

        $this->pdfEmpenho->cargoordenadespesa = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenadespesa), 0)->cargoordenadespesa;

        //assinaturas
        $this->pdfEmpenho->ordenadespesa = $this->empenho->ordenadesp;
        $this->pdfEmpenho->liquida = $this->empenho->liquida;
        $this->pdfEmpenho->ordenapagamento = $this->empenho->ordenapaga;
        $this->pdfEmpenho->contador = $this->empenho->contador;
        $this->pdfEmpenho->crccontador = $this->empenho->crc;
        $this->pdfEmpenho->controleinterno = $this->empenho->controleinterno;
        $this->pdfEmpenho->emptipo = $this->empenho->e41_descr;
        $this->pdfEmpenho->prefeitura = $this->oInstituicao->nomeinst;
        $this->pdfEmpenho->enderpref = $this->oInstituicao->ender . ", " . $this->oInstituicao->numero;
        $this->pdfEmpenho->cgcpref = $this->oInstituicao->cgc;
        $this->pdfEmpenho->municpref = $this->oInstituicao->munic;
        $this->pdfEmpenho->telefpref = $this->oInstituicao->telef;
        $this->pdfEmpenho->emailpref = $this->oInstituicao->email;
        $this->pdfEmpenho->inscricaoestadualinstituicao = '';
        $this->pdfEmpenho->numcgm = $this->empenho->z01_numcgm;
        $this->pdfEmpenho->nome = $this->empenho->z01_nome;
        $this->pdfEmpenho->telefone = $this->empenho->z01_telef;
        $this->pdfEmpenho->ender = $this->empenho->z01_ender;
        $this->pdfEmpenho->bairro = $this->empenho->z01_bairro;
        $this->pdfEmpenho->munic = $this->empenho->z01_munic;
        $this->pdfEmpenho->cnpj = $this->empenho->z01_cgccpf;
        $this->pdfEmpenho->cep = $this->empenho->z01_cep;
        $this->pdfEmpenho->ufFornecedor = $this->empenho->z01_uf;
        $this->pdfEmpenho->prazo_entrega = $this->empenho->e54_praent;
        $this->pdfEmpenho->condicao_pagamento = $this->empenho->e54_conpag;
        $this->pdfEmpenho->outras_condicoes = $this->empenho->e54_codout;
        $this->pdfEmpenho->iBancoFornecedor = $oPcFornecOnPad->pc63_banco;
        $this->pdfEmpenho->iAgenciaForncedor = $oPcFornecOnPad->pc63_agencia . "-" . $oPcFornecOnPad->pc63_agencia_dig;
        $this->pdfEmpenho->iContaForncedor = $oPcFornecOnPad->pc63_conta . "-" . $oPcFornecOnPad->pc63_conta_dig;
        $this->pdfEmpenho->dotacao = $this->empenho->estrutural;
        $this->pdfEmpenho->solicitacao = $pc10_numero;
        $this->pdfEmpenho->num_licitacao = $this->empenho->e60_numerol;
        $this->pdfEmpenho->cod_concarpeculiar = $this->empenho->e60_concarpeculiar;
        $this->pdfEmpenho->descr_concarpeculiar = substr($this->empenho->c58_descr, 0, 34);
        $this->pdfEmpenho->logo = $this->oInstituicao->logo;
        $this->pdfEmpenho->SdescrPacto = $o74_descricao;
        $this->pdfEmpenho->iPlanoPacto = $o78_pactoplano;
        $this->pdfEmpenho->contrapartida = $e56_orctiporec;
        $this->pdfEmpenho->observacaoitem = "pc23_obs";
        $this->pdfEmpenho->Snumeroproc = "pc81_codproc";
        $this->pdfEmpenho->Snumero = "pc11_numero";
        $this->pdfEmpenho->marca = "e55_marca";
        $this->pdfEmpenho->processo_administrativo = $sProcessoAdministrativo;
        $this->pdfEmpenho->coddot = $this->empenho->o58_coddot;
        $this->pdfEmpenho->destino = $this->empenho->e60_destin;
        $this->pdfEmpenho->licitacao = $this->empenho->e60_codtipo;
        $this->pdfEmpenho->recorddositens = $resultitem;
        $this->pdfEmpenho->linhasdositens = pg_num_rows($resultitem);
        //Zera as variáveis
        $this->pdfEmpenho->resumo = "";
        $resumo_lic = "";

        $this->pdfEmpenho->edital_licitacao = $this->empenho->e60_numerol;
        $this->pdfEmpenho->modalidade = $this->empenho->e54_nummodalidade;
        $this->pdfEmpenho->descr_tipocompra = $this->empenho->pc50_descr;

        $result_licita = $this->clempautitem->sql_record($this->clempautitem->sql_query_lic(null, null, "distinct l20_edital, l20_numero, l20_anousu, l20_objeto,l03_descr", null, "e55_autori = ".$this->empenho->e54_autori) );

        if ($this->clempautitem->numrows > 0) {
            $licitacao = db_fieldsmemory($result_licita, 0);
            $this->pdfEmpenho->edital_licitacao = $licitacao->l20_edital . '/' . $licitacao->l20_anousu;
            $this->pdfEmpenho->ano_licitacao = $licitacao->l20_anousu;
            $this->pdfEmpenho->modalidade = $licitacao->l20_numero . '/' . $licitacao->l20_anousu;
            $resumo_lic = $licitacao->l20_objeto;
            $this->pdfEmpenho->observacaoitem = "pc23_obs";
        }

        if (isset($resumo_lic) && $resumo_lic != "") {
            if ($e30_impobslicempenho == 't') {
                $this->pdfEmpenho->resumo = $resumo_lic . "\n" . $this->empenho->e60_resumo;
            } else {
                $this->pdfEmpenho->resumo = $this->empenho->e60_resumo;
            }
        } else {
            $this->pdfEmpenho->resumo = $this->empenho->e60_resumo;
        }

        $Sresumo = $this->pdfEmpenho->resumo;
        $vresumo = explode("\n", $Sresumo);

        if (count($vresumo) > 1) {
            $Sresumo = "";
            $separador = "";
            for ($x = 0; $x < count($vresumo); $x++) {
                if (trim($vresumo[$x]) != "") {
                    $separador = ". ";
                    $Sresumo .= $vresumo[$x] . $separador;
                }
            }
        }

        if (count($vresumo) == 0) {
            $Sresumo = str_replace("\n", ". ", $Sresumo);
        }

        $Sresumo = str_replace("\r", "", $Sresumo);

        $this->pdfEmpenho->resumo = substr($Sresumo, 0, 730);

        if (in_array($this->empenho->e54_tipoautorizacao, array('0', '1', '2', '3', '4'))) {

            $oAutoriza = $this->clemite_nota_emp->get_dados_licitacao($this->empenho->e54_tipoautorizacao, $this->empenho->e54_autori, $this->empenho->pc50_descr);

            $this->pdfEmpenho->edital_licitacao = $oAutoriza->edital_licitacao;
            $this->pdfEmpenho->modalidade = $oAutoriza->modalidade;
            $this->pdfEmpenho->resumo = $this->empenho->e60_resumo;
            $this->pdfEmpenho->descr_tipocompra = $oAutoriza->descr_tipocompra;
            $this->pdfEmpenho->descr_modalidade = $oAutoriza->descr_modalidade;
        }

        $oAcordo = $this->clemite_nota_emp->get_acordo($this->empenho->e60_numemp);

        $this->pdfEmpenho->resumo = substr($this->pdfEmpenho->resumo, 0, 730);

        if (!empty($e54_praent)) {
            $this->pdfEmpenho->prazo_ent = $e54_praent;
        } else {
            $this->pdfEmpenho->prazo_ent = db_utils::fieldsMemory($resultitem, 0)->l20_prazoentrega;
        }

        $this->pdfEmpenho->quantitem = "e62_quant";
        $this->pdfEmpenho->valoritem = "e62_vltot";
        $this->pdfEmpenho->valor = "e62_vlrun";
        $this->pdfEmpenho->descricaoitem = "pc01_descrmater";
        $this->pdfEmpenho->complmater = "pc01_complmater";
        $this->pdfEmpenho->sequenitem = "e62_sequen";
        $this->pdfEmpenho->m61_descr = "m61_descr";

        $this->pdfEmpenho->orcado = $this->empenho->e60_vlrorc;
        $this->pdfEmpenho->saldo_ant = $this->empenho->e60_salant;
        $this->pdfEmpenho->empenhado = $this->empenho->e60_vlremp;
        $this->pdfEmpenho->numemp = $this->empenho->e60_numemp;
        /*OC4401*/
        $this->pdfEmpenho->usuario = $this->empenho->nome;
        /*FIM - OC4401*/
        $this->pdfEmpenho->codemp = $this->empenho->e60_codemp;
        $this->pdfEmpenho->numaut = $this->empenho->e61_autori;
        $this->pdfEmpenho->orgao = $this->empenho->o58_orgao;
        $this->pdfEmpenho->descr_orgao = $this->empenho->o40_descr;
        $this->pdfEmpenho->unidade = $this->empenho->o58_unidade;
        $this->pdfEmpenho->descr_unidade = $this->empenho->o41_descr;
        $this->pdfEmpenho->funcao = $this->empenho->o58_funcao;
        $this->pdfEmpenho->descr_funcao = $this->empenho->o52_descr;
        $this->pdfEmpenho->subfuncao = $this->empenho->o58_subfuncao;
        $this->pdfEmpenho->descr_subfuncao = $this->empenho->o53_descr;
        $this->pdfEmpenho->programa = $this->empenho->o58_programa;
        $this->pdfEmpenho->descr_programa = $this->empenho->o54_descr;
        $this->pdfEmpenho->projativ = $this->empenho->o58_projativ;
        $this->pdfEmpenho->descr_projativ = $this->empenho->o55_descr;
        $this->pdfEmpenho->analitico = "o56_elemento";
        $this->pdfEmpenho->descr_analitico = "o56_descr";
        $this->pdfEmpenho->sintetico = $this->empenho->sintetico;
        $this->pdfEmpenho->descr_sintetico = $this->empenho->descr_sintetico;
        $this->pdfEmpenho->recurso = $this->empenho->o58_codigo;
        $this->pdfEmpenho->descr_recurso = $this->empenho->o15_descr;
        $this->pdfEmpenho->banco = null;
        $this->pdfEmpenho->agencia = null;
        $this->pdfEmpenho->conta = null;
        $this->pdfEmpenho->tipos = $this->empenho->tipos;
        $this->pdfEmpenho->numero = $this->empenho->z01_numero;
        $this->pdfEmpenho->marca = 'e55_marca';
        $this->pdfEmpenho->acordo = $oAcordo->ac16_numeroacordo;
        $this->pdfEmpenho->anoacordo = $oAcordo->ac16_anousu;
        $this->pdfEmpenho->seqacordo = $oAcordo->ac16_sequencial;


        $clControleOrc = new ControleOrcamentario;
        $e60_codco = $this->empenho->e60_codco == null ? '0000' : $this->empenho->e60_codco;
        $clControleOrc->setCodCO($e60_codco);

        $this->pdfEmpenho->codco = $e60_codco . ' - ' . $clControleOrc->getDescricaoResumoCO();

        $sql = "select c61_codcon
              from conplanoreduz
                   inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu
                   inner join consistema on c52_codsis = c60_codsis
             where c61_instit   = " . db_getsession("DB_instit") . "
               and c61_anousu   =" . db_getsession("DB_anousu") . "
               and c61_codigo   = ".$this->empenho->o58_codigo."
               and c52_descrred = 'F' ";
        $result_conta = db_query($sql);

        if ($result_conta != false && (pg_numrows($result_conta) == 1)) {

            $oPlanoConta = db_utils::fieldsMemory($result_conta, 0);
            $sqlconta = "select * from conplanoconta where c63_codcon = $oPlanoConta->c61_codcon and c63_anousu = " . db_getsession("DB_anousu");
            $result_conta = db_query($sqlconta);

            if (pg_result($result_conta, 0) == 1) {

                $oConta = db_utils::fieldsMemory($result_conta, 0);
                $this->pdfEmpenho->banco = $oConta->c63_banco;
                $this->pdfEmpenho->agencia = $oConta->c63_agencia;
                $this->pdfEmpenho->conta = $oConta->c63_conta;
            }
        }

        $this->pdfEmpenho->emissao = db_formatar($this->empenho->e60_emiss, 'd');
        $this->pdfEmpenho->texto = "";
        $this->pdfEmpenho->imprime();

    }

    public function gerarFormularioEmpenho($e60_numemp)
    {
        $this->numEmpenho = $e60_numemp;
        $this->criaPDF();
    }

    public function solitarAssinaturaEmpenho()
    {
        try {
            $sInstituicao = str_replace(" ", "_", strtoupper($this->oInstituicao->nomeinstabrev));
            $nomeDocumento = "EMPENHO_{$this->empenho->e60_codemp}_{$this->empenho->e60_anousu}_{$sInstituicao}.pdf";
            $this->pdfEmpenho->objpdf->Output("tmp/$nomeDocumento", false, true);
            $this->oAssintaraDigital->gerarArquivoBase64($nomeDocumento);
            $this->oAssintaraDigital->assinarEmpenho($this->empenho->e60_numemp, $this->empenho->e60_coddot, $this->empenho->e60_anousu, $this->empenho->e60_emiss, $nomeDocumento, $this->empenho->e60_codemp."/".$this->empenho->e60_anousu);
        } catch (Exception $eErro) {
           throw new Exception($eErro->getMessage());
        }
    }

    public function geraPDF()
    {
        $this->pdfEmpenho->objpdf->Output();
    }

    private function getEmpParametros()
    {
        try {
                $sCampos = "e30_nroviaemp, e30_numdec, e30_impobslicempenho, e30_dadosbancoempenho";
                $sSqlEmpParam = $this->clempparametro->sql_query_file($this->anousu, $sCampos);
                $result02 = $this->clempparametro->sql_record($sSqlEmpParam);
                if ($this->clempparametro->numrows == 0) {
                    throw new Exception("Nenhum registro encontrado na empparametro!");
                }
                $this->empParametro = db_utils::fieldsMemory($result02, 0);
            } catch (Exception $eErro) {
                throw new Exception($eErro->getMessage());
        }
    }

    private function getInstituicao()
    {
        $sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao, nomeinstabrev ";
        $sqlpref .= "  from db_config                                                     ";
        $sqlpref .= " inner join cgm on cgm.z01_numcgm = db_config.numcgm                 ";
        $sqlpref .=    "	where codigo = " . db_getsession("DB_instit");
        $this->oInstituicao = db_utils::fieldsMemory(db_query($sqlpref), 0);
    }

}
