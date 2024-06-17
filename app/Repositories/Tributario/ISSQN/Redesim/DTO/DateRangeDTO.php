<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use DateTime;

class DateRangeDTO extends BaseDTO
{
    public DateTime $inicio;

    public ?DateTime $termino = null;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $attribute => $value) {
            $value = $this->formatDateBr($value);
            $this->$attribute = $value;
        }
    }
}
