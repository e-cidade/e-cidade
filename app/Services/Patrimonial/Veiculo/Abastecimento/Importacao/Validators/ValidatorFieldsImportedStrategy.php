<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool\CheckDateTimeFieldValidator;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool\CheckIntFieldValidator;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool\CheckNullFieldsValidator;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool\CheckStringFieldValidator;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool\CheckUniquikeyAbastValidator;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool\ChekDublePrecisionFieldValidator;

class ValidatorFieldsImportedStrategy
{
    private array $poolValidator;

    private ResultValidatorFieldsImported $resultvalidator;

    public function __construct()
    {
        $this->resultvalidator = new ResultValidatorFieldsImported();
        $this->poolValidator[] = new CheckNullFieldsValidator($this->resultvalidator);
        $this->poolValidator[] = new CheckUniquikeyAbastValidator($this->resultvalidator);
        $this->poolValidator[] = new CheckDateTimeFieldValidator($this->resultvalidator);
        $this->poolValidator[] = new CheckStringFieldValidator($this->resultvalidator);
        $this->poolValidator[] = new ChekDublePrecisionFieldValidator($this->resultvalidator);
        $this->poolValidator[] = new CheckIntFieldValidator($this->resultvalidator);
    }

    public function execute(array $data): ResultValidatorFieldsImported
    {
        foreach ($data as $key => $row) {
            /** @var ValidatorFieldsImportedInterface $validator */
            foreach ($this->poolValidator as $validator) {
                $validator->execute($row, $row['codigo_abastecimento']);
            }
        }

       return $this->resultvalidator;
    }
}
