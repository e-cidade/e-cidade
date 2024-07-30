<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;

class CheckUniquikeyAbastValidator implements ValidatorFieldsImportedInterface
{
    private ResultValidatorFieldsImported $resultValidator;

    private static array $uniqueKeys = [];

    public function __construct(ResultValidatorFieldsImported $resultValidator)
    {
        $this->resultValidator = $resultValidator;
    }

    public function execute(array $fields, string $idAbastecimento): void
    {
        if (in_array($fields['codigo_abastecimento'], self::$uniqueKeys)) {
            $this->resultValidator->setError("codigo abastecimento", 'Codigo de abastecimento duplicado', $idAbastecimento);
            return;
        }

     self::$uniqueKeys[] = $fields['codigo_abastecimento'];
    }
}

