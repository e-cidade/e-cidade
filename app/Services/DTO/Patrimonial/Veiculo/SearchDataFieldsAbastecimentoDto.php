<?php

namespace App\Services\DTO\Patrimonial\Veiculo;

use DateTime;

class SearchDataFieldsAbastecimentoDto
{
    public string $primaryKeyField;
    public int $codeVeic;
    public string $whereField;
    public string $searchKmField;
    public string $searchDateField;
    public ?string $searchHourField;
    public int $abastecimentoKm;
    public DateTime $abastecimentoDate;
    public DateTime $abastecimentoHour;

    public function __construct(
        string $primaryKeyField,
        string $whereField,
        int $codeVeic,
        string $searchKmField,
        int $abastecimentoKm,
        string $searchDateField,
        DateTime $abastecimentoDate,
        ?string $searchHourField = null,
        ?DateTime $abastecimentoHour = null
    ) {
        $this->primaryKeyField = $primaryKeyField;
        $this->codeVeic = $codeVeic;
        $this->whereField = $whereField;
        $this->searchKmField = $searchKmField;
        $this->searchDateField = $searchDateField;
        $this->searchHourField = $searchHourField;
        $this->abastecimentoKm = $abastecimentoKm;
        $this->abastecimentoDate = $abastecimentoDate;
        $this->abastecimentoHour = $abastecimentoHour;
    }
}
