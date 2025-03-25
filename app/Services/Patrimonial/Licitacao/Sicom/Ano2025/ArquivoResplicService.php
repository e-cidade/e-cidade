<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoResplicRepository;
use App\Services\Licitacao\Sicom\StringService;
use Illuminate\Database\Capsule\Manager as DB;
use GerarAM;

class ArquivoResplicService extends GerarAM
{

    /**
     * @var ArquivoResplicRepository
     */
    private $arquivoResplicRepository;

    public function __construct()
    {
        $this->arquivoResplicRepository = new ArquivoResplicRepository();
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
        $this->sArquivo = "RESPLIC";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dadosReg10 = $this->arquivoResplicRepository->getDadosRegistro10($licitacoes);

        if (empty($dadosReg10)) {
            $aCSV['tiporegistro'] = '99';
            $this->sLinha = $aCSV;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        $aLicitacoes = array();

        foreach ($dadosReg10 as $dadoReg10) {

            if ($dadoReg10->l20_naturezaobjeto != 6 && $dadoReg10->tiporesp == 9) {
                continue;
            }

            if($dadoReg10->tiporesp == 6 || $dadoReg10->tiporesp == 7) continue;

            $aCSV10['si55_tiporegistro']           = $this->padLeftZero("10", 2);
            $aCSV10['si55_codorgao']               = $this->padLeftZero($dadoReg10->codorgaoresp, 3);
            $codunidadesub = !empty($oDados10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;

            $aCSV10['si55_codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
            $aCSV10['si55_codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual, 4) : '';            

            $aCSV10['si55_exerciciolicitacao']     = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
            $aCSV10['si55_nroprocessolicitatorio'] = substr($dadoReg10->nroprocessolicitatorio, 0, 12);
            $aCSV10['si55_tiporesp']               = $this->padLeftZero($dadoReg10->tiporesp, 2);
            $aCSV10['si55_nrocpfresp']             = $this->padLeftZero($dadoReg10->nrocpfresp, 11);

            $this->sLinha = $aCSV10;
            $this->adicionaLinha();

            if (!in_array($dadoReg10->codlicitacao, $aLicitacoes)) {
                $aLicitacoes[] = $dadoReg10->codlicitacao;
                $dadosReg10PrecoReferencia = DB::select("select * from precoreferencia where si01_processocompra = (select pc81_codproc from pcprocitem where pc81_codprocitem = (select max(l21_codpcprocitem) from liclicitem where l21_codliclicita = $dadoReg10->codlicitacao))")[0] ?? null;

                    if ( $dadosReg10PrecoReferencia->si01_tipocotacao != "") {

                        $dadosReg10CpfResponsavel = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $dadosReg10PrecoReferencia->si01_numcgmcotacao")[0] ?? null;
                        
                        $aCSV10['si55_tiporegistro']           = $this->padLeftZero("10", 2);
                        $aCSV10['si55_codorgao']               = $this->padLeftZero($dadoReg10->codorgaoresp, 3);
                        $codunidadesub = !empty($oDados10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;
                        $aCSV10['si55_codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                        $aCSV10['si55_codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual, 4) : '';
                        
                        $aCSV10['si55_exerciciolicitacao']     = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
                        $aCSV10['si55_nroprocessolicitatorio'] = substr($dadoReg10->nroprocessolicitatorio, 0, 12);
                        $aCSV10['si55_tiporesp']               = $this->padLeftZero($dadosReg10PrecoReferencia->si01_tipocotacao, 2);

                        $dadosReg10CpfResponsavel = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $dadosReg10PrecoReferencia->si01_numcgmcotacao")[0] ?? null;

                        $aCSV10['si55_nrocpfresp']             = $this->padLeftZero($dadosReg10CpfResponsavel->z01_cgccpf, 11);

                        $this->sLinha = $aCSV10;
                        $this->adicionaLinha();

                        $aCSV10['si55_tiporegistro']           = $this->padLeftZero("10", 2);
                        $aCSV10['si55_codorgao']               = $this->padLeftZero($dadoReg10->codorgaoresp, 3);
                        $codunidadesub = !empty($oDados10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;
                        $aCSV10['si55_codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                        $aCSV10['si55_codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual, 4) : '';
                        
                        $aCSV10['si55_exerciciolicitacao']     = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
                        $aCSV10['si55_nroprocessolicitatorio'] = substr($dadoReg10->nroprocessolicitatorio, 0, 12);
                        $aCSV10['si55_tiporesp']               = $this->padLeftZero($dadosReg10PrecoReferencia->si01_tipoorcamento, 2);

                        $dadosReg10CpfResponsavel = DB::select("select z01_cgccpf from cgm where z01_numcgm =  $dadosReg10PrecoReferencia->si01_numcgmorcamento")[0] ?? null;

                        $aCSV10['si55_nrocpfresp']             = $this->padLeftZero($dadosReg10CpfResponsavel->z01_cgccpf, 11);

                        $this->sLinha = $aCSV10;
                        $this->adicionaLinha();
                 }
            }

        }

        $dadosReg20 = $this->arquivoResplicRepository->getDadosRegistro20($licitacoes);

        foreach ($dadosReg20 as $dadoReg20) {
            $aCSV20['si56_tiporegistro']           = $this->padLeftZero("20", 2);
            $aCSV20['si56_codorgao']               = $this->padLeftZero($dadoReg20->codorgaoresp, 3);
            $codunidadesub = !empty($dadoReg20->codunidsubant) ? $dadoReg20->codunidsubant : $dadoReg20->codunidadesubresp;
            $aCSV20['si55_codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
            $aCSV20['si55_codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg20->codunidadesubrespestadual, 4) : '';
            $aCSV20['si56_exerciciolicitacao']     = $this->padLeftZero($dadoReg20->exerciciolicitacao, 4);
            $aCSV20['si56_nroprocessolicitatorio'] = substr($dadoReg20->nroprocessolicitatorio, 0, 12);
            $codtipocomissao = $dadoReg20->leidalicitacao == 2 ? $dadoReg20->codtipocomissao : null;
            $aCSV20['si56_codtipocomissao']        = $codtipocomissao;
            $aCSV20['si56_descricaoatonomeacao']   = $this->padLeftZero($dadoReg20->descricaoatonomeacao, 1);
            $aCSV20['si56_nroatonomeacao']         = substr($dadoReg20->nroatonomeacao, 0, 7);
            $aCSV20['si56_dataatonomeacao']        = $this->sicomDate($dadoReg20->dataatonomeacao);
            $aCSV20['si56_iniciovigencia']         = $this->sicomDate($dadoReg20->iniciovigencia);
            $aCSV20['si56_finalvigencia']          = $this->sicomDate($dadoReg20->finalvigencia);
            $aCSV20['si56_cpfmembrocomissao']      = $this->padLeftZero($dadoReg20->cpfmembrocomissao, 11);
            $aCSV20['si56_codatribuicao']          = $this->padLeftZero($dadoReg20->codatribuicao, 1);
            $aCSV20['si56_cargo']                  = $dadoReg20->cargo == null ? '' : substr($stringService->removeCaracteres($dadoReg20->cargo), 0, 50);
            $aCSV20['si56_naturezacargo']          = $this->padLeftZero($dadoReg20->naturezacargo, 1);
            $aCSV20['dscnaturezaCargo']          = "";


            $this->sLinha = $aCSV20;
            $this->adicionaLinha();
    
        }

        $this->fechaArquivo();
    }
}
