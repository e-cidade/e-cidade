<?php

require_once("fpdf151/impcarne.php");
require_once("fpdf151/scpdf.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_empanulado_classe.php");
require_once("classes/db_empanuladoele_classe.php");
require_once("classes/db_pcforneconpad_classe.php");
require_once("libs/db_liborcamento.php");
require_once("model/protocolo/AssinaturaDigital.model.php");

class FormularioAnulacaoEmpenho
{
    private $oAssintaraDigital;
    private $anousu;
    private $oInstituicao;
    private $iAnulacao;
    private $clempparametro;
    private $clempanulado;
    private $cldb_pcforneconpad;
    private $clempanuladoele;

    private $pdf;

    private $oEmpenho;
    private $oAnulacao;

    public function __construct()
    {
        $this->oAssintaraDigital =  new AssinaturaDigital();
        $this->anousu = db_getsession("DB_anousu");
        $this->getInstituicao();
        $this->clempparametro	    = new cl_empparametro;
        $this->clempanulado	      = new cl_empanulado;
        $this->cldb_pcforneconpad = new cl_pcforneconpad;
        $this->clempanuladoele    = new cl_empanuladoele;
    }

    private function criaPDF()
    {
        $dbwhere = " e94_codanu = ".$this->iAnulacao;
        $sqlemp  = "select empempenho.*,                                                              ";
        $sqlemp .= "       cgm.* ,                                                                    ";
        $sqlemp .= "       o58_orgao,                                                                 ";
        $sqlemp .= "       o40_descr,                                                                 ";
        $sqlemp .= "       o58_unidade,                                                               ";
        $sqlemp .= "       o41_descr,                                                                 ";
        $sqlemp .= "       o58_funcao,                                                                ";
        $sqlemp .= "       o52_descr,                                                                 ";
        $sqlemp .= "       o58_subfuncao,                                                             ";
        $sqlemp .= "       o53_descr,                                                                 ";
        $sqlemp .= "       o58_programa,                                                              ";
        $sqlemp .= "       o54_descr,                                                                 ";
        $sqlemp .= "       o58_projativ,                                                              ";
        $sqlemp .= "       o55_descr,                                                                 ";
        $sqlemp .= "       o58_coddot,                                                                ";
        $sqlemp .= "       o58_anousu,                                                                ";
        $sqlemp .= "       o56_elemento as sintetico,                                                 ";
        $sqlemp .= "       o56_descr as descr_sintetico,                                              ";
        $sqlemp .= "       o58_codigo,                                                                ";
        $sqlemp .= "       o15_descr,                                                                 ";
        $sqlemp .= "       e61_autori,                                                                ";
        $sqlemp .= "       pc50_descr as l03_descr,                                                   ";
        /*OC4401*/
        $sqlemp .= "        e60_id_usuario,                                                           ";
        $sqlemp .= "        db_usuarios.nome,                                                         ";
        /*FIM - OC4401*/
        $sqlemp .= "       fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural                  ";
        $sqlemp .= "  from empempenho                                                                 ";
        /*OC4401*/
        $sqlemp .= " LEFT JOIN db_usuarios ON db_usuarios.id_usuario = e60_id_usuario                  ";
        /*FIM - OC4401*/
        $sqlemp .= "       left join pctipocompra	 on pc50_codcom = e60_codcom                      ";
        $sqlemp .= "       left join orcdotacao    	 on o58_coddot       = e60_coddot                 ";
        $sqlemp .= "                                and o58_instit       = ".db_getsession("DB_instit");
        $sqlemp .= "	                            and o58_anousu       = e60_anousu                 ";
        $sqlemp .= "       inner join orcorgao   	 on o58_orgao        = o40_orgao                  ";
        $sqlemp .= "                                and o40_anousu       = e60_anousu                 ";
        $sqlemp .= "       inner join orcunidade 	 on o58_unidade      = o41_unidade                ";
        $sqlemp .= "                                and o58_orgao        = o41_orgao                  ";
        $sqlemp .= "                                and o41_anousu       = o58_anousu                 ";
        $sqlemp .= "       inner join orcfuncao   	 on o58_funcao       = o52_funcao                 ";
        $sqlemp .= "       inner join orcsubfuncao   on o58_subfuncao    = o53_subfuncao              ";
        $sqlemp .= "       inner join orcprograma    on o58_programa     = o54_programa               ";
        $sqlemp .= "                                and o54_anousu       = o58_anousu                 ";
        $sqlemp .= "       inner join orcprojativ  	 on o58_projativ     = o55_projativ               ";
        $sqlemp .= "                                and o55_anousu       = o58_anousu                 ";
        $sqlemp .= "       inner join orcelemento a	 on o58_codele       = o56_codele                 ";
        $sqlemp .= "                                and o58_anousu       = o56_anousu                 ";
        $sqlemp .= "       inner join orctiporec  	 on o58_codigo       = o15_codigo                 ";
        $sqlemp .= "       inner join cgm 		     on z01_numcgm       = e60_numcgm                 ";
        $sqlemp .= "       inner join empanulado     on e94_numemp       = e60_numemp                 ";
        $sqlemp .= "       left join empempaut	     on e60_numemp       = e61_numemp                 ";
        $sqlemp .= "	where  $dbwhere ";
        $result = db_query($sqlemp);

        if($result==false || pg_num_rows($result) == 0 ){
            db_redireciona('db_erros.php?fechar=true&db_erro=Anulação n'.chr(176).' '.$this->iAnulacao.' não encontrada. Verifique!');
        }
        $this->oEmpenho = db_utils::fieldsMemory($result,0);
        /**
         *
         * Busca dados bancários
         */
        $sSqlPcFornecOnPad  = $this->cldb_pcforneconpad->sql_query(null, "*", null, "pc63_numcgm = {$this->oEmpenho->e60_numcgm}");
        $rsSqlPcFornecOnPad = $this->cldb_pcforneconpad->sql_record($sSqlPcFornecOnPad);

        if (!$rsSqlPcFornecOnPad == false && $this->cldb_pcforneconpad->numrows > 0) {
            $oPcFornecOnPad     = db_utils::fieldsMemory($rsSqlPcFornecOnPad,0);
        } else {

            $oPcFornecOnPad = new stdClass();
            $oPcFornecOnPad->pc63_banco       = '';
            $oPcFornecOnPad->pc63_agencia     = '';
            $oPcFornecOnPad->pc63_agencia_dig = '';
            $oPcFornecOnPad->pc63_conta       = '';
            $oPcFornecOnPad->pc63_conta_dig   = '';
        }


        $scpdf = new scpdf();
        $scpdf->Open();
        $this->pdf = new db_impcarne($scpdf,'12');
        $this->pdf->objpdf->SetTextColor(0,0,0);

        $result02 = db_query("select * from empanulado where e94_codanu =".$this->iAnulacao);
        $this->oAnulacao =    db_utils::fieldsMemory($result02, 0);

        $this->pdf->nvias= 1;
        $nValorTotalAnulado = 0;
//        $dataAnt = date('Y-m-d', strtotime('-1 days', strtotime($this->oAnulacao->e94_data)));
//        $res_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = {$this->oEmpenho->o58_coddot} and o58_anousu = {$this->oEmpenho->o58_anousu}", $this->oEmpenho->o58_anousu , $this->oEmpenho->o58_anousu."-01-01", $dataAnt);
//        if (pg_num_rows($res_dot)>0){
//            $oDotacao = db_utils::fieldsMemory($res_dot,0);
//        }

        $sqlitens  = "select distinct empanuladoele.*,";
        $sqlitens .= "                empanulado.*,";
        $sqlitens .= "                empanuladoitem.*,";
        $sqlitens .= "                empempitem.*,";
        $sqlitens .= "                empempenho.*,";
        $sqlitens .= "                m61_descr,";
        $sqlitens .= "                db_usuarios.*,";
        $sqlitens .= "                orcelemento.*,";
        $sqlitens .= "                pcmater.*    ";
        $sqlitens .= "                from empanuladoele";
        $sqlitens .= "                inner join empanulado      on e94_codanu            = e95_codanu            ";
        $sqlitens .= "                inner join empanuladoitem  on e37_empanulado        = e94_codanu            ";
        $sqlitens .= "                inner join empempitem      on e62_sequencial        = e37_empempitem        ";
        $sqlitens .= "                                          and empempitem.e62_numemp = empanulado.e94_numemp ";
        $sqlitens .= "                inner join empempenho      on e60_numemp            = e62_numemp            ";
        $sqlitens .= "                left join empempaut        on e60_numemp           = e61_numemp             ";
        $sqlitens .= "                left join empautoriza      on e61_autori           = e54_autori             ";
        $sqlitens .= "                left join empautitem       on e54_autori           = e55_autori             ";
        $sqlitens .= "                                           and e62_item = e55_item                          ";
        $sqlitens .= "                left join matunid          on e55_unid             = m61_codmatunid         ";
        $sqlitens .= "                LEFT JOIN db_usuarios ON db_usuarios.id_usuario = e94_id_usuario            ";
        $sqlitens .= "       		     inner join orcelemento     on o56_codele            = e62_codele         ";
        $sqlitens .= "       		      					                and o56_anousu = e60_anousu           ";
        $sqlitens .= "                inner join pcmater         on pc01_codmater         = e62_item              ";
        $sqlitens .= " 	where e95_codanu = ".$this->oAnulacao->e94_codanu ;
        $resultitem = db_query($sqlitens);

        $nValorTotalAnulado          += $this->oAnulacao->e94_valor;
        $this->pdf->notaanulacao      = $this->oAnulacao->e94_codanu;
        $this->pdf->prefeitura        = $this->oInstituicao->nomeinst;
        $this->pdf->enderpref         = trim($this->oInstituicao->ender).",".$this->oInstituicao->numero;
        $this->pdf->municpref         = $this->oInstituicao->munic;
        $this->pdf->telefpref         = $this->oInstituicao->telef;
        $this->pdf->emailpref         = $this->oInstituicao->email;
        $this->pdf->logo              = $this->oInstituicao->logo;
        $this->pdf->numcgm            = $this->oEmpenho->z01_numcgm;
        $this->pdf->nome              = $this->oEmpenho->z01_nome;
        $this->pdf->ender             = $this->oEmpenho->z01_ender;
        $this->pdf->munic             = $this->oEmpenho->z01_munic;
        $this->pdf->cgcpref           = $this->oEmpenho->cgc;
        $this->pdf->dotacao           = $this->oEmpenho->estrutural;
        $this->pdf->descr_licitacao   = $this->oEmpenho->l03_descr;
        $this->pdf->coddot            = $this->oEmpenho->o58_coddot;
        $this->pdf->destino           = $this->oEmpenho->e60_destin;

        $this->pdf->valorTotalAnulado = $nValorTotalAnulado;

        $e60_resumo = str_replace("\n",'   -   ',$this->oAnulacao->e94_motivo);
        $e60_resumo = str_replace("\r",'',$this->oAnulacao->e94_motivo);
        $e60_resumo = mb_convert_encoding($e60_resumo, "ISO-8859-1", mb_detect_encoding($e60_resumo, "UTF-8, ISO-8859-1, ISO-8859-15", true));

        $this->pdf->resumo           = $e60_resumo;
        $this->pdf->licitacao        = $this->oEmpenho->e60_codtipo;

        $this->pdf->recorddositens   = $resultitem;
        $this->pdf->linhasdositens   = pg_num_rows($resultitem);
        $this->pdf->sequencialitem   = "e62_sequen";
        $this->pdf->quantitem        = "e37_qtd";
        $this->pdf->item             = "pc01_codmater";
        $this->pdf->unidadeitem      = "m61_descr";
        $this->pdf->valoritem        = "e95_valor";
        $this->pdf->descricaoitem    = "pc01_descrmater";

        $this->pdf->orcado	         = $this->oEmpenho->e60_vlrorc;
        $this->pdf->saldo_ant        = $this->oAnulacao->e94_saldoant;
        $this->pdf->saldo_atu        = $this->oAnulacao->e94_saldoant + $this->oAnulacao->e94_valor;
        $this->pdf->empenhado        = $this->oEmpenho->e60_vlremp;
        $this->pdf->anulado          = $this->oAnulacao->e94_valor;
        $this->pdf->usuario          = $this->oEmpenho->nome;
        $this->pdf->numemp           = $this->oEmpenho->e60_numemp;
        $this->pdf->codemp           = $this->oEmpenho->e60_codemp;
        $this->pdf->anousu           = $this->oEmpenho->e60_anousu;
        $this->pdf->numaut           = $this->oEmpenho->e61_autori;
        $this->pdf->orgao            = $this->oEmpenho->o58_orgao;
        $this->pdf->descr_orgao      = $this->oEmpenho->o40_descr;
        $this->pdf->unidade          = $this->oEmpenho->o58_unidade;
        $this->pdf->descr_unidade    = $this->oEmpenho->o41_descr;
        $this->pdf->funcao           = $this->oEmpenho->o58_funcao;
        $this->pdf->descr_funcao     = $this->oEmpenho->o52_descr;
        $this->pdf->subfuncao        = $this->oEmpenho->o58_subfuncao;
        $this->pdf->descr_subfuncao  = $this->oEmpenho->o53_descr;
        $this->pdf->programa         = $this->oEmpenho->o58_programa;
        $this->pdf->descr_programa   = $this->oEmpenho->o54_descr;
        $this->pdf->projativ         = $this->oEmpenho->o58_projativ;
        $this->pdf->descr_projativ   = $this->oEmpenho->o55_descr;
        $this->pdf->analitico        = "o56_elemento";
        $this->pdf->descr_analitico  = "o56_descr";
        $this->pdf->sintetico        = $this->oEmpenho->sintetico;
        $this->pdf->descr_sintetico  = $this->oEmpenho->descr_sintetico;
        $this->pdf->recurso          = $this->oEmpenho->o58_codigo;
        $this->pdf->descr_recurso    = $this->oEmpenho->o15_descr;
        $this->pdf->emissao          = db_formatar($this->oAnulacao->e94_data,'d');
        $this->pdf->texto		     = db_getsession("DB_login").'  -  '.date("d-m-Y",db_getsession("DB_datausu")).'    '.db_hora(db_getsession("DB_datausu"));
        $this->pdf->cnpj             = $this->oEmpenho->z01_cgccpf;
        $this->pdf->cep              = $this->oEmpenho->z01_cep;


        /**
         * Dados Bancários
         */
        $this->pdf->iBancoFornecedor     = $oPcFornecOnPad->pc63_banco;
        $this->pdf->iAgenciaForncedor    = $oPcFornecOnPad->pc63_agencia."-".$oPcFornecOnPad->pc63_agencia_dig;
        $this->pdf->iContaForncedor      = $oPcFornecOnPad->pc63_conta."-".$oPcFornecOnPad->pc63_conta_dig;
        $this->pdf->imprime();

    }

