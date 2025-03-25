<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoPartlicRepository;
use GerarAM;

class ArquivoPartlicService extends GerarAM
{

    /**
     * @var ArquivoPartlicRepository
     */
    private $arquivoPartlicRepository;

    public function __construct()
    {
        $this->arquivoPartlicRepository = new ArquivoPartlicRepository();
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

        $aCSV = array();
        $this->sArquivo = "PARTLIC";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dados = $this->arquivoPartlicRepository->getDados($licitacoes);

        if (empty($dados)) {
            $aCSV['tiporegistro'] = '99';
            $this->sLinha = $aCSV;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        foreach ($dados as $dado) {

            $aCSV['tiporegistro']          = $this->padLeftZero('10', 2);
            $aCSV['codorgao']         =  $this->padLeftZero($dado->codorgaoresp, 3);
            if (!empty($dado->codunidsubant)) {
                $codUnidadeSub = $dado->codunidsubant;
            } else {
                $codUnidadeSub = !empty($dado->codunidadesubresp) 
                    ? $dado->codunidadesubresp 
                    : '00000';
            }

            $aCSV['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
            $aCSV['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dado->codunidadesubrespestadual, 4) : '';            
            
            $aCSV['exerciciolicitacao']             = $this->padLeftZero($dado->exerciciolicitacao, 4);
            $aCSV['nroprocessolicitatorio']             = substr($dado->nroprocessolicitatorio, 0, 12);
            $aCSV['tipodocumento']           = $this->padLeftZero($dado->tipodocumento, 1);
            $aCSV['nrodocumento'] = substr($dado->nrodocumento, 0, 14);

            $this->sLinha = $aCSV;
            $this->adicionaLinha();
        }

        $this->fechaArquivo();
    }
}
