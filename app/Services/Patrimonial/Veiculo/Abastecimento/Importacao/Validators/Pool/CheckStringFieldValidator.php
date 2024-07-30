<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;
use App\Services\ValidadorCpfService;

class CheckStringFieldValidator implements ValidatorFieldsImportedInterface
{
    private ResultValidatorFieldsImported $resultValidator;

    public function __construct(ResultValidatorFieldsImported $resultValidator)
    {
        $this->resultValidator = $resultValidator;
    }

    public function execute(array $fields, string $idAbastecimento): void
    {
        try {
            $cpf = $fields['cpf'];
            $validadorCpf = new ValidadorCpfService();
            if ($validadorCpf->execute($cpf)) {
                return;
            }

            $message = "O campo CPF {$cpf} é inválido";
            $this->resultValidator->setError("cpf", $message, $idAbastecimento);
        } catch (\Exception | \Error $e) {
            $this->resultValidator->setError("cpf", $e->getMessage(), $idAbastecimento);
        }
    }
}
