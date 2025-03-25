<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoHablicRepository;
use App\Services\Licitacao\Sicom\StringService;
use GerarAM;

class ArquivoHablicService extends GerarAM
{

    /**
     * @var ArquivoHablicRepository
     */
    private $arquivoHablicRepository;

    public function __construct()
    {
        $this->arquivoHablicRepository = new ArquivoHablicRepository();
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
        $aCSV11 = array();
        $aCSV20 = array();

        $this->sArquivo = "HABLIC";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dadosReg10 = $this->arquivoHablicRepository->getDadosRegistro10($licitacoes);

        if (empty($dadosReg10)) {
            $aCSV['tiporegistro'] = '99';
            $this->sLinha = $aCSV;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        foreach ($dadosReg10 as $dadoReg10) {

            $aCSV10['si57_tiporegistro']                        = $this->padLeftZero($dadoReg10->tiporegistro, 2);
            $aCSV10['tipocadastro']                             = "1";
            $aCSV10['si57_codorgao']                            = $this->padLeftZero($dadoReg10->codorgaoresp, 3);

            $codunidadesub = !empty($dadoReg10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;

            $aCSV10['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
            $aCSV10['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual, 4) : '';   

            $aCSV10['si57_exerciciolicitacao']                  = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
            $aCSV10['si57_nroprocessolicitatorio']              = substr($dadoReg10->nroprocessolicitatorio, 0, 12);
            $aCSV10['si57_tipodocumento']                       = $this->padLeftZero($dadoReg10->tipodocumento, 1);
            $aCSV10['si57_nrodocumento']                        = substr($dadoReg10->nrodocumento, 0, 14);

            $objetosocial = $dadoReg10->tipodocumento == "1" ? '' : $stringService->removeCaracteres(substr($dadoReg10->objetosocial, 0, 2000));
            $aCSV10['si57_objetosocial']                        = substr($objetosocial, 0, 2000);

            $orgaorespregistro = $dadoReg10->tipodocumento == "1" ? '' : $dadoReg10->orgaorespregistro;
            $aCSV10['si57_orgaorespregistro']                   = $orgaorespregistro == 0 ? '' : substr($orgaorespregistro, 0, 1);

            $dataregistro = $dadoReg10->tipodocumento == "1" ? '' : $dadoReg10->dataregistro;
            $aCSV10['si57_dataregistro']                        = $this->sicomDate($dataregistro);

            $nroregistro = 
            ($dadoReg10->orgaorespregistro == "4" || $dadoReg10->tipodocumento == "1") 
            ? '' 
            : $dadoReg10->nroregistro;
            $aCSV10['si57_nroregistro']                         = substr($nroregistro, 0, 20);

            $dataregistrocvm = $dadoReg10->tipodocumento == "1" ? '' : ($dadoReg10->dataregistrocvm != "" && $dadoReg10->nroregistrocvm != "") ? $dadoReg10->dataregistrocvm : "";
            $aCSV10['si57_dataregistrocvm']                     = $this->sicomDate($dataregistrocvm);

            $nroregistrocvm = $dadoReg10->tipodocumento == "1" ? '' : ($dadoReg10->dataregistrocvm != "" && $dadoReg10->nroregistrocvm != "") ? $dadoReg10->nroregistrocvm : "";
            $aCSV10['si57_nroregistrocvm']                      = substr($nroregistrocvm, 0, 20);

            $aCSV10['si57_nroinscricaoestadual']                = substr($dadoReg10->nroinscricaoestadual, 0, 30);
            $aCSV10['si57_ufinscricaoestadual']                 = $dadoReg10->ufinscricaoestadual == 0 ? '' : substr($dadoReg10->ufinscricaoestadual, 0, 2);
            $aCSV10['si57_nrocertidaoregularidadeinss']         = substr($dadoReg10->nrocertidaoregularidadeinss, 0, 30);
            $aCSV10['si57_dtemissaocertidaoregularidadeinss']   = $this->sicomDate($dadoReg10->dtemissaocertidaoregularidadeinss);
            $aCSV10['si57_dtvalidadecertidaoregularidadeinss']  = $this->sicomDate($dadoReg10->dtvalidadecertidaoregularidadeinss);
            $aCSV10['si57_nrocertidaoregularidadefgts']         = substr($dadoReg10->nrocertidaoregularidadefgts, 0, 30);
            $aCSV10['si57_dtemissaocertidaoregularidadefgts']   = $this->sicomDate($dadoReg10->dtemissaocertidaoregularidadefgts);
            $aCSV10['si57_dtvalidadecertidaoregularidadefgts']  = $this->sicomDate($dadoReg10->dtvalidadecertidaoregularidadefgts);
            $aCSV10['si57_nrocndt']                             = substr($dadoReg10->nrocndt, 0, 30);
            $aCSV10['si57_dtemissaocndt']                       = $this->sicomDate($dadoReg10->dtemissaocndt);
            $aCSV10['si57_dtvalidadecndt']                      = $this->sicomDate($dadoReg10->dtvalidadecndt);
            $aCSV10['si57_dthabilitacao']                       = $this->sicomDate($dadoReg10->dthabilitacao);
            $aCSV10['si57_presencalicitantes']                  = $dadoReg10->leidalicitacao == 2 ? $dadoReg10->presencalicitantes : null;
            $aCSV10['si57_renunciarecurso']                     = $dadoReg10->leidalicitacao == 2 ? $dadoReg10->renunciarecurso : null;

            $this->sLinha = $aCSV10;
            $this->adicionaLinha();

            $dadosReg11 = $this->arquivoHablicRepository->getDadosRegistro11($dadoReg10->codlicitacao,$dadoReg10->nrodocumento);

            foreach ($dadosReg11 as $dadoReg11) {

                $aCSV11['si58_tiporegistro']                    = $this->padLeftZero($dadoReg11->tiporegistro, 2);
                $aCSV11['si58_codorgao']                        = $this->padLeftZero($dadoReg11->codorgaoresp, 3);
                $codunidadesub = !empty($dadoReg11->codunidsubant) ? $dadoReg11->codunidsubant : $dadoReg11->codunidadesubresp;
                $aCSV11['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                $aCSV11['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg11->codunidadesubrespestadual, 4) : '';
                $aCSV11['si58_exerciciolicitacao']              = $this->padLeftZero($dadoReg11->exerciciolicitacao, 4);
                $aCSV11['si58_nroprocessolicitatorio']          = substr($dadoReg11->nroprocessolicitatorio, 0, 12);
                $aCSV11['si58_cnpjempresahablic']               = substr($dadoReg11->cnpjempresahablic, 0, 14);
                $aCSV11['si58_tipodocumentosocio']              = substr($dadoReg11->tipodocumentosocio, 0, 1);
                $aCSV11['si58_nrodocumentosocio']               = substr($dadoReg11->nrodocumentosocio, 0, 14);
                $aCSV11['si58_tipoparticipacao']                = $this->padLeftZero($dadoReg11->tipoparticipacao, 1);
    
                $this->sLinha = $aCSV11;
                $this->adicionaLinha();

            }

        }

        foreach ($dadosReg10 as $dadoReg10) {
            $dadosReg20 = $this->arquivoHablicRepository->getDadosRegistro20($dadoReg10->codlicitacao);

            foreach ($dadosReg20 as $dadoReg20) {
                $aCSV20['si59_tiporegistro']                        = $this->padLeftZero($dadoReg20->tiporegistro, 2);
                $aCSV20['tipocadastro']                             = "1";
                $aCSV20['si59_codorgao']                            = $this->padLeftZero($dadoReg20->codorgao, 2);
                $codunidadesub = !empty($dadoReg20->codunidsubant) ? $dadoReg20->codunidsubant : $dadoReg20->codunidadesubresp;
                $aCSV20['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesub, 5);
                $aCSV20['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg20->codunidadesubrespestadual, 4) : '';
                $aCSV20['si59_exerciciolicitacao']                  = $this->padLeftZero($dadoReg20->exerciciolicitacao, 4);
                $aCSV20['si59_nroprocessolicitatorio']              = substr($dadoReg20->nroprocessolicitatorio, 0, 12);
                $aCSV20['si59_tipodocumento']                       = $this->padLeftZero($dadoReg20->tipodocumento, 1);
                $aCSV20['si59_nrodocumento']                        = substr($dadoReg20->nrodocumento, 0, 14);
                $aCSV20['si59_datacredenciamento']                  = $this->sicomDate($dadoReg20->datacredenciamento);
                $aCSV20['si59_nrolote']                             = substr($dadoReg20->nrolote, 0, 4);
                $aCSV20['si59_coditem']                             = substr($dadoReg20->coditem, 0, 15);
                $aCSV20['si59_nroinscricaoestadual']                = substr($dadoReg20->nroinscricaoestadual, 0, 30);
                $aCSV20['si59_ufinscricaoestadual']                 = $this->padLeftZero($dadoReg20->ufinscricaoestadual, 2);
                $aCSV20['si59_nrocertidaoregularidadeinss']         = substr($dadoReg20->nrocertidaoregularidadeinss, 0, 30);
                $aCSV20['si59_dataemissaocertidaoregularidadeinss'] = $this->sicomDate($dadoReg20->dataemissaocertidaoregularidadeinss);
                $aCSV20['si59_dtvalidadecertidaoregularidadeinss']  = $this->sicomDate($dadoReg20->dtvalidadecertidaoregularidadeinss);
                $aCSV20['si59_nrocertidaoregularidadefgts']         = substr($dadoReg20->nrocertidaoregularidadefgts, 0, 30);
                $aCSV20['si59_dtemissaocertidaoregularidadefgts']   = $this->sicomDate($dadoReg20->dtemissaocertidaoregularidadefgts);
                $aCSV20['si59_dtvalidadecertidaoregularidadefgts']  = $this->sicomDate($dadoReg20->dtvalidadecertidaoregularidadefgts);
                $aCSV20['si59_nrocndt']                             = substr($dadoReg20->nrocndt, 0, 30);
                $aCSV20['si59_dtemissaocndt']                       = $this->sicomDate($dadoReg20->dtemissaocndt);
                $aCSV20['si59_dtvalidadecndt']                      = $this->sicomDate($dadoReg20->dtvalidadecndt);

                $this->sLinha = $aCSV20;
                $this->adicionaLinha();
            }

        }

        $this->fechaArquivo();
    }
}
