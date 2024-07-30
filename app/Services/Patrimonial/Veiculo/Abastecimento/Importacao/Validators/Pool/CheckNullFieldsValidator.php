<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;

class CheckNullFieldsValidator implements ValidatorFieldsImportedInterface
{
    private ResultValidatorFieldsImported $resultValidator;

    public function __construct(ResultValidatorFieldsImported $resultValidator)
    {
        $this->resultValidator = $resultValidator;
    }

    public function execute(array $fields, string  $idAbastecimento): void
    {
        $keyError = '';
        try{
            foreach ($fields as $key => $field) {
                $keyError = $key;
                if (empty($field)) {
                    $message = "O campo {$key} não pode ser nulo";
                    $this->resultValidator->setError($key, $message, $idAbastecimento);
                }
            }
        } catch(\Exception | \Error $e) {
            $this->resultValidator->setError($keyError, $e->getMessage(), $idAbastecimento);
        }
    }
}
