<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoReglicRepository;
use GerarAM;

class ArquivoReglicService extends GerarAM
{

    /**
     * @var ArquivoReglicRepository
     */
    private $arquivoReglicRepository;

    public function __construct()
    {
        $this->arquivoReglicRepository = new ArquivoReglicRepository();
    }

    public function gerarArquivo()
    {

        $aCSV = array();
        $this->sArquivo = "REGLIC";
        $this->abreArquivo();
        $aCSV['tiporegistro'] = '99';
        $this->sLinha = $aCSV;
        $this->adicionaLinha();
        $this->fechaArquivo();
    }
}
