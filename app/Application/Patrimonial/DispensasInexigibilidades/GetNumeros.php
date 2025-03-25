<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\GetLicLicitaService;
use App\Services\PccfEditalNum\GetPccfEditalNumService;
use App\Services\PccfLicitaNum\GetPccfLicitaNumService;
use App\Services\PccfLicitaPar\GetPccfLicitaParService;

class GetNumeros implements HandleRepositoryInterface{

    private GetLicLicitaService $getLicLicitaService;
    private GetPccfLicitaNumService $getPccfLicitaNumService;
    private GetPccfEditalNumService $getPccfEditalNumService;
    private GetPccfLicitaParService $getPccfLicitaParService;

    public function __construct()
    {
        $this->getLicLicitaService = new GetLicLicitaService();
        $this->getPccfLicitaNumService = new GetPccfLicitaNumService();
        $this->getPccfEditalNumService = new GetPccfEditalNumService();
        $this->getPccfLicitaParService = new GetPccfLicitaParService();
    }

    public function handle(object $data)
    {
        if(!empty($l20_codigo)){
            $aData = $this->getLicLicitaService->execute((object)[
                'l20_codigo' => (int)$data->l20_codigo
            ])['data']['licitacao'];
        }

        $oProcesso = (!empty($aData->l20_numero))
                        ? $aData->l20_numero
                        : $this->getPccfLicitaParService->execute((object)[
                            'l25_codcflicita' => $data->l20_codtipocom,
                            'l25_anousu' => $data->anousu
                        ])['data']['l20_numero'];

        $oNumeracao = (!empty($aData->l20_edital))
                        ? $aData->l20_edital
                        : $this->getPccfLicitaNumService->execute((object)[
                            'l24_instit' => $data->instit,
                            'l24_anousu' => $data->anousu
                        ])['data']['l20_edital'];

        $oEdital = (!empty($aData->l20_nroedital))
                        ? $aData->l20_nroedital
                        : $this->getPccfEditalNumService->execute((object)[
                            'l47_instit' => $data->instit,
                            'l47_anousu' => $data->anousu
                        ])['data']['l20_nroedital'];

        return [
            'status'    => 200,
            'message' => 'Numeros carregados com sucesso',
            'data' => [
                'processo'  => $oProcesso,
                'numeracao' => $oNumeracao,
                'edital'    => $oEdital
            ]
        ];
    }
}
