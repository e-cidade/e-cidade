<?php

namespace App\Services\Tributario\ISSQN\Redesim;

use App\Models\Cgm;
use App\Models\Issbase;
use App\Models\ISSQN\Ativid;
use App\Models\ISSQN\AtividCnae;
use App\Models\ISSQN\Cnae;
use App\Models\ISSQN\CnaeAnalitica;
use App\Models\ISSQN\InscricaoRedesim;
use App\Models\ISSQN\IssbaseParalisacao;
use App\Models\ISSQN\IssCadSimples;
use App\Models\ISSQN\IssMotivoBaixa;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyActivityDTO;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyPartnerDTO;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\DateRangeDTO;
use App\Services\Tributario\ISSQN\AlvaraSimplesNacionalService;
use App\Services\Tributario\ISSQN\CompanyShutdownService;
use App\Services\Tributario\ISSQN\CompanySuspensionService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateActivityService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateAlvaraService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCgmCompanyPartnerService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCgmCompanyService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyActivityService;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyAddress;
use App\Services\Tributario\ISSQN\Redesim\Alvara\UpdateOrCreateCompanyPartnerService;
use App\Support\String\StringHelper;
use BusinessException;
use Throwable;

class RedesimApiService
{
    public const DEFAULT_LOG_MESSAGE = 'INTEGRAÇÃO REDESIM';

    private UpdateOrCreateCgmCompanyService $cgmCompanyService;
    private UpdateOrCreateCompanyPartnerService $companyPartnerService;
    private UpdateOrCreateCompanyActivityService $companyActivityService;
    private UpdateOrCreateAlvaraService $createAlvaraService;
    private InscricaoRedesim $inscricaoRedesim;
    private UpdateOrCreateCompanyAddress $companyAddress;
    private CompanySuspensionService $companySuspensionService;
    private CompanyShutdownService $companyShutdownService;
    private AlvaraSimplesNacionalService $alvaraSimplesNacionalService;

    public function __construct(
        UpdateOrCreateCgmCompanyService      $cgmCompanyService,
        UpdateOrCreateCompanyPartnerService  $companyPartnerService,
        UpdateOrCreateCompanyActivityService $activityService,
        UpdateOrCreateAlvaraService          $createAlvaraService,
        UpdateOrCreateCompanyAddress         $companyAddress,
        InscricaoRedesim                     $inscricaoRedesim,
        CompanySuspensionService             $companySuspensionService,
        CompanyShutdownService               $companyShutdownService,
        AlvaraSimplesNacionalService         $alvaraSimplesNacionalService
    ) {
        $this->cgmCompanyService = $cgmCompanyService;
        $this->createAlvaraService = $createAlvaraService;
        $this->companyPartnerService = $companyPartnerService;
        $this->inscricaoRedesim = $inscricaoRedesim;
        $this->companyActivityService = $activityService;
        $this->companyAddress = $companyAddress;
        $this->companySuspensionService = $companySuspensionService;
        $this->companyShutdownService = $companyShutdownService;
        $this->alvaraSimplesNacionalService = $alvaraSimplesNacionalService;
    }

    /**
     * @throws BusinessException
     * @throws Throwable
     */
    public function execute(CompanyDTO $data)
    {
        $cgmCompany = $this->updateOrCreateCompanyCgm($data);

        foreach ($data->socios as $socio) {
            $this->updateOrCreateCgmCompanyPartners($socio);
        }

        foreach ($data->atividades as $atividade) {
            $this->updateOrCreateActivity($atividade);
        }

        $issbase = $this->updateOrCreateCompany($data, $cgmCompany);

        $this->updateOrCreateCompanyPartner($data, $cgmCompany, $issbase);

        $this->createAlvaraRedesim($issbase, $data);

        $this->updateOrCreateCompanyActivity($data);

        $this->updateOrCreateCompanyAddress($data);

        if ($data->situacao === CompanyDTO::STATUS_BAIXADA && !$issbase->isBaixada()) {
            $this->companyShutdownService->execute($issbase, 'INTEGRAÇÃO REDESIM');
        }

        if (in_array($data->situacao, [CompanyDTO::STATUS_PARALIZADA,CompanyDTO::STATUS_SUSPENSA]) && !$issbase->isParalisada()) {
            $this->companySuspensionService->execute(
                $issbase->q02_inscr,
                $data->dataDoAcontecimento,
                IssbaseParalisacao::MOTIVO_PARALIZACAO_REDESIM,
                self::DEFAULT_LOG_MESSAGE
            );
        }

        if ($data->situacao === CompanyDTO::STATUS_ATIVA && $issbase->isParalisada()) {
            $this->companySuspensionService->execute(
                $issbase->q02_inscr,
                $data->dataDoAcontecimento,
                IssbaseParalisacao::MOTIVO_PARALIZACAO_REDESIM,
                self::DEFAULT_LOG_MESSAGE,
                true
            );
        }

        if ($data->mei) {
            $this->updateOrCreateSimplesNacional($data->periodosMEI, $issbase->q02_inscr, true);
        }

        if (!$data->mei && $data->simples) {
            $this->updateOrCreateSimplesNacional($data->periodosSimples, $issbase->q02_inscr);
        }
    }

