<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoIdeRepository;
use GerarAM;

class ArquivoIdeService extends GerarAM
{

    /**
     * @var ArquivoIdeRepository
     */
    private $arquivoIdeRepository;

    public function __construct()
    {
        $this->arquivoIdeRepository = new ArquivoIdeRepository();
    }

    public function gerarArquivo($licitacoes,$remessa)
    {

        $aCSV = array();
        $this->sArquivo = "IDE";
        $this->abreArquivo();

        $dados = $this->arquivoIdeRepository->getDados();

        if (empty($dados)) {
            $aCSV['tiporegistro'] = '99';
            $this->sLinha = $aCSV;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        foreach ($dados as $dado) {

            $aCSV['codmunicipio']          = $this->padLeftZero($dado->codmunicipio, 5);
            $aCSV['cnpjmunicipio']         = $this->padLeftZero($dado->cnpjmunicipio, 14);
            $aCSV['codorgao']              = $this->padLeftZero($dado->codorgao, 3);
            $aCSV['tipoorgao']             = $this->padLeftZero($dado->tipoorgao, 2);
            $aCSV['exercicio']             = $this->padLeftZero(db_getsession("DB_anousu"), 4);
            $aCSV['datageracao']           = $this->sicomDate(date("Y-m-d"));
            $aCSV['codControleRemessa'] =  " ";
            $aCSV['codSeqRemessa'] = $remessa;

            $this->sLinha = $aCSV;
            $this->adicionaLinha();
        }

        $this->fechaArquivo();
    }
}
