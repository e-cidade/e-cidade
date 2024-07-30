<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use App\Support\String\StringHelper;
use DateTime;

class CompanyDTO extends BaseDTO
{

    public const STATUS_ATIVA = 'A';

    public const STATUS_BAIXADA = 'B';

    public const STATUS_PARALIZADA = 'P';

    public const STATUS_SUSPENSA = 'S';

    public const STATUS_RFB = 'R';

    /**
     * 'J' para Pessoa Jurídica e 'F' para Pessoa Física
     * @var string
     */
    public string $tipoPessoa;

    /**
     * 'A' para "Ativa", 'B' para "Baixada", 'P' para "Paralisada", 'S' para
     * "Suspensa", 'R' para "Situação da RFB"
     * @var string
     */
    public string $situacao = 'A';

    public string $telefone = '';

    public ?string $codigoNaturezaJuridica = '';

    /**
     * @var DateRangeDTO[]
     */
    public ?array $periodosMEI = [];

    public ?bool $simples = false;

    public ?bool $mei = false;

    public string $nomeFantasia = '';

    public DateTime $dataDoAcontecimento;

    public int $id;

    public string $email = '';

    /**
     * @var CompanyActivityDTO[]
     */
    public ?array $atividades = [];

    public CompanyAdressDTO $endereco;

    public ?string $inscricaoMunicipal = '';

    /**
     * Número no órgão de registro
     * @var string
     */
    public string $numeroRegistro = '';

    /**
     * Data de registro no órgão
     * @var DateTime|null
     */
    public ?DateTime $dataRegistro;

    /**
     * @var CompanyPartnerDTO[]
     */
    public ?array $socios = [];

    /**
     * Município
     * @var string
     */
    public ?string $cliente = '';

    public DateTime $dataCnpj;

    /**
     * @var DateRangeDTO[]
     */
    public array $periodosSimples = [];

    public DateTime $inclusao;

    public string $cpfCnpj = '';

    public string $razaoSocial = '';

    public ?float $capitalSocial = null;

    public ?int $dddTelefone = null;

    public ?DateTime $abertura;

    public ?DateTime $encerramento = null;

    public float $areaTotal = 0;

    public float $areaUtilizada = 0;

    /**
     * Used in CreateRedesimLogService to store the original data
     * @var array
     */
    public array $originalData = [];

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        $this->originalData = $data;

        foreach ($data as $attribute => $value) {

            if (in_array($attribute, ['dataDoAcontecimento', 'inclusao', 'dataCnpj', 'abertura', 'encerramento', 'dataRegistro'])) {
                $value = $this->formatDateBr($value);
            }

            if (in_array($attribute, ['periodosMEI','periodosSimples'])) {
                $value = $this->handleDateRage($value);
            }

            if ($attribute === 'endereco') {
                $value = new CompanyAdressDTO((array)$value);
            }

            if ($attribute === 'atividades') {
                $companyActivity = [];
                for ($i = 0; $i < count($value); $i++) {
                    $companyActivity[] = new CompanyActivityDTO((array)$value[$i]);
                }
                $value = $companyActivity;
            }

            if ($attribute === 'socios') {
                $companyPartners = [];
                for ($i = 0; $i < count($value); $i++) {
                    $companyPartners[] = new CompanyPartnerDTO((array)$value[$i]);
                }
                $value = $companyPartners;
            }

            if (in_array($attribute, ['razaoSocial','nomeFantasia'])) {
                $value = \DBString::removerCaracteresEspeciais($value);
            }

            $this->$attribute = $value;
        }
    }

    public function getDataInicioEcidade(): string
    {
        return !empty($this->abertura) ? $this->abertura->format('Y-m-d') : $this->dataCnpj->format('Y-m-d');
    }

    public function getDateEncerramentoEcidade(): ?string
    {
        return !empty($this->encerramento) ? $this->encerramento->format('Y-m-d') : null;
    }

    public function getDateBaixaEcidade(): ?string
    {
        return $this->situacao === self::STATUS_BAIXADA ? $this->dataDoAcontecimento->format('Y-m-d') : null;
    }
}
