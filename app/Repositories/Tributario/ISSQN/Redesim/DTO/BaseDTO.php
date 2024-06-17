<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use DateTime;

class BaseDTO
{
    public function formatDateBr(string $value): DateTime
    {
        $value = DateTime::createFromFormat('d/m/Y H:i', $value);

        if (!$value) {
            $value = (new DateTime())->format('d/m/Y H:i');
        }

        return $value;
    }

    /**
     * @param array $value
     * @return DateRangeDTO[]
     */
    public function handleDateRage(array $value): array
    {
        $ranges = [];
        foreach ($value as $range) {
            $ranges[] = new DateRangeDTO((array) $range);
        }
        return $ranges;
    }
}
