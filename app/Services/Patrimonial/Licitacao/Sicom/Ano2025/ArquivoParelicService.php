<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoParelicRepository;
use GerarAM;

class ArquivoParelicService extends GerarAM
{

    /**
     * @var ArquivoParelicRepository
     */
    private $arquivoParelicRepository;

    public function __construct()
    {
        $this->arquivoParelicRepository = new ArquivoParelicRepository();
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
        $this->sArquivo = "PARELIC";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));

        $dados = $this->arquivoParelicRepository->getDados($licitacoes);

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
            $codUnidadeSub = !empty($dado->codunidsubant) ? $dado->codunidsubant : $dado->codunidadesubresp;
            $aCSV['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codUnidadeSub, 5);
            $aCSV['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dado->codunidadesubrespestadual, 4) : '';   
            $aCSV['exerciciolicitacao']             = $this->padLeftZero($dado->exerciciolicitacao, 4);
            $aCSV['nroprocessolicitatorio']             = substr($dado->nroprocessolicitatorio, 0, 12);
            $aCSV['dataparecer']           = $this->sicomDate($dado->dataparecer);
            $aCSV['tipoparecer']            = $this->padLeftZero($dado->tipoparecer, 1);
            $aCSV['descricaoparecer']      = " ";
            $aCSV['nrocpf']            = $this->padLeftZero($dado->nrocpf, 11);

            $this->sLinha = $aCSV;
            $this->adicionaLinha();
        }

        $this->fechaArquivo();
    }
}
