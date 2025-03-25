<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoDispensaRepository;
use App\Services\Licitacao\Sicom\StringService;
use Illuminate\Database\Capsule\Manager as DB;
use GerarAM;
use stdClass;

class ArquivoDispensaService extends GerarAM
{

    /**
     * @var ArquivoDispensaRepository
     */
    private $arquivoDispensaRepository;

    public function __construct()
    {
        $this->arquivoDispensaRepository = new ArquivoDispensaRepository();
    }

    public function StringReplaceSicom($string){
        $string = preg_replace(array("/(á|à|ã|â|ä|å|æ)/","/(Á|À|Ã|Â|Ä|Å|Æ)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö|Ø)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/","/(ý|ÿ)/","/(Ý)/"),explode(" ","a A e E i I o O u U n N c C y Y"),$string);
        $string = preg_replace('/[^A-Za-z0-9 ?|_;{}\[\]]/', '', $string);
        $string = preg_replace("/[?|?_??]/u", "-", $string);
        $string = preg_replace("/[;]/u", ".", $string);
        $string = preg_replace("/[\[<{|]/u", "(", $string);
        $string = preg_replace("/[\]>}]/u", ")", $string);
        $string = preg_replace("/[+$&]/u", "", $string);
        return $string = preg_replace('/\s{2,}/', ' ', $string);
  }

