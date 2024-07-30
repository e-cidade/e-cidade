<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Pool;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\Contracts\ValidatorFieldsImportedInterface;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;
use DateTime;

class CheckDateTimeFieldValidator implements ValidatorFieldsImportedInterface
{
    private ResultValidatorFieldsImported $resultValidator;

    public function __construct(ResultValidatorFieldsImported $resultValidator)
    {
        $this->resultValidator = $resultValidator;
    }

    public function execute(array $fields, string  $idAbastecimento): void
    {
        $this->checkFieldDate($fields['data'],  $idAbastecimento);
        $this->checkFieldHour($fields['horario'],  $idAbastecimento);
    }

    private function checkFieldDate(string $field, string  $idAbastecimento): void
    {
        try {
            if ($this->checkDateFormat($field)) {
                return;
            }
            $message = "O campo data é inválido";
            $this->resultValidator->setError('data', $message,  $idAbastecimento);
        } catch (\Exception | \Error $e) {
            $this->resultValidator->setError('data', $e->getMessage(), $idAbastecimento);
        }
    }

    private function checkFieldHour(string $field, string  $idAbastecimento): void
    {

        try {

            $horaRaw =  explode(':', $field);
            $horaFormatada = strlen($horaRaw[0]) < 2 ? '0'.$field : $field;

            if ($this->checkDateFormat($horaFormatada, 'H:i:s') || $this->checkDateFormat($horaFormatada, 'H:i')) {
                return;
            }

            $message = "O campo hora é inválido";
            $this->resultValidator->setError('hora', $message,  $idAbastecimento);
        } catch (\Exception | \Error $e) {
            $this->resultValidator->setError('hora', $e->getMessage(), $idAbastecimento);
        }
    }

    private function checkDateFormat($date, $format = 'd/m/Y'): bool
    {
        if (!empty($date) && $v_date = date_create_from_format($format, $date)) {
            $v_date = date_format($v_date, $format);
            return ($v_date && $v_date == $date);
        }

        return false;
    }
}
