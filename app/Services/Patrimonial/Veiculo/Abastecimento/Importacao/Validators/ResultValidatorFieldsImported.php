<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators;

class ResultValidatorFieldsImported
{

    private array $errors = [];

    public function setError(string $field, string $message, string $codigoAbastecimento): void
    {
        $error = new \stdClass();
        $error->codigoAbastecimento = $codigoAbastecimento;
        $error->message = $message;
        $error->field = $field;
        $this->errors[] = $error;
    }

    /**
     * @return boolean
     */
    public function hasErrors(): bool
    {
        return  count($this->errors) > 0 ;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
