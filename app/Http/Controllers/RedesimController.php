<?php

namespace App\Http\Controllers;

use App\Models\Cadastro\Bairro;
use App\Models\Cadastro\Rua;
use App\Models\Cadastro\RuaBairro;
use App\Models\Cgm;
use App\Models\Issbase;
use App\Models\ISSQN\Ativprinc;
use App\Models\ISSQN\Cnae;
use App\Models\ISSQN\InscricaoRedesim;
use App\Models\ISSQN\IssBairro;
use App\Models\ISSQN\IssbaseLog;
use App\Models\ISSQN\IssbaseParalisacao;
use App\Models\ISSQN\IssCadSimples;
use App\Models\ISSQN\IssCadSimplesBaixa;
use App\Models\ISSQN\IssRua;
use App\Models\ISSQN\RedesimLog;
use App\Models\ISSQN\RedesimSettings;
use App\Models\ISSQN\Tabativ;
use App\Models\ISSQN\Tabativbaixa;
use App\Models\Socio;
use App\Repositories\Tributario\ISSQN\Redesim\Alvara\Filters\ObterEmpresasFilter;
use App\Repositories\Tributario\ISSQN\Redesim\ApiRedesimSettings;
use App\Repositories\Tributario\ISSQN\Redesim\ConfirmacaoLeitura\Filters\ConfirmacaoLeituraEmpresaFilter;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Services\Tributario\ISSQN\AlvaraSimplesNacionalService;
use App\Services\Tributario\ISSQN\CompanyShutdownService;
use App\Services\Tributario\ISSQN\CompanySuspensionService;
use App\Services\Tributario\ISSQN\CreateIssbaseLogService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\GetCompaniesService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateAlvaraService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCgmCompanyService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyActivityService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyAddress;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyPartnerService;
use App\Services\Tributario\ISSQN\Redesim\ConfirmacaoLeitura\ReadConfirmationCompanyService;
use App\Services\Tributario\ISSQN\Redesim\CreateRedesimLogService;
use App\Services\Tributario\ISSQN\Redesim\RedesimApiService;
use App\Services\Tributario\ISSQN\Redesim\RelatorioInscricoesService;
use BusinessException;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LogicException;
use Throwable;

class RedesimController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $settingsModel = RedesimSettings::all()->first();

        if (!$settingsModel->q180_active) {
            return response()->json(['statusCode' => 200, 'message' => 'Integracao com REDESIM desativada.']);
        }

        $cgmCompanyService = new UpdateOrCreateCgmCompanyService((new Cgm()));
        $companyPartnerService = new UpdateOrCreateCompanyPartnerService(
            (new Cgm()),
            (new Socio()),
            (new CreateIssbaseLogService(new IssbaseLog()))
        );
        $activityService = new UpdateOrCreateCompanyActivityService(
            (new Cnae()),
            (new InscricaoRedesim()),
            (new Tabativ()),
            (new Ativprinc()),
            (new CreateIssbaseLogService(new IssbaseLog()))
        );
        $addressCompanyService = new UpdateOrCreateCompanyAddress(
            (new Rua()),
            (new Bairro()),
            (new RuaBairro()),
            (new IssRua()),
            (new IssBairro()),
            (new InscricaoRedesim())
        );
        $createAlvaraService = new UpdateOrCreateAlvaraService(
            (new Issbase()),
            (new CreateIssbaseLogService(new IssbaseLog()))
        );

        $companySuspensionService = new CompanySuspensionService((new IssbaseParalisacao()));
        $companyShutdownService = new CompanyShutdownService(
            new Tabativbaixa(),
            new CreateIssbaseLogService(new IssbaseLog())
        );
        $alvaraSimplesNacionalService = new AlvaraSimplesNacionalService(new IssCadSimples(), new IssCadSimplesBaixa());
        $redesimSettingsRepository = new ApiRedesimSettings($settingsModel);
        $confirmacaoLeituraEmpresaFilter = new ConfirmacaoLeituraEmpresaFilter();

        try {

            $companies = $this->getAlvaraRedesim($request);

            if (empty($companies)) {
                return response()->json(['statusCode' => 200, 'message' => 'Nenhuma empresa nova no REDESIM.']);
            }

            $redesimApiService = new RedesimApiService(
                $cgmCompanyService,
                $companyPartnerService,
                $activityService,
                $createAlvaraService,
                $addressCompanyService,
                (new InscricaoRedesim()),
                $companySuspensionService,
                $companyShutdownService,
                $alvaraSimplesNacionalService
            );

            foreach ($companies as $company) {
                $redesimApiService->execute($company);
                $createRedesimLogService = new CreateRedesimLogService((new RedesimLog()));
                $createRedesimLogService->execute($company);
                $confirmacaoLeituraEmpresaFilter->idsConfirmarLeitura[] = $company->id;
            }

            $readConfirmationCompanyService = new ReadConfirmationCompanyService(
                $settingsModel,
                $redesimSettingsRepository,
                (new Client())
            );

            $readConfirmationCompanyService->execute($confirmacaoLeituraEmpresaFilter);
        } catch (Throwable $e) {
            return response()->json(['statusCode' => 500, 'message' => utf8_encode($e->getMessage())], 500);
        }

        return response()->json(['statusCode' => 200, 'message' => 'Success']);
    }

    public function companiesReport(Request $request): JsonResponse
    {
        try {

            $dataInicio = DateTime::createFromFormat('d/m/Y', $request->get('dataInicio'));
            $dataFim = DateTime::createFromFormat('d/m/Y', $request->get('dataFim', date('d/m/Y')));
            $service = new RelatorioInscricoesService();
            $service->setDataInicio($dataInicio->format('Y-m-d'));
            $service->setDataFim($dataFim->format('Y-m-d'));

            $service->execute();

        } catch (Exception $exception) {

            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }

        return response()->json(['data' =>
            [
                "arquivo" => $service->getFile()
            ]
        ]);
    }

    public function alvara(Request $request)
    {
        $companies = [];
        $cpfCnpj = $request->query->get('cpfCnpj');

        if($cpfCnpj) {
            try {
                $companies = $this->getAlvaraRedesim($request);
                if(empty($companies)) {
                    $request->session()->now('warning', 'CNPJ não encontrado no REDESIM.');
                }
            } catch (Throwable $e) {
                $request->session()->now('error', $e->getMessage());
            }
        }

        return view(
            'redesim.alvara',
            compact('companies', 'cpfCnpj')
        );
    }

    /**
     * @param Request $request
     * @return CompanyDTO | null
     * @throws BusinessException
     * @throws GuzzleException
     */
    private function getAlvaraRedesim(Request $request): ?array
    {
        $settingsModel = RedesimSettings::all()->first();

        if (!$settingsModel->q180_active) {
            throw new LogicException('Integracao com REDESIM desativada.');

        }
        $redesimSettingsRepository = new ApiRedesimSettings($settingsModel);
        $service = new GetCompaniesService($settingsModel, $redesimSettingsRepository, (new Client()));

        $filter = new ObterEmpresasFilter($request->all());

        return $service->execute($filter);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }
}
