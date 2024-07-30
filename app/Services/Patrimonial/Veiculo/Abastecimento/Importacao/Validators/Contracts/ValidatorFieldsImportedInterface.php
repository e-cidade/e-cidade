<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts;

interface ValidatorFieldsImportedInterface
{
    public function execute(array $fields, string $idAbastecimento): void;
}

