<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoHomolicRepository;
use GerarAM;
use stdClass;

class ArquivoHomolicService extends GerarAM
{

    /**
     * @var ArquivoHomolicRepository
     */
    private $arquivoHomolicRepository;

    public function __construct()
    {
        $this->arquivoHomolicRepository = new ArquivoHomolicRepository();
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

        $aCSV10 = array();
        $aCSV20 = array();
        $aCSV30 = array();
        $aCSV50 = array();
        $aCSV60 = array();

        $this->sArquivo = "HOMOLIC";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dadosReg10 = $this->arquivoHomolicRepository->getDadosRegistro10($licitacoes);

        if (empty($dadosReg10)) {
            $aCSV10['tiporegistro'] = '99';
            $this->sLinha = $aCSV10;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        $aDadosAgrupados = array();
		$aLicitacoes = array();

        foreach ($dadosReg10 as $dadoReg10) {

            if ($dadoReg10->criterioadjudicacao != 2 && $dadoReg10->criterioadjudicacao != 1) {
                $sHash = $dadoReg10->exerciciolicitacao . $dadoReg10->nroprocessolicitatorio . $dadoReg10->nrodocumento . $dadoReg10->nrolote . $dadoReg10->coditem;

                if (!isset($aDadosAgrupados[$sHash])) {

                    $oDados10 = new stdClass();
                    
                    $oDados10->tiporegistro          = 10;
                    $oDados10->tipocadastro          = 1;
                    $oDados10->codorgao              = $dadoReg10->codorgaoresp;
                    $codUnidadeSub = !empty($dadoReg10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;
                    $oDados10->codunidadesub = in_array($tipoOrgao, $tiposOrgaos) ? '' : $codUnidadeSub;
                    $oDados10->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $dadoReg10->codunidadesubrespestadual : '';   
                    $oDados10->exerciciolicitacao             = $dadoReg10->exerciciolicitacao;
                    $oDados10->nroprocessolicitatorio             = $dadoReg10->nroprocessolicitatorio;

                    $oDados10->tipodocumento          = $dadoReg10->tipodocumento;
                    $oDados10->nrodocumento           = $dadoReg10->nrodocumento;
                    $oDados10->nrolote                = $dadoReg10->nrolote == 0 ? ' ' : $dadoReg10->nrolote;
                    $oDados10->coditem                = $dadoReg10->coditem;
                    $oDados10->quantidade             = $dadoReg10->quantidade;
                    $oDados10->vlunitariohomologado   = $dadoReg10->vlunitario;
                    $aDadosAgrupados[$sHash] = $oDados10;
                } else {
                    $aDadosAgrupados[$sHash]->quantidade += $dadoReg10->quantidade;

                }
            }

            if (!in_array($dadoReg10->codlicitacao, $aLicitacoes)) {
				$aLicitacoes[] = $dadoReg10->codlicitacao;
			}

        }

        foreach ($aDadosAgrupados as $oDadosAgrupados) {

            $aCSV10['tiporegistro']          = $this->padLeftZero('10', 2);
            $aCSV10['tipocadastro']          = "1";
            $aCSV10['codorgao']              = $this->padLeftZero($oDadosAgrupados->codorgao, 3);
            $aCSV10['codunidadesub'] = $oDadosAgrupados->codunidadesub == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesub, 5);
            $aCSV10['codunidadesubrespestadual'] = $oDadosAgrupados->codunidadesubrespestadual == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubrespestadual, 4); 
            $aCSV10['exerciciolicitacao']             = $this->padLeftZero($oDadosAgrupados->exerciciolicitacao, 4);
            $aCSV10['nroprocessolicitatorio']             = substr($oDadosAgrupados->nroprocessolicitatorio, 0, 12);

            $aCSV10['tipodocumento']       = $this->padLeftZero($oDadosAgrupados->tipodocumento, 1);
            $aCSV10['nrodocumento']           = substr($oDadosAgrupados->nrodocumento, 0, 14);
            $aCSV10['nrolote']                = substr($oDadosAgrupados->nrolote, 0, 4);
            $aCSV10['coditem']               = substr($oDadosAgrupados->coditem, 0, 15);
            $aCSV10['quantidade']             = $this->sicomNumberReal($oDadosAgrupados->quantidade, 4);
            $aCSV10['vlunitariohomologado']   = $this->sicomNumberReal($oDadosAgrupados->vlunitariohomologado, 4);

            $this->sLinha = $aCSV10;
            $this->adicionaLinha();

		}

        $dadosReg20 = $this->arquivoHomolicRepository->getDadosRegistro20($aLicitacoes);
        $aDadosAgrupados20 = array();

        foreach ($dadosReg20 as $dadoReg20) {

            if($dadoReg20->criterioadjudicacao == 1){
                $sHash20 = '20' . $dadoReg20->exerciciolicitacao . $dadoReg20->nroprocessolicitatorio . $dadoReg20->nrodocumento . $dadoReg20->nrolote . $dadoReg20->coditem;
               
                if (!isset($aDadosAgrupados20[$sHash20])) {

                    $oDados20 = new stdClass();
                    
					$oDados20->tiporegistro = 20;
                    $oDados20->tipocadastro = 1;
					$oDados20->codorgao = $dadoReg20->codorgaoresp;

                    $codUnidadeSub = !empty($dadoReg20->codunidsubant) ? $dadoReg20->codunidsubant : $dadoReg20->codunidadesubresp;
                    $oDados20->codunidadesub = in_array($tipoOrgao, $tiposOrgaos) ? '' : $codUnidadeSub;
                    $oDados20->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $dadoReg20->codunidadesubrespestadual : ''; 

					$oDados20->exerciciolicitacao = $dadoReg20->exerciciolicitacao;
					$oDados20->nroprocessolicitatorio = $dadoReg20->nroprocessolicitatorio;
					$oDados20->tipodocumento = $dadoReg20->tipodocumento;
					$oDados20->nrodocumento = $dadoReg20->nrodocumento;
					$oDados20->nrolote = $dadoReg20->nrolote;
					$oDados20->coditem = $dadoReg20->coditem;
					$oDados20->percdesconto = $dadoReg20->percdesconto;

					$aDadosAgrupados20[$sHash20] = $oDados20;

				}

            }
        }

        foreach ($aDadosAgrupados20 as $oDadosAgrupados) {

            $aCSV20['tiporegistro']          = $this->padLeftZero('20', 2);
            $aCSV20['tipocadastro']          = "1";
            $aCSV20['codorgao']              = $this->padLeftZero($oDadosAgrupados->codorgaoresp, 3);
            $aCSV20['codunidadesub'] = $oDadosAgrupados->codunidadesub == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesub, 5);
            $aCSV20['codunidadesubrespestadual'] = $oDadosAgrupados->codunidadesubrespestadual == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubrespestadual, 4);   
            $aCSV20['exerciciolicitacao']             = $this->padLeftZero($oDadosAgrupados->exerciciolicitacao, 4);
            $aCSV20['nroprocessolicitatorio']             = substr($oDadosAgrupados->nroprocessolicitatorio, 0, 12);

