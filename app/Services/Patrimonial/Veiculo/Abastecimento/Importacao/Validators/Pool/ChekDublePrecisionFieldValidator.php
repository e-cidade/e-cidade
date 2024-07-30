<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;
use App\Services\ValidadorDoublePrecision;
class ChekDublePrecisionFieldValidator implements ValidatorFieldsImportedInterface
{
    private ResultValidatorFieldsImported $resultValidator;

    public function __construct(ResultValidatorFieldsImported $resultValidator)
    {
        $this->resultValidator = $resultValidator;
    }

    public function execute(array $fields, string $idAbastecimento): void
    {

        try {
            $valorUnd = $fields['preco_unitario'];
            $validadorDouble = new ValidadorDoublePrecision();

            if (!$validadorDouble->execute($valorUnd)) {
                $message = "O campo Preco Unit. {$valorUnd} é inválido. Dica: use 1234.56 ou 1234,56";
                $this->resultValidator->setError("Preco Unit", $message, $idAbastecimento);
            }

            $valor = $fields['valor'];
            if (!$validadorDouble->execute($valor)) {
                $message = "O campo Valor. {$valor} é inválido. Dica: use 1234.56 ou 1234,56";
                $this->resultValidator->setError("Valor", $message, $idAbastecimento);
            }

        } catch (\Exception | \Error $e) {
            $this->resultValidator->setError('', $e->getMessage(), $idAbastecimento);
        }
    }

}
