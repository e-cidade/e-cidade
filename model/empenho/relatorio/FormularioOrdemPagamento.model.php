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
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_emite_nota_liq.php");
require_once("classes/db_empdiaria_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("classes/db_identificacaoresponsaveis_classe.php");
require_once("model/orcamento/ControleOrcamentario.model.php");
require_once("libs/exceptions/BusinessException.php");

class FormularioOrdemPagamento
{
    private $oAssintaraDigital;
    private $anousu;
    private $oOrdemPagamento;

    private $iCodOrdem;
    private $iMovimento;

    private $oInstituicao;

    private $clpagordem     ;
    private $clpagordemele  ;
    private $clempautitem   ;
    private $clempagetipo   ;
    private $clempagemov   ;
    private $clemite_nota_liq ;
    private $clDiaria ;
    private $clRhpessoal ;
    private  $clReponsaveis ;

    private $pdfOrdemPagamento;

    private $dataPagamento;
    public function __construct()
    {
        $this->oAssintaraDigital =  new AssinaturaDigital();
        $this->anousu = db_getsession("DB_anousu");
        $this->getInstituicao();
        $this->clpagordem       = new cl_pagordem;
        $this->clpagordemele    = new cl_pagordemele;
        $this->clempautitem     = new cl_empautitem;
        $this->clempagetipo     = new cl_empagetipo;
        $this->clempagemov      = new cl_empagemov;
        $this->clemite_nota_liq = new cl_emite_nota_liq;
        $this->clDiaria         = new cl_empdiaria;
        $this->clRhpessoal      = new cl_rhpessoal;
        $this->clReponsaveis    = new cl_identificacaoresponsaveis;
    }

    private function criaPDF()
    {

        $dbwhere = " e50_codord in ({$this->iCodOrdem}) ";
        $dbwhere .= "and e60_instit = " . $this->oInstituicao->codigo;
        $dbwhere .= "and e81_codmov = " . $this->iMovimento;

        $pdf = new scpdf();
        $pdf->Open();
        $this->pdfOrdemPagamento = new db_impcarne($pdf, '85');
        $this->pdfOrdemPagamento->objpdf->SetTextColor(0, 0, 0);

        $sSqlPagordem = $this->clpagordem->consultaNotaDespesa('', 'distinct e50_codord,e60_numemp,e71_codnota,e60_numerol,pc50_descr,e50_numliquidacao', ' e60_numemp, e50_numliquidacao ', $dbwhere);
        $result = $this->clpagordem->sql_record($sSqlPagordem);

        if ($this->clpagordem->numrows < 1) {
            db_redireciona('db_erros.php?fechar=true&db_erro=Dados não encontrados. Verifique!');
        }

        $result2 = db_query("select * from empparametro where e39_anousu = " . db_getsession("DB_anousu"));

        if (pg_num_rows($result2) > 0) {
            $empparametro = db_utils::fieldsMemory($result2, 0);
            $this->pdfOrdemPagamento->nvias = $empparametro->e30_nroviaord;
        }

        for ($i = 0; $i < $this->clpagordem->numrows; $i++) {

            $oClPagOrdem = db_utils::fieldsMemory($result, $i);

            $dbWhere = "e60_instit = ".db_getsession('DB_instit')." and e50_codord = " . $oClPagOrdem->e50_codord . " and e81_codmov = ". $this->iMovimento;
            if(isset($sMovimentos) && $sMovimentos != ''){
                $dbWhere .= " and e81_codmov in (".$sMovimentos.")";
            }

            $camposMovimentos = " case when k12_sequencial is not null then false
                                  when k12_sequencial is null and e81_cancelado is not null then true
                                  when k12_sequencial is null and e81_cancelado is null then false
                                  end as estornado, * ";
            $sqlMovimentos = $this->clempagemov->consultaMovimentosDaOp(null, $camposMovimentos, null, $dbWhere);
            $rsMovimentos  = $this->clempagemov->sql_record($sqlMovimentos);

            for ($j = 0; $j < $this->clempagemov->numrows; $j++) {

                $oMovimento = db_utils::fieldsMemory($rsMovimentos, $j);
                if($oMovimento->k12_sequencial != null || $oMovimento->estornado == 't'){

                    $rsBanco = db_utils::fieldsMemory(db_query("SELECT db90_descr FROM db_bancos WHERE db90_codban = '{$oMovimento->c63_banco}'"), 0)->db90_descr;
                    $rsForma = db_utils::fieldsMemory(db_query("SELECT e96_descr FROM empageforma WHERE e96_codigo = " . $oMovimento->e97_codforma), 0)->e96_descr;

                    if (strpos($oMovimento->db83_descricao, $oMovimento->db83_conta) !== false) {
                        $conta = $oMovimento->c61_reduz . ' - ' . $oMovimento->db83_descricao;
                    } else {
                        $conta = $oMovimento->c61_reduz . ' - ' . $oMovimento->db83_conta . '-' . $oMovimento->db83_dvconta . ' - ' . $oMovimento->db83_descricao;
                    }

                    $oRetencaoNota = new retencaoNota($oClPagOrdem->e71_codnota);
                    $oRetencaoNota->setINotaLiquidacao($oClPagOrdem->e50_codord);

                    $sql = $this->clemite_nota_liq->get_sql_ordem_pagamento(db_getsession("DB_instit"), db_getsession("DB_anousu"), $oClPagOrdem->e50_codord);

                    $resultord = db_query($sql);

                    if (pg_num_rows($resultord) == 0) continue;

                    $ordem_pagamento =  db_utils::fieldsMemory($resultord, 0);

                    $sql1         = $this->clemite_nota_liq->get_sql_assinaturas($oClPagOrdem->e50_codord);
                    $assinatura = db_utils::fieldsMemory(db_query($sql1), 0);

                    $aRetencoes = $oRetencaoNota->getRetencoesFromDB($oClPagOrdem->e50_codord, false, 1, "", "", true, false);

                    $sqlfornecon         = $this->clemite_nota_liq->get_sql_fornecedor($ordem_pagamento->z01_cgccpf);
                    $result_pcfornecon   = db_query($sqlfornecon);

                    if (pg_num_rows($result_pcfornecon) > 0) {
                        $oPcfornecon = db_utils::fieldsMemory($result_pcfornecon, 0);
                    }

                    if ($ordem_pagamento->o41_cnpj != "" && $ordem_pagamento->o41_cnpj != $ordem_pagamento->cgc) {
                        $nomeinst = $ordem_pagamento->o41_descr;
                        $cgc      = $ordem_pagamento->o41_cnpj;
                    }

                    $clControleOrc = new ControleOrcamentario;
                    $e60_codco = $oMovimento->e60_codco == null ? '0000' : $oMovimento->e60_codco;
                    $clControleOrc->setCodCO($e60_codco);

                    $this->pdfOrdemPagamento->bancoPagamento   = $oMovimento->c63_banco . ' - ' . $rsBanco;
                    $this->pdfOrdemPagamento->agenciaPagamento = $oMovimento->c63_agencia;
                    $this->pdfOrdemPagamento->contaPagamento   = $conta;
                    $this->pdfOrdemPagamento->movimento        = $oMovimento->e81_codmov;
                    $this->pdfOrdemPagamento->tipoDocumento    = $rsForma . ($oMovimento->e81_numdoc == null ? '' : ' / ' . $oMovimento->e81_numdoc);

                    if($oMovimento->k12_sequencial != null){
                        $sDataPagamento = $oMovimento->k12_data == null ? '' : date('d/m/Y', strtotime($oMovimento->k12_data));
                        $sDataPagamentoISO = $oMovimento->k12_data;
                    }else{
                        $sDataPagamento = $oMovimento->e86_data == null ? '' : date('d/m/Y', strtotime($oMovimento->e86_data));
                        $sDataPagamentoISO = $oMovimento->e86_data;
                    }

                    $this->pdfOrdemPagamento->dataPagamento = $sDataPagamento;
                    $this->dataPagamento = $sDataPagamento;
                    if ($oMovimento->e81_cancelado == null) {
                        $sSqlEstorno = $this->clempagemov->consultaEstorno('c70_valor',$oClPagOrdem->e50_codord,$sDataPagamentoISO, $oMovimento->e81_codmov);
                        $aEstorno = pg_fetch_all(db_query($sSqlEstorno));
                        $vlrEstorno = 0;
                        foreach ($aEstorno as $estorno) {
                            $vlrEstorno += $estorno['c70_valor'];
                        }
                    } else {
                        $vlrEstorno = $oMovimento->e81_valor;
                    }

                    $sqlTesoureiro = $this->clReponsaveis->sql_query(null,'z01_nome',null, " si166_tiporesponsavel = 5 and ('$oMovimento->e80_data' between si166_dataini and si166_datafim) and si166_instit = ".db_getsession("DB_instit"));
                    $tesoureiro = db_utils::fieldsMemory(db_query($sqlTesoureiro), 0)->z01_nome;

                    //assinaturas
                    $this->pdfOrdemPagamento->ordenadespesa    = $assinatura->assindsp;
                    $this->pdfOrdemPagamento->liquida          = $assinatura->assinlqd;
                    $this->pdfOrdemPagamento->ordenapagamento  = $assinatura->assinord;
                    $this->pdfOrdemPagamento->tesoureiro       = $tesoureiro;
                    $this->pdfOrdemPagamento->vlrEstorno       = $vlrEstorno == null ? 0 : $vlrEstorno;
                    $this->pdfOrdemPagamento->vlrPago          = $oMovimento->e81_valor;
                    $this->pdfOrdemPagamento->numliquidacao    = $oClPagOrdem->e50_numliquidacao;
                    $this->pdfOrdemPagamento->logo             = $this->oInstituicao->logo;
                    $this->pdfOrdemPagamento->processo         = $oClPagOrdem->e60_numerol;
                    $this->pdfOrdemPagamento->descr_tipocompra = $this->oInstituicao->pc50_descr;
                    $this->pdfOrdemPagamento->prefeitura       = $this->oInstituicao->nomeinst;
                    $this->pdfOrdemPagamento->enderpref        = $this->oInstituicao->ender;
                    $this->pdfOrdemPagamento->municpref        = $this->oInstituicao->munic;
                    $this->pdfOrdemPagamento->telefpref        = $this->oInstituicao->telef;
                    $this->pdfOrdemPagamento->emailpref        = $this->oInstituicao->email;
                    $this->pdfOrdemPagamento->cgcpref          = $this->oInstituicao->cgc;
                    $this->pdfOrdemPagamento->banco            = $ordem_pagamento->pc63_banco;
                    $this->pdfOrdemPagamento->agencia          = $ordem_pagamento->pc63_agencia;
                    $this->pdfOrdemPagamento->agenciadv        = $ordem_pagamento->pc63_agencia_dig;
                    $this->pdfOrdemPagamento->conta            = $ordem_pagamento->pc63_conta;
                    $this->pdfOrdemPagamento->tipoconta        = $ordem_pagamento->pc63_tipoconta;
                    $this->pdfOrdemPagamento->contadv          = $ordem_pagamento->pc63_conta_dig;
                    $this->pdfOrdemPagamento->numcgm           = $ordem_pagamento->z01_numcgm;
                    $this->pdfOrdemPagamento->nome             = $ordem_pagamento->z01_nome;
                    $this->pdfOrdemPagamento->cnpj             = $ordem_pagamento->z01_cgccpf;
                    $this->pdfOrdemPagamento->ender            = $ordem_pagamento->z01_ender;
                    $this->pdfOrdemPagamento->bairro           = $ordem_pagamento->z01_bairro;
                    $this->pdfOrdemPagamento->cep              = $ordem_pagamento->z01_cep;
                    $this->pdfOrdemPagamento->ufFornecedor     = $ordem_pagamento->z01_uf;
                    $this->pdfOrdemPagamento->munic            = $ordem_pagamento->z01_munic;
                    $this->pdfOrdemPagamento->ordpag           = $ordem_pagamento->e50_codord;
                    $this->pdfOrdemPagamento->coddot           = $ordem_pagamento->o58_coddot;
                    $this->pdfOrdemPagamento->dotacao          = $ordem_pagamento->estrutural;
                    $this->pdfOrdemPagamento->ano              = $ordem_pagamento->e60_anousu;
                    $this->pdfOrdemPagamento->aRetencoes       = $aRetencoes;
                    $this->pdfOrdemPagamento->empenhado        = $ordem_pagamento->e60_vlremp;
                    $this->pdfOrdemPagamento->codemp           = $ordem_pagamento->e60_codemp;
                    $this->pdfOrdemPagamento->numemp           = $ordem_pagamento->e60_codemp . '/' . $ordem_pagamento->e60_anousu;
                    $this->pdfOrdemPagamento->orgao            = $ordem_pagamento->o58_orgao;
                    $this->pdfOrdemPagamento->descr_orgao      = $ordem_pagamento->o40_descr;
                    $this->pdfOrdemPagamento->unidade          = $ordem_pagamento->o58_unidade;
                    $this->pdfOrdemPagamento->descr_unidade    = $ordem_pagamento->o41_descr;
                    $this->pdfOrdemPagamento->funcao           = $ordem_pagamento->o58_funcao;
                    $this->pdfOrdemPagamento->descr_funcao     = $ordem_pagamento->o52_descr;
                    $this->pdfOrdemPagamento->subfuncao        = $ordem_pagamento->o58_subfuncao;
                    $this->pdfOrdemPagamento->descr_subfuncao  = $ordem_pagamento->o53_descr;
                    $this->pdfOrdemPagamento->projativ         = $ordem_pagamento->o58_projativ;
                    $this->pdfOrdemPagamento->descr_projativ   = $ordem_pagamento->o55_descr;
                    $this->pdfOrdemPagamento->recurso          = $ordem_pagamento->o58_codigo;
                    $this->pdfOrdemPagamento->descr_recurso    = $ordem_pagamento->o15_descr;
                    $this->pdfOrdemPagamento->elemento         = $ordem_pagamento->o56_elemento;
                    $this->pdfOrdemPagamento->descr_elemento   = $ordem_pagamento->o56_descr;
                    $this->pdfOrdemPagamento->obs              = substr($ordem_pagamento->e50_obs, 0, 300);
                    $this->pdfOrdemPagamento->texto            = db_getsession("DB_login") . '  -  ' . date("d-m-Y", db_getsession("DB_datausu")) . '    ' . db_hora(db_getsession("DB_datausu"));
                    $this->pdfOrdemPagamento->telef            = $ordem_pagamento->z01_telef;
                    $this->pdfOrdemPagamento->fax              = $ordem_pagamento->z01_numero;
                    $this->pdfOrdemPagamento->codco            = $ordem_pagamento->e60_codco . ' - ' . $clControleOrc->getDescricaoResumoCO();

                    if ($this->clpagordem->numrows == 1 && isset($valor_ordem)) {
                        if (isset($historico) && trim($historico) != "") {
                            $this->pdfOrdemPagamento->obs = "$historico";
                        } else {
                            $this->pdfOrdemPagamento->obs = "$ordem_pagamento->e50_obs";
                        }
                    } else {
                        $this->pdfOrdemPagamento->valor_ordem = "";
                        $this->pdfOrdemPagamento->obs     = "$ordem_pagamento->e50_obs";
                    }

                    if (in_array($ordem_pagamento->e54_tipoautorizacao, array('0', '1', '2', '3', '4'))) {

                        $oAutoriza = $this->clemite_nota_liq->get_dados_licitacao($ordem_pagamento->e54_tipoautorizacao, $ordem_pagamento->e54_autori);

                        $this->pdfOrdemPagamento->processo         = $oAutoriza->processo;
                        $this->pdfOrdemPagamento->descr_tipocompra = $ordem_pagamento->pc50_descr;
                    }
                    $this->pdfOrdemPagamento->imprime();
                }
            }
        }
    }

    public function gerarFormularioOrdemPagamento(string $iMovimento)
    {
        $this->getCodOrdem($iMovimento);
        $this->iCodOrdem = $this->oOrdemPagamento->e50_codord;
        $this->iMovimento = $iMovimento;
        $this->criaPDF();

    }

    public function solitarAssinatura()
    {
        try {
            $sInstituicao = str_replace( " ", "_", strtoupper($this->oInstituicao->nomeinstabrev));
            $lqd = $this->oOrdemPagamento->e60_codemp."-".$this->oOrdemPagamento->e50_numliquidacao;
            $nomeDocumento = "ORDEM_PAGAMENTO_EMP_{$this->oOrdemPagamento->e60_codemp}_{$this->oOrdemPagamento->e60_anousu}_LQD_{$lqd}_OP_{$this->oOrdemPagamento->e50_codord}_MOVIMENTO_{$this->oOrdemPagamento->e82_codmov}_{$sInstituicao}.pdf";
            $this->pdfOrdemPagamento->objpdf->Output("tmp/$nomeDocumento", false, true);
            $this->oAssintaraDigital->gerarArquivoBase64($nomeDocumento);
            $this->oAssintaraDigital->assinarOrdemPagamento($this->oOrdemPagamento->e82_codmov, $this->oOrdemPagamento->e60_coddot, $this->oOrdemPagamento->e60_anousu, $this->dataPagamento, $nomeDocumento, $nomeDocumento);
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

    private function getCodOrdem($iMovimento)
    {
        $sqlPagOrdem  = "select * from  empenho.empord inner join empenho.pagordem on e50_codord=e82_codord inner join empempenho on e50_numemp=e60_numemp  where e82_codmov=$iMovimento";
        $this->oOrdemPagamento = db_utils::fieldsMemory(db_query($sqlPagOrdem), 0);
    }
}
