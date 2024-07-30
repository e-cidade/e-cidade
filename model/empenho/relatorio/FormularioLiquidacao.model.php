<?php

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_pagordem_classe.php");
require_once("classes/db_pagordemele_classe.php");
require_once("model/retencaoNota.model.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_emite_nota_liq.php");
require_once("classes/db_empdiaria_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("model/orcamento/ControleOrcamentario.model.php");
require_once("model/protocolo/AssinaturaDigital.model.php");

class FormularioLiquidacao
{
    private $oAssintaraDigital;
    private $pdfLiquidacao;
    private $anousu;

    private $empParametro;

    private $numEmpenho;

    private $oInstituicao;
    private $clpagordem;
    private $clpagordemele;
    private $clempautitem;
    private $clempagetipo;
    private $clemite_nota_liq;
    private $clDiaria;
    private $clRhpessoal;

    private $liquidacao;

    private $clempparametro;

    public function __construct()
    {
        $this->clpagordem     = new cl_pagordem;
        $this->clpagordemele  = new cl_pagordemele;
        $this->clempautitem   = new cl_empautitem;
        $this->clempagetipo   = new cl_empagetipo;
        $this->clemite_nota_liq = new cl_emite_nota_liq;
        $this->clempparametro = new cl_empparametro();
        $this->clDiaria = new cl_empdiaria;
        $this->clRhpessoal = new cl_rhpessoal;
        $this->oAssintaraDigital =  new AssinaturaDigital();
        $this->anousu = db_getsession("DB_anousu");
        $this->getInstituicao();
        $this->getEmpParametros();
    }

    private function criaPDF()
    {
        $pdf = new scpdf();
        $pdf->Open();
        $modelo = $this->empParametro->e30_modeloop == 2 ? '7_alt' : '7';
        $this->pdfLiquidacao = new db_impcarne($pdf, $modelo);
        $this->pdfLiquidacao->objpdf->SetTextColor(0,0,0);
        $this->pdfLiquidacao->nvias= $this->empParametro->e30_nroviaord;
        $dbwhere = " e60_numemp = ".$this->numEmpenho;
        $sSqlPagordem = $this->clpagordem->sql_query_notaliquidacao('','e50_codord, e71_codnota, e60_numerol, pc50_descr, e50_numliquidacao, e50_numemp, e50_data, z01_cgccpf','e50_codord ', $dbwhere);
        $result = $this->clpagordem->sql_record($sSqlPagordem);
        $notaliquidacao = db_utils::fieldsMemory($result, 0);

        $oRetencaoNota = new retencaoNota($notaliquidacao->e71_codnota);
        $oRetencaoNota->setINotaLiquidacao($notaliquidacao->e50_codord);

        $sql = $this->clemite_nota_liq->get_sql_ordem_pagamento(db_getsession("DB_instit"), db_getsession("DB_anousu"), $notaliquidacao->e50_codord);
        $resultord = db_query($sql);

        $this->liquidacao = db_utils::fieldsMemory($resultord, 0);

        $sql1         = $this->clemite_nota_liq->get_sql_assinaturas($notaliquidacao->e50_codord);
        $resultordass = db_query($sql1);
        $oAssinaturas = db_utils::fieldsMemory($resultordass, 0);

        $sqlitem      = $this->clemite_nota_liq->get_sql_item_ordem($notaliquidacao->e50_codord, $notaliquidacao->e50_data);
        $resultitem   = db_query($sqlitem);

        $sqloutrasordens      = $this->clemite_nota_liq->get_sql_outras_ordens($notaliquidacao->e50_codord, $notaliquidacao->e50_numemp, $notaliquidacao->e50_data);
        $resultoutrasordens   = db_query($sqloutrasordens);
        $outrasordens =  db_utils::fieldsMemory($resultoutrasordens, 0)->outrasordens;

        $aRetencoes = $oRetencaoNota->getRetencoesFromDB($notaliquidacao->e50_codord, false, 0, "","",true);

        $sqlDiaria = $this->clDiaria->sql_query(null,'empdiaria.*,e60_numcgm',null,' e140_codord = '.$this->liquidacao->e50_codord);
        $resultDiaria   = db_query($sqlDiaria);

        if(pg_num_rows($resultDiaria) > 0){

            $diaria = db_utils::fieldsMemory($resultDiaria,0);
            $diaria->matricula = $diaria->e140_matricula;
            $diaria->cargo = $diaria->e140_cargo;
            $separador = '/ *-/';
            $diaria->origem = preg_split($separador, $diaria->e140_origem);
            $diaria->destino = preg_split($separador, $diaria->e140_destino);
            $diaria->e140_dtautorizacao = $diaria->e140_dtautorizacao == null ? '' : date('d/m/Y', strtotime($diaria->e140_dtautorizacao));
            $diaria->e140_dtinicial = $diaria->e140_dtinicial == null ? '' : date('d/m/Y', strtotime($diaria->e140_dtinicial));
            $diaria->e140_dtfinal = $diaria->e140_dtfinal == null ? '' : date('d/m/Y', strtotime($diaria->e140_dtfinal));
            $this->pdfLiquidacao->diaria = $diaria;

        } else {

            $this->pdfLiquidacao->diaria = null;

        }

        $sSqlFuncaoOrdenaPagamento = $this->clemite_nota_liq->get_sql_funcao_ordena_pagamento($oAssinaturas->cgmpaga);
        $this->pdfLiquidacao->cargoordenapagamento = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenaPagamento),0)->cargoordenapagamento;

        $sSqlFuncaoOrdenadespesa = $this->clemite_nota_liq->get_sql_funcao_ordena_despesa($oAssinaturas->cgmordenadespesa);
        $this->pdfLiquidacao->cargoordenadespesa = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenadespesa),0)->cargoordenadespesa;

        $sSqlFuncaoLiquida = $this->clemite_nota_liq->get_sql_funcao_ordena_liquida($oAssinaturas->cgmliquida);
        $this->pdfLiquidacao->cargoliquida = db_utils::fieldsMemory(db_query($sSqlFuncaoLiquida),0)->cargoliquida;

        $this->pdfLiquidacao->usuario = $this->liquidacao->usuario;

        //assinaturas
        $this->pdfLiquidacao->ordenadespesa      = $oAssinaturas->assindsp;
        $this->pdfLiquidacao->liquida            = $oAssinaturas->assinlqd;
        $this->pdfLiquidacao->ordenapagamento    = $oAssinaturas->assinord;
        $this->pdfLiquidacao->contador           = $this->liquidacao->contador;
        $this->pdfLiquidacao->crccontador        = $this->liquidacao->crc;
        $this->pdfLiquidacao->controleinterno    = $this->liquidacao->controleinterno;
        $this->pdfLiquidacao->numliquidacao      = $this->liquidacao->e50_numliquidacao;
        $this->pdfLiquidacao->numeronota         = $this->liquidacao->e69_numero;
        $this->pdfLiquidacao->datanota           = $this->liquidacao->e69_dtnota;
        $this->pdfLiquidacao->valor_ordem        = '';
        $this->pdfLiquidacao->logo               = $this->oInstituicao->logo;
        $this->pdfLiquidacao->processo           = $this->liquidacao->e60_numerol;
        $this->pdfLiquidacao->descr_tipocompra   = $this->liquidacao->pc50_descr;
        $this->pdfLiquidacao->prefeitura         = $this->oInstituicao->nomeinst;
        $this->pdfLiquidacao->enderpref          = $this->oInstituicao->ender;
        $this->pdfLiquidacao->municpref          = $this->oInstituicao->munic;
        $this->pdfLiquidacao->telefpref          = $this->oInstituicao->telef;
        $this->pdfLiquidacao->emailpref          = $this->oInstituicao->email;
        $this->pdfLiquidacao->cgcpref            = $this->oInstituicao->cgc;
        $this->pdfLiquidacao->banco              = $this->liquidacao->pc63_banco;
        $this->pdfLiquidacao->agencia            = $this->liquidacao->pc63_agencia;
        $this->pdfLiquidacao->agenciadv          = $this->liquidacao->pc63_agencia_dig;
        $this->pdfLiquidacao->conta              = $this->liquidacao->pc63_conta;
        $this->pdfLiquidacao->tipoconta          = $this->liquidacao->pc63_tipoconta;
        $this->pdfLiquidacao->contadv            = $this->liquidacao->pc63_conta_dig;
        $this->pdfLiquidacao->numcgm             = $this->liquidacao->z01_numcgm;
        $this->pdfLiquidacao->nome               = $this->liquidacao->z01_nome;
        $this->pdfLiquidacao->cnpj               = $this->liquidacao->z01_cgccpf;
        $this->pdfLiquidacao->ender              = $this->liquidacao->z01_ender;
        $this->pdfLiquidacao->bairro             = $this->liquidacao->z01_bairro;
        $this->pdfLiquidacao->cep                = $this->liquidacao->z01_cep;
        $this->pdfLiquidacao->ufFornecedor       = $this->liquidacao->z01_uf;
        $this->pdfLiquidacao->munic              = $this->liquidacao->z01_munic;
        $this->pdfLiquidacao->ordpag             = $this->liquidacao->e50_codord;
        $this->pdfLiquidacao->coddot             = $this->liquidacao->o58_coddot;
        $this->pdfLiquidacao->dotacao            = $this->liquidacao->estrutural;
        $this->pdfLiquidacao->outrasordens       = $outrasordens;
        $this->pdfLiquidacao->recorddositens     = $resultitem;
        $this->pdfLiquidacao->ano		         = $this->liquidacao->e60_anousu;
        $this->pdfLiquidacao->linhasdositens     = pg_num_rows($resultitem);
        $this->pdfLiquidacao->elementoitem       = "o56_elemento";
        $this->pdfLiquidacao->descr_elementoitem = "o56_descr";
        $this->pdfLiquidacao->vlremp             = "e53_valor";
        $this->pdfLiquidacao->vlranu             = "e53_vlranu";
        $this->pdfLiquidacao->vlrpag             = "e53_vlrpag";
        $this->pdfLiquidacao->vlrsaldo           = "saldo";
        $this->pdfLiquidacao->saldo_final        = "saldo_final";
        $this->pdfLiquidacao->aRetencoes         = $aRetencoes;
        $this->pdfLiquidacao->orcado	         = $this->liquidacao->e60_vlrorc;
        $this->pdfLiquidacao->saldo_ant          = $this->liquidacao->e60_salant;
        $this->pdfLiquidacao->empenhado          = $this->liquidacao->e60_vlremp;
        $this->pdfLiquidacao->empenho_anulado    = $this->liquidacao->e60_vlranu;
        $this->pdfLiquidacao->codemp             = $this->liquidacao->e60_codemp;
        $this->pdfLiquidacao->numemp             = $this->liquidacao->e60_codemp.'/'.$this->liquidacao->e60_anousu;
        $this->pdfLiquidacao->orgao              = $this->liquidacao->o58_orgao;
        $this->pdfLiquidacao->descr_orgao        = $this->liquidacao->o40_descr;
        $this->pdfLiquidacao->unidade            = $this->liquidacao->o58_unidade;
        $this->pdfLiquidacao->descr_unidade      = $this->liquidacao->o41_descr;
        $this->pdfLiquidacao->funcao             = $this->liquidacao->o58_funcao;
        $this->pdfLiquidacao->descr_funcao       = $this->liquidacao->o52_descr;
        $this->pdfLiquidacao->subfuncao          = $this->liquidacao->o58_subfuncao;
        $this->pdfLiquidacao->descr_subfuncao    = $this->liquidacao->o53_descr;
        $this->pdfLiquidacao->programa           = $this->liquidacao->o58_programa;
        $this->pdfLiquidacao->descr_programa     = $this->liquidacao->o54_descr;
        $this->pdfLiquidacao->projativ           = $this->liquidacao->o58_projativ;
        $this->pdfLiquidacao->descr_projativ     = $this->liquidacao->o55_descr;
        $this->pdfLiquidacao->recurso            = $this->liquidacao->o58_codigo;
        $this->pdfLiquidacao->descr_recurso      = $this->liquidacao->o15_descr;
        $this->pdfLiquidacao->elemento     	     = $this->liquidacao->o56_elemento;
        $this->pdfLiquidacao->descr_elemento     = $this->liquidacao->o56_descr;
        $this->pdfLiquidacao->obs		         = substr($this->liquidacao->e50_obs,0,300);
        $this->pdfLiquidacao->emissao            = db_formatar($this->liquidacao->e50_data,'d');
        $this->pdfLiquidacao->texto		         = db_getsession("DB_login").'  -  '.date("d-m-Y",db_getsession("DB_datausu")).'    '.db_hora(db_getsession("DB_datausu"));
        $this->pdfLiquidacao->telef              = $this->liquidacao->z01_telef;
        $this->pdfLiquidacao->fax                = $this->liquidacao->z01_numero;


        $clControleOrc = new ControleOrcamentario;
        $e60_codco = $this->liquidacao->e60_codco == null ? '0000' : $this->liquidacao->e60_codco;
        $clControleOrc->setCodCO($e60_codco);

        $this->pdfLiquidacao->codco  = $e60_codco.' - '.$clControleOrc->getDescricaoResumoCO();


        /**
         * Variáveis utilizadas na assinatura. Sómente utilizada na impressão por movimento
         */
        $this->pdfLiquidacao->iReduzido         = "";
        $this->pdfLiquidacao->sContaContabil    = "";
        $this->pdfLiquidacao->sBanco            = "";
        $this->pdfLiquidacao->sAgencia          = "";
        $this->pdfLiquidacao->sDigtoAgencia     = "";
        $this->pdfLiquidacao->sContaBanco       = "";
        $this->pdfLiquidacao->sDigitoContaBanco = "";
        $this->pdfLiquidacao->iTipoPagamento    = "";
        $this->pdfLiquidacao->sCheque           = "";
        $this->pdfLiquidacao->sAutenticacao     = "";
        $this->pdfLiquidacao->nValorMovimento   = "";

        if($this->clpagordem->numrows == 1 && isset($valor_ordem)){

            if( $valor_ordem > pg_result($resultitem,0,"saldo") ){
                $valor_ordem = pg_result($resultitem,0,"saldo");
            }

            $this->pdfLiquidacao->valor_ordem  = "$valor_ordem";
            if (isset($historico) && trim($historico)!= ""){
                $this->pdfLiquidacao->obs = "$historico";
            }else{
                $this->pdfLiquidacao->obs = "$this->liquidacao->e50_obs";
            }
        } else {
            $this->pdfLiquidacao->valor_ordem = "";
            $this->pdfLiquidacao->obs 		= $this->liquidacao->e50_obs;
        }

        if (in_array($this->liquidacao->e54_tipoautorizacao, array('0','1','2','3','4'))) {

            $oAutoriza = $this->clemite_nota_liq->get_dados_licitacao($this->liquidacao->e54_tipoautorizacao, $this->liquidacao->e54_autori);
            $this->pdfLiquidacao->processo         = $oAutoriza->processo;
            $this->pdfLiquidacao->descr_tipocompra = $this->liquidacao->pc50_descr;

        }

        if ($this->liquidacao->e50_contapag != '') {

            $sCampos            = " e83_conta, e83_descr, c63_agencia, c63_dvagencia, c63_conta, c63_dvconta ";
            $sWhere             = " e83_codtipo = {$this->liquidacao->e50_contapag} ";
            $sSqlContaPagadora  = $this->clempagetipo->sql_query_conplanoconta(null, $sCampos, null, $sWhere);
            $rsContaPagadora    = $this->clempagetipo->sql_record($sSqlContaPagadora);

            if ($this->clempagetipo->numrows > 0) {

                $clempagetipo = db_utils::fieldsMemory($rsContaPagadora, 0);
                $this->pdfLiquidacao->conta_pagadora_reduz     = $clempagetipo->e83_conta;
                $this->pdfLiquidacao->conta_pagadora_agencia   = "{$clempagetipo->c63_agencia}-{$clempagetipo->c63_dvagencia}";
                $this->pdfLiquidacao->conta_pagadora_conta     = "{$clempagetipo->c63_conta}-{$clempagetipo->c63_dvconta} {$clempagetipo->e83_descr}";

            }
        } else {
            $this->pdfLiquidacao->conta_pagadora_reduz   = null;
            $this->pdfLiquidacao->conta_pagadora_agencia = null;
            $this->pdfLiquidacao->conta_pagadora_conta   = null;
        }

        $this->pdfLiquidacao->imprime();
    }

    public function gerarFormularioLiquidacao(int $e60_numemp)
    {
        $this->numEmpenho = $e60_numemp;
        $this->criaPDF();
    }

    public function solitarAssinaturaLiquidacao()
    {
        try {
            $sInstituicao = str_replace( " ", "_", strtoupper($this->oInstituicao->nomeinstabrev));
            $lqd = $this->liquidacao->e60_codemp."-".$this->liquidacao->e50_numliquidacao;
            $nomeDocumento = "LIQUIDACAO_{$this->liquidacao->e60_codemp}_{$this->liquidacao->e60_anousu}_LQD_{$lqd}_OP_{$this->liquidacao->e50_codord}_{$sInstituicao}.pdf";
            $this->pdfLiquidacao->objpdf->Output("tmp/$nomeDocumento", false, true);
            $this->oAssintaraDigital->gerarArquivoBase64($nomeDocumento);
            $this->oAssintaraDigital->assinarLiquidacao($this->liquidacao->e60_numemp,
                        $this->liquidacao->e69_codnota, $this->liquidacao->e60_coddot, $this->liquidacao->e60_anousu,
                        $this->liquidacao->e50_data, $nomeDocumento,
                "LIQUIDAÇÃO EMP {$this->liquidacao->e60_codemp}/{$this->liquidacao->e60_anousu} LQD {$lqd} OP {$this->liquidacao->e50_codord}");
        } catch (Exception $eErro) {
            throw new Exception("Erro ao solicitar assinatura :".$eErro->getMessage());
        }
    }



    private function getEmpParametros()
    {
        try {
            $sCampos      = "*";
            $sSqlEmpParam = $this->clempparametro->sql_query_file($this->anousu, $sCampos);
            $result02     = $this->clempparametro->sql_record($sSqlEmpParam);
            if ($this->clempparametro->numrows == 0) {
                throw new Exception("Nenhum registro encontrado na empparametros");
            }
            $this->empParametro = db_utils::fieldsMemory($result02, 0);
        } catch (Exception $eErro) {
            throw new Exception("Erro ao solicitar assinatura :".$eErro->getMessage());
        }
    }

    private function getInstituicao()
    {
        $sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao, nomeinstabrev ";
        $sqlpref .= "  from db_config                                                                  ";
        $sqlpref .= "  inner join cgm on cgm.z01_numcgm = db_config.numcgm                             ";
        $sqlpref .= "where codigo = " . db_getsession("DB_instit");
        $this->oInstituicao = db_utils::fieldsMemory(db_query($sqlpref), 0);
    }
}