    public function gerarArquivo($licitacoes)
    {

        if (empty($licitacoes)) {
            $aCSV10 = array();
            $aCSV10['tiporegistro'] = '99';
            $this->sLinha = $aCSV10;
            $this->abreArquivo();
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        $stringService = new StringService();

        $aCSV10 = array();
        $aCSV20 = array();
        $aCSV30 = array();
        $aCSV50 = array();
        $aCSV60 = array();

        $this->sArquivo = "DISPENSA";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dadosReg10 = $this->arquivoDispensaRepository->getDadosRegistro10($licitacoes);

        if (empty($dadosReg10)) {
            $aCSV10['tiporegistro'] = '99';
            $this->sLinha = $aCSV10;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        foreach ($dadosReg10 as $dadoReg10) {

            $aCSV10['tiporegistro']                             = $this->padLeftZero($dadoReg10->tiporegistro, 2);
            $aCSV10['tipocadastro']           = 1;
            $aCSV10['codorgaoresp']                            = $this->padLeftZero($dadoReg10->codorgaoresp, 3);
            $codunidadesubresp = !empty($dadoReg10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;
            $aCSV10['codunidadesub'] =  in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesubresp, 5); 
            $aCSV10['codunidadesubedital'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual, 4) : '';
            $aCSV10['exerciciolicitacao']                  = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
            $aCSV10['nroprocessolicitatorio']              = substr($dadoReg10->nroprocessolicitatorio, 0, 12);
            $aCSV10['tipoprocesso']                       = $this->padLeftZero($dadoReg10->tipoprocesso, 1);
            $aCSV10['dscCadastroDispensaInexigibilidade'] = "";
            $aCSV10['leiDispensaInexigibilidade']   =   $this->padLeftZero($dadoReg10->l20_leidalicitacao, 1);
            $aCSV10['dtabertura']   = $this->sicomDate($dadoReg10->dtabertura);
            $aCSV10['naturezaobjeto']   =   $this->padLeftZero($dadoReg10->naturezaobjeto, 1);

            $aCSV10['tipocriterio'] = $dadoReg10->tipoprocesso == 5 || $dadoReg10->tipoprocesso == 6 ? $dadoReg10->l20_criterioadjudicacao : '';

            if( in_array($dadoReg10->tipoprocesso,[3,4]) && $dadoReg10->l20_tipnaturezaproced == 1 && $dadoReg10->l20_leidalicitacao == 1){
                $aCSV10['tipocriterio'] = 3;
            }


            $aCSV10['objeto'] = substr($this->StringReplaceSicom($dadoReg10->objeto),0,500);
            $aCSV10['justificativa'] = substr($this->StringReplaceSicom($dadoReg10->justificativa),0,250);
            $aCSV10['razao'] = substr($this->StringReplaceSicom($dadoReg10->razao),0,250);
            $aCSV10['vlrecurso'] = $dadoReg10->vlrecurso == null ? $this->sicomNumberReal(0, 2) : $this->sicomNumberReal($dadoReg10->vlrecurso, 2); 
            $aCSV10['link'] = $dadoReg10->linkpub == null ? '' :  $stringService->removeCaracteres($dadoReg10->linkpub);
            $aCSV10['dtpublicacaotermoratificacao']   = $this->sicomDate($dadoReg10->dtpublicacaotermoratificacao);

            $aCSV10['veiculopublicacao'] = $dadoReg10->veiculopublicacao == null ? '' : substr($stringService->removeCaracteres($dadoReg10->veiculopublicacao), 0, 50);
            $aCSV10['processoporlote'] = $this->padLeftZero($dadoReg10->processoporlote, 1);
            $aCSV10['emailcontato']  = $dadoReg10->emailcontato == null ? '' : $stringService->removeCaracteres($dadoReg10->emailcontato);

            $this->sLinha = $aCSV10;
            $this->adicionaLinha();
            $dadosReg11 = $this->arquivoDispensaRepository->getDadosRegistro11($dadoReg10->codlicitacao);

            $aDadosAgrupados11 = array();

            foreach ($dadosReg11 as $dadoReg11) {

                $sHash11 = $dadoReg11->dsclote;

                if (!isset($aDadosAgrupados11[$sHash11])) {

                    $aCSV11['tiporegistro']                    = 11;
                    $aCSV11['codorgaoresp']                        = $this->padLeftZero($dadoReg11->codorgaoresp, 3);
                    $codunidadesub = !empty($dadoReg11->codunidsubant) ? $dadoReg11->codunidsubant : $dadoReg11->codunidadesubresp;
                    $aCSV11['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                    $aCSV11['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg11->codunidadesubrespestadual, 4) : '';
                    $aCSV11['exerciciolicitacao']              = $this->padLeftZero($dadoReg11->exerciciolicitacao, 4);
                    $aCSV11['nroprocessolicitatorio']          = substr($dadoReg11->nroprocessolicitatorio, 0, 12);
                    $aCSV11['tipoprocesso']               = $this->padLeftZero($dadoReg11->tipoprocesso, 1);
                    $aCSV11['nrolote']              = substr($dadoReg11->nrolote, 0, 4);
                    $aCSV11['dsclote']               = substr($dadoReg11->dsclote, 0, 250);
        
                    $this->sLinha = $aCSV11;
                    $this->adicionaLinha();

                    $aDadosAgrupados11[$sHash11] = $aCSV11;

                }

            }

            $dadosReg12 = $this->arquivoDispensaRepository->getDadosRegistro12($dadoReg10->codlicitacao);
            $nroitem = 0;

            foreach ($dadosReg12 as $dadoReg12) {

                $aCSV12['tiporegistro']                    = 12;
                $aCSV12['codorgaoresp']                        = $this->padLeftZero($dadoReg12->codorgaoresp, 3);
                $codunidadesub = !empty($dadoReg12->codunidsubant) ? $dadoReg12->codunidsubant : $dadoReg12->codunidadesubresp;
                $aCSV12['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                $aCSV12['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg12->codunidadesubrespestadual, 4) : '';
                $aCSV12['exerciciolicitacao']              = $this->padLeftZero($dadoReg12->exerciciolicitacao, 4);
                $aCSV12['nroprocessolicitatorio']          = substr($dadoReg12->nroprocessolicitatorio, 0, 12);
                $aCSV12['tipoprocesso']               = $this->padLeftZero($dadoReg12->tipoprocesso, 1);
                $aCSV12['coditem'] = substr($dadoReg12->coditem, 0, 15);
                $nroitem++;
                $aCSV12['nroitem'] = substr($nroitem, 0, 5);

                $this->sLinha = $aCSV12;
                $this->adicionaLinha();
            }

            $dadosReg13 = $this->arquivoDispensaRepository->getDadosRegistro13($dadoReg10->codlicitacao);

            foreach ($dadosReg13 as $dadoReg13) {

                $aCSV13['tiporegistro']                    = 13;
                $aCSV13['codorgaoresp']                        = $this->padLeftZero($dadoReg13->codorgaoresp, 3);
                $codunidadesub = !empty($dadoReg13->codunidsubant) ? $dadoReg13->codunidsubant : $dadoReg13->codunidadesubresp;
                $aCSV13['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                $aCSV13['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg13->codunidadesubrespestadual, 4) : '';
                $aCSV13['exerciciolicitacao']              = $this->padLeftZero($dadoReg13->exerciciolicitacao, 4);
                $aCSV13['nroprocessolicitatorio']          = substr($dadoReg13->nroprocessolicitatorio, 0, 12);
                $aCSV13['tipoprocesso']               = $this->padLeftZero($dadoReg13->tipoprocesso, 1);
                $aCSV13['nrolote'] = substr($dadoReg13->nrolote, 0, 4);
                $aCSV13['coditem'] = substr($dadoReg13->coditem, 0, 15);

                $this->sLinha = $aCSV13;
                $this->adicionaLinha();
            }

            $dadosReg14 = $this->arquivoDispensaRepository->getDadosRegistro14($dadoReg10->codlicitacao);
            $aLicitacoes1 = array();
            $tipoRes = 0;

            foreach ($dadosReg14 as $dadoReg14) {
        
                if ($dadoReg14->tiporesp == 7) {
                  if ($tipoRes == 0) {

                    $aCSV14['tiporegistro']                    = 14;
                    $aCSV14['codorgaoresp']                        = $this->padLeftZero($dadoReg14->codorgaoresp, 3);
                    $codunidadesub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                    $aCSV14['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                    $aCSV14['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg14->codunidadesubrespestadual, 4) : '';
                    $aCSV14['exerciciolicitacao']              = $this->padLeftZero($dadoReg14->exerciciolicitacao, 4);
                    $aCSV14['nroprocessolicitatorio']          = substr($dadoReg14->nroprocessolicitatorio, 0, 12);
                    $aCSV14['tipoprocesso']               = $this->padLeftZero($dadoReg14->tipoprocesso, 1);
                    $aCSV14['tiporesp'] = $this->padLeftZero($dadoReg14->tiporesp, 1);
                    $aCSV14['nrocpfresp'] = $this->padLeftZero($dadoReg14->nrocpfresp, 11);

                    $this->sLinha = $aCSV14;
                    $this->adicionaLinha();

                    if (!in_array($dadoReg10->codlicitacao, $aLicitacoes1)) {

                      $aLicitacoes1[] = $dadoReg10->codlicitacao;
                        
                      $oDados10Preco = DB::select("select * from precoreferencia where si01_processocompra = (select pc81_codproc from pcprocitem where pc81_codprocitem = (select max(l21_codpcprocitem) from liclicitem where l21_codliclicita = $dadoReg10->codlicitacao))")[0] ?? null;
        
                      if ($oDados10Preco->si01_tipocotacao != "") {
        
                        $rsResultCPF = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $oDados10Preco->si01_numcgmcotacao")[0] ?? null;
        
                        $aCSV14['tiporegistro']                    = 14;
                        $aCSV14['codorgaoresp']                        = $this->padLeftZero($dadoReg14->codorgaoresp, 3);
                        $codunidadesub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                        $aCSV14['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                        $aCSV14['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg14->codunidadesubrespestadual, 4) : '';
                        $aCSV14['exerciciolicitacao']              = $this->padLeftZero($dadoReg14->exerciciolicitacao, 4);
                        $aCSV14['nroprocessolicitatorio']          = substr($dadoReg14->nroprocessolicitatorio, 0, 12);
                        $aCSV14['tipoprocesso']               = $this->padLeftZero($dadoReg14->tipoprocesso, 1);
                        $aCSV14['tiporesp'] = $this->padLeftZero(2, 1);
                        $aCSV14['nrocpfresp'] = $this->padLeftZero($rsResultCPF->z01_cgccpf, 11);

                        $this->sLinha = $aCSV14;
                        $this->adicionaLinha();

                        $rsResultCPF1 = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $oDados10Preco->si01_numcgmorcamento")[0] ?? null;

                        $aCSV14['tiporegistro']                    = 14;
                        $aCSV14['codorgaoresp']                        = $this->padLeftZero($dadoReg14->codorgaoresp, 3);
                        $codunidadesub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                        $aCSV14['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                        $aCSV14['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg14->codunidadesubrespestadual, 4) : '';
                        $aCSV14['exerciciolicitacao']              = $this->padLeftZero($dadoReg14->exerciciolicitacao, 4);
                        $aCSV14['nroprocessolicitatorio']          = substr($dadoReg14->nroprocessolicitatorio, 0, 12);
                        $aCSV14['tipoprocesso']               = $this->padLeftZero($dadoReg14->tipoprocesso, 1);
                        $aCSV14['tiporesp'] = $this->padLeftZero(3, 1);
                        $aCSV14['nrocpfresp'] = $this->padLeftZero($rsResultCPF1->z01_cgccpf, 11);

                        $this->sLinha = $aCSV14;
                        $this->adicionaLinha();

                      }
                    }
                    $tipoRes = 1;
                  }
                } else {
        
                    $aCSV14['tiporegistro']                    = 14;
                    $aCSV14['codorgaoresp']                        = $this->padLeftZero($dadoReg14->codorgaoresp, 3);
                    $codunidadesub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                    $aCSV14['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                    $aCSV14['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg14->codunidadesubrespestadual, 4) : '';
                    $aCSV14['exerciciolicitacao']              = $this->padLeftZero($dadoReg14->exerciciolicitacao, 4);
                    $aCSV14['nroprocessolicitatorio']          = substr($dadoReg14->nroprocessolicitatorio, 0, 12);
                    $aCSV14['tipoprocesso']               = $this->padLeftZero($dadoReg14->tipoprocesso, 1);
                    $aCSV14['tiporesp'] = $this->padLeftZero($dadoReg14->tiporesp, 1);
                    $aCSV14['nrocpfresp'] = $this->padLeftZero($dadoReg14->nrocpfresp, 11);

                    $this->sLinha = $aCSV14;
                    $this->adicionaLinha();

                  if (!in_array($dadoReg10->codlicitacao, $aLicitacoes1)) {
                    $aLicitacoes1[] = $dadoReg10->codlicitacao;

                    $oDados10Preco = DB::select("select * from precoreferencia where si01_processocompra = (select pc81_codproc from pcprocitem where pc81_codprocitem = (select max(l21_codpcprocitem) from liclicitem where l21_codliclicita = $dadoReg10->codlicitacao))")[0] ?? null;

                    if ($oDados10Preco->si01_tipocotacao != "") {

                        $rsResultCPF = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $oDados10Preco->si01_numcgmcotacao")[0] ?? null;
        
                        $aCSV14['tiporegistro']                    = 14;
                        $aCSV14['codorgaoresp']                        = $this->padLeftZero($dadoReg14->codorgaoresp, 3);
                        $codunidadesub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                        $aCSV14['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                        $aCSV14['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg14->codunidadesubrespestadual, 4) : '';
                        $aCSV14['exerciciolicitacao']              = $this->padLeftZero($dadoReg14->exerciciolicitacao, 4);
                        $aCSV14['nroprocessolicitatorio']          = substr($dadoReg14->nroprocessolicitatorio, 0, 12);
                        $aCSV14['tipoprocesso']               = $this->padLeftZero($dadoReg14->tipoprocesso, 1);
                        $aCSV14['tiporesp'] = $this->padLeftZero(2, 1);
                        $aCSV14['nrocpfresp'] = $this->padLeftZero($rsResultCPF->z01_cgccpf, 11);

                        $this->sLinha = $aCSV14;
                        $this->adicionaLinha();
        
                        $rsResultCPF1 = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $oDados10Preco->si01_numcgmorcamento")[0] ?? null;

                        $aCSV14['tiporegistro']                    = 14;
                        $aCSV14['codorgaoresp']                        = $this->padLeftZero($dadoReg14->codorgaoresp, 3);
                        $codunidadesub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                        $aCSV14['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                        $aCSV14['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg14->codunidadesubrespestadual, 4) : '';
                        $aCSV14['exerciciolicitacao']              = $this->padLeftZero($dadoReg14->exerciciolicitacao, 4);
                        $aCSV14['nroprocessolicitatorio']          = substr($dadoReg14->nroprocessolicitatorio, 0, 12);
                        $aCSV14['tipoprocesso']               = $this->padLeftZero($dadoReg14->tipoprocesso, 1);
                        $aCSV14['tiporesp'] = $this->padLeftZero(3, 1);
                        $aCSV14['nrocpfresp'] = $this->padLeftZero($rsResultCPF1->z01_cgccpf, 11);

                        $this->sLinha = $aCSV14;
                        $this->adicionaLinha();
                    }
                  }
                }

            }

            $dadosReg15 = $this->arquivoDispensaRepository->getDadosRegistro15($dadoReg10->codlicitacao);

            $aDadosAgrupados15 = array();

            foreach ($dadosReg15 as $dadoReg15) {

                $sHash15 = $dadoReg15->exerciciolicitacao . $dadoReg15->nroprocessolicitatorio . $dadoReg15->nrolote . $dadoReg15->coditem;

                if (!isset($aDadosAgrupados15[$sHash15])) {

                    $oDados15 = new stdClass();

                    $oDados15->tiporegistro = 15;
                    $oDados15->codorgaoresp = $this->padLeftZero($dadoReg15->codorgaoresp, 3);
                    $codunidadesub = !empty($dadoReg15->codunidsubant) ? $dadoReg15->codunidsubant : $dadoReg15->codunidadesubresp;
                    $oDados15->codunidadesubresp =  in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                    $oDados15->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg15->codunidadesubrespestadual, 4) : '';
                    $oDados15->exerciciolicitacao = $this->padLeftZero($dadoReg15->exerciciolicitacao, 4);
                    $oDados15->nroprocessolicitatorio = substr($dadoReg15->nroprocessolicitatorio, 0, 12);
                    $oDados15->tipoprocesso =  $this->padLeftZero($dadoReg15->tipoprocesso, 1);
                    $oDados15->nrolote = $dadoReg15->nrolote == 0 ? ' ' : substr($dadoReg15->nrolote, 0, 4);
                    $oDados15->coditem = substr($dadoReg15->coditem, 0, 15);
                    $oDados15->vlcotprecosunitario = $this->sicomNumberReal($dadoReg15->vlcotprecosunitario, 4);
                    $oDados15->quantidade = $dadoReg15->quantidade;
                    $aDadosAgrupados15[$sHash15] = $oDados15;

                } else {
                    $aDadosAgrupados15[$sHash15]->quantidade += $dadoReg15->quantidade;
                }

            }

            foreach ($aDadosAgrupados15 as $oDadosAgrupados15) {

                $aCSV15['tiporegistro']                    = 15;
                $aCSV15['codorgaoresp']                        = $oDadosAgrupados15->codorgaoresp;
                $aCSV15['codunidadesubresp'] = $oDadosAgrupados15->codunidadesubresp  == '' ? '' : $this->padLeftZero($oDadosAgrupados15->codunidadesubresp, 5);
                $aCSV15['codunidadesubrespestadual'] = $oDadosAgrupados15->codunidadesubrespestadual;
                $aCSV15['exerciciolicitacao']        = $oDadosAgrupados15->exerciciolicitacao;
                $aCSV15['nroprocessolicitatorio']          = $oDadosAgrupados15->nroprocessolicitatorio;
                $aCSV15['tipoprocesso']               = $oDadosAgrupados15->tipoprocesso;
                $aCSV15['nrolote'] =  $oDadosAgrupados15->nrolote; 
                $aCSV15['coditem'] = $oDadosAgrupados15->coditem;
                $aCSV15['vlcotprecosunitario'] = $oDadosAgrupados15->vlcotprecosunitario;
                $aCSV15['quantidade'] = $this->sicomNumberReal($oDadosAgrupados15->quantidade, 4);

                $this->sLinha = $aCSV15;
                $this->adicionaLinha();

            }

            $dadosReg16 = $this->arquivoDispensaRepository->getDadosRegistro16($dadoReg10->codlicitacao);

            foreach ($dadosReg16 as $dadoReg16) {

                $aCSV16['tiporegistro']                    = 16;
                $aCSV16['codorgaoresp']                        = $this->padLeftZero($dadoReg16->codorgaoresp, 3);
                $codunidadesub = !empty($dadoReg16->codunidsubant) ? $dadoReg16->codunidsubant : $dadoReg16->codunidadesubresp;
                $aCSV16['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                $aCSV16['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg16->codunidadesubrespestadual, 4) : '';
                $aCSV16['exerciciolicitacao']              = $this->padLeftZero($dadoReg16->exerciciolicitacao, 4);
                $aCSV16['nroprocessolicitatorio']          = substr($dadoReg16->nroprocessolicitatorio, 0, 12);
                $aCSV16['tipoprocesso']               = $this->padLeftZero($dadoReg16->tipoprocesso, 1);
                $aCSV16['tipodocumento'] = $this->padLeftZero($dadoReg16->tipodocumento, 1);
                $aCSV16['nrodocumento'] = substr($dadoReg16->nrodocumento, 0, 14);
                $aCSV16['nroinscricaoestadual'] = substr($dadoReg16->nroinscricaoestadual, 0, 30);
                $aCSV16['ufinscricaoestadual'] = strlen($dadoReg16->ufinscricaoestadual) < 2 ? ' ' : substr($dadoReg16->ufinscricaoestadual, 0, 2);


                $nrocertidaoregularidadeinss = $dadoReg16->tipodocumento == 2 ? $dadoReg16->nrocertidaoregularidadeinss : "";
                $dtemissaocertidaoregularidadeinss = $dadoReg16->tipodocumento == 2 ? $dadoReg16->dataemissaocertidaoregularidadeinss : "";
                $dtvalidadecertidaoregularidadeinss = $dadoReg16->tipodocumento == 2 ? $dadoReg16->datavalidadecertidaoregularidadeinss : "";
                $nrocertidaoregularidadefgts = $dadoReg16->tipodocumento == 2 ? $dadoReg16->nrocertidaoregularidadefgts : "";
                $dtemissaocertidaoregularidadefgts = $dadoReg16->tipodocumento == 2 ? $dadoReg16->dataemissaocertidaoregularidadefgts : "";
                $dtvalidadecertidaoregularidadefgts = $dadoReg16->tipodocumento == 2 ? $dadoReg16->datavalidadecertidaoregularidadefgts : "";

                $aCSV16['nrocertidaoregularidadeinss'] = substr($nrocertidaoregularidadeinss, 0, 30);
                $aCSV16['dtemissaocertidaoregularidadeinss'] = $this->sicomDate($dtemissaocertidaoregularidadeinss);
                $aCSV16['dtvalidadecertidaoregularidadeinss'] = $this->sicomDate($dtvalidadecertidaoregularidadeinss);
                $aCSV16['nrocertidaoregularidadefgts'] = substr($nrocertidaoregularidadefgts, 0, 30);
                $aCSV16['dtemissaocertidaoregularidadefgts'] = $this->sicomDate($dtemissaocertidaoregularidadefgts);
                $aCSV16['dtvalidadecertidaoregularidadefgts'] = $this->sicomDate($dtvalidadecertidaoregularidadefgts);

                $nrocndt = $dadoReg16->tipodocumento == 1 ? ' ' : $dadoReg16->nrocndt;
                $dtemissaocndt = $dadoReg16->tipodocumento == 1 ? '' : $dadoReg16->dtemissaocndt;
                $dtvalidadecndt = $dadoReg16->tipodocumento == 1 ? '' : $dadoReg16->dtvalidadecndt;

                $aCSV16['nrocndt'] = substr($nrocndt, 0, 30);
                $aCSV16['dtemissaocndt'] = $this->sicomDate($dtemissaocndt);
                $aCSV16['dtvalidadecndt'] = $this->sicomDate($dtvalidadecndt);

                $aCSV16['nrolote'] = $dadoReg16->nrolote == 0 ? ' ' : substr($dadoReg16->nrolote, 0, 4);
                $aCSV16['coditem'] = substr($dadoReg16->coditem, 0, 15);
                $aCSV16['quantidade'] = $this->sicomNumberReal($dadoReg16->quantidade, 4);
                $aCSV16['vlitem'] = $this->sicomNumberReal($dadoReg16->vlunitario, 4);

                $this->sLinha = $aCSV16;
                $this->adicionaLinha();

            }

        }

        $dadosReg20 = $this->arquivoDispensaRepository->getDadosRegistro20($licitacoes);

        foreach ($dadosReg20 as $dadoReg20) {
            $aCSV20['tiporegistro']                    = 20;
            $aCSV20['codorgaoresp']                        = $this->padLeftZero($dadoReg20->codorgaoresp, 3);
            $codunidadesub = !empty($dadoReg20->codunidsubant) ? $dadoReg20->codunidsubant : $dadoReg20->codunidadesubresp;
            $aCSV20['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
            $aCSV20['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg20->codunidadesubrespestadual, 4) : '';
            $aCSV20['exerciciolicitacao']              = $this->padLeftZero($dadoReg20->exerciciolicitacao, 4);
            $aCSV20['nroprocessolicitatorio']          = substr($dadoReg20->nroprocessolicitatorio, 0, 12);
            $aCSV20['tipoprocesso']               = $this->padLeftZero($dadoReg20->tipoprocesso, 1);
            $aCSV20['tipodocumento'] = $this->padLeftZero($dadoReg20->tipodocumento, 1);
            $aCSV20['nrodocumento'] = substr($dadoReg20->nrodocumento, 0, 14);

            $aCSV20['datacredenciamento']                  = $this->sicomDate($dadoReg20->datacredenciamento);
            $aCSV20['nrolote']                             = $dadoReg20->nrolote == '0' ? ' ' : substr($dadoReg20->nrolote, 0, 4);
            $aCSV20['coditem']                             = substr($dadoReg20->coditem, 0, 15);
            $aCSV20['nroinscricaoestadual']                = substr($dadoReg20->nroinscricaoestadual, 0, 30);
            $aCSV20['ufinscricaoestadual']                 = strlen($dadoReg20->ufinscricaoestadual) < 2 ? ' ' : substr($dadoReg20->ufinscricaoestadual, 0, 2);
            $aCSV20['nrocertidaoregularidadeinss']         = substr($dadoReg20->nrocertidaoregularidadeinss, 0, 30);
            $aCSV20['dataemissaocertidaoregularidadeinss'] = $this->sicomDate($dadoReg20->dataemissaocertidaoregularidadeinss);
            $aCSV20['dtvalidadecertidaoregularidadeinss']  = $this->sicomDate($dadoReg20->datavalidadecertidaoregularidadeinss);
            $aCSV20['nrocertidaoregularidadefgts']         = substr($dadoReg20->nrocertidaoregularidadefgts, 0, 30);
            $aCSV20['dtemissaocertidaoregularidadefgts']   = $this->sicomDate($dadoReg20->dataemissaocertidaoregularidadefgts);
            $aCSV20['dtvalidadecertidaoregularidadefgts']  = $this->sicomDate($dadoReg20->datavalidadecertidaoregularidadefgts);
            $aCSV20['nrocndt']                             = substr($dadoReg20->nrocndt, 0, 30);
            $aCSV20['dtemissaocndt']                       = $this->sicomDate($dadoReg20->dtemissaocndt);
            $aCSV20['dtvalidadecndt']                      = $this->sicomDate($dadoReg20->dtvalidadecndt);

            $this->sLinha = $aCSV20;
            $this->adicionaLinha();
        }

        $dadosReg30 = $this->arquivoDispensaRepository->getDadosRegistro30($licitacoes);

        foreach ($dadosReg30 as $dadosReg30) {

            $aCSV30['tiporegistro']                    = 30;
            $aCSV30['codorgaoresp']                        = $this->padLeftZero($dadosReg30->codorgaoresp, 3);
            $codunidadesub = !empty($dadosReg30->codunidsubant) ? $dadosReg30->codunidsubant : $dadosReg30->codunidadesubresp;
            $aCSV30['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
            $aCSV30['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadosReg30->codunidadesubrespestadual, 4) : '';
            $aCSV30['exerciciolicitacao']              = $this->padLeftZero($dadosReg30->exerciciolicitacao, 4);
            $aCSV30['nroprocessolicitatorio']          = substr($dadosReg30->nroprocessolicitatorio, 0, 12);
            $aCSV30['tipoprocesso']               = $this->padLeftZero($dadosReg30->tipoprocesso, 1);
            $aCSV30['tipodocumento'] = $this->padLeftZero($dadosReg30->tipodocumento, 1);
            $aCSV30['nrodocumento'] = substr($dadosReg30->nrodocumento, 0, 14);
            $aCSV30['nrolote'] = $dadosReg30->processoporlote == 1 ? substr($dadosReg30->nrolote, 0, 4) : '';
            $aCSV30['coditem'] = $dadosReg30->processoporlote == 1 ? '' : substr($dadosReg30->coditem, 0, 15);
            $aCSV30['percdesconto']       = $this->sicomNumberReal($dadosReg30->percdesconto, 2);

            $this->sLinha = $aCSV30;
            $this->adicionaLinha();
        }

        $dadosReg40 = $this->arquivoDispensaRepository->getDadosRegistro40($licitacoes);

        foreach ($dadosReg40 as $dadosReg40) {

            $aCSV40['tiporegistro']                    = 40;
            $aCSV40['codorgaoresp']                        = $this->padLeftZero($dadosReg40->codorgaoresp, 3);
            $codunidadesub = !empty($dadosReg40->codunidsubant) ? $dadosReg40->codunidsubant : $dadosReg40->codunidadesubresp;
            $aCSV40['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
            $aCSV40['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadosReg40->codunidadesubrespestadual, 4) : '';
            $aCSV40['exerciciolicitacao']              = $this->padLeftZero($dadosReg40->exerciciolicitacao, 4);
            $aCSV40['nroprocessolicitatorio']          = substr($dadosReg40->nroprocessolicitatorio, 0, 12);
            $aCSV40['tipoprocesso']               = $this->padLeftZero($dadosReg40->tipoprocesso, 1);
            $aCSV40['tipodocumento'] = $this->padLeftZero($dadosReg40->tipodocumento, 1);
            $aCSV40['nrodocumento'] = substr($dadosReg40->nrodocumento, 0, 14);
            $aCSV40['nrolote'] = $dadosReg40->processoporlote == 1 ? substr($dadosReg40->nrolote, 0, 4) : '';
            $aCSV40['coditem'] = $dadosReg40->processoporlote == 1 ? '' : substr($dadosReg40->coditem, 0, 15);

            $aCSV40['perctaxaadm']       = $this->sicomNumberReal(0, 2);

            if($dadoReg10->l20_criterioadjudicacao == 1 && $dadosReg40->pc01_tabela == 't'){
                $aCSV40['perctaxaadm']       = $this->sicomNumberReal($dadosReg40->pc23_perctaxadesctabela, 2);
            }else if($dadoReg10->l20_criterioadjudicacao == 2 && $dadosReg40->pc01_taxa == 't'){
                $aCSV40['perctaxaadm']       = $this->sicomNumberReal($dadosReg40->pc23_percentualdesconto, 2);
            } else {
                continue;
            }

            $this->sLinha = $aCSV40;
            $this->adicionaLinha();
        }

        $dadosReg50 = $this->arquivoDispensaRepository->getDadosRegistro50($licitacoes);
        $aDadosAgrupados50 = array();

        foreach ($dadosReg50 as $dadosReg50) {

            $sHash50 = $dadosReg50->julgamento == 3 ? $dadosReg50->lote : $dadosReg50->sequencialendereco;

            if (!isset($aDadosAgrupados50[$sHash50])) {


                $aCSV50['tiporegistro']                    = 50;
                $aCSV50['codorgaoresp']                        = $this->padLeftZero($dadosReg50->codorgaoresp, 3);
                $codunidadesub = !empty($dadosReg50->codunidsubant) ? $dadosReg50->codunidsubant : $dadosReg50->codunidadesubresp;
                $aCSV50['codunidadesubresp'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                $aCSV50['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadosReg50->codunidadesubrespestadual, 4) : '';
                $aCSV50['exercicioprocesso']              = $this->padLeftZero($dadosReg50->exercicioprocesso, 4);
                $aCSV50['nroprocessolicitatorio']          = substr($dadosReg50->nroprocesso, 0, 12);
                $aCSV50['tipoprocesso']               = $this->padLeftZero($dadosReg50->tipoprocesso, 1);
                $aCSV50['classeObjeto']               = substr(intval($dadosReg50->classeobjeto), 0, 12);
                $aCSV50['tipoAtividadeObra']               = $dadosReg50->tipoatividadeobra == 0 ? '' : $this->padLeftZero($dadosReg50->tipoatividadeobra, 2);
                $aCSV50['desctipoAtividadeObra']               = '';
                $aCSV50['tipoAtividadeServico']               = !$dadosReg50->tipoatividadeservico ? '' : $this->padLeftZero($dadosReg50->tipoatividadeservico, 2);
                $aCSV50['dscatividadeServico']               = substr($dadosReg50->dscatividadeservico, 0, 12);
                $aCSV50['tipoAtividadeServEspecializado']               = !$dadosReg50->tipoatividadeservespecializado ? '' : $this->padLeftZero($dadosReg50->tipoatividadeservespecializado, 2);
                $aCSV50['dscatividadeServEspecializado']               = substr($dadosReg50->dscatividadeservespecializado, 0, 12);
                $aCSV50['codFuncao']               = $this->padLeftZero(intval($dadosReg50->codfuncao), 2);
                $aCSV50['codSubFuncao']               = $this->padLeftZero(intval($dadosReg50->codsubfuncao), 3);
                $aCSV50['codBemPublico']               = $this->padLeftZero($dadosReg50->codbempublico, 4);
                $aCSV50['regimeexecucaoobras']               = $dadosReg50->regimeexecucaoobras == 0 ? '' : substr($dadosReg50->regimeexecucaoobras, 0, 1);
                $aCSV50['bdi']               = in_array($dadosReg50->naturezaobjeto, ['1', '7']) ? $this->sicomNumberReal($dadosReg50->bdi, 2) : '';
                $aCSV50['mesreforc'] = substr($dadosReg50->datacotacao, 5, 2);
                $aCSV50['exercicioreforc'] = substr($dadosReg50->datacotacao, 0, 4);
                $aCSV50['nrolote'] = $dadosReg50->processoporlote == 1 ? substr($dadosReg50->nrolote, 0, 4) : '';
                $aCSV50['utilizacaoplanilha'] = $dadosReg50->utilizacaoplanilha;
                $aCSV50['logradouro'] = $dadosReg50->logradouro == null ? '' : $stringService->removeCaracteres(utf8_decode($dadosReg50->logradouro));
                $aCSV50['numero'] = $dadosReg50->numero ? $dadosReg50->numero : '';
                $aCSV50['bairro'] = $dadosReg50->bairro;
                $aCSV50['distrito'] = $dadosReg50->distrito;
                $aCSV50['municipio'] = $dadosReg50->municipio == null ? '' :  $stringService->removeCaracteres($dadosReg50->municipio);
                $aCSV50['cep'] = $dadosReg50->cep;
                $aCSV50['latitude'] = $this->sicomNumberReal($dadosReg50->latitude, 6);
                $aCSV50['longitude'] = $this->sicomNumberReal($dadosReg50->longitude, 6);
                $tipoInstituicao = db_gettipoinstit(db_getsession('DB_instit'));
                $aCSV50['codmunicipioibge'] = $tipoInstituicao == "51" ? $dadosReg50->codmunicipioibge : "";

                $aDadosAgrupados50[$sHash50] = true;

                $this->sLinha = $aCSV50;
                $this->adicionaLinha();

            }
        }

        $this->fechaArquivo();
    }
}