            $aCSV20['tipodocumento']       = $this->padLeftZero($oDadosAgrupados->tipodocumento, 1);
            $aCSV20['nrodocumento']           = substr($oDadosAgrupados->nrodocumento, 0, 14);
            $aCSV20['nrolote']                = substr($oDadosAgrupados->nrolote, 0, 4);
            $aCSV20['coditem']               = substr($oDadosAgrupados->coditem, 0, 15);
            $aCSV20['percdesconto']   = $this->sicomNumberReal($oDadosAgrupados->percdesconto, 2);

            $this->sLinha = $aCSV20;
            $this->adicionaLinha();

		}

        $dadosReg30 = $this->arquivoHomolicRepository->getDadosRegistro30($aLicitacoes);
        $aDadosAgrupados30 = array();

        foreach ($dadosReg30 as $dadoReg30) {

            $sHash30 = '30' . $dadoReg30->exerciciolicitacao . $dadoReg30->nroprocessolicitatorio . $dadoReg30->nrodocumento . $dadoReg30->nrolote . $dadoReg30->coditem;

            if (!isset($aDadosAgrupados30[$sHash30])) {
                if ($dadoReg30->criterioadjudicacao == 2) {
                    $oDados30 = new stdClass();
                    
					$oDados30->tiporegistro = 30;
                    $oDados30->tipocadastro = 1;
					$oDados30->codorgao = $dadoReg30->codorgaoresp;

                    $codUnidadeSub = !empty($dadoReg30->codunidsubant) ? $dadoReg30->codunidsubant : $dadoReg30->codunidadesubresp;
                    $oDados30->codunidadesub = in_array($tipoOrgao, $tiposOrgaos) ? '' : $codUnidadeSub;
                    $oDados30->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $dadoReg30->codunidadesubrespestadual : ''; 

					$oDados30->exerciciolicitacao = $dadoReg30->exerciciolicitacao;
					$oDados30->nroprocessolicitatorio = $dadoReg30->nroprocessolicitatorio;
					$oDados30->tipodocumento = $dadoReg30->tipodocumento;
					$oDados30->nrodocumento = $dadoReg30->nrodocumento;
					$oDados30->nrolote = $dadoReg30->nrolote;
					$oDados30->coditem = $dadoReg30->coditem;
					$oDados30->perctaxaadm = $dadoReg30->perctaxaadm;

					$aDadosAgrupados30[$sHash30] = $oDados30;
                }
            }

        }

        foreach ($aDadosAgrupados30 as $oDadosAgrupados) {

            $aCSV30['tiporegistro']          = $this->padLeftZero('30', 2);
            $aCSV30['tipocadastro']          = "1";
            $aCSV30['codorgao']              = $this->padLeftZero($oDadosAgrupados->codorgaoresp, 3);
            $aCSV30['codunidadesub'] =$oDadosAgrupados->codunidadesub == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesub, 5);
            $aCSV30['codunidadesubrespestadual'] = $oDadosAgrupados->codunidadesubrespestadual == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubrespestadual, 4);    
            $aCSV30['exerciciolicitacao']             = $this->padLeftZero($oDadosAgrupados->exerciciolicitacao, 4);
            $aCSV30['nroprocessolicitatorio']             = substr($oDadosAgrupados->nroprocessolicitatorio, 0, 12);

            $aCSV30['tipodocumento']       = $this->padLeftZero($oDadosAgrupados->tipodocumento, 1);
            $aCSV30['nrodocumento']           = substr($oDadosAgrupados->nrodocumento, 0, 14);
            $aCSV30['nrolote']                = substr($oDadosAgrupados->nrolote, 0, 4);
            $aCSV30['coditem']               = substr($oDadosAgrupados->coditem, 0, 15);
            $aCSV30['perctaxaadm']   = $this->sicomNumberReal($oDadosAgrupados->perctaxaadm, 2);

            $this->sLinha = $aCSV30;
            $this->adicionaLinha();

		}

        $dadosReg50 = $this->arquivoHomolicRepository->getDadosRegistro50($aLicitacoes);

        foreach ($dadosReg50 as $dadoReg50) {

                $aCSV50['tiporegistro']          = $this->padLeftZero('50', 2);
                $aCSV50['tipocadastro']          = "1";
                $aCSV50['codorgao']              = $this->padLeftZero($dadoReg50->codorgaoresp, 3);
                $codUnidadeSub = !empty($dadoReg50->codunidsubant) ? $dadoReg50->codunidsubant : $dadoReg50->codunidadesubresp;
                $aCSV50['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
                $aCSV50['codunidadesubrespestadual'] =in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg50->codunidadesubrespestadual,4) : '';  
                $aCSV50['exerciciolicitacao']             = $this->padLeftZero($dadoReg50->exerciciolicitacao, 4);
                $aCSV50['nroprocessolicitatorio']             = substr($dadoReg50->nroprocessolicitatorio, 0, 12);
                $aCSV50['dthomologacao']       = $this->sicomDate($dadoReg50->dthomologacao);
                $aCSV50['dtadjudicacao']       = $this->sicomDate($dadoReg50->dtadjudicacao);
    
    
                $this->sLinha = $aCSV50;
                $this->adicionaLinha();

        }

        $dadosReg60 = $this->arquivoHomolicRepository->getDadosRegistro60($aLicitacoes);

        foreach ($dadosReg60 as $dadoReg60) {

            $aCSV60['tiporegistro']          = $this->padLeftZero('60', 2);
            $aCSV60['tipocadastroinicial']         =  "1";
            $aCSV60['codorgao']         =  $this->padLeftZero($dadoReg60->codorgaoresp, 3);
            $codUnidadeSub = !empty($dadoReg60->codunidsubant) ? $dadoReg60->codunidsubant : $dadoReg60->codunidadesubresp;
            $aCSV60['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
            $aCSV60['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg60->codunidadesubrespestadual, 4) : '';   
            $aCSV60['exerciciolicitacao']             = $this->padLeftZero($dadoReg60->exerciciolicitacao, 4);
            $aCSV60['nroprocessolicitatorio']             = substr($dadoReg60->nroprocessolicitatorio, 0, 12);
            $aCSV60['codrepsonsavel']                     = $dadoReg60->codresponsavel;
            $aCSV60['tiporesp']                     = $this->padLeftZero($dadoReg60->tiporesp, 2);
            $aCSV60['nrocpfresp']                     = $dadoReg60->nrocpfresp;


            $this->sLinha = $aCSV60;
            $this->adicionaLinha();

        }


        $this->fechaArquivo();
    }
}
