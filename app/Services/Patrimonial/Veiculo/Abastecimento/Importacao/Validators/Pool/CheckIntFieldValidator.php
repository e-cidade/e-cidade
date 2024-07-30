<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;
use App\Services\ValidadorInt;

class CheckIntFieldValidator implements ValidatorFieldsImportedInterface
{
    private ResultValidatorFieldsImported $resultValidator;

    public function __construct(ResultValidatorFieldsImported $resultValidator)
    {
        $this->resultValidator = $resultValidator;
    }

    public function execute(array $fields, string $idAbastecimento): void
    {

        try {

            $codigoAbast = $fields['codigo_abastecimento'];
            $validadorInt = new ValidadorInt();

            if ($validadorInt->execute($codigoAbast)) {
                return;
            }

            $message = "O campo Codigo Abastecimento. {$codigoAbast} é inválido";

            $this->resultValidator->setError("Codigo Abastecimento", $message, $idAbastecimento);

        } catch (\Exception | \Error $e) {
            $this->resultValidator->setError('', $e->getMessage(), $idAbastecimento);
        }
    }
}