    /**
     * @param CompanyDTO $data
     * @return Cgm
     * @throws BusinessException
     */
    private function updateOrCreateCompanyCgm(CompanyDTO $data): Cgm
    {
        return $this->cgmCompanyService->execute($data);
    }

    /**
     * @param CompanyPartnerDTO $socio
     * @return void
     * @throws BusinessException
     */
    private function updateOrCreateCgmCompanyPartners(CompanyPartnerDTO $socio): void
    {
        $cgmCompanyPartnerService = new UpdateOrCreateCgmCompanyPartnerService((new Cgm()));
        $cgmCompanyPartnerService->execute($socio);
    }

    /**
     * @param CompanyDTO $data
     * @return void
     * @throws BusinessException
     */
    private function updateOrCreateCompanyPartner(CompanyDTO $data, Cgm $cgmCompany, Issbase $issbase): void
    {
        $this->companyPartnerService->execute($data, $cgmCompany, $issbase);
    }

    /**
     * @param CompanyActivityDTO $atividade
     * @return void
     */
    private function updateOrCreateActivity(CompanyActivityDTO $atividade): void
    {
        $activityService = new UpdateOrCreateActivityService((new Cnae()), (new CnaeAnalitica()), (new AtividCnae()), (new Ativid()));
        $activityService->execute($atividade->atividade);
    }

    /**
     * @param CompanyDTO $data
     * @param Cgm $cgmCompany
     * @return Issbase
     */
    private function updateOrCreateCompany(CompanyDTO $data, Cgm $cgmCompany): Issbase
    {
        return $this->createAlvaraService->execute($data, $cgmCompany);
    }

    /**
     * @param Issbase $issbase
     * @param CompanyDTO $data
     * @return void
     * @throws Throwable
     */
    public function createAlvaraRedesim(Issbase $issbase, CompanyDTO $data): void
    {
        $this->inscricaoRedesim
            ->insert(
                [
                    'q179_inscricao' => $issbase->q02_inscr,
                    'q179_inscricaoredesim' => $data->inscricaoMunicipal,
                    'q179_dadosregistro' => json_encode(StringHelper::utf8_encode_all($data->originalData), JSON_UNESCAPED_UNICODE)
                ]
            );
    }

    /**
     * @param CompanyDTO $data
     * @return void
     * @throws BusinessException
     */
    private function updateOrCreateCompanyActivity(CompanyDTO $data): void
    {
        $this->companyActivityService->execute($data);
    }

    /**
     * @param CompanyDTO $data
     * @return void
     * @throws BusinessException
     */
    private function updateOrCreateCompanyAddress(CompanyDTO $data): void
    {
        $this->companyAddress->execute($data);
    }

    /**
     * @param DateRangeDTO[] $periods
     * @param int $inscricao
     * @param bool $isMei
     * @return void
     */
    public function updateOrCreateSimplesNacional(array $periods, int $inscricao, bool $isMei = false): void
    {
        foreach ($periods as $period) {
            $this->alvaraSimplesNacionalService->execute(
                $inscricao,
                $period->inicio,
                $isMei ? IssCadSimples::CATEGORIA_MEI : IssCadSimples::CATEGORIA_ME,
                $period->termino,
                IssMotivoBaixa::MOTIVO_BAIXA_REDESIM,
            );
        }
    }
}
