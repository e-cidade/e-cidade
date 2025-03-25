<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoRegadesaoRepository;
use App\Services\Licitacao\Sicom\StringService;
use GerarAM;

class ArquivoRegadesaoService extends GerarAM
{
    /**
     * @var ArquivoRegadesaoRepository
     */
    private $arquivoRegadesaoRepository;

    public function __construct()
    {
        $this->arquivoRegadesaoRepository = new ArquivoRegadesaoRepository();
    }

    public function gerarArquivo($adesoes)
    {

        if (empty($adesoes)) {
            $aCSV10 = array();
            $aCSV10['tiporegistro'] = '99';
            $this->sArquivo = "REGADESAO";
            $this->sLinha = $aCSV10;
            $this->abreArquivo();
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        $stringService = new StringService();

        $aCSV10 = array();
        $aCSV11 = array();
        $aCSV12 = array();
        $aCSV13 = array();
        $aCSV14 = array();
        $aCSV20 = array();

        $this->sArquivo = "REGADESAO";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dadosReg10 = $this->arquivoRegadesaoRepository->getDadosRegistro10($adesoes);

        if (empty($dadosReg10)) {
            $aCSV10['tiporegistro'] = '99';
            $this->sLinha = $aCSV10;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        foreach ($dadosReg10 as $dadoReg10) {

                $aCSV10['tiporegistro']          = $this->padLeftZero('10', 2);
                $aCSV10['tipocadastro']          = 1;
                $aCSV10['leilicitacao']          = str_pad($dadoReg10->si06_leidalicitacao, 1, "0", STR_PAD_LEFT);
                $aCSV10['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                $aCSV10['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                $aCSV10['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                $aCSV10['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                $aCSV10['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                $aCSV10['dtabertura']                 = $this->sicomDate($dadoReg10->si06_dataabertura);
                $aCSV10['cnpjorgaogerenciador']       = substr($dadoReg10->cnpjorgaogerenciador, 0, 100);
                $aCSV10['exerciciolicitacao']         = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
                $aCSV10['nroprocessolicitatorio']     = substr($dadoReg10->si06_numeroprc, 0, 20);
                $aCSV10['regimecontratacao']          = $dadoReg10->si06_regimecontratacao;
                $aCSV10['codmodalidadelicitacao'] = in_array($dadoReg10->si06_regimecontratacao, ["2", "3"]) ? '' : $dadoReg10->si06_modalidade;
                $aCSV10['tipocriterio']               = $dadoReg10->si06_criterioadjudicacao;
                $aCSV10['descricaotipocriterio']      = '';
                $nroedital = in_array($dadoReg10->si06_regimecontratacao, ["2", "3"]) ? '' : $dadoReg10->si06_edital; 
                $aCSV10['nroedital'] = !$nroedital ? '': substr($nroedital, 0, 10);
                $exercicioedital = in_array($dadoReg10->si06_regimecontratacao, ["2", "3"]) ? '' : $dadoReg10->exerciciolicitacao; 
                $aCSV10['exercicioedital'] = !$exercicioedital ? '': $this->padLeftZero($exercicioedital, 4);
                $aCSV10['dtataregpreco']              = $this->sicomDate($dadoReg10->si06_dataata);
                $aCSV10['dtvalidade']                 = $this->sicomDate($dadoReg10->si06_datavalidade);
                $aCSV10['dtpublicacaoavisointencao']  = $this->sicomDate($dadoReg10->si06_publicacaoaviso);
                $aCSV10['objetoadesao']               = $dadoReg10->si06_objetoadesao == null ? '' : substr($stringService->removeCaracteres($dadoReg10->si06_objetoadesao), 0, 500);
                $aCSV10['cpfresponsavel']             = $this->padLeftZero($dadoReg10->cpfresponsavel, 11);
                $aCSV10['processoporlote']            = $this->padLeftZero($dadoReg10->si06_processoporlote, 1);
        
                $this->sLinha = $aCSV10;
                $this->adicionaLinha();

                $dadosReg11 = $this->arquivoRegadesaoRepository->getDadosRegistro11($dadoReg10->si06_sequencial);

                foreach ($dadosReg11 as $dadoReg11) {
        
                    $aCSV11['tiporegistro']          = $this->padLeftZero('11', 2);
                    $aCSV11['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV11['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV11['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV11['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV11['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV11['nrolote']                  = substr(($dadoReg11->si07_numerolote == 0 ? ' ' : $dadoReg11->si07_numerolote), 0, 4);
                    $aCSV11['dsclote']                  = $dadoReg11->desclote == null ? '' : substr($stringService->removeCaracteres($dadoReg11->desclote), 0, 250);
        
                    $this->sLinha = $aCSV11;
                    $this->adicionaLinha();
        
                }

                $dadosReg12 = $this->arquivoRegadesaoRepository->getDadosRegistro12($dadoReg10->si06_sequencial);

                foreach ($dadosReg12 as $dadoReg12) {
        
                    $aCSV12['tiporegistro']          = $this->padLeftZero('12', 2);
                    $aCSV12['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV12['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV12['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV12['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV12['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV12['coditem']                  = substr($dadoReg12->coditem, 0, 15);
                    $aCSV12['nroitem']                  = $dadoReg12->si07_numeroitem;

                    $this->sLinha = $aCSV12;
                    $this->adicionaLinha();
        
                }

                $dadosReg13 = $this->arquivoRegadesaoRepository->getDadosRegistro13($dadoReg10->si06_sequencial);

                foreach ($dadosReg13 as $dadoReg13) {
        
                    $aCSV13['tiporegistro']          = $this->padLeftZero('13', 2);
                    $aCSV13['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV13['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV13['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV13['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV13['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV13['nrolote']                  = substr(($dadoReg13->si07_numerolote == 0 ? ' ' : $dadoReg13->si07_numerolote), 0, 4);
                    $aCSV13['coditem']                  = substr($dadoReg13->coditem, 0, 15);

                    $this->sLinha = $aCSV13;
                    $this->adicionaLinha();
        
                }

                $dadosReg14 = $this->arquivoRegadesaoRepository->getDadosRegistro14($dadoReg10);

                foreach ($dadosReg14 as $dadoReg14) {
        
                    $aCSV14['tiporegistro']          = $this->padLeftZero('14', 2);
                    $aCSV14['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV14['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV14['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV14['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV14['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV14['nrolote']                  = substr(($dadoReg14->si07_numerolote == 0 ? ' ' : $dadoReg14->si07_numerolote), 0, 4);
                    $aCSV14['coditem']                  = substr($dadoReg14->coditem, 0, 15);

                    $aCSV14['dtcotacao']            = $this->sicomDate($dadoReg14->si01_datacotacao);
                    $aCSV14['vlcotprecosunitario']  = $this->sicomNumberReal($dadoReg14->si02_vlprecoreferencia, 4, ",", "");
                    $aCSV14['quantidade']           = $this->sicomNumberReal($dadoReg14->pc11_quant, 4, ",", "");

                    $this->sLinha = $aCSV14;
                    $this->adicionaLinha();
        
                }

                $dadosReg20 = $this->arquivoRegadesaoRepository->getDadosRegistro20($dadoReg10->si06_sequencial);

                foreach ($dadosReg20 as $dadoReg20) {
        
                    $aCSV20['tiporegistro']          = $this->padLeftZero('20', 2);
                    $aCSV20['tipocadastro']          = 1;
                    $aCSV20['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV20['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV20['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV20['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV20['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV20['nrolote']                  = substr(($dadoReg20->si07_numerolote == 0 ? ' ' : $dadoReg20->si07_numerolote), 0, 4);
                    $aCSV20['coditem']                  = substr($dadoReg20->coditem, 0, 15);
                    $aCSV20['precounitario']      = $this->sicomNumberReal($dadoReg20->si07_precounitario, 4, ",", "");
                    $aCSV20['quantidadelicitada'] = $this->sicomNumberReal($dadoReg20->si07_quantidadelicitada, 4, ",", "");
                    $aCSV20['quantidadeaderida']  = $this->sicomNumberReal($dadoReg20->si07_quantidadeaderida, 4, ",", "");
                    $aCSV20['tipodocumento']      = $this->padLeftZero($dadoReg20->tipodocumento, 1);
                    $aCSV20['nrodocumento']       = substr($dadoReg20->z01_cgccpf, 0, 14);

                    $this->sLinha = $aCSV20;
                    $this->adicionaLinha();
        
                }

                $dadosReg30 = $this->arquivoRegadesaoRepository->getDadosRegistro30($dadoReg10->si06_sequencial);

                foreach ($dadosReg30 as $dadoReg30) {
        
                    $aCSV30['tiporegistro']          = $this->padLeftZero('30', 2);
                    $aCSV30['tipocadastro']          = 1;
                    $aCSV30['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV30['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV30['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV30['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV30['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV30['nrolote']                  = substr(($dadoReg30->si07_numerolote == 0 ? ' ' : $dadoReg30->si07_numerolote), 0, 4);
                    $aCSV30['coditem']                  = substr($dadoReg30->coditem, 0, 15);
                    $aCSV30['percentual']      = $this->sicomNumberReal($dadoReg30->si07_percentual, 2, ",", "");
                    $aCSV30['tipodocumento']      = $this->padLeftZero($dadoReg30->tipodocumento, 1);
                    $aCSV30['nrodocumento']       = substr($dadoReg30->z01_cgccpf, 0, 14);

                    $this->sLinha = $aCSV30;
                    $this->adicionaLinha();
        
                }

                $dadosReg40 = $this->arquivoRegadesaoRepository->getDadosRegistro40($dadoReg10->si06_sequencial);

                foreach ($dadosReg40 as $dadoReg40) {
        
                    $aCSV40['tiporegistro']          = $this->padLeftZero('40', 2);
                    $aCSV40['tipocadastro']          = 1;
                    $aCSV40['codorgao']              = $this->padLeftZero($dadoReg10->codorgao, 3);
                    $codUnidadeSub = !empty($dadoReg10->si06_codunidadesubant) ? $dadoReg10->si06_codunidadesubant : $dadoReg10->codunidadesub;
                    $aCSV40['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                    $aCSV40['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual,4) : '';  
                    $aCSV40['numeroadesao']             = substr($dadoReg10->si06_numeroadm, 0, 12);
                    $aCSV40['exercicioadesao']             = substr($dadoReg10->exercicioadesao,0, 4);
                    $aCSV40['nrolote']                  = substr(($dadoReg40->si07_numerolote == 0 ? ' ' : $dadoReg40->si07_numerolote), 0, 4);
                    $aCSV40['coditem']                  = substr($dadoReg40->coditem, 0, 15);
                    $aCSV40['percentual']      = $this->sicomNumberReal($dadoReg40->si07_percentual, 2, ",", "");
                    $aCSV40['tipodocumento']      = $this->padLeftZero($dadoReg40->tipodocumento, 1);
                    $aCSV40['nrodocumento']       = substr($dadoReg40->z01_cgccpf, 0, 14);

                    $this->sLinha = $aCSV40;
                    $this->adicionaLinha();
        
                }

        }



        $this->fechaArquivo();
    }
}
