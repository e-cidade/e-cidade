<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoConsidRepository;
use GerarAM;

class ArquivoConsidService extends GerarAM
{

    /**
     * @var ArquivoConsidRepository
     */
    private $arquivoConsidRepository;

    public function __construct()
    {
        $this->arquivoConsidRepository = new ArquivoConsidRepository();
    }

    public function gerarArquivo()
    {

        $aCSV = array();
        $this->sArquivo = "CONSID";
        $this->abreArquivo();
        $aCSV['tiporegistro'] = '99';
        $this->sLinha = $aCSV;
        $this->adicionaLinha();
        $this->fechaArquivo();
    }
}