    public function gerarFormularioAnulacaoEmpenho(int $iAnulacao)
    {
        $this->iAnulacao = $iAnulacao;
        $this->criaPDF();
    }

    public function gerarFormularioAnulacaoEmpenhoByEmpenho($iEmpenho)
    {
        $this->iAnulacao = $this->getNumeroAnulacaoByEmpenho($iEmpenho);
        $this->criaPDF();
    }

    public function solitarAssinatura()
    {
        try {
            $sInstituicao = str_replace( " ", "_", strtoupper($this->oInstituicao->nomeinstabrev));
            $nomeDocumento = "ANULACAO_EMPENHO_{$this->oEmpenho->e60_codemp}_{$this->oEmpenho->e60_anousu}_{$this->oAnulacao->e94_codanu}_{$sInstituicao}.pdf";
            $this->pdf->objpdf->Output("tmp/$nomeDocumento", false, true);
            $this->oAssintaraDigital->gerarArquivoBase64($nomeDocumento);
            $this->oAssintaraDigital->assinarAnulacaoEmpenho($this->oAnulacao->e94_codanu, $this->oEmpenho->e60_coddot, $this->oEmpenho->e60_anousu,  $this->oAnulacao->e94_data, $nomeDocumento, $nomeDocumento);
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

    private function getNumeroAnulacaoByEmpenho($iNumEmp)
    {
        $sqlAnulacao  = "select e94_codanu ";
        $sqlAnulacao .= "  from empenho.empanulado                                                                  ";
        $sqlAnulacao .= "where e94_numemp = " . $iNumEmp;

        return db_utils::fieldsMemory(db_query($sqlAnulacao), 0)->e94_codanu;
    }

}
